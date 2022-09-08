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

abstract class HelperValidate
{
    /**
     * Comprueba si un valor esta vacío.
     * Espacios en blanco se consideran vacios. Fechas a cero, se 
     * consideran vacias.
     * 0, true o false se considera no vacio.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function isEmpty($value): bool
    {
        if ($value === false || is_object($value) || is_null($value)) {
            return false;
        }
        if (is_array($value) && count($value) === 0) {
            return true;
        }
        if (is_array($value) && count($value) !== 0) {
            return false;
        }
        $tmp = strtolower(trim('' . $value));
        if ($tmp === '0') {
            return false;
        }

        if (
            $tmp === '0000-00-00' ||
            $tmp === '0000-00-00 00:00:00' ||
            $tmp === '00:00:00' ||
            $tmp === 'null'
        ) {
            return true;
        }
        return ($tmp === '');
    }

    /**
     * Indica si el correo pasado como parámetro es válida o no
     *
     * @param string $sEmail
     *
     * @return bool
     * @version 1.0
     */
    public static function isEmail($sEmail)
    {
        /* filter_var: Si NO es email devuelve false, si es correcto 
           devuelve el mismo email */
        return !(filter_var($sEmail, FILTER_VALIDATE_EMAIL) === false);
    }


    /**
     * Comprueba si una fecha i hora en formato "yyyy-mm-dd hh:mm:ss" 
     * es correcta
     *
     * @param string $datetime
     * @param string $sep
     *
     * @return bool
     */

    public static function isDateTime($datetime, $sep = '-'): bool
    {
        $datetime = '' . $datetime;
        // yyyy-mm-dd hh:mm:ss
        if (
            19 != strlen($datetime) ||
            $datetime[4] != $sep ||
            $datetime[7] != $sep ||
            $datetime[10] != ' ' ||
            $datetime[13] != ':' ||
            $datetime[16] != ':'
        ) {

            return false;
        }

        list($date, $hour) = explode(' ', $datetime);
        list($year, $month, $day) = explode($sep, $date);
        list($hour, $min, $sec) = explode(':', $hour);
        return ($datetime == date(
            'Y-m-d H:i:s',
            mktime($hour, $min, $sec, $month, $day, $year)
        ));
    }

    /**
     * Indica si una fecha en formato "yyyy-mm-dd" es válida.
     *
     * @param string $date
     * @param string $sep
     *
     * @return bool
     */
    public static function isDate($date, $sep = '-'): bool
    {
        $date = '' . $date;

        // yyyy-mm-dd
        if (
            10 != strlen($date) ||
            $date[4] != $sep ||
            $date[7] != $sep
        ) {
            return false;
        }

        list($year, $month, $day) = explode($sep, $date);
        return ($date == date('Y-m-d', mktime(0, 0, 0, $month, $day, $year)));
    }

    /**
     * Valida si es un string de hora correcta.
     *
     * @param $time
     *
     * @return bool
     */
    public static function isTime($time)
    {
        if (!@preg_match("/^\d{2}:\d{2}:\d{2}$/", $time)) {
            return false;
        }

        $arrTime = explode(":", $time);

        list($hour, $min, $sec) = $arrTime;
        settype($hour, "integer");
        settype($min, "integer");
        settype($sec, "integer");

        if ($hour >= 0 && $hour <= 23) {
            if ($min >= 0 && $min <= 59) {
                if ($sec >= 0 && $sec <= 59) {
                    $ret = true;
                } else {
                    $ret = false;
                }
            } else {
                $ret = false;
            }
        } else {
            $ret = false;
        }

        return $ret;
    }

    /**
     * Indica si el dispositivo es un dispositivo móvil
     * @return bool
     */
    public static function isDeviceMobile(): bool
    {
        $httpAgent = HelperString::toLower(
            HelperServer::getValue('HTTP_USER_AGENT')
        );

        $iPod       = stripos($httpAgent, "ipod") !== false;
        $iPhone     = stripos($httpAgent, "iphone") !== false;
        $iPad       = stripos($httpAgent, "ipad") !== false;
        $Android    = stripos($httpAgent, "android") !== false;
        $webOS      = stripos($httpAgent, "webos") !== false;
        $blackberry = stripos($httpAgent, "blackberry") !== false;

        return ($iPod || $iPhone || $iPad || $Android || $webOS || $blackberry);
    }

    /**
     * Indica si la llamada actual es un método POST
     * @return bool
     */
    public static function isMethodPost()
    {
        return (strtoupper(HelperServer::getValue('REQUEST_METHOD')) === 'POST');
    }

    /**
     * Indica si la llamada actual es un método PUT
     * @return bool
     */
    public static function isMethodPut()
    {
        return (strtoupper(HelperServer::getValue('REQUEST_METHOD')) === 'PUT');
    }

    /**
     * Indica si la llamada actual es un método UPDATE
     * @return bool
     */
    public static function isMethodUpdate()
    {
        return (strtoupper(HelperServer::getValue('REQUEST_METHOD')) === 'UPDATE');
    }

