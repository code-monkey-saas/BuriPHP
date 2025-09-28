<?php

namespace Core;

use Core\Helpers\HelperConvert;
use Core\Helpers\HelperValidate;

/**
 * Clase abstracta Debug
 * 
 * Esta clase proporciona funcionalidades de depuración para la aplicación.
 * 
 * @package BuriPHP
 * @author Kiske
 * @since 2.0Alpha
 * @version 1.3
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 * @abstract
 */
abstract class Debug
{
    /**
     * Imprime el contenido de una variable en diferentes formatos.
     *
     * @param mixed $txt El contenido a imprimir.
     * @param string $type El tipo de formato para imprimir: 'array', 'json', 'string'.
     * 
     * Este método utiliza la clase HelperConvert para convertir el contenido a un array
     * si es necesario y luego lo imprime en el formato especificado.
     */
    public static function print($txt, $type = 'json')
    {
        switch ($type) {
            case 'array':
                print_r(HelperConvert::toArray($txt));
                break;

            case 'json':
                echo json_encode(HelperConvert::toArray($txt), JSON_PRETTY_PRINT);
                break;

            case 'string':
                if (is_array($txt)) {
                    $txt = print_r($txt, true);
                }

                echo $txt;
                break;

            default:
                print_r($txt);
                break;
        }

        echo PHP_EOL;
    }

    /**
     * Guarda el rastro de depuración en un archivo.
     *
     * @param mixed $string El rastro de depuración que puede ser un array, una cadena JSON o una cadena simple.
     * @param string|null $filename El nombre del archivo donde se guardará el rastro. Si es null, se usará la fecha actual.
     *
     * Este método convierte el rastro a una cadena utilizando print_r si es un array,
     * o decodifica el JSON si es una cadena JSON. Luego, guarda el rastro en un archivo
     * en el directorio de depuración. Si el directorio no existe, se intenta crear.
     */
    public static function dump($string, $filename = null)
    {
        if (is_array($string)) {
            $string = print_r($string, true);
        } else if (HelperValidate::isJson($string)) {
            $string = print_r(json_decode($string, true), true);
        } else {
            $string = $string . "\n";
        }

        $path = PATH_ROOT . DS . 'debug' . DS;

        if (!HelperValidate::isDir($path)) {
            try {
                if (!mkdir($path, 0700)) {
                    throw new \Exception('No se pudo crear el directorio' . $path);
                }
            } catch (\Throwable $th) {
                throw $th;
                return false;
            }
        }

        error_log($string . PHP_EOL, 3, $path . ((is_null($filename)) ? date('Y-m-d') : $filename));
    }
}
