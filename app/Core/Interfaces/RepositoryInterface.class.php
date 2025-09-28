<?php

namespace Core\Interfaces;

use Core\Model;

/**
 * Interface RepositoryInterface
 *
 * Esta interfaz define los métodos que debe implementar un repositorio en BuriPHP.
 * 
 * @package BuriPHP\Interfaces
 * @author Kiske
 * @since 2.0Alpha
 * @version 1.1
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
interface RepositoryInterface
{
    /**
     * Constructor del repositorio.
     * Este método se utiliza para inicializar el repositorio con un modelo opcional.
     * 
     * @param Model|null $model Instancia del modelo a utilizar en el repositorio. Puede ser nula si no se proporciona.
     */
    public function __construct(?Model $model = null);

    /**
     * Ejecuta una consulta SQL y devuelve los resultados en formato camelCase.
     *
     * @param string $query La consulta SQL a ejecutar.
     * @param array $map Parámetros de la consulta.
     * @return array Los resultados de la consulta en formato camelCase.
     */
    public function query(string $query, array $map = []);

    /**
     * Ejecuta una transacción en la base de datos.
     * 
     * @param callable $callback Función que contiene las operaciones de la transacción
     * @return mixed El resultado de la transacción o false si falla
     * @throws \Exception Si ocurre un error durante la transacción
     */
    public function transaction(callable $callback);

    /**
     * Obtiene todos los registros de una tabla específica.
     *
     * @param string $table Nombre de la tabla de la que se obtendrán los registros
     * @param array|string $columns Columnas a seleccionar (opcional)
     * @param array $where Condiciones de filtrado (opcional)
     * @param array $options Opciones adicionales como ORDER, LIMIT, etc. (opcional)
     * @return array Retorna un array con todos los registros encontrados
     */
    public function fetchAll(...$args);

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
    public function fetchByColumn(...$args);

    /**
     * Crea un nuevo registro en la tabla especificada.
     *
     * @param string $table Nombre de la tabla donde se insertará el registro
     * @param array $data Datos a insertar (formato columna => valor)
     * @return int|bool ID del registro insertado o false en caso de error
     */
    public function create(...$args);

    /**
     * Actualiza registros en una tabla según las condiciones especificadas.
     *
     * @param string $table Nombre de la tabla a actualizar
     * @param array $data Datos a actualizar (formato columna => valor)
     * @param array $where Condiciones para determinar qué registros actualizar
     * @return int|bool Número de filas afectadas o false en caso de error
     */
    public function update(...$args);

    /**
     * Elimina registros de una tabla según las condiciones especificadas.
     *
     * @param string $table Nombre de la tabla de donde eliminar
     * @param array $where Condiciones para determinar qué registros eliminar
     * @return int|bool Número de filas afectadas o false en caso de error
     */
    public function delete(...$args);
}
