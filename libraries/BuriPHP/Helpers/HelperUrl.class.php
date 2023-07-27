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

abstract class HelperUrl
{
    /**
     * Quita caracteres especiales de un string.
     *
     * @static
     *
     * @param   string    $str    Cadena de texto
     *
     * @return  string
     */
    public static function urlSanitized($str)
    {
        if (!HelperValidate::isEmpty($str)) {
            $str = HelperString::sanitizeBlanks($str);
            $str = HelperString::sanitizeAll($str);
        }

        return $str;
    }

    /**
     * Devuelve el query string de la url
     *
     * @param string $url
     *
     * @return string
     */
    public static function getQueryString($url)
    {
        $ret = strpos($url, '?');
        if (false === $ret) {
            return null;
        }
        return substr($url, ($ret + 1), strlen($url));
    }

    /**
     * Devuelve el contenido de la página de la url
     *
     * @param string $httpUrl
     *
     * @return string
     */
    public static function getPageContent($httpUrl)
    {
        $file = fopen($httpUrl, "r");
        $line = '';
        if ($file) {
            while (!feof($file)) {
                $line .= fread($file, 1024 * 50);
            }
            return $line;
        }
        return false;
    }

    /**
     * Devuelve el nombre del host de una url
     * Ex: "http://www.php.net/index.html" => www.php.net
     *
     * @param string $url
     *
     * @return string
     */
    public static function getHost($txt)
    {
        return parse_url($txt)['host'];
    }

    /**
     * Devuelve un array con los parametros y valores o null si no hay 
     * valores
     *
     * @param string $url
     *
     * @return array
     */
    public static function getArrayParams($url)
    {
        if (false === strpos($url, '?')) {
            // No hay parámetros
            return null;
        }

        $q = self::getQueryString($url);
        $t = [];
        $a = explode('&', $q);
        foreach ($a as $key => $value) {
            $b = explode('=', $a[$key]);

            $t[$b[0]] = $b[1];
        }
        return $t;
    }

    /**
     * Codifica como URL una cadena
     * Ex:    'http://www.php.es/par word/any?no=true&si=23'
     *        'http%3A%2F%2Fwww.php.es%2Fpar%20word%2Fany%3Fno%3Dtrue%26si%3D23'
     *
     * @param string $url
     *
     * @return string
     */
    public static function encode($url)
    {
        return urlencode($url);
    }

    /**
     * Decodifica una cadena cifrada como URL
     * Ex:    'http%3A%2F%2Fwww.php.es%2Fpar%20word%2Fany%3Fno%3Dtrue%26si%3D23'
     *         'http://www.php.es/par word/any?no=true&si=23'
     *
     * @param string $urlEncoded
     *
     * @return string
     */
    public static function decode($urlEncoded)
    {
        return urldecode($urlEncoded);
    }

    /**
     * Añade un parámetro que es un número aleatorio.
     * Se usa para que cada url sea diferente
     *
     * @param string $url
     * @param int    $lenRandom
     *
     * @return string
     */
    public static function addRandom($url, $lenRandom = 4)
    {
        $sep = '&';
        if (false === strpos($url, '?')) {
            $sep = '?';
        }
        $url .= $sep . "rnd=" . HelperString::random($lenRandom);
        return $url;
    }

    /**
     * Devuelve una nueva url con un parrámetro/valor añadido
     *
     * @param $url
     * @param $parameter
     * @param $value
     *
     * @return string
     */
    public static function addParam($url, $parameter, $value)
    {
        $sep = '&';
        if (false === strpos($url, '?')) {
            $sep = '?';
        }
        $url .= $sep . $parameter . '=' . $value;
        return $url;
    }
}
