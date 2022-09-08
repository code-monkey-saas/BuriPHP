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

abstract class HelperLog
{
    /**
     * Guardamos una traza en un archivo.
     * Acepta múltiples parámetros formato sprintf con %1, %2, ...
     *
     * @param string $txt
     */
    public static function saveTrace($txt)
    {
        if (!HelperValidate::isDir(PATH_LOGS)) {
            try {
                if (!mkdir(PATH_LOGS, 0700)) {
                    throw new \Exception('No se pudo crear el directorio de LOGS');
                }
            } catch (\Throwable $th) {
                throw $th;

                return false;
            }
        }

        /* Ruta completa donde ubicar el archivo de logs */
        $fileLogs = PATH_LOGS . 'log-' . date('Y-m-d') . '.log';

        $argNum  = func_num_args();
        $argList = func_get_args();

        for ($i = 1; $i < $argNum; $i++) {
            $txt = HelperString::replaceFirst($txt, "%$i", $argList[$i]);
        }

        error_log(date('[Y-m-d H:i e] ') . $txt . PHP_EOL, 3, $fileLogs);
    }

    /**
     * Guardamos una traza en el archivo log del sistema
     *
     * @param string $txt
     */
    public static function saveSystem($txt)
    {
        $argNum  = func_num_args();
        $argList = func_get_args();

        for ($i = 1; $i < $argNum; $i++) {
            $txt = HelperString::replaceFirst($txt, "%$i", $argList[$i]);
        }

        error_log(date('[Y-m-d H:i e] ') . $txt . PHP_EOL);
    }


    /**
     * Guardamos un error en un archivo de log un una ruta concreta.
     * Acepta múltiples parámetros formato sprintf con %1, %2, ...
     *
     * @param Exception $ex
     * @param           $txt
     */
    public static function saveExcepcion(\Exception $ex, $txt)
    {
        if (!HelperValidate::isDir(PATH_LOGS)) {
            try {
                if (!mkdir(PATH_LOGS, 0700)) {
                    throw new \Exception('No se pudo crear el directorio de LOGS');
                }
            } catch (\Throwable $th) {
                throw $th;

                return false;
            }
        }

        /* Ruta completa donde ubicar el archivo de logs */
        $fileLogs = PATH_LOGS . 'log-error-' . date('Y-m-d') . '.log';

        $argNum  = func_num_args();
        $argList = func_get_args();

        for ($i = 2; $i < $argNum; $i++) {
            $txt = HelperString::replaceFirst($txt, "%$i", $argList[$i]);
        }

        error_log(date('[Y-m-d H:i e]') . ' ERR: ' . $txt . PHP_EOL, 3, $fileLogs);

        if (!empty($ex)) {
            error_log(date('[Y-m-d H:i e]') . ' EXC: ' . $ex->getMessage() . PHP_EOL, 3, $fileLogs);
            error_log(date('[Y-m-d H:i e]') . ' EXC: ' . $ex->getTraceAsString() . PHP_EOL, 3, $fileLogs);
        }
    }

    /**
     * Guardamos un error en un archivo de log un una ruta concreta.
     * Acepta múltiples parámetros formato sprintf con %1, %2, ...
     *
     * @param $txt
     */
    public static function saveError($txt)
    {
        if (!HelperValidate::isDir(PATH_LOGS)) {
            try {
                if (!mkdir(PATH_LOGS, 0700)) {
                    throw new \Exception('No se pudo crear el directorio de LOGS');
                }
            } catch (\Throwable $th) {
                throw $th;

                return false;
            }
        }

        /* Ruta completa donde ubicar el archivo de logs */
        $fileLogs = PATH_LOGS . 'log-error-' . date('Y-m-d') . '.log';

        $argNum  = func_num_args();
        $argList = func_get_args();

        for ($i = 1; $i < $argNum; $i++) {
            $txt = HelperString::replaceFirst($txt, "%$i", $argList[$i]);
        }

        error_log(date('[Y-m-d H:i e]') . ' ERR: ' . $txt . PHP_EOL, 3, $fileLogs);
    }
}
