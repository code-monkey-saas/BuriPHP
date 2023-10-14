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

abstract class HelperString
{
    /**
     * Convierte un string a mayúsculas.
     * Es insensible a lo acéntos.
     *
     * @param string $txt
     *
     * @return string
     */
    public static function toUpper($txt): string
    {
        if (function_exists('mb_strtoupper')) {
            // Convierte carcateres especiales
            return '' . mb_strtoupper($txt);
        }
        return '' . strtoupper($txt);
    }

    /**
     * Convierte un string a minúsculas.
     * Es insensible a lo acéntos.
     *
     * @param string $txt
     *
     * @return string
     */
    public static function toLower($txt): string
    {
        if (function_exists('mb_strtolower')) {
            // Convierte carcateres especiales
            return '' . mb_strtolower($txt);
        }
        return '' . strtolower($txt);
    }

    /**
     * Reemplaza sólo la primera ocurrencia de un string por otro.
     * Es insensible a mayúsculas i minúsculas pero no a los acentos.
     * Si no existe la ocurrencia, devuelve el mismo string
     *
     * @param string $txt
     * @param string $origin
     * @param string $destination
     *
     * @return string
     */
    public static function replaceFirst($txt, $origin, $destination)
    {
        $origin = '/' . preg_quote('' . $origin, '/') . '/i';

        return '' . preg_replace($origin, '' . $destination, '' . $txt, 1);
    }

    /**
     * Reemplaza todas las ocurrencias.
     * Es insensible a mayúsculas y minúsculas pero no a los acentos
     * Devuelve el numero de ocurrencias sustiruidas
     *
     * @param string   $txt
     * @param string   $origin
     * @param string   $destination
     * @param int|null $numSuccess
     *
     * @return string
     */
    public static function replaceAll($txt, $origin, $destination, &$numSuccess = null): string
    {
        return str_ireplace(
            '' . $origin,
            '' . $destination,
            '' . $txt,
            $numSuccess
        );
    }

    /**
     * Devuelve la posición de la primera ocurrencia en un texto.
     * Es indiferente a mayúsculas, minúsculas y acentos
     * Se puede delimitar donde empezar a buscar.
     * Devuelve -1 si no existe, siendo 0 la primera posición
     *
     * @param     $txt
     * @param     $occurrence
     * @param int $posStartSearch
     *
     * @return int
     */
    public static function indexOf($txt, $occurrence, $posStartSearch = 0): int
    {
        $txt       = self::removeAccents($txt);
        $occurrence = self::removeAccents($occurrence);
        $pos       = stripos($txt, $occurrence, $posStartSearch);
        if ($pos === false) {
            return -1;
        }
        return $pos;
    }

    /**
     * Devuelve la posición de la última ocurrencia en un texto.
     * Es indiferente a mayúsculas y minúsculas pero no a los acentos
     * Se puede delimitar hasta donde buscar.
     * Devuelve -1 si no existe, siendo 0 la primera posición
     *
     * @param string $txt
     * @param string $occurrence
     * @param int    $posEndSearch
     *
     * @return int
     */
    public static function indexOfLast($txt, $occurrence, $posEndSearch = 0): int
    {
        $pos = strripos($txt, $occurrence, -1 * $posEndSearch);
        if ($pos === false) {
            return -1;
        }
        return $pos;
    }

    /**
     * Elimina los tabuladores, y saltos de línea
     *
     * @param string $str
     *
     * @return string
     */
    public static function sanitizeTabReturn($str): string
    {
        return preg_replace('/[\n\r\t]+/', '', $str);
    }

    /**
     * Elimina los espacios en blanco innecesarios
     *
     * @param string $str
     *
     * @return string
     */
    public static function sanitizeBlanks($str): string
    {
        return trim(preg_replace('/\s{2,}/', ' ', $str));
    }

    /**
     * Elimina todas las letras con acentos, elimina caracteres no 
     * alfanumericos
     * puntos o subrayados, cambia el espacio en blanco por un guión 
     * bajo
     *
     * @param string $txt
     *
     * @return string
     */
    public static function sanitizeAll($txt): string
    {
        $txt = preg_replace('/[ ]{2}/', ' ', $txt);

        /* Elimina acentos, ñ y ç */
        $txt = self::removeAccents($txt);
        $txt = strtolower(strtr($txt, 'çñ ', 'cn_'));

        /* Elimina cualquier carácter no alfanumerico */
        return preg_replace('/[^A-Za-z0-9._\-]/', '', $txt);
    }

    /**
     * Devuelve la parte de la derecha despues de un texto delimitador
     * que se busca desde la izquierda.
     * No devuelve el delimitador
     *
     * @param $txt
     * @param $delimiter
     *
     * @return string
     */
    public static function getRightStringBack($txt, $delimiter)
    {
        $pos = stripos($txt, $delimiter);
        $ret = '';
        if (false !== $pos) {
            /* Busca el delimitador partiendo de la izquierda
             y si lo encuentra devuelve la parde de la la derecha */
            $ret = substr($txt, ($pos + strlen($delimiter)));
            if ($ret === false) {
                return '';
            }
        }
        return $ret;
    }

