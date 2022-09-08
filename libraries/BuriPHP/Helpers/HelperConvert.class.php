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

abstract class HelperConvert
{
    /**
     * Transforma un string SI/NO-YES/NO-Y/S-S/N-1/0 a bool.
     * Si el valor está vacío o null, devuelve null.
     * Devuelve null si no se puede convertir a true o false.
     *
     * @param string $strBool
     *
     * @return bool
     */
    public static function toBool($strBool)
    {
        $ret = -1;
        if (HelperValidate::isEmpty($strBool)) {
            $ret = null;
        } else {
            if (is_string($strBool)) {
                $name = strtolower(strtr($strBool, 'ÍÓíó', 'ioio'));

                $sn = preg_replace(
                    '/^[:alnum:]/ui',
                    '',
                    strtoupper(trim($name))
                );

                if ($sn == 'S' || $sn == 'Y' || $sn == 'SI' || $sn == 'YES' || $sn == '1') {
                    $ret = true;
                } else {
                    if ($sn == 'N' || $sn == 'NO' || $sn == '0') {
                        $ret = false;
                    } else {
                        $ret = null;
                    }
                }
            } else {
                if (is_numeric($strBool)) {
                    if ($strBool === 1) {
                        $ret = true;
                    } else {
                        if ($strBool === 0) {
                            $ret = false;
                        } else {
                            $ret = null;
                        }
                    }
                } else {
                    if (is_bool($strBool)) {
                        return $strBool;
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * Convierte un valor a un array.
     * Siempre devuelve un array.
     *
     * @param $element
     *
     * @return array
     */
    public static function toArray($element): array
    {
        if (is_array($element)) {
            return $element;
        } elseif (HelperValidate::isEmpty($element)) {
            // No aseguramos que no tenga valor
            return [];
        } elseif (is_object($element)) {

            return (array)$element;
        } else {
            return array($element);
        }
    }

    /**
     * Devuelve un texto sin especiales HTML y sustitutyendo los CRLF
     * por <br />
     * Si es blanco o null devuelve '&nbsp;'
     *
     * @param $txt
     *
     * @return string
     */
    public static function toHtml($txt)
    {
        if (HelperValidate::isEmpty($txt)) {
            return '&nbsp;';
        }
        return nl2br(htmlspecialchars($txt));
    }

    /**
     * Devuelve un valor en formato monetario +/- 1.234,00 $.
     * Si el valor esta vació, devuelve null.
     *
     * @param string|int $value
     * @param bool       $simbol
     *
     * @return string
     */
    public static function toCurrency($value, $simbol = true, $simbolSign = '$')
    {
        if (HelperValidate::isEmpty($value)) {
            return null;
        }

        $f = floatval($value);

        $sSimbol = $simbol ? ' ' . $simbolSign : '';

        if (empty($f)) {
            return '0,00' . $sSimbol;
        }

        if ($f >= 0) {
            $sSign = '';
        } else {
            $f      *= (-1);
            $sSign = '-';
        }

        $t = number_format($f, 2, ',', '.');

        return $sSign . $t . $sSimbol;
    }

    /**
     * Convierte una TimStamp en una fecha.
     * Formato fecha: yyyy-mm-dd
     *
     * @param int $timestamp
     *
     * @return string
     */
    public static function timeStamp2Date($timestamp)
    {
        $date_str = getdate($timestamp);

        $year = $date_str["year"];
        $mon  = $date_str["mon"];
        $day  = $date_str["mday"];

        return sprintf("%4d-%02d-%02d", $year, $mon, $day);
    }

    /**
     * Convierte un timestamp en una fecha y hora
     * Fomato fecha: yyyy-mm-dd hh:mm:ss
     *
     * @param int $timestamp
     *
     * @return string
     */
    public static function timeStamp2DateTime($timestamp)
    {
        $date_str = getdate($timestamp);

        $year = $date_str["year"];
        $mon  = $date_str["mon"];
        $mday = $date_str["mday"];
        $h    = $date_str["hours"];
        $m    = $date_str["minutes"];
        $s    = $date_str["seconds"];

        return sprintf(
            "%4d-%02d-%02d %02d:%02d:%02d",
            $year,
            $mon,
            $mday,
            $h,
            $m,
            $s
        );
    }

    /**
     * Convierte una hora, minutos y segundo en segundos
     *
     * @param $time
     *
     * @return int
     */
    public static function toSecs($time)
    {
        if (HelperValidate::isTime($time)) {

            $arrTime = explode(":", $time);

            settype($arrTime[0], "integer");
            settype($arrTime[1], "integer");
            settype($arrTime[2], "integer");

            $seconds = ($arrTime[0] * pow(60, 2));
            $seconds += (($arrTime[1] * 60) + $arrTime[2]);

            return $seconds;
        } else {
            return 0;
        }
    }

    /**
     * Convierte un string en un double.
     * El separador decimales en el string es la coma
     * Los miles no llevan punto.
     *
     * @param $txt
     *
     * @return float
     */
    public static function string2Double($txt)
    {
        /*  Cambiamos la coma decimal por el punto */
        return doubleval(str_replace(',', '.', $txt));
    }

    /**
     * Separa una frase a un array donde cada palabra es una posición
     *
     * @param string $str
     *
     * @return array
     */
    public static function string2ArrayWord($str)
    {
        return preg_split('/ /', $str, -1);
    }

    /**
     * Transforma un string en un array donde cada caracter es una
     * posición
     *
     * @param string $str
     *
     * @return array
     */
    public static function string2ArrayChar($str)
    {
        return preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Devuelve un string en formato decimal de un numero.
     * Por defecto no muestra decimales, separador miles el punto.
     * Puede redondear.
     *
     * @param int|double $num
     * @param int        $decimals
     * @param bool       $round
     *
     * @return string
     */
    public static function number2String($num, $decimals = 0, $round = true)
    {
        $sSepDecimals = '';
        if ($decimals != 0) {
            $sSepDecimals = ',';
        }
        $sSepMiles = '.';
        if (empty($num)) {
            return '0';
        }
        if (!$round) {
            return number_format($num, $decimals, $sSepDecimals, $sSepMiles);
        }
        $str = number_format($num, $decimals + 1, $sSepDecimals, $sSepMiles);
        return substr($str, 0, strlen($str) - 1);
    }

    /**
     * Devuelve un valor numérico como BYTES
     *
     * @param int $size
     *
     * @return string
     */
    public static function number2Bytes($size)
    {
        $match    = null;
        $suffixes = array(
            ''  => 1,
            'k' => 1024,
            'm' => 1048576, /* 1024 * 1024 */
            'g' => 1073741824, /* 1024 * 1024 * 1024 */
        );
        if (preg_match(
            '/([0-9]+)\s*(k|m|g)?(b?(ytes?)?)/i',
            $size,
            $match
        )) {
            return $match[1] * $suffixes[strtolower($match[2])];
        }

        return '';
    }

    /**
     * Muestra el valor string 0 per un valor null, vacio o zero
     *
     * @param string $str
     *
     * @return string
     */
    public static function empty2Zero($str)
    {
        return HelperValidate::isEmpty($str) ? '0' : $str;
    }

    /**
     * En lugar de mostrar el zero, null o vacío, muestra un espacio
     * en blanco
     *
     * @param int $val
     *
     * @return string
     */
    public static function empty2Nbsp($val)
    {
        return HelperValidate::isEmpty($val) ? '&nbsp;' : $val;
    }

    /**
     * Decodifica los caracteres especiales a html
     * &quot; => "
     *
     * @param $txt
     *
     * @return string
     */
    public static function decodeEntities($txt)
    {
        return html_entity_decode($txt);
    }

    /**
     * Devuelve la fecha y hora (yyyy-mm-dd hh:mm:ss) partiendo de una
     * fecha en formato UTC
     * Formato UTC:  20070724T224556Z
     *
     * @param string $dateUtc
     *
     * @return string
     */
    public static function dateUTC2DateTime($dateUtc)
    {
        $utcdiff = date('Z', time()); /* Diferencia UTC en segundos */

        /* UTC = 20070724T224556Z */
        $y = (int)substr($dateUtc, 0, 4);
        $m = (int)substr($dateUtc, 4, 2);
        $d = (int)substr($dateUtc, 6, 2);
        $h = (int)substr($dateUtc, 9, 2);
        $i = (int)substr($dateUtc, 11, 2);
        $s = (int)substr($dateUtc, 13, 2);

        $stamp = mktime($h, $i, $s, $m, $d, $y);

        $stamp += $utcdiff;

        return date('Y-m-d H:i:s', $stamp);
    }

    /**
     * Convierte una fecha y hora (GMT) en otra en función de una zona 
     * horaria
     *
     * @param string $datetime
     * @param string $timezone
     *
     * @return string
     * @throws Exception
     */
    public static function dateTime2TimeZone($datetime, $timezone = 'America/Mexico_City')
    {
        if (HelperValidate::isEmpty($datetime)) {
            return '';
        }

        if (strlen($datetime) == 16) {
            $datetime .= ':00';
        }

        $offset = 0;

        $userTimezone = new \DateTimeZone($timezone);
        try {
            $date = new \DateTime($datetime, $userTimezone);
        } catch (\Exception $e) {
            throw $e;
        }

        $offset = $userTimezone->getOffset($date);

        return HelperDateTime::addMinutes($datetime, $offset);
    }

    /**
     * Devuevle sólo la fecha de una fecha y hora
     * Formato fecha: yyyy-mm-dd
     *
     * @param $dateHour
     *
     * @return string
     */
    public static function dateTime2Date($dateHour)
    {
        if (strrpos($dateHour, ' ') === false) {
            return null;
        }
        list($dt,) = explode(' ', $dateHour);
        return $dt;
    }

    /**
     * Convierte una fecha Std en timestamp, 15/05/2020 => 1273874400
     * Formato fecha: yyyy-mm-dd
     *
     * @param $date
     *
     * @return int
     */
    public static function date2TimeStamp($date)
    {
        $hours   = 0;
        $minutes = 0;
        $seconds = 0;
        list($year, $month, $day) = explode("-", $date);

        return mktime(
            $hours,
            $minutes,
            $seconds,
            $month,
            $day,
            $year
        );
    }

    /**
     * Devuelve el dia de la fecha en formato texto 
     * (lunes, 23 de enero del 2009)
     * Formato fecha: yyyy-mm-dd
     *
     * @param $date
     *
     * @return string
     */
    public static function date2Text($date)
    {
        $arrDays = array(
            'Domingo',
            'Lunes',
            'Martes',
            'Miercoles',
            'Jueves',
            'Viernes',
            'Sábado'
        );

        $arrMonths = array(
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        );

        list($year, $month, $day) = explode('-', $date);

        $wd = date('w', mktime(0, 0, 0, $month, $day, $year));

        return $arrDays[$wd] . ", " . $day . " de " . $arrMonths[$month - 1] . " del " . $year;
    }

    /**
     * Muestra la fecha en función de un formato.
     * No comprueba si la fecha es correcta
     * Formato de fecha: yyyy-mm-dd
     *
     * @param        $date
     * @param string $showFormat
     *
     * @return string
     */
    public static function date2Format($date, $showFormat = 'dd/mm/yyyy')
    {
        if (HelperValidate::isEmpty($date)) {
            return '';
        }
        /* El formato de fecha de entrada es yyyy-mm-dd */
        list($y, $m, $d) = explode('-', $date);

        switch ($showFormat) {
            case 'dd/mm/yyyy':
                return "$d/$m/$y";

            case 'mm/dd/yyyy':
                return "$m/$d/$y";

            case 'yyyy/mm/dd':
                return "$y/$m/$d";

            case 'dd-mm-yyyy':
                return "$d-$m-$y";

            case 'mm-dd-yyyy':
                return "$m-$d-$y";

            case 'yyyy-mm-dd':
                return "$y-$m-$d";

            case 'dd.mm.yyyy':
                return "$d.$m.$y";

            case 'mm.dd.yyyy':
                return "$m.$d.$y";

            case 'yyyy.mm.dd':
                return "$y.$m.$d";

            default:
                return $date;
        }
    }

    /**
     * Devuelve un fecha y hora en formato UTC partiendo de una fecha 
     * yyyy-mm-dd
     * Format utc: YYYYMMDDTHHiissZ
     *
     * @param string $date
     *
     * @return string
     */
    public static function date2DateUTC($date)
    {
        $utcdiff = date('Z', time()); /* Diferencia UTC en segundos */
        list($year, $month, $day) = explode('-', $date);
        $stamp = mktime(0, 0, 0, $month, $day, $year);
        $stamp -= $utcdiff;
        return date('Ymd\THi\0\0\Z', $stamp);
    }

    /**
     * Devuelve un datetime partiendo de una fecha yyy-mm-dd  y una 
     * hora, minutos, y segundos.
     *
     * @param string $date
     * @param int    $hour
     * @param int    $minutes
     * @param int    $seconds
     *
     * @return string
     */
    public static function date2DateTime($date, $hour = 0, $minutes = 0, $seconds = 0)
    {
        return $date . ' ' . substr('00' . $hour, -2) . ':'
            . substr('00' . $minutes, -2) . ':'
            . substr('00' . $seconds, -2);
    }


    /**
     * Sustitule el tab <br> html por un \n
     *
     * @param $str
     *
     * @return string
     */
    public static function br2nl($str)
    {
        if (empty($str)) {
            return $str;
        }
        preg_match_all("#<[^>]+br.+?>#i", $str, $matches);

        foreach ($matches[0] as $match) {
            $str = str_replace($match, "<br>", $str);
        }

        $brs = array('<br>', '<br/>', '<br />');
        $str = str_replace("\r\n", "\n", $str);
        $str = str_replace("\n\r", "\n", $str);
        $str = str_replace("\r", "\n", $str);
        $str = str_ireplace($brs, "\n", $str);

        return $str;
    }

    /**
     * Devuelve el literal SI o NO en función de un valor booleano
     * Devuelve '' si el valor esta vacio
     *
     * @param $value
     *
     * @return string
     */
    public static function bool2YesNo($value)
    {
        if ($value === '' || is_null($value)) {
            return '';
        }
        if ($value == true || $value == 1) {
            return 'Sí';
        } else {
            if ($value == false || $value == 0 || empty($value)) {
                return 'No';
            } else {
                return $value;
            }
        }
    }
}
