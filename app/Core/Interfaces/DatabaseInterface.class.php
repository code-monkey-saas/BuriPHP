<?php

namespace Core\Interfaces;

use Medoo\Medoo;

/**
 * Interface DatabaseInterface
 *
 * Esta interfaz define los métodos que debe implementar un manejador de base de datos en BuriPHP.
 * 
 * @package BuriPHP\Interfaces
 * @author Kiske
 * @since 2.0Alpha
 * @version 1.2
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
interface DatabaseInterface
{
    /**
     * Obtiene la instancia única de Database
     * 
     * @return self
     */
    public static function getInstance(): self;

    /**
     * Obtiene una conexión a la base de datos.
     * Este método devuelve una conexión existente según la clave proporcionada.
     * 
     * @param string $key La clave de la conexión deseada.
     * @param array $options Opciones adicionales para la conexión.
     * @return Medoo La instancia de conexión a la base de datos.
     */
    public function getConnection(string $key = 'default', array $options = []): Medoo;

    /**
     * Cierra una conexión a la base de datos.
     * Este método cierra la conexión especificada o la conexión por defecto si no se proporciona una clave.
     * 
     * @param string|null $key La clave de la conexión a cerrar. Si es nula, se cierra la conexión por defecto.
     * @return bool Devuelve verdadero si la conexión se cerró exitosamente, falso en caso contrario.
     */
    public function closeConnection(?string $key = null): bool;
}
