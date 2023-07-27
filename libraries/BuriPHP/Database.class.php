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

    /**
     * Convierte los keys en CamelCase de un array a SNAKE_CASE en mayúsculas.
     *
     * @param array $data
     *
     * @return array
     */
    final static function camelToSnake($data)
    {
        if (is_array($data)) {
            $response = [];

            foreach ($data as $key => $value) {
                if ($key !== strtoupper($key)) {
                    $response[preg_replace('/(?<!^)[A-Z]/', '_$0', $key)] = $value;
                } else {
                    $response[$key] = $value;
                }
            }

            return array_change_key_case($response, CASE_UPPER);
        }

        if (ctype_upper($data)) {
            return strtoupper($data);
        }

        return strtoupper(preg_replace('/(?<!^)[A-Z]/', '_$0', $data));
    }

    /**
     * Convierte los keys en SNAKE_CASE de un array a CamelCase.
     *
     * @param array $data
     *
     * @return array
     */
    final static function snakeToCamel($data)
    {
        if (is_array($data)) {
            $response = [];

            foreach ($data as $k => $v) {
                foreach ($v as $key => $value) {
                    if ($key !== lcfirst(ucwords($key))) {
                        $response[$k][lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($key)))))] = $value;
                    }
                }
            }

            return $response;
        }

        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($data)))));
    }
}
