<?php

namespace Core;

use Config\Settings;
use Core\Helpers\HelperString;
use Core\Interfaces\RepositoryInterface;

/**
 * Clase Repository
 * 
 * Esta clase se encarga de manejar las operaciones de almacenamiento y recuperación de datos.
 * 
 * @package BuriPHP
 * @author Kiske
 * @since 2.0Alpha
 * @version 1.4
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
class Repository implements RepositoryInterface
{
    /**
     * Instancia de la conexión a la base de datos.
     * 
     * Esta propiedad almacena la conexión a la base de datos que se utilizará
     * para realizar operaciones de almacenamiento y recuperación de datos.
     * 
     * @var mixed
     */
    protected $database;

    /**
     * Nombre de la tabla asociada al modelo.
     * 
     * Esta propiedad almacena el nombre de la tabla en la base de datos que
     * se utilizará para las operaciones de este repositorio. Debe ser
     * asignada en el modelo correspondiente.
     * 
     * @var string
     */
    protected $table;

    /**
     * Instancia del modelo asociado a este repositorio.
     * 
     * Esta propiedad almacena una instancia del modelo que se utilizará
     * para realizar operaciones específicas relacionadas con los datos
     * de la tabla asociada.
     * 
     * @var Model
     */
    protected Model $model;

    /**
     * Constructor de la clase Repository.
     * 
     * Inicializa la conexión a la base de datos utilizando la configuración por defecto.
     */
    public function __construct(?Model $model = null)
    {
        if (Settings::$useDatabase) {
            $db = Database::getInstance();
            $this->database = $db->getConnection();
        }

        if ($model) {
            $this->table = $model->table;
            $this->model = $model;
        }
    }

    /**
     * Ejecuta una consulta SQL y devuelve los resultados en formato camelCase.
     *
     * @param string $query La consulta SQL a ejecutar.
     * @param array $map Parámetros de la consulta.
     * @return array Los resultados de la consulta en formato camelCase.
     */
    public function query(string $query, array $map = [])
    {
        if (!$this->database) {
            return false;
        }

        $result = $this->database->query($query, $map)->fetchAll(\PDO::FETCH_ASSOC);
        return $this->convertToCamelCase($result) ?: [];
    }

    /**
     * Ejecuta una transacción en la base de datos.
     * 
     * @param callable $callback Función que contiene las operaciones de la transacción
     * @return mixed El resultado de la transacción o false si falla
     * @throws \Exception Si ocurre un error durante la transacción
     */
    public function transaction(callable $callback, bool $debug = false)
    {
        if (!$this->database) {
            return false;
        }

        if ($debug) {
            $this->database->beginDebug();
        }

        try {
            // Iniciamos la transacción
            $this->database->pdo->beginTransaction();

            // Ejecutamos el callback
            $result = $callback();

            // Si el resultado es false o hubo un error, hacemos rollback
            if ($result === false) {
                $this->database->pdo->rollBack();
                return false;
            }

            // Si todo salió bien, hacemos commit
            $this->database->pdo->commit();

            return $result;
        } catch (\Exception $e) {
            // Si hay cualquier excepción, hacemos rollback
            $this->database->pdo->rollBack();
            throw $e;
        }

        if ($debug) {
            Debug::print($this->database->debugLog());
        }
    }

    /**
     * Obtiene todos los registros de una tabla específica.
     *
     * @param string $table Nombre de la tabla de la que se obtendrán los registros
     * @param array|string $columns Columnas a seleccionar (opcional)
     * @param array $where Condiciones de filtrado (opcional)
     * @param array $options Opciones adicionales como ORDER, LIMIT, etc. (opcional)
     * @return array Retorna un array con todos los registros encontrados
     */
    public function fetchAll(...$args)
    {
        if (!$this->database) {
            return [];
        }

        $args = array_change_key_case($args, CASE_UPPER);

        $table = $args['TABLE'] ?? $this->table;
        $columns = $args['COLUMNS'] ?? "*";
        $where = $args['WHERE'] ?? [];
        $options = $args['OPTIONS'] ?? [];
        $join = $args['JOIN'] ?? null;
        $columnsDiscard = $args['COLUMNS_DISCARD'] ?? [];
        $showQuery = $args['SHOW_QUERY'] ?? false;

        if (isset($this->model)) {
            $columns = (!empty($this->model->columns) && $columns == "*") ? $this->model->columns : $columns;
        }

        if ($join) {
            $joinTables = [];
            $joinColumns = [];

            foreach ($join as $joinItem) {
                switch (strtoupper($joinItem['type'])) {
                    case 'LEFT_JOIN':
                        $joinSymbol = "[>]";
                        break;
                    case 'RIGHT_JOIN':
                        $joinSymbol = "[<]";
                        break;
                    case 'FULL_JOIN':
                        $joinSymbol = "[<>]";
                        break;
                    case 'INNER_JOIN':
                    case 'JOIN':
                    default:
                        $joinSymbol = "[><]";
                        break;
                }

                $joinItem['table'] = strtoupper(HelperString::camelCaseToSnakeCase($joinItem['table']));

                $joinTable = $joinSymbol . $joinItem['table'];

                // Procesar las condiciones ON del JOIN
                if (!isset($joinItem['conditions'])) {
                    continue;
                }

                $onConditions = [];
                if (is_array($joinItem['conditions'])) {
                    foreach ($joinItem['conditions'] as $key => $value) {
                        $snakeKey = strtoupper(HelperString::camelCaseToSnakeCase($key));
                        $snakeValue = strtoupper(HelperString::camelCaseToSnakeCase($value));
                        $onConditions[$snakeKey] = $snakeValue;
                    }
                } else {
                    $onConditions = strtoupper(HelperString::camelCaseToSnakeCase($joinItem['conditions']));
                }

                $joinTables[$joinTable] = $onConditions;

                // Procesar las columnas del JOIN
                if (!isset($joinItem['columns']) || !is_array($joinItem['columns'])) {
                    continue;
                }

                $tableName = $joinItem['table'];
                foreach ($joinItem['columns'] as $columnItem) {
                    if (!is_string($columnItem)) {
                        continue;
                    }
                    $columnName = strtoupper(HelperString::camelCaseToSnakeCase($columnItem));
                    $joinColumns[] = sprintf('%s.%s', $tableName, $columnName);
                }
            }

            $columns = array_map(function ($column) use ($table) {
                return sprintf('%s.%s', $table, $column);
            }, $columns);

            $columns = array_merge($columns, $joinColumns);
        }

        if (!empty($columnsDiscard)) {
            foreach ($columnsDiscard as $column) {
                $columns = array_diff($columns, [strtoupper(HelperString::camelCaseToSnakeCase($column))]);
            }
        }

        $finalWhere = $where;

        // Aplicar opciones adicionales al array where
        if (!empty($options)) {
            $finalWhere = array_merge($finalWhere, $options);
        }

        $finalWhere = $this->whereCamelCaseToSnakeCase($finalWhere);

        if ($showQuery) {
            $this->database->beginDebug();
        }

        if ($join) {
            $result = $this->database->select($table, $joinTables, $columns, $finalWhere);
        } else {
            $result = $this->database->select($table, $columns, $finalWhere);
        }

        if ($showQuery) {
            Debug::print($this->database->debugLog());
        }

        return $this->convertToCamelCase($result) ?: [];
    }

    /**
     * Busca un registro en la base de datos por una columna específica.
     * 
     * Este método busca un registro en la tabla asociada al repositorio utilizando el valor de una columna específica.
     * Si se proporciona un modelo, se utilizarán sus columnas definidas para la consulta.
     * 
     * @param string $column El nombre de la columna por la que se busca.
     * @param mixed $value El valor que se busca en la columna especificada.
     * @param string|array $columns Las columnas a seleccionar. Por defecto, se seleccionan todas las columnas.
     * @return array|null Devuelve un array con los resultados encontrados o null si no se encuentra ningún registro.
     */
    public function fetchByColumn(...$args)
    {
        if (!$this->database) {
            return null;
        }

        $args = array_change_key_case($args, CASE_UPPER);

        $table = $args['TABLE'] ?? $this->table;
        $column = $args['COLUMN'] ?? '';
        $value = $args['VALUE'] ?? '';
        $columns = $args['COLUMNS'] ?? '*';
        $where = $args['WHERE'] ?? [];
        $join = $args['JOIN'] ?? null;
        $columnsDiscard = $args['COLUMNS_DISCARD'] ?? [];
        $showQuery = $args['SHOW_QUERY'] ?? false;

        if (isset($this->model)) {
            if (is_array($columns)) {
                $columnsArray = [];

                foreach ($columns as $v) {
                    $columnsArray[] = strtoupper(HelperString::camelCaseToSnakeCase($v));
                }

                $columns = $columnsArray;
            } else {
                $columns = (!empty($this->model->columns)) ? $this->model->columns : $columns;
            }
        }

        if (!empty($column) && !empty($value)) {
            $where[(($join) ? $table . '.' : '') . strtoupper(HelperString::camelCaseToSnakeCase($column))] = $value;
        }

        if ($join) {
            $joinTables = [];
            $joinColumns = [];

            foreach ($join as $joinItem) {
                switch (strtoupper($joinItem['type'])) {
                    case 'LEFT_JOIN':
                        $joinSymbol = "[>]";
                        break;
                    case 'RIGHT_JOIN':
                        $joinSymbol = "[<]";
                        break;
                    case 'FULL_JOIN':
                        $joinSymbol = "[<>]";
                        break;
                    case 'INNER_JOIN':
                    case 'JOIN':
                    default:
                        $joinSymbol = "[><]";
                        break;
                }

                $joinItem['table'] = strtoupper(HelperString::camelCaseToSnakeCase($joinItem['table']));

                $joinTable = $joinSymbol . $joinItem['table'];

                // Procesar las condiciones ON del JOIN
                if (!isset($joinItem['conditions'])) {
                    continue;
                }

                $onConditions = [];
                if (is_array($joinItem['conditions'])) {
                    foreach ($joinItem['conditions'] as $key => $value) {
                        $snakeKey = strtoupper(HelperString::camelCaseToSnakeCase($key));
                        $snakeValue = strtoupper(HelperString::camelCaseToSnakeCase($value));
                        $onConditions[$snakeKey] = $snakeValue;
                    }
                } else {
                    $onConditions = strtoupper(HelperString::camelCaseToSnakeCase($joinItem['conditions']));
                }

                $joinTables[$joinTable] = $onConditions;

                // Procesar las columnas del JOIN
                if (!isset($joinItem['columns']) || !is_array($joinItem['columns'])) {
                    continue;
                }

                $tableName = $joinItem['table'];
                foreach ($joinItem['columns'] as $columnItem) {
                    if (!is_string($columnItem)) {
                        continue;
                    }
                    $columnName = strtoupper(HelperString::camelCaseToSnakeCase($columnItem));
                    $joinColumns[] = sprintf('%s.%s', $tableName, $columnName);
                }
            }

            $columns = array_map(function ($column) use ($table) {
                return sprintf('%s.%s', $table, $column);
            }, $columns);

            $columns = array_merge($columns, $joinColumns);
        }

        if (!empty($columnsDiscard)) {
            foreach ($columnsDiscard as $column) {
                $columns = array_diff($columns, [strtoupper(HelperString::camelCaseToSnakeCase($column))]);
            }
        }

        $finalWhere = $this->whereCamelCaseToSnakeCase($where);

        if ($showQuery) {
            $this->database->beginDebug();
        }

        if ($join) {
            $result = $this->database->get($table, $joinTables, $columns, $finalWhere);
        } else {
            $result = $this->database->get($table, $columns, $finalWhere);
        }

        if ($showQuery) {
            Debug::print($this->database->debugLog());
        }

        if (!is_null($result)) {
            $result = $this->convertToCamelCase($result);
        }

        return $result ?: null;
    }

    /**
     * Crea un nuevo registro en la tabla especificada.
     *
     * @param string $table Nombre de la tabla donde se insertará el registro
     * @param array $data Datos a insertar (formato columna => valor)
     * @return int|bool ID del registro insertado o false en caso de error
     */
    public function create(...$args)
    {
        if (!$this->database) {
            return false;
        }

        $args = array_change_key_case($args, CASE_UPPER);

        $dataCopy = $args['DATA'] ?? [];
        $data = [];

        foreach ($dataCopy as $key => $value) {
            $data[strtoupper(HelperString::camelCaseToSnakeCase($key))] = $value;
        }

        if (isset($this->model)) {
            $filteredArray = array_filter($this->model->columns, function ($item) {
                return strpos($item, $this->model->primaryKey . " (") === 0 || $item === $this->model->primaryKey;
            });

            $primaryKey = strtoupper(array_values($filteredArray)[0]);

            if (preg_match('/\((.*?)\)/', $primaryKey, $matches)) {
                $primaryKey = $matches[1];
            }

            if (array_key_exists($primaryKey, $data)) {
                unset($data[$primaryKey]);
            }
        }

        // Insertar datos usando Medoo
        $result = $this->database->insert($this->table, $data);

        // Si la inserción fue exitosa, devolver el último ID insertado
        if ($result && $result->rowCount() > 0) {
            return (int) $this->database->id();
        }

        return false;
    }

    /**
     * Actualiza registros en una tabla según las condiciones especificadas.
     *
     * @param string $table Nombre de la tabla a actualizar
     * @param array $data Datos a actualizar (formato columna => valor)
     * @param array $where Condiciones para determinar qué registros actualizar
     * @return int|bool Número de filas afectadas o false en caso de error
     */
    public function update(...$args)
    {
        if (!$this->database) {
            return false;
        }

        $args = array_change_key_case($args, CASE_UPPER);

        $data = $args['DATA'] ?? [];
        $where = $args['WHERE'] ?? [];

        $dataCopy = $data;
        $data = [];

        foreach ($dataCopy as $key => $value) {
            $data[strtoupper(HelperString::camelCaseToSnakeCase($key))] = $value;
        }

        if (isset($this->model)) {
            if (array_key_exists($this->model->primaryKey, $data)) {
                unset($data[$this->model->primaryKey]);
            }
        }

        $where = $this->whereCamelCaseToSnakeCase($where);

        // Actualizar datos usando Medoo
        $result = $this->database->update($this->table, $data, $where);

        // Devolver el número de filas afectadas
        return $result ? (int) $result->rowCount() : false;
    }

    /**
     * Elimina registros de una tabla según las condiciones especificadas.
     *
     * @param string $table Nombre de la tabla de donde eliminar
     * @param array $where Condiciones para determinar qué registros eliminar
     * @return int|bool Número de filas afectadas o false en caso de error
     */
    public function delete(...$args)
    {
        if (!$this->database) {
            return false;
        }

        $args = array_change_key_case($args, CASE_UPPER);

        $where = $args['WHERE'] ?? [];

        // Verificar que $where no esté vacío para evitar eliminar toda la tabla
        if (empty($where)) {
            return false;
        }

        $where = $this->whereCamelCaseToSnakeCase($where);

        // Eliminar datos usando Medoo
        $result = $this->database->delete($this->table, $where);

        // Devolver el número de filas afectadas
        return $result ? (int) $result->rowCount() : false;
    }

    /**
     * Convierte las claves de un array de camelCase a SNAKE_CASE.
     *
     * Este método procesa un array y convierte las claves de formato camelCase a
     * formato SNAKE_CASE, omitiendo las claves que son palabras reservadas. Si
     * el valor de una clave es un array, el método se llama recursivamente para
     * procesar las claves dentro de ese array.
     *
     * @param array $data El array a procesar, cuyas claves se convertirán.
     * @param array $reservedWords Un array de palabras reservadas que no deben ser convertidas.
     * @return array Un nuevo array con las claves convertidas a SNAKE_CASE.
     */
    public function whereCamelCaseToSnakeCase(array $data, array $reservedWords = ["OR", "AND", "ORDER", "MATCH", "LIMIT", "GROUP", "HAVING"]): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            // Verificar si la clave es una palabra reservada
            if (in_array($key, $reservedWords)) {
                // Si la clave es reservada, mantenerla igual pero procesar su valor si es un array
                if (is_array($value)) {
                    $result[$key] = $this->whereCamelCaseToSnakeCase($value, $reservedWords);
                } else {
                    $result[$key] = $value;
                }
            } else {
                // Si no es reservada, convertir la clave a SNAKE_CASE
                $newKey = strtoupper(HelperString::camelCaseToSnakeCase($key));

                // Si el valor es un array, procesar recursivamente
                if (is_array($value)) {
                    $result[$newKey] = $this->whereCamelCaseToSnakeCase($value, $reservedWords);
                } else {
                    $result[$newKey] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * Convierte las claves de un array de snake_case a camelCase
     * 
     * @param array $data Array a convertir
     * @return array Array con claves en camelCase
     */
    public function convertToCamelCase(array $data): array
    {
        if (empty($data)) {
            return [];
        }

        // Si es un array simple (no array de arrays)
        if (!is_array(reset($data))) {
            $result = [];
            foreach ($data as $key => $value) {
                $camelCaseKey = HelperString::snakeCaseToCamelCase($key);
                $result[$camelCaseKey] = $value;
            }
            return $result;
        }

        // Si es un array de arrays
        return array_map(function ($item) {
            return $this->convertToCamelCase($item);
        }, $data);
    }
}
