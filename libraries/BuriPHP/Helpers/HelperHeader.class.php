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

use Libraries\BuriPHP\Debug;

abstract class HelperHeader
{
    /**
     * Asigna el codigo de status del protocolo http
     *
     * @param int    $code
     * @param string $text
     */
    public static function setStatusCode($code = 200, $text = '')
    {
        $status = array(
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',

            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',

            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',

            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );

        if ($code == '' || !is_numeric($code)) {
            Debug::alert('Status codes must be numeric');
        }

        if (isset($status[$code]) and $text == '') {
            $text = $status[$code];
        }

        if ($text == '') {
            Debug::alert('No status text available.  Please check your status code number or supply your own message text.');
        }

        $serverProtocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : false;

        if (substr(php_sapi_name(), 0, 3) == 'cgi') {
            header("Status: {$code} {$text}", true);
        } elseif (
            $serverProtocol == 'HTTP/1.1'  ||
            $serverProtocol == 'HTTP/1.0'
        ) {
            header($serverProtocol . " {$code} {$text}", true, $code);
        } else {
            header("HTTP/1.1 {$code} {$text}", true, $code);
        }
    }

    /**
     * Al acarga runa página, redirecciona a otra
     *
     * @param $http
     */
    public static function redirect($http)
    {
        header("Refresh: 0;url={$http}");
    }

    /**
     * Cabecera que redirecciona a una url
     *
     * @param $url
     */
    public static function goLocation($url)
    {
        header('Location: ' . $url);
        die();
    }

    /**
     * Desactivamos la cache
     */

    public static function cacheExpired()
    {
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2010 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, no-store, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");
        header("Pragma: no-cache");
    }

    /**
     * Elimina el funcionament de la cache
     */
    public static function clearCache()
    {
        header("Cache-Control: no-cache");
        header("Expires: -1");
    }

    /**
     * Activa la autenticación en modo Basic.
     * El navegador muestra el cuadro de diálogo estándar donde se 
     * pide el usuario y contraseña esperando al usuario
     * Si se cancela, sige sin la ejecución y finaliza con el die, ya 
     * que no ha entrado los datos.
     * Si entra el usuario y contraseña, se vuelve a cargar la misma 
     * página pero con las variables
     * del servidor $_SERVER['PHP_AUTH_USER'] y
     * $_SERVER['PHP_AUTH_PW'] en donde podremos comprobar si son 
     * correctos
     *
     * @param $realm
     */
    public static function setAutenticacion($realm)
    {
        header('WWW-Authenticate: Basic realm="' . $realm . '"');
        header('HTTP/1.0 401 Unauthorized');
    }

    public static function setContentType($str)
    {
        switch ($str) {
            case 'html':
                header('Content-Type: text/html');
                break;
            case 'xhtml':
                header('Content-Type: text/xhtml+xml');
                break;
            case 'css':
                header('Content-Type: text/css');
                break;
            case 'javascript':
                header('Content-Type: text/javascript');
                break;
            case 'jpeg':
                header('Content-Type: image/jpeg');
                break;
            case 'svg':
                header('Content-Type: image/svg+xml');
                break;
            case 'webp':
                header('Content-Type: image/webp');
                break;
            case 'json':
                header('Content-Type: application/json');
                break;
            case 'pdf':
                header('Content-Type: application/pdf');
                break;
            case 'xml':
                header('Content-Type: application/xml');
                break;
            case 'plain':
                header('Content-Type: text/plain');
                break;
        }
    }
}