    /**
     * Indica si la llamada actual es un método DELETE
     * @return bool
     */
    public static function isMethodDelete()
    {
        return (strtoupper(HelperServer::getValue('REQUEST_METHOD')) === 'DELETE');
    }

    /**
     * Indica si la llamada actual es un método GET
     * @return bool
     */
    public static function isMethodGet()
    {
        return (strtoupper(HelperServer::getValue('REQUEST_METHOD')) === 'GET');
    }

    /**
     * Indica si el valor es un GUI válido
     *
     * @param $guid
     *
     * @return bool
     */
    public static function isGUID($guid)
    {
        if (36 != strlen($guid)) {
            return false;
        }
        return (preg_match(
            "/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/i",
            $guid
        ));
    }

    /**
     * Indica si un archivo es un linke
     *
     * @param string $filename
     *
     * @return bool
     */
    public static function isFileLink($filename)
    {
        return is_link($filename);
    }

    /**
     * Indica si un archivo tiene extensión de imagen
     * Contempla jpeg, png y gif
     *
     * @param string $file
     *
     * @return bool
     */
    public static function isFileImage($file)
    {
        return (preg_match('/\.(?:jpeg|png|gif|jpg)$/i', $file));
    }

    /**
     * Indica si un archivo es ejecutable
     *
     * @param string $filename
     *
     * @return bool
     */
    public static function isFileExe($filename)
    {
        return is_executable($filename);
    }

    /**
     * Indica si un archivo es un archivo (no es un directrorio)
     *
     * @param string $filename
     *
     * @return bool
     */
    public static function isFile($filename)
    {
        return is_file($filename);
    }

    /**
     * Indica si el archivo es un directorio
     *
     * @param $filename
     *
     * @return bool
     */
    public static function isDir($filename)
    {
        return is_dir($filename);
    }

    /**
     * Indica si la data esta en format UTC
     * Format: YYYYMMDDTHHiissZ
     *
     * @param $datetimeUTC
     *
     * @return bool
     */
    public static function isDateTimeUTC($datetimeUTC): bool
    {
        /* -- UTC = 20070724T224556Z */
        $datetimeUTC = strtoupper($datetimeUTC);
        $date       = substr($datetimeUTC, 0, 4) . '-' .
            substr($datetimeUTC, 4, 2) . '-' .
            substr($datetimeUTC, 6, 2);
        $t           = substr($datetimeUTC, 8, 1);
        $hour        = substr($datetimeUTC, 9, 2) . ':' .
            substr($datetimeUTC, 11, 2) . ':' .
            substr($datetimeUTC, 13, 2);

        if (!HelperValidate::isDateTime($date . " " . $hour)) {
            return false;
        }
        return ($t == 'T' && 'Z' == substr($datetimeUTC, 15, 1));
    }

    /**
     * Indica si un objeto es de una clase determinada.
     *
     * @param mixed  $obj
     * @param string $className
     *
     * @return bool
     */
    public static function isClassOf($obj, $className): bool
    {
        return strtoupper(get_class($obj)) == strtoupper($className);
    }

    /**
     * Devuelve true si una fecha esta entre otras dos
     *
     * @param string $date
     * @param string $start
     * @param string $end
     * @param bool   $closed
     *
     * @return bool
     */
    public static function isBetweenDates($date, $start, $end, $closed = true): bool
    {
        if ($closed) {
            if ($start === $date || $end === $date) {
                return true;
            }
        }
        return (HelperDate::getDif($start, $date) < 0 && HelperDate::getDif($end, $date) > 0);
    }

    /**
     * Indica si un valor esta comprendido entre otros dos
     *
     * @param int  $num
     * @param int  $min
     * @param int  $max
     * @param bool $closed
     *
     * @return bool
     */
    public static function isBetween($num, $min, $max, $closed = true)
    {
        if ($closed) {
            return ($num >= $min && $num <= $max);
        } else {
            return ($num > $min && $num < $max);
        }
    }

    /**
     * Busca si existe una palabra entera dentro de un texto
     * Ex:  "web" => "PHP is web scripting" => true (es una palabra)
     * Ex:  "web" => "PHP is web, scripting" => true (es una palabra)
     * Ex:  "web" => "PHP is web,scripting" => true (es una palabra)
     *      "web" => "PHP is the website scripting" => false (no es
     *               una palabra)
     *
     * @param string $txt
     * @param string $wordSearch
     *
     * @return bool
     */
    public static function existWord($txt, $wordSearch)
    {
        // false => error
        $ret = preg_match("/\b" . $wordSearch . "\b/i", $txt);
        if (false === $ret) {
            return false;
        }
        // 0 si no coincide
        return 0 !== $ret;
    }

    /**
     * Devuelve true si el texto tiene CR o LF
     *
     * @param $txt
     *
     * @return bool
     */
    public static function existCRLF($txt)
    {
        // false => error
        $ret = preg_match("/(%0A|%0D|\\n+|\\r+)/i", $txt);
        if (false === $ret) {
            return false;
        }
        // 0 si no coincide
        return 0 !== $ret;
    }

