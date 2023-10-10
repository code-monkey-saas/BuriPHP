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

use IntlDateFormatter;

abstract class HelperDate
{
    /**
     * Devuelve el año actual
     * @return int (1-12)
     */
    public static function getYearToday()
    {
        return date('Y');
    }

    /**
     * Devuelve el número de año de la fecha.
     * Formato de fecha: yyyy-mm-dd
     *
     * @param $date
     *
     * @return int
     */
    public static function getOnlyYear($date)
    {
        list($year,,) = explode('-', $date);
        return intval($year);
    }

    /**
     * Devuelve el mes actual
     * @return int (01-12)
     */
    public static function getMonthToday()
    {
        return date('m');
    }

    /**
     * Devuelve el número de mes de la fecha.
     * Formato de fecha: yyyy-mm-dd
     *
     * @param $date
     *
     * @return int
     */
    public static function getOnlyMonth($date)
    {
        list(, $month,) = explode('-', $date);
        return intval($month);
    }

    /**
     * Devuelve el nombre de un mes.
     *
     * @param $month
     *
     * @return string
     */
    public static function getMonthName($month, $short = false)
    {
        if (!$short) {
            return ucfirst(date("F", mktime(0, 0, 0, $month, 1, 2000)));
            /*  Diciembre */
        } else {
            return ucfirst(date("M", mktime(0, 0, 0, $month, 1, 2000)));
            /*  Dec */
        }
    }

    /**
     * Devuelve la fecha del primer y último día del mes actual.
     * Formato de fecha: yyyy-mm-dd
     *
     * @return array
     */
    public static function getDaysCurrentMonth()
    {
        return [
            date('Y-m-d', strtotime('first day of this month')),
            date('Y-m-d', strtotime('last day of this month')),
        ];
    }

    /**
     * Devuelve la fecha del primer día de un mes y año determinado.
     * Formato de fecha: yyyy-mm-dd
     *
     * @param int $month
     * @param int $year
     *
     * @return string
     */
    public static function getFirstDayOfMonth($month, $year)
    {
        if ($month < 1 && $month > 12) {
            return 0;
        }
        return $year . '-' . substr('00' . $month, -2) . '-01';
    }

    /**
     * Devuelve la fecha del último día de un mes y año 
     * determinado.
     * Si el mes o el año son incorrecto, devuelve null
     * Formato de fecha: yyyy-mm-dd
     *
     * @param int $month
     * @param int $year
     *
     * @return string
     */
    public static function getLastDayOfMonth($month, $year)
    {
        if ($month < 1 && $month > 12) {
            return null;
        }
        $day = date("d", mktime(0, 0, 0, $month + 1, 0, $year));

        return $year . '-' . substr('00' . $month, -2) . '-' . $day;
    }

    /**
     * Devuelve la fecha del último día hábil del mes.
     * No es ni sábado ni domingo.
     * Formato de fecha: yyyy-mm-dd
     *
     * @param int $month
     * @param int $year
     *
     * @return string
     */
    public static function getLastDayOfMonthBusiness($month, $year)
    {
        $sDate = self::getLastDayOfMonth($month, $year);
        $nDay   = self::getDayOfWeek($sDate);

        if ($nDay == 0) {
            $nDay--;
        }

        if ($nDay == 6) {
            $nDay--;
        }

        return date('Y-m-d', mktime(0, 0, 0, $month, $nDay, $year));
    }

    /**
     * Devuelve el número de la semana dentro del año de una fecha 
     * concreta
     * Formato de fecha: yyyy-mm-dd
     *
     * @param $date
     *
     * @return string
     * @throws \Exception
     */
    public static function getNumberOfWeek($date)
    {
        $date = new \DateTime($date);
        return $date->format("W");
    }

    /**
     * Devuelve el día actual.
     * Formato de fecha: yyyy-mm-dd
     * @return string
     */
    public static function getToday(): string
    {
        return date('Y-m-d');
    }

    /***
     * Devuelve el dia actual formato yyyy-mm-dd GMT
     * @return string
     */
    public static function getTodayGMT()
    {
        return gmdate('Y-m-d');
    }

