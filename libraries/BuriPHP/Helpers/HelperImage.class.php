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

abstract class HelperImage
{
    /**
     * Crea una miniatura de un aimagen en función a una proporción
     * indicada.
     * Se puede modificar la calidade de la miniatura.
     * Devuelve true si se ha generado la miniatura o false en otro 
     * caso
     *
     * @param     $filename
     * @param     $nameThumbnail
     * @param     $proportion
     * @param int $quality
     *
     * @return bool
     */
    public static function generateThumbnail($filename, $nameThumbnail, $proportion, $quality = 75)
    {
        // Al archivo ha de existir
        if (!file_exists($filename)) {
            return false;
        }

        // Miramos que extensión tiene
        $extension = HelperString::toLower(
            HelperFile::getOnlyExtension($filename)
        );
        if (HelperValidate::isEmpty($extension)) {
            return false;
        }

        if ($extension == 'png') {
            $img = imagecreatefrompng($filename);
        } elseif ($extension = 'jpg') {
            $img = imagecreatefromjpeg($filename);
        } else {
            // Otra extensión
            return false;
        }

        // Dimensiones actuales de la imagen
        $currentWidth = imagesx($img);
        $currentHeight  = imagesy($img);

        // Calculamos las dimenstions de la miniatura
        $miniWidth = $currentWidth * $proportion;
        $miniHeight  = $currentHeight * $proportion;

        // Creamos imagen contenedor con la misma proporción
        $imgMini = imagecreatetruecolor($miniWidth, $miniHeight);

        // Copiamos la imagen original a la reducida
        imagecopyresized($imgMini, $img, 0, 0, 0, 0, $miniWidth, $miniHeight, $currentWidth, $currentHeight);

        // Generamos la miniatura
        imagejpeg($imgMini, $nameThumbnail, $quality);
        return true;
    }
}