    /**
     * Devuevle la parte de la derecha después de un delimitador
     * Busca el delimitador empezando desde la derecha
     *
     * @param $txt
     * @param $delimiter
     *
     * @return string
     */
    public static function getRightString($txt, $delimiter): string
    {
        $ret = '';
        $pos = stripos($txt, $delimiter);
        if ($pos !== false) {
            $ret = substr(
                $txt,
                $pos + strlen($delimiter),
                strlen($txt)
            );
            if ($ret === false) {
                return '';
            }
        }
        return $ret;
    }

    /**
     * Devuelve los N caracteres de de la derecha.
     * Empieza a contar desde la derecha
     *
     * @param $str
     * @param $len
     *
     * @return string
     */
    public static function getRightNum($str, $len): string
    {
        $len_str = strlen($str);
        if ($len > $len_str) {
            $ret = $str;
        } else {
            $ret = substr($str, $len_str - $len);
            if (false === $ret) {
                return '';
            }
        }
        return $ret;
    }

    /**
     * Devuelve los N caracteres después de un delimitador
     *
     * @param $txt
     * @param $delimiter
     * @param $len
     *
     * @return string
     */
    public static function getMiddleString($txt, $delimiter, $len): string
    {
        $ret = '';
        if (stripos($txt, $delimiter) !== false) {
            $pi  = stripos($txt, $delimiter) + strlen($delimiter);
            $ret = substr($txt, $pi, $len);
            if ($ret === false) {
                return '';
            }
        }
        return $ret;
    }

    /**
     * Devuelve los N caracteres después de una posición determinada 
     * empezando por la izquierda
     *
     * @param $txt
     * @param $posStart
     * @param $len
     *
     * @return string
     */
    public static function getMiddleNum($txt, $posStart, $len): string
    {
        $ret = substr($txt, $posStart, $len);
        if ($ret === false) {
            return '';
        }
        return $ret;
    }

    /**
     * Devuelve la parte de la izquierda hasta un texto delimitador
     *
     * @param $txt
     * @param $delimiter
     *
     * @return string
     */
    public static function getLeftString($txt, $delimiter): string
    {
        $pos = stripos($txt, $delimiter);
        $ret = '';
        if (false !== $pos) {
            $ret = substr($txt, 0, $pos);
            if ($ret === false) {
                return '';
            }
        }
        return $ret;
    }

    /**
     * Empezando a contar desde la izquierda, devuelve la parte de la
     * derecha
     * despres de una longitud determinada
     *
     * @param $txt
     * @param $len
     *
     * @return string
     */
    public static function getRightNumBack($txt, $len): string
    {
        /* Empezando por la izquierda, devuelve la parte derecha */
        $ret = substr($txt, $len);
        if (false === $ret) {
            return '';
        }
        return $ret;
    }

    /**
     * Devuelve el texto de la izquierda de una determinada longitud.
     * Empieza a contar por la izquierda.
     * Si no se puede obtener, devuelve un string vacío
     *
     * @param $txt
     * @param $len
     *
     * @return string
     */
    public static function getLeftNum($txt, $len): string
    {
        /* Empezando por la izquierda, devuelve la perte de la izquierda */
        $ret = substr('' . $txt, 0, $len);

        if (false === $ret) {
            return '';
        }
        return $ret;
    }

    /**
     * Devuelve el último carácter de un string
     *
     * @param string $txt
     *
     * @return false|string
     */
    public static function getLastChar($txt): string
    {
        if (HelperValidate::isEmpty($txt)) {
            return '';
        }
        return '' . substr('' . $txt, -1);
    }

    /**
     * Devuelve el texto delimitado entre otros dos textos.
     * Es case insensitive.
     * Devuelve null si no encuentra.
     * '' => No hay caracteres entre los delimitadores
     *
     * @param $txt
     * @param $strStart
     * @param $strEnd
     *
     * @return string
     */
    public static function getBetween($txt, $strStart, $strEnd)
    {
        $ret = null;

        if (stripos($txt, $strStart) !== false) {

            $pi  = stripos($txt, $strStart) + strlen($strStart);
            $lon = stripos($txt, $strEnd) - $pi;
            $ret = substr($txt, $pi, $lon);

            if (false === $ret) {
                return '';
            }
        }

        return $ret;
    }

    /**
     * Rellena un texto por la derecha hasta un número determinado 
     * mediante un caraácter
     *
     * @param $txt
     * @param $size
     * @param $charPad
     *
     * @return string
     */
    public static function fillRight($txt, $size, $charPad = '')
    {
        return str_pad($txt, $size, $charPad, STR_PAD_RIGHT);
    }