    /**
     * Devuelve el número de día de la fecha
     * Formato de fecha: yyyy-mm-dd
     *
     * @param $date
     *
     * @return int
     */
    public static function getOnlyDay($date)
    {
        if (!HelperValidate::isDate($date)) {
            return 0;
        }
        list(,, $day) = explode('-', $date);
        return intval($day);
    }

    /**
     * Devuelve el día de la semana
     *     0: "Domingo"
     *     1:  "Lunes"
     *     2:  "Martes"
     *     3:  "Miércoles"
     *     4:  "Jueves"
     *     5:  "Viernes"
     *     6:  "Sábado"
     *
     * @param string $date
     *
     * @return string
     */
    public static function getDayOfWeek($date)
    {
        return date('w', strtotime($date));
    }

    /**
     * Devuelve el nombre del día de la semana
     * Formato fecha: yyyy-mm-dd
     *
     * @param string $date
     *
     * @return string
     */
    public static function getDayName($date)
    {
        return date('l', strtotime($date));
    }

    /**
     * Devuelve el número de dias de un més y año
     *
     * @param        $month
     * @param string $year
     *
     * @return int
     */
    public static function getDaysInMonth($month, $year = '')
    {
        if ($month <= 0 || $month > 12) {
            return 0;
        }
        if (empty($year)) {
            $year = date('Y');
        }
        // Composer
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    /**
     * Devuelve la fecha en formato largo o corto.
     *
     * @param        $month
     * @param string $year
     *
     * @return int
     */
    public static function getDate($date = false, $short = false, $locale = 'es_ES')
    {
        if (false === $date) {
            $date = HelperDate::getToday();
        } else {
            $date = HelperDateTime::getOnlyDate($date);
        }

        if (!$short) {
            $formatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        } else {
            $formatter = new IntlDateFormatter($locale, IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE);
        }

        return $formatter->format(strtotime($date));
    }

    /**
     * Partiendo de una fecha en un foramto determinado, devuelve el 
     * dia, mes y año
     *
     * @param $value
     * @param $day
     * @param $month
     * @param $year
     */
    public static function explode($value, &$day, &$month, &$year)
    {
        $day = $month = $year = 0;
        list($year, $month, $day) = explode('-', $value);
    }

    /**
     * Compara dos fechas indicando cual es mayor, menor o iguales
     * Formato de fecha: yyyy-mm-dd
     * Devuele:
     *  Menor que 0 si fechaStd1 < fechaStd2,
     *  Igual a 0 si fechaStd1 = fechaStd2,
     *  Mayor que 0 si fechaStd1 > fechaStd2
     *
     * @param string $date1
     * @param string $date2
     *
     * @return int
     */
    public static function getDiff($date1, $date2)
    {
        list($year1, $month1, $day1) = explode('-', $date1);
        list($year2, $month2, $day2) = explode('-', $date2);
        return mktime(0, 0, 0, $month1, $day1, $year1) -
            mktime(0, 0, 0, $month2, $day2, $year2);
    }

    /**
     * Devuelve el numero de días entre dos fechas.
     * Formato de fecha: yyyy-mm-dd
     *
     * @param $date1
     * @param $date2
     *
     * @return int
     */
    public static function getDiffDays($date1, $date2)
    {
        list($year1, $month1, $day1) = explode('-', $date1);
        list($year2, $month2, $day2) = explode('-', $date2);

        /* calculo timestam de las dos fechas */
        $timestamp1 = mktime(0, 0, 0, $month1, $day1, $year1);
        $timestamp2 = mktime(0, 0, 0, $month2, $day2, $year2);

        /* resto a una fecha la otra */
        $secondsDifference = $timestamp1 - $timestamp2;


        /* convierto segundos en días */
        $daysDifference = $secondsDifference / (60 * 60 * 24);

        /* obtengo el valor absoluto de los dÃías 
           (quito el posible signo negativo) */
        $daysDifference = abs($daysDifference);

        /* quito los decimales a los dí­as de diferencia  */
        $daysDifference = floor($daysDifference);

        return intval($daysDifference);
    }

    /**
     * Devuelve el separador utilizado en una fecha.
     *
     * @param string $date
     *
     * @return string
     */
    public static function getSeparator($date)
    {
        if (strpos($date, '/') !== false) {
            return '/';
        } elseif (strpos($date, '-') !== false) {
            return '-';
        } elseif (strpos($date, '.') !== false) {
            return '.';
        } else {
            return '';
        }
    }

    /**
     * Devuevle el pattern para un formato de fecha determinado.
     *
     * @param $format
     *
     * @return string
     */
    public static function getPattern($format = 'yyyy-mm-dd')
    {
        switch ($format) {
            case 'dd/mm/yyyy':
            case 'mm/dd/yyyy':
                return "\\d{2}/\\d{2}/\\d{4}";

            case 'yyyy/mm/dd':
                return "\\d{4}/\\d{2}/\\d{2}";

            case 'dd-mm-yyyy':
            case 'mm-dd-yyyy':
                return "\\d{2}\\-\\d{2}\\-\\d{4}";

            case 'yyyy-mm-dd':
                return "\\d{4}\\-\\d{2}\\-\\d{2}";

            case 'dd.mm.yyyy':
            case 'mm.dd.yyyy':
                return "\\d{2}\\.\\d{2}\\.\\d{4}";

            case 'yyyy.mm.dd':
                return "\\d{4}\\.\\d{2}\\.\\d{2}";

            default:
                return "";
                break;
        }
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
        list($year, $month, $day) = explode('-', $date);

        if ($month + $months > 12) {

            $addMonths = (intval(($month + $months) % 12));
            $addYears  = intval(($month + $months) / 12);
            $month        = $addMonths; /* -- No se suma */
            $year       += $addYears;
        } else {

            $month  += $months;
            $year += 0;
        }
        return $year . '-' .
            substr('00' . $month, -2) . '-' .
            substr('00' . $day, -2);
    }

    /**
     * Suma un valor a una fecha.
     * Formato de fecha: yyyy-mm-dd
     *
     * @param string $date
     * @param int    $dd
     * @param int    $mm
     * @param int    $yy
     * @param int    $hh
     * @param int    $mn
     * @param int    $ss
     *
     * @return string
     */
    public static function addValueToDate($date, $dd = 0, $mm = 0, $yy = 0, $hh = 0, $mn = 0, $ss = 0): string
    {
        list($any, $month, $day) = explode('-', $date);
        $date   = $any . '-' . $month . '-' . $day;
        $date_r = getdate(strtotime($date));
        return date(
            'Y-m-d',
            mktime(($date_r["hours"] + $hh),
                ($date_r["minutes"] + $mn),
                ($date_r["seconds"] + $ss),
                ($date_r["mon"] + $mm),
                ($date_r["mday"] + $dd),
                ($date_r["year"] + $yy)
            )
        );
    }

    /**
     * Suma N días naturales a una fecha.
     * Formato de fecha: yyyy-mm-dd
     *
     * @param string $date
     * @param int    $days
     *
     * @return string
     */
    public static function addDaysToDate($date, $days)
    {
        if ($days < 0) {
            return '';
        }

        $dateArray = explode("-", $date);

        $sd = $days;

        while ($sd > 0) {
            if (
                $sd <= date("t", mktime(
                    0,
                    0,
                    0,
                    $dateArray[1],
                    1,
                    $dateArray[0]
                )) -
                $dateArray[2]
            ) {

                $dateArray[2] = $dateArray[2] + $sd;

                $sd = 0;
            } else {

                $sd = $sd - (date("t", mktime(
                    0,
                    0,
                    0,
                    $dateArray[1],
                    1,
                    $dateArray[0]
                )) -
                    $dateArray[2]);
                $dateArray[2] = 0;

                if ($dateArray[1] < 12) {
                    $dateArray[1]++;
                } else {
                    $dateArray[1] = 1;
                    $dateArray[0]++;
                }
            }
        }

        $sDay = '00' . $dateArray[2];
        $sDay = substr($sDay, -2);

        $sMonth = '00' . $dateArray[1];
        $sMonth = substr($sMonth, -2);

        return $dateArray[0] . '-' . $sMonth . '-' . $sDay;
    }

    /**
     * Estandariza un numero de día o mes a dos caracteres con ceros
     *
     * @param $num
     *
     * @return string
     */
    public static function normalize2Chars($num)
    {
        return str_pad($num, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Calcula la edad de una persona por la fecha de su nacimiento
     *
     * @param string $date
     *
     * @return int
     */
    public static function calculateAge($date)
    {
        return intval(self::getDiffDays($date, date("Y-m-d")) / 365);
    }
}
