<?php

/**
 * @package BuriPHP.Libraries.Helpers
 * 
 * @abstract
 *
 * @since 2.0Alpha
 * @version 1.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

namespace Libraries\BuriPHP\Helpers;

abstract class HelperGet
{
    /**
     * Devuelve una variable del GET como un string
     * No contiene carcatres html especiales.
     * Los valores enviadors mediante el GET, han de ser tratados 
     * previamente con urlencode.
     *
     * @param string $key
     *
     * @return string
     */
    public static function getString($key)
    {
        return htmlspecialchars(filter_input(INPUT_GET, $key));
    }

    /**
     * Devuelve el valor de una variable del GET como un integer.
     * Si no es un integer, devuelve null.
     * Max int: 2147483647
     *
     * @param string $key
     *
     * @return int|bool
     */
    public static function getInt($key)
    {
        /* Max  2147483647 */
        $ret = filter_input(INPUT_GET, $key);
        if (strval($ret) !== strval(intval($ret))) {
            return null;
        }
        return intval($ret);
    }

    /**
     * Transforma un valor del GET formato SI/NO-YES/NO-Y/S-S/N-1/0 a bool.
     * Devuelve -1 si no se puede convertir a true o false
     *
     * @param $key
     *
     * @return bool
     */
    public static function getBool($key)
    {
        return HelperConvert::toBool('' . filter_input(INPUT_GET, $key));
    }
}