    /**
     * Rellena un texto por la izquierda hasta un número determinado 
     * mediante un caraácter
     *
     * @param $txt
     * @param $size
     * @param $charPad
     *
     * @return string
     * @since 20/09/2020
     */
    public static function fillLeft($txt, $size, $charPad = '')
    {
        return str_pad($txt, $size, $charPad, STR_PAD_LEFT);
    }

    /**
     * Elimian los acentos de un texto
     *
     * @param $str
     *
     * @return string
     */
    public static function removeAccents($str): string
    {
        return strtr(trim('' . $str), array(
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'à' => 'a',
            'è' => 'e',
            'ì' => 'i',
            'ò' => 'o',
            'ù' => 'u',
            'Á' => 'A',
            'É' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ú' => 'U',
            'À' => 'A',
            'È' => 'E',
            'Ì' => 'I',
            'Ò' => 'O',
            'Ù' => 'U',
            'ä' => 'a',
            'ë' => 'e',
            'ï' => 'i',
            'ö' => 'o',
            'ü' => 'u',
            'Ä' => 'A',
            'Ë' => 'E',
            'Ï' => 'I',
            'Ö' => 'O',
            'Ü' => 'U',
            'â' => 'a',
            'ê' => 'e',
            'î' => 'i',
            'ô' => 'o',
            'û' => 'u',
            'Â' => 'A',
            'Ê' => 'E',
            'Î' => 'I',
            'Ô' => 'O',
            'Û' => 'U'
        ));
    }

    /**
     * Genera un string de caracteres random.
     *
     * @param integer $length
     *
     * @return string
     */
    public static function random($length = 8)
    {
        $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $base = strlen($salt);
        $stringRandom = '';

        $random = HelperCrypt::randomBytes($length + 1);
        $shift = ord($random[0]);

        for ($i = 1; $i <= $length; ++$i) {
            $stringRandom .= $salt[($shift + ord($random[$i])) % $base];
            $shift += ord($random[$i]);
        }

        return $stringRandom;
    }

    /**
     * Genera una clave aleatoria de 10 carcarteres.
     * Letras y números.
     * @return string
     */
    public static function createKey(): string
    {
        return '' . substr(md5(uniqid()), 0, 10);
    }

    /**
     * Devuevle un GUID válido.
     * @return string
     */
    public static function createGUID(): string
    {
        $microTime = microtime();

        list($arrDec, $arrSec) = explode(" ", $microTime);

        $decHex = (int) dechex($arrDec * 1000000);
        $secHex = dechex($arrSec);

        self::guidSetLen($decHex, 5);
        self::guidSetLen($secHex, 6);

        $guid = '';
        $guid .= $decHex;
        $guid .= self::guidCreateSection(3);
        $guid .= '-';
        $guid .= self::guidCreateSection(4);
        $guid .= '-';
        $guid .= self::guidCreateSection(4);
        $guid .= '-';
        $guid .= self::guidCreateSection(4);
        $guid .= '-';
        $guid .= $secHex;
        $guid .= self::guidCreateSection(6);

        return $guid;
    }

    /**
     * Asignar longitud
     *
     * @param $string
     * @param $length
     */
    private static function guidSetLen(&$string, $length)
    {
        $strlen = strlen($string);
        if ($strlen < $length) {
            $string = str_pad($string, $length, '0');
        } else {
            if ($strlen > $length) {
                $string = substr($string, 0, $length);
            }
        }
    }

    /**
     * Crear sección
     *
     * @param $len
     *
     * @return string
     */
    private static function guidCreateSection($len)
    {
        $ret = "";
        for ($i = 0; $i < $len; $i++) {
            $ret .= dechex(mt_rand(0, 15));
        }
        return $ret;
    }

    /**
     * Separa un texto en un número de caracteres por línea
     * Por defecto inserta al final de cada linea \n.
     * Ex: "The quick brown fox jumped over the lazy dog."
     *            The quick brown fox<br />
     *             jumped over the lazy<br />
     *             dog.
     *
     * @param string $txt
     * @param int    $len
     * @param string $break
     *
     * @return string
     */
    public static function wordWrap($txt, $len, $break = ''): string
    {
        if (empty($break)) {
            return wordwrap($txt, $len);
        } else {
            return wordwrap($txt, $len, $break);
        }
    }

    /**
     * Trunca un string a un número determinado de caracteres.
     * No recorta añade un string al final.
     *
     * @param        $txt
     * @param        $len
     * @param string $fill
     *
     * @return string
     */
    public static function truncate($txt, $len, $fill = '...'): string
    {
        if (HelperValidate::isEmpty($txt)) {
            return '';
        }
        if (strlen($txt) > $len) {
            return '' . substr($txt, 0, $len) . $fill;
        }
        return $txt;
    }

    /**
     * Elimina los tags html de un texto.
     * Se puede indicar que tags no ha de eliminar
     *
     * @param string      $txt
     * @param string|null $tagsDontRemove
     *
     * @return string
     */
    public static function removeHtmlTags($txt, $tagsDontRemove = null)
    {
        return strip_tags($txt, $tagsDontRemove);
    }
}
