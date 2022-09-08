<?php

namespace BuriPHP\System\Libraries;

/**
 *
 * @package BuriPHP.Libraries
 *
 * @since 1.0.0
 * @version 1.1.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

class Dates
{
    public function __construct()
    {
        date_default_timezone_set(\BuriPHP\Configuration::$time_zone);
    }

    static public function formatted_date($date = null, $get = null)
    {
        if (is_null($date))
            $date = date('Y-m-d');

        $day_number = date('d', strtotime($date));
        $year = date('Y', strtotime($date));

        switch ($get) {
            case 'formatted':
                return self::get_day_week($date) . " " . $day_number . " de " . self::get_month($date, true) . " del " . $year;
                break;

            default:
                return $day_number . "/" . self::get_month($date, true) . "/" . $year;
                break;
        }
    }

    static public function get_day_week($date = null)
    {
        date_default_timezone_set(\BuriPHP\Configuration::$time_zone);

        if (!is_null($date)) {
            $days = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

            return $days[date('N', strtotime($date)) - 1];
        } else
            return null;
    }

    static public function get_month($date = null, $month_complete = false)
    {
        date_default_timezone_set(\BuriPHP\Configuration::$time_zone);

        if (!is_null($date)) {
            if ($month_complete == false)
                $months = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
            else
                $months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

            return $months[date('m', strtotime($date)) - 1];
        } else
            return null;
    }

    static public function get_current_month()
    {
        return [date('Y-m-d', strtotime('first day of this month')), date('Y-m-d', strtotime('last day of this month'))];
    }

    static public function get_last_month()
    {
        return [date('Y-m-d', strtotime('first day of last month')), date('Y-m-d', strtotime('last day of last month'))];
    }

    static public function get_specific_month($date)
    {
        $date = date("Y-m-01", strtotime(date("d-m-Y") . $date));

        return [date('Y-m-01', strtotime($date)), date('Y-m-t', strtotime($date))];
    }
}