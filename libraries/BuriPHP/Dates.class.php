<?php

/**
 * @package BuriPHP.Libraries
 *
 * @since 1.0
 * @version 2.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 * 
 * @deprecated
 */

namespace Libraries\BuriPHP;

use Libraries\BuriPHP\Helpers\HelperDate;

class Dates
{
    /**
     * Formato de fecha: yyyy-mm-dd
     * 
     * @param date $date
     * @param mixed $get
     * 
     * @deprecated
     * @see HelperDate::getDate()
     * 
     * @return string
     */
    static public function formatted_date($date = null, $get = null)
    {
        trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

        if (is_null($date))
            $date = HelperDate::getToday();

        switch ($get) {
            case 'formatted':
                return HelperDate::getDate($date);
                break;

            default:
                return HelperDate::getDate($date, true);
                break;
        }
    }

    /**
     * Formato de fecha: yyyy-mm-dd
     * 
     * @param date $date
     * 
     * @deprecated
     * @see HelperDate::getDayName()
     * 
     * @return string
     */
    static public function get_day_week($date = null)
    {
        trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

        if (is_null($date))
            $date = HelperDate::getToday();

        return HelperDate::getDayName($date);
    }

    /**
     * Formato de fecha: yyyy-mm-dd
     * 
     * @param date $date
     * @param mixed $month_complete
     * 
     * @deprecated
     * @see HelperDate::getMonthName()
     * 
     * @return string
     */
    static public function get_month($date = null, $month_complete = false)
    {
        trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

        if (is_null($date))
            $date = HelperDate::getToday();

        if ($month_complete == false)
            return HelperDate::getMonthName(HelperDate::getOnlyMonth($date), true);
        else
            return HelperDate::getMonthName(HelperDate::getOnlyMonth($date));
    }

    /**
     * @deprecated
     * @see HelperDate::getDaysCurrentMonth()
     */
    static public function get_current_month()
    {
        trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

        return HelperDate::getDaysCurrentMonth();
    }

    /**
     * @deprecated
     * @see HelperDate::getFirstDayOfMonth
     * @see HelperDate::getLastDayOfMonth
     */
    static public function get_last_month()
    {
        trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

        return [date('Y-m-d', strtotime('first day of last month')), date('Y-m-d', strtotime('last day of last month'))];
    }

    /**
     * @deprecated
     * @see HelperDate::getFirstDayOfMonth
     * @see HelperDate::getLastDayOfMonth
     */
    static public function get_specific_month($date)
    {
        trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

        $date = date("Y-m-01", strtotime(date("d-m-Y") . $date));

        return [date('Y-m-01', strtotime($date)), date('Y-m-t', strtotime($date))];
    }
}
