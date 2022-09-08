<?php

/**
 * @package BuriPHP.Libraries
 *
 * @since 1.0
 * @version 2.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

namespace Libraries\BuriPHP;

use BuriPHP\Settings;
use Medoo\Medoo;

include_once PATH_BURIPHP_LIBRARIES . 'Medoo.php';

class Database
{
    /**
     * Crea una instancia a la base de datos.
     * Se envia un array con las opciones para hacer la conexion.
     *
     * @param array $options
     *
     * @return mixed
     */
    final public function newInstance($options = [])
    {
        $arr = [
            // [required]
            'type' => isset($options['type']) ? $options['type'] : Settings::$dbType,
            'host' => isset($options['host']) ? $options['host'] : Settings::$dbHost,
            'database' => isset($options['database']) ? $options['database'] : Settings::$dbName,
            'username' => isset($options['username']) ? $options['username'] : Settings::$dbUser,
            'password' => isset($options['password']) ? $options['password'] : Settings::$dbPass,

            // [optional]
            'charset' => isset($options['charset']) ? $options['charset'] : Settings::$dbCharset,
            'port' => isset($options['port']) ? $options['port'] : Settings::$dbPort,

            // [optional] The table prefix. All table names will be prefixed as PREFIX_table.
            'prefix' => isset($options['port']) ? $options['port'] : Settings::$dbPrefix
        ];

        return new Medoo([
            'type' => $arr['type'],
            'host' => $arr['host'],
            'database' => $arr['database'],
            'username' => $arr['username'],
            'password' => $arr['password']
        ]);
    }
}
