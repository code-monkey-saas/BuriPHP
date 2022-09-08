<?php

/**
 * @package BuriPHP.Libraries
 *
 * @since 2.0Alpha
 * @version 1.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

namespace Libraries\BuriPHP;

use Libraries\BuriPHP\Helpers\HelperConvert;

abstract class Debug
{
    /**
     * Muetra un alert por navegador.
     *
     * @param String
     */
    public static function alert($txt)
    {
        echo "<script>alert( '" . addslashes($txt) . "' );</script>";
    }

    /**
     * Imprime una variable.
     *
     * @param String
     */
    public static function echo($txt)
    {
        echo $txt . "\n";
    }

    /**
     * Imprime un array.
     *
     * @param String
     */
    public static function print($txt, $json = false)
    {
        $r = (!$json) ? HelperConvert::toArray($txt) : json_encode(HelperConvert::toArray($txt));

        print_R($r);
    }

    /**
     * Imprime un array con etiqueta <pre>.
     *
     * @param String
     */
    public static function pre($txt)
    {
        echo "<pre>";
        self::print($txt);
        echo "</pre>\n";
    }

    /**
     * Muestra un variable o estructura por una nueva ventana del navegador.
     *
     * @param $txt
     */
    public static function pr($txt)
    {
        $txt = '<pre>' . addslashes(nl2br(print_r($txt, true))) . '</pre>';
        $txt = str_replace(array("\r", "\n", "\r\n"), array(''), $txt);

        echo '<script>';
        echo 'w=window.open( "","_blank","toolbar=yes, location=yes, directories=no, status=yes, menubar=yes, scrollbars=yes, resizable=yes, copyhistory=yes" );';
        echo 'w.document.write( "<html lang=\"es\"> <head> <title>debug</title> </head> <body>' . $txt . '</body>" );';
        echo 'w.document.close( );';
        echo '</script>';
    }
}