    /**
     * Comprueba si una string termina por un string determinado
     * Insensible a mayúsculas, minúsculas y acentos
     *
     * @param $str
     * @param $end
     *
     * @return bool
     */
    public static function endsWith($str, $end): bool
    {
        $len = strlen($end);
        $lenStr = strlen($str);
        if ($len > 0  && $lenStr > 0 && ($lenStr - $len) > 0) {
            $str = substr($str, $lenStr - $len);
            return (0 == strcasecmp(
                HelperString::removeAccents($str),
                HelperString::removeAccents($end)
            ));
        }
        return false;
    }

    /**
     * Indica si un string empieza por un determinado string.
     * Insensible a mayúsculas, minúsculas y acentos
     *
     * @param $str
     * @param $begin
     *
     * @return bool
     */
    public static function beginsWith($str, $begin): bool
    {
        $len = strlen($begin);
        if ($len > 0) {
            return (0 == strncasecmp(
                HelperString::removeAccents($str),
                HelperString::removeAccents($begin),
                strlen($begin)
            )
            );
        }
        return false;
    }

    /**
     * Devuelve true si todos los caracteres son letras, numero o 
     * decimales  (int, float)
     * Incluye el punto como decimal.
     * Pueden pasarse como parámetro otros carácteres para considerar 
     * ser válidos
     *
     * @param mixed  $mixed
     * @param string $sAllowedChars
     *
     * @return bool
     */
    public static function areOnlyNumLetters($mixed, $sAllowedChars = '')
    {
        return (preg_match("/^[a-zA-Z0-9." . $sAllowedChars . "]+$/", '' . $mixed));
    }

    /**
     * Devuelve true si todos los caracteres números (int)
     * No acepta el punto decimal
     * Pueden pasarse como parámetro otros carácteres para considerar 
     * ser válidos
     *
     * @param mixed  $mixed
     * @param string $sAllowedChars
     *
     * @return bool
     */
    public static function areOnlyNum($mixed, $sAllowedChars = '')
    {
        return (preg_match('/^[0-9' . $sAllowedChars . ']+$/', '' . $mixed));
    }

    /**
     * Devuelve true si todos los caracteres son letras
     * Pueden pasarse como parámetro otros carácteres para considerar 
     * ser válidos
     *
     * @param mixed  $mixed
     * @param string $sAllowedChars
     *
     * @return bool
     */
    public static function areOnlyLetters($mixed, $sAllowedChars = '')
    {
        return (preg_match("/^[a-zA-Z" . $sAllowedChars . "]+$/", '' . $mixed));
    }

    /**
     * Comprueba si dos valores son iguales, pueden ser string, o 
     * números.
     * Insensible a mayúsculas, minúsculas y acentos
     *
     * @param $mix1
     * @param $mix2
     *
     * @return bool
     */
    public static function areEquals($mix1, $mix2): bool
    {
        return (0 == strcasecmp(
            HelperString::removeAccents($mix1),
            HelperString::removeAccents($mix2)
        ));
    }

    /**
     * Indica si es un código postal válido
     *
     * @param string $zipcode
     *
     * @return bool
     */
    public static function isZipCode($zipcode)
    {
        $zipcode  = trim($zipcode);
        $ok       = (preg_match("/^[0-9]+$/", $zipcode) &&
            strlen($zipcode) == 5);
        $province = intval(substr($zipcode, 0, 2));

        return ($province >= 1 && $province <= 52 && $ok);
    }

    /**
     * Indica si un año es bisiesto
     *
     * @param $year
     *
     * @return bool
     */
    public static function isYearLeap($year)
    {
        return (($year % 4) == 0 && (($year % 100) != 0 || ($year % 400) == 0));
    }

    /**
     * Comprueva la sintaxis de una url es correcta
     * Puede contener o no el protocolo.
     * Detecta el protocolo http, https y ftp
     *
     * @param      $url
     * @param bool $protocol
     *
     * @return false|int
     */
    public static function isUrl($url, $protocol = false)
    {
        // Carácteres permitidos
        $chars = '[a-z0-9\/:_\-_\.\?\$,;~=#&%\+]';
        if ($protocol) {
            return preg_match(
                "/^(http|https|ftp):\/\/" . $chars . "+$/i",
                $url
            );
        } else {
            return preg_match("/^" . $chars . "+$/i", $url);
        }
    }

    /**
     * Valida si un número de teléfono pertenece a un móvil
     *
     * @param string $phone
     *
     * @return bool
     */
    public static function isPhoneMovile($phone)
    {
        $phone = trim($phone);
        $ok    = (preg_match("/^[0-9]+$/", $phone) && strlen($phone) == 9);
        $code  = intval(substr($phone, 0, 1));
        return (($code == 6 || $code == 7) && $ok);
    }

    /**
     * Valida si existe una peticion AJAX
     *
     * @return bool
     */
    public static function ajaxRequest()
    {
        if (
            !self::isEmpty(HelperServer::getValue('HTTP_X_REQUESTED_WITH')) &&
            strtolower(HelperServer::getValue('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest'
        ) {
            return true;
        }

        return false;
    }
}
