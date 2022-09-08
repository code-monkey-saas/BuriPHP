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

abstract class HelperServer
{
    /**
     * Devuevle la url actual
     * 
     * @return string
     */
    public static function getCurrentUrl()
    {
        $domain = self::getDomainHttp();
        $file   = self::getValue('SCRIPT_NAME');
        $qs     = self::getQueryString();
        if (empty($qs)) {
            return $domain . $file;
        } else {
            return $domain . $file . '?' . $qs;
        }
    }

    /**
     * Obtiene la url dividida en array.
     *
     * @return array
     */
    public static function getCurrentPathInfo()
    {
        if (!empty(self::getValue('PATH_INFO'))) {
            $pathInfo = self::getValue('PATH_INFO');
        } else if (!empty(self::getValue('ORIG_PATH_INFO'))) {
            $pathInfo = self::getValue('ORIG_PATH_INFO');
        } else {
            $pathInfo = '';
        }

        if (!empty($pathInfo)) {
            $arrUrl = explode('/', $pathInfo);
            $arrUrl = HelperArray::compact($arrUrl);

            foreach ($arrUrl as $key => $value) {
                $arrUrl[$key] = HelperUrl::urlSanitized($value);
            }

            return $arrUrl;
        } else {
            return HelperConvert::toArray("/");
        }
    }

    /**
     * Devuelve el valor de una variable del array superglobal 
     * $_SERVER
     * NOTA: filter_input para las opciones INPUT_SERVER y INPUT_ENV 
     * no funcionan para FASTCGI
     * Si la variable no existe, devuelve '' (string vacío)
     *
     * @param string $key
     *
     * @return string
     */
    public static function getValue($key)
    {
        if (filter_has_var(INPUT_SERVER, $key)) {
            $value = filter_input(INPUT_SERVER, $key, FILTER_UNSAFE_RAW);
        } else {
            $value = '';
            if (array_key_exists($key, $_SERVER)) {
                $value = filter_var($_SERVER[$key], FILTER_UNSAFE_RAW);
            }
            return $value;
        }
        if ($value === false || is_null($value)) {
            return '';
        }
        return $value;
    }

    /**
     * Comprueba si el navegador soporta compresión con gzip
     * @return bool
     */
    public static function compressionZip()
    {
        $acceptEnc = HelperServer::getValue('HTTP_ACCEPT_ENCODING');

        return (stripos($acceptEnc, 'gzip') >= 0 ||
            stripos($acceptEnc, 'x-gzip') >= 0);
    }

    /**
     * Devuelve un valor de un parámetro de la query_string.
     * Devuelve null si no existe
     *
     * @param $parameter
     *
     * @return bool|mixed
     */
    public static function getQueryStringParam($parameter)
    {
        $matches = array();
        $qs      = self::getQueryString();
        $number  = preg_match("/{$parameter}=([a-zA-Z0-9_-]+)[&]?/", $qs, $matches);

        if ($number) {
            return '' . $matches[1];
        } else {
            return null;
        }
    }

    /**
     * Devuelve un array con los parametros y valores de la 
     * query_string
     * @return array
     */
    public static function getQueryStringParams()
    {
        $qs = self::getQueryString();
        if (HelperValidate::isEmpty($qs)) {
            return [];
        }
        $arrayTmp    = [];
        $arrayParams = explode('&', $qs);
        foreach ($arrayParams as $key => $value) {

            $b = explode('=', $arrayParams[$key]);

            $arrayTmp[$b[0]] = $b[1];
        }
        return $arrayTmp;
    }

    /**
     * Devuelve la query string completa de la url
     * @return string
     */
    public static function getQueryString()
    {
        return self::getValue('QUERY_STRING');
    }

    /**
     * Devuevle la url actual
     * 
     * @return string
     */
    public static function getDomain()
    {
        return HelperUrl::getHost(self::getCurrentUrl());
    }

    /**
     * Devuelve el Dominio con protocolo Http/https del servidor
     * @return string
     */
    public static function getDomainHttp()
    {
        $httpHost = self::getValue('HTTP_HOST');
        if (empty($httpHost)) {
            return '';
        }

        return self::getProtocol() . "://" . $httpHost;
    }

    /**
     * Devuelve el protocolo Http/https del servidor
     * @return string
     */
    public static function getProtocol()
    {
        return (!empty(self::getValue('HTTPS')) &&
            (self::getValue('HTTPS') == 'on' || self::getValue('HTTPS') == 1) ||
            !empty(self::getValue('HTTP_X_FORWARDED_PROTO')) &&
            self::getValue('HTTP_X_FORWARDED_PROTO') == 'https'
        ) ? "https" : "http";
    }

    /**
     * Devuelve el path del servidor.
     * @return mixed
     */
    public static function getDocumentRoot()
    {
        return self::getValue('DOCUMENT_ROOT');
    }

    /**
     * Devuelve un array con todos los valores de la variable 
     * superglobal $_SERVER
     * NOTA: filter_input para las opciones INPUT_SERVER y INPUT_ENV 
     * no funcionan para FASTCGI
     * @return array
     */
    public static function getAllValues()
    {
        $ret = [];
        foreach (array_keys($_SERVER) as $key) {
            $exists = filter_has_var(INPUT_SERVER, $key);
            if ($exists) {
                $value = filter_input(INPUT_SERVER, $key);
            } else {
                $value = filter_var(
                    $_SERVER[$key],
                    FILTER_DEFAULT
                );
            }
            $ret[$key] = $value;
        }

        return $ret;
    }

    /**
     * Devuelve el usuario y contraseña procedentes del dialogo de 
     * autenticación.
     * $_SERVER['PHP_AUTH_USER'] y $_SERVER['PHP_AUTH_PW']
     *
     * @param string $user
     * @param string $psw
     */
    public static function getAuthentication(&$user, &$psw)
    {
        $user = self::getValue('PHP_AUTH_USER');

        $psw = self::getValue('PHP_AUTH_PW');
    }

    /**
     * Establece cuáles errores de PHP son notificados.
     *
     * @param string $str
     *
     * @return mixed
     */
    public static function errorReporting($str)
    {
        $case = [];

        switch ($str) {
            case 'none':
            case '0':
                $case['error'] = '0';
                $case['ini'] = '0';
                break;

            case 'simple':
                $case['error'] = 'E_ERROR | E_WARNING | E_PARSE';
                $case['ini'] = '0';
                break;

            case 'maximum':
                $case['error'] = 'E_ALL';
                $case['ini'] = '1';
                break;

            case 'development':
                $case['error'] = '-1';
                $case['ini'] = '1';
                break;

            case 'default':
            case '-1':
            default:
                $case['error'] = '';
                $case['ini'] = '0';
                break;
        }

        return error_reporting($case['error']) . ini_set('display_errors', $case['ini']);
    }
}
