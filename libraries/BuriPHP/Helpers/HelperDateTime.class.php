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

use BuriPHP\Settings;

abstract class HelperDateTime
{
    /**
     * Devuelve sólo la hora de una fecha y hora
     * Formato fecha y hora: yyyy-mm-dd hh:mm:ss
     * Formato hora: hh:mm:ss
     *
     * @param string $datetime
     *
     * @return string
     */
    public static function getOnlyTime($datetime)
    {
        list(, $time) = explode(' ', $datetime);
        return $time;
    }

    /**
     * Devuelve sólo la fecha de una fecha y hora
     * Formato fecha y hora: yyyy-mm-dd hh:mm:ss
     * Formato fecha: yyyy-mm-dd
     *
     * @param string $datetime
     * @return string
     *
     */
    public static function getOnlyDate($datetime)
    {
        list($date,) = explode(' ', $datetime);
        return $date;
    }

    /**
     * Devuelve el día y hora actual GMT en formato dd/mm/yyyy 
     * hh:mm:ss
     * Formato de fecha: yyyy-mm-dd hh.mm:ss
     * @return string
     */
    public static function getNowGMT()
    {
        return gmdate('Y-m-d H:i:s');
    }

    /**
     * Devuelve el día y hora actual en formato dd/mm/yyyy con la zona horaria
     * hh:mm:ss
     * Formato de fecha: yyyy-mm-dd hh.mm:ss
     * @return string
     */
    public static function getNowTimezone()
    {
        $timestamp = time();
        $dt = new \DateTime("now", new \DateTimeZone(Settings::$timeZone));
        $dt->setTimestamp($timestamp);
        return $dt->format('Y-m-d H:i:sP');
    }

    /**
     * Devuelve el día y hora actual en formato dd/mm/yyyy hh:mm:ss
     * Formato de fecha: yyyy-mm-dd hh.mm:ss
     * @return string
     */
    public static function getNow()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Indica que fecha/hora es menor
     * > 1 => dt1 > dt2
     * < 1 => dt1 < dt2
     * = 0 => dt1 = dt2
     *
     * @param $strDT1
     * @param $strDT2
     *
     * @return int
     */
    public static function getDiff($strDT1, $strDT2)
    {
        list($d1, $h1) = explode(' ', $strDT1);
        list($d2, $h2) = explode(' ', $strDT2);

        list($nDay1, $nMonth1, $nYear1) = explode('-', $d1);
        list($nDay2, $nMonth2, $nYear2) = explode('-', $d2);

        list($nh1, $nm1, $na1) = explode(':', $h1);
        list($nh2, $nm2, $na2) = explode(':', $h2);

        return mktime($nh1, $nm1, $na1, $nMonth1, $nDay1, $nYear1) -
            mktime($nh2, $nm2, $na2, $nMonth2, $nDay2, $nYear2);
    }

    /**
     * Muestra la fecha y hora en función de un formato.
     *
     * @param string $datetime
     * @param string $format
     * @param bool   $showSeconds
     *
     * @return string
     */
    public static function displayWithFormat($datetime, $format = 'dd/mm/yyyy', $showSeconds = true)
    {
        if (HelperValidate::isEmpty($datetime)) {
            return '';
        }

        if (strlen($datetime) == 16) {
            $datetime .= ':00';
        }

        $offset = 0;

        list($dt, $tm) = explode(' ', $datetime);

        $dt = ''; //HelperDate::display($dt, $format);

        if ($showSeconds) {
            return "$dt $tm";
        } else {
            list($h, $m,) = explode(':', $tm);
            return "$dt $h:$m";
        }
    }

    /**
     * Sumar minutos a una fecha.
     * Formato de fecha: yyyy-mm-ss
     *
     * @param $date
     * @param $minutes
     *
     * @return false|string
     */
    public static function addMinutes($date, $minutes)
    {
        return date(
            'Y-m-d H:i:s',
            strtotime($date . ' + ' . $minutes . ' minute')
        );
    }

    /**
     * Suma una cantidad de días, meses, años, horas, minutos y/o 
     * segundos a una fecha y hora
     * Formato de fecha y hora: yyyy-mm-dd hh:mm:ss
     *
     * @param     $date
     * @param int $dd
     * @param int $mm
     * @param int $yy
     * @param int $hh
     * @param int $mn
     * @param int $ss
     *
     * @return string
     */
    public static function addDateTime($date, $dd = 0, $mm = 0, $yy = 0, $hh = 0, $mn = 0, $ss = 0)
    {
        list($year, $month, $day) = explode('-', $date);
        $date   = $year . '-' . $month . '-' . $day;
        $date_r = getdate(strtotime($date));
        return date('Y-m-d', mktime(($date_r["hours"] + $hh),
            ($date_r["minutes"] + $mn),
            ($date_r["seconds"] + $ss),
            ($date_r["mon"] + $mm),
            ($date_r["mday"] + $dd),
            ($date_r["year"] + $yy)
        ));
    }

    /**
     * Suma un número de meses a una fecha.
     * Formato de fecha: yyyy-mm-dd.
     *
     * @param string $date
     * @param int    $months
     *
     * @return string
     */
    public static function addMonthsToDate($date, $months)
    {
        $dt = new \DateTime($date, new \DateTimeZone(Settings::$timeZone));

        $dt->setTimestamp(time());
        $dt->modify("+{$months} month");

        return $dt->format('Y-m-d H:i:sP');
    }

    /**
     * Establece la configuración horaria
     */
    public static function setLocateTimeZone()
    {
        date_default_timezone_set(Settings::$timeZone);
        setlocale(LC_ALL, Settings::$locale);
        setlocale(LC_TIME, Settings::$locale);
    }
}
