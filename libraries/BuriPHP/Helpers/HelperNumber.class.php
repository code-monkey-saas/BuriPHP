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

abstract class HelperNumber
{
    /**
     * Devuelve un string de una cantidad en fomarto del pais locale
     *
     * @param        $number
     * @param string $symbol
     *
     * @return string
     */
    public static function displayMoney($number, $symbol = '')
    {
        $ex = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?' . '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
        if (setlocale(LC_MONETARY, 0) == 'C') {
            setlocale(LC_MONETARY, '');
        }

        $locale = localeconv();
        $format = '%i';
        preg_match_all($ex, $format, $matches, PREG_SET_ORDER);

        foreach ($matches as $fmatch) {
            $value = floatval($number);
            $flags = array(
                'fillchar'  => preg_match(
                    '/\=(.)/',
                    $fmatch[1],
                    $match
                ) ? $match[1] : ' ',
                'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
                'usesignal' => preg_match(
                    '/\+|\(/',
                    $fmatch[1],
                    $match
                ) ? $match[0] : '+',
                'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
                'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
            );
            $width = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
            $left  = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
            $right = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];

            $conversion     = $fmatch[5];
            $positiveValue = true;

            if ($value < 0) {
                $positiveValue = false;
                $value          *= -1;
            }
            $letter = $positiveValue ? 'p' : 'n';

            $prefix = $suffix = $cprefix = $csuffix = $sign = '';

            $sign = $positiveValue ? $locale['positive_sign'] : $locale['negative_sign'];

            switch (true) {
                case $locale["{$letter}_sign_posn"] == 1 &&
                    $flags['usesignal'] == '+':

                    $prefix = $sign;
                    break;

                case $locale["{$letter}_sign_posn"] == 2 &&
                    $flags['usesignal'] == '+':
                    $suffix = $sign;
                    break;
                case $locale["{$letter}_sign_posn"] == 3 &&
                    $flags['usesignal'] == '+':
                    $cprefix = $sign;
                    break;
                case $locale["{$letter}_sign_posn"] == 4 &&
                    $flags['usesignal'] == '+':
                    $csuffix = $sign;
                    break;
                case $flags['usesignal'] == '(':
                case $locale["{$letter}_sign_posn"] == 0:
                    $prefix = '(';
                    $suffix  = ')';
                    break;
            }
            if (!$flags['nosimbol']) {
                $currency = $cprefix . ($conversion == 'i' ?
                    $locale['int_curr_symbol'] :
                    $locale['currency_symbol']) .
                    $csuffix;
            } else {
                $currency = $cprefix . $csuffix;
            }

            // Eliminamos el texto de la moneda 
            $currency = " " . $symbol . " ";
            $espacio = $locale["{$letter}_sep_by_space"] ? ' ' : '';

            $value = number_format(
                $value,
                $right,
                $locale['mon_decimal_point'],
                $flags['nogroup'] ? '' : $locale['mon_thousands_sep']
            );
            $value = @explode($locale['mon_decimal_point'], $value);

            $n = strlen($prefix) +
                strlen($currency) +
                strlen($value[0]);
            if ($left > 0 && $left > $n) {
                $value[0] = str_repeat(
                    $flags['fillchar'],
                    $left - $n
                ) . $value[0];
            }
            $value = implode($locale['mon_decimal_point'], $value);
            if ($locale["{$letter}_cs_precedes"]) {
                $value = $prefix . $currency . $espacio . $value . $suffix;
            } else {
                $value = $prefix . $value . $espacio . $currency . $suffix;
            }
            if ($width > 0) {
                $value = str_pad(
                    $value,
                    $width,
                    $flags['fillchar'],
                    $flags['isleft'] ? STR_PAD_RIGHT : STR_PAD_LEFT
                );
            }

            $format = str_replace($fmatch[0], $value, $format);
        }
        $format = trim(str_replace('  ', ' ', $format));
        return $format;
    }

    /**
     * Devuelve un string unicamente con los números.
     *
     * @param        $number
     *
     * @return string
     */
    public static function getNumbers($number)
    {
        return (string) preg_replace('/[^0-9]/', '', $number);
    }

    /**
     * Genera un número aleatorio de N digitos de longitud.
     *
     * @param int $numDigits
     *
     * @return string
     */
    public static function random($numDigits = 6): string
    {
        $random = '';
        for ($n = $numDigits; $n > 0; $n--) {
            $generated = '' . mt_rand();
            $position = mt_rand(1, strlen($generated) - 1);
            $random   .= $generated[$position];
        }
        return strval($random);
    }
}
