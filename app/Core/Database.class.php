<?php

namespace Core;

use Core\Interfaces\DatabaseInterface;
use Medoo\Medoo;

/**
 * Clase Database
 * 
 * Esta clase proporciona métodos para interactuar con la base de datos.
 * Implementa el patrón Singleton para asegurar una única instancia global.
 * 
 * @package BuriPHP
 * @author Kiske
 * @since 1.0
 * @version 2.5
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
class Database implements DatabaseInterface
{
    /**
     * Instancia única de la clase Database
     * 
     * @var Database|null
     */
    private static ?Database $instance = null;

    /**
     * Almacena las instancias de conexión a bases de datos.
     * 
     * @var array
     */
    private static $connections = [];

    /**
     * Constructor privado para prevenir instanciación directa
     */
    private function __construct() {}

    /**
     * Previene la clonación del objeto
     */
    private function __clone() {}

    /**
     * Obtiene la instancia única de Database
     * 
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Crea una nueva conexión a la base de datos utilizando la biblioteca Medoo.
     * 
     * Este método configura y devuelve una nueva instancia de Medoo con los parámetros
     * de conexión proporcionados en las opciones o, si no se proporcionan, utiliza los
     * valores definidos en el entorno.
     * 
     * @param array $options Opciones de configuración para la conexión a la base de datos.
     *                      Puede incluir: type, host, database, username, password, charset, port, prefix.
     *                      Si no se proporciona un valor, se utilizará el correspondiente del entorno.
     * @return Medoo Instancia de la clase Medoo configurada con los parámetros de conexión.
     */
    private function createConnection($options = []): Medoo
    {
        $environment = Environment::getInstance();

        return new Medoo([
            // [required]
            'type' => $options['type'] ?? $environment->get('DB_TYPE'),
            'host' => $options['host'] ?? $environment->get('DB_HOST'),
            'database' => $options['database'] ?? $environment->get('DB_NAME'),
            'username' => $options['username'] ?? $environment->get('DB_USER'),
            'password' => $options['password'] ?? $environment->get('DB_PASS'),

            // [optional]
            'charset' => $options['charset'] ?? $environment->get('DB_CHARSET'),
            'port' => $options['port'] ?? $environment->get('DB_PORT'),

            // [optional] The table prefix. All table names will be prefixed as PREFIX_table.
            'prefix' => $options['prefix'] ?? $environment->get('DB_PREFIX')
        ]);
    }

    /**
     * Obtiene una instancia de conexión a la base de datos.
     * Si ya existe una instancia con la clave proporcionada, la retorna.
     * Si no existe, crea una nueva instancia con las opciones proporcionadas.
     *
     * @param string $key Clave única para identificar la instancia de la conexión.
     * @param array $options Opciones para configurar la conexión a la base de datos.
     * @return Medoo Instancia de la clase Medoo.
     */
    public function getConnection(string $key = 'default', array $options = []): Medoo
    {
        if (!isset(self::$connections[$key])) {
            self::$connections[$key] = $this->createConnection($options);
        }

        return self::$connections[$key];
    }

    /**
     * Cierra una conexión específica o todas las conexiones.
     *
     * @param string|null $key Clave de la conexión a cerrar. Si es null, cierra todas las conexiones.
     * @return bool True si se cerró correctamente, false en caso contrario.
     */
    public function closeConnection(?string $key = null): bool
    {
        if ($key !== null) {
            if (isset(self::$connections[$key])) {
                self::$connections[$key] = null;
                unset(self::$connections[$key]);
                return true;
            }
            return false;
        }

        self::$connections = [];
        return true;
    }
}
