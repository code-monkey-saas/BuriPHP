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

abstract class HelpZip
{
    /**
     * Comprime una archivo mediante el zip.exe del ordenador
     *
     * @param $fileName
     * @param $fileOut
     */
    public static function zipToFile($fileName, $fileOut)
    {
        //$fileName = "filename.zip";
        echo system("zip -P 1234 -j {$fileName} \"{$fileOut}\"");
    }

    /**
     * Devuelde un valor encriptado y zipado o null si no puede
     *
     * @param $mixed
     *
     * @return string|null
     */
    public static function zipCompress($mixed)
    {
        // añadir composer
        $ret = gzcompress(base64_encode(serialize($mixed)), 9);
        if (false === $ret) {
            return null;
        }
        return $ret;
    }

    /**
     * Descomprime un string encriptado y zipado o null si no puede
     *
     * @param $sZip
     *
     * @return string|null
     */
    public static function zipDecompress($sZip)
    {
        $ret = gzuncompress($sZip);
        if (false === $ret) {
            return null;
        }
        return unserialize(base64_decode($ret));
    }
}
