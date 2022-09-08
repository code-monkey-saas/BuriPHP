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

abstract class HelperPost
{
    /**
     * Devuelve una variable del POST como un string
     * " => &quot;
     *
     * @param string $key
     * @param bool   $convertSpatialChar
     *
     * @return string
     */
    public static function getString($key, $convertSpatialChar = false)
    {
        $ret = filter_input(INPUT_POST, $key);
        if ($convertSpatialChar) {
            return htmlspecialchars($ret);
        } else {
            return '' . $ret;
        }
    }

    /**
     * Devuelve el valor de una variable del POST como un integer.
     * Si no es un integer, devuelve false.
     * Max int: 2147483647
     *
     * @param string $key
     *
     * @return int|bool
     */
    public static function getInt($key)
    {
        /* Max  2147483647 */
        $ret = filter_input(INPUT_POST, $key);
        if (strval($ret) !== strval(intval($ret))) {
            return null;
        }
        return intval($ret);
    }

    /**
     * Devuelve un texto swl POST con tags de formato html.
     *
     * @param string $key
     *
     * @return string|null
     */
    public static function getHtml($key)
    {
        /* NO contiene htmlspecialchars() */
        return '' . filter_input(INPUT_POST, $key);
    }

    /**
     * Devuelve el valor de una variable del POST como un double.
     * Si no es un integer o double, devuelve null.
     *
     * @param string $key
     *
     * @return double|bool
     */
    public static function getFloat($key)
    {
        $value = str_replace(',', '.', filter_input(INPUT_POST, $key));
        $ret = filter_var(
            $value,
            FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION
        );
        if (false === $ret) {
            return null;
        }
        /* No usar doubleval */
        return (float)($value);
    }

    /**
     * Devuelve un string del POST.
     * No se comprueba el formato de la fecha.
     *
     * @param string $key
     *
     * @return string
     */
    public static function getDateTime($key)
    {
        /* Devuelve un string normal. El formato de fecha y hora ha de 
           ser comprobado desde afuera */
        /* ya que puede estar vacío */
        return '' . filter_input(INPUT_POST, $key);
    }

    /**
     * Devuelve un string de fecha del POST.
     * No se comprueba el formato de la fecha.
     *
     * @param string $key
     *
     * @return string
     */
    public static function getDate($key)
    {
        /* Devuelve un string normal. El formato de fecha ha de ser
           comprobado desde afuera */
        /* ya que puede estar vacío */
        return '' . filter_input(INPUT_POST, $key);
    }

    /**
     * Transforma un valor del POST formato SI/NO-YES/NO-Y/S-S/N-1/0 a
     * bool.
     * Devuelve -1 si no se puede convertir a true o false
     *
     * @param $key
     *
     * @return bool
     */
    public static function getBool($key)
    {
        return HelperConvert::toBool('' . filter_input(
            INPUT_POST,
            $key
        ));
    }

    /**
     * Devuelve una variable del POST como un array.
     *
     * @param string $key
     *
     * @return array
     * @see validIsEmpty()
     */
    public static function getArray($key)
    {
        $arrayTmp = filter_input(
            INPUT_POST,
            $key,
            FILTER_DEFAULT,
            FILTER_REQUIRE_ARRAY
        );

        if (HelperValidate::isEmpty($arrayTmp)) {
            /* Puede que sólo haya un valor, con lo que no es un array */
            $tmp = filter_input(INPUT_POST, $key);

            if (HelperValidate::isEmpty($tmp)) {
                return [];
            }
            return [$tmp];
        }
        return $arrayTmp;
    }
}
