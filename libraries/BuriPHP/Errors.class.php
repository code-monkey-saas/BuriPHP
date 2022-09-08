<?php namespace BuriPHP\System\Libraries;

/**
 *
 * @package BuriPHP.Libraries
 *
 * @since 1.0.0
 * @version 1.1.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

defined('_EXEC') or die;

class Errors
{
    /**
    * Muestra los errores del framework.
    *
    * @static
    *
    * @param   string    $error    Tipo de error solicitado.
    * @param   string    $message  Mensaje personalizado.
    *
    * @return  void
    */
    public static function system( $error = false, $message = '' )
    {
        if ( strtolower($error) != "could_not_create_directory_logs" )
        {
            self::writeLog($message);
        }

        if ( !class_exists('\BuriPHP\Configuration') || \BuriPHP\Configuration::$error_reporting != 'none' )
        {
            die(sprintf("<b style='background-color: #f44336; color: #FFF;'>BuriPHP Error</b> %s", $message));
        }
        else die();
    }

    /**
    * Crea un codigo http.
    *
    * @static
    *
    * @param   string    $error    Tipo de error http para mostrar.
    *
    * @return  void
    */
    public static function http( $error = false )
    {
        switch( $error )
        {
            case '404':
                echo (new Format())->import_component([
                    'component' => 'PlatformErrors',
                    'controller' => 'Errors',
                    'method' => 'not_found',
                ]);
                break;
            case '401':
            case '403':
            case '405':
            case '406':
            case '408':
            case '409':
            case '415':
            case '500':
            case '501':
            case '502':
            case '503':
            case '504':
            case '505':
                http_response_code($error);
                break;

            default:
                exit('Unknown http status code "' . htmlentities($error) . '"');
                break;
        }
    }

    /**
    * Escribe el error en el archivo de logs
    *
    * @static
    *
    * @param   string    $error    Tipo de error http para mostrar.
    * @param   string    $message  Mensaje personalizado.
    *
    * @return  void
    */
    public static function writeLog( $error, $file = 'BuriPHP' )
    {
        if ( !file_exists(PATH_LOGS . $file .'.log') )
        {
            if ( !is_dir( PATH_LOGS ) )
            {
                if ( !mkdir(PATH_LOGS, 0600) )
                {
                    self::system('could_not_create_directory_logs', 'Could not create directory for logs.');
                }
            }
        }

        if ( class_exists('\BuriPHP\Configuration') )
        {
            date_default_timezone_set(\BuriPHP\Configuration::$time_zone);
        }

        error_log(date('[Y-m-d H:i e] '). strip_tags($error) .PHP_EOL, 3, PATH_LOGS . $file .'.log');
    }
}
