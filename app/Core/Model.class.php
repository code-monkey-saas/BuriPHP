<?php

namespace Core;

use Config\Settings;
use Core\Helpers\HelperString;
use Core\Interfaces\ModelInterface;

/**
 * Clase Model
 * 
 * Esta clase proporciona modelos esenciales para la aplicación.
 * 
 * @package BuriPHP
 * @author Kiske
 * @since 2.0Alpha
 * @version 1.7
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
class Model implements ModelInterface
{
    /**
     * Variables del modelo.
     * 
     * Este array almacena los valores de las propiedades del modelo. Se utiliza
     * para gestionar los datos que se asignan a las propiedades del modelo.
     * 
     * @var array
     */
    protected $variables = [];

    /**
     * Constructor de la clase Model.
     * 
     * Este constructor inicializa una nueva instancia del modelo. Verifica que
     * se haya declarado la tabla y la llave primaria, y asigna valores a las
     * propiedades del modelo basándose en las columnas definidas.
     * 
     * @throws \Error Si no se declara la tabla o la llave primaria en el modelo.
     */
    public function __construct()
    {
        if (!isset($this->table)) {
            trigger_error("Error: No se declaro la tabla en el modelo.", E_USER_ERROR);
        }

        if (!isset($this->primaryKey)) {
            trigger_error("Error: No se declaro la llave primaria en el modelo.", E_USER_ERROR);
        }

        if (!isset($this->columns)) {
            trigger_error("Error: No se declaro las columnas en el modelo.", E_USER_ERROR);
        }

        $this->createSql();

        $this->columns = array_keys(array_filter($this->columns, function ($valor) {
            return is_array($valor);
        }));

        foreach ($this->columns as $value) {
            if (preg_match('/\((.*?)\)/', $value, $matches)) {
                $value = $matches[1];
            } else {
                $value = preg_replace(['/\s*\(.*?\)\s*/', '/\s*\[.*?\]\s*/'], ' ', $value);
            }

            $value = trim($value);
            $this->$value = null;
        }
    }

    /**
     * Establece el valor de una propiedad del modelo.
     * 
     * Este método mágico permite asignar valores a las propiedades del modelo,
     * convirtiendo automáticamente los nombres de las propiedades de camelCase a SNAKE_CASE.
     *
     * @param string $name El nombre de la propiedad en formato camelCase
     * @param mixed $value El valor que se asignará a la propiedad
     * @return void
     */
    public function __set(string $name, mixed $value): void
    {
        $name = HelperString::toUpper(HelperString::camelCaseToSnakeCase($name));
        $this->variables[$name] = $value;
    }

    /**
     * Obtiene el valor de una propiedad del modelo.
     * 
     * Este método mágico permite acceder a los valores de las propiedades del modelo,
     * convirtiendo automáticamente los nombres de las propiedades de camelCase a SNAKE_CASE.
     *
     * @param string $name El nombre de la propiedad en formato camelCase
     * @return mixed El valor de la propiedad especificada o null si no existe
     */
    public function __get(string $name): mixed
    {
        $name = HelperString::toUpper(HelperString::camelCaseToSnakeCase($name));
        return $this->variables[$name];
    }

    /**
     * Convierte las variables del modelo a un array asociativo.
     * 
     * Este método transforma todas las propiedades del modelo almacenadas
     * en el array $variables a un array asociativo, convirtiendo las llaves
     * de SNAKE_CASE a camelCase para mantener la consistencia en la presentación
     * de los datos.
     *
     * @return array Un array asociativo que representa el modelo con llaves en camelCase
     */
    public function toArray(): array
    {
        $variables = [];

        foreach ($this->variables as $key => $value) {
            $variables[HelperString::snakeCaseToCamelCase($key)] = $value;
        }

        return $variables;
    }

    /**
     * Crea la tabla en la base de datos si no existe.
     * 
     * Este método verifica si la tabla definida en el modelo ya existe en la base de datos.
     * Si la tabla no existe, se crea utilizando las columnas definidas en el modelo.
     * 
     * @return bool Devuelve true si la tabla fue creada exitosamente, o false si ya existía.
     * 
     * @throws \Exception Lanza una excepción si hay un error al intentar crear la tabla.
     */
    public function createSql()
    {
        if (!Settings::$useDatabase) {
            return false;
        }

        $database = Database::getInstance();
        $database = $database->getConnection();

        $environment = Environment::getInstance();

        $tableExists = $database->query(
            "SELECT COUNT(*) AS table_exists 
            FROM information_schema.tables 
            WHERE table_schema=:database 
            AND table_name=:table;",
            [
                ":database" => $environment->get('DB_NAME'),
                ":table" => $this->table
            ]
        )->fetchAll();

        if (isset($tableExists[0]) && $tableExists[0]['table_exists'] == 0) {
            $columns = [];

            foreach ($this->columns as $key => $value) {
                // Verificar si el elemento tiene clave (es un string) o no (es un índice numérico)
                if (is_string($key)) {
                    // Eliminar textos entre paréntesis y corchetes
                    $cleanKey = preg_replace('/\s*\([^)]*\)|\s*\[[^\]]*\]/', '', $key);
                    $columns[$cleanKey] = $value;
                } else {
                    // Mantener elementos sin clave (como las restricciones)
                    $columns[] = $value;
                }
            }

            $database->create($this->table, $columns);
            return true;
        }

        return false;
    }
}
