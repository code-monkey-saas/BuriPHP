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

abstract class HelperDevice
{
    /**
     * Devuelve el sistema opeativo del cliente
     * @return string
     */
    public static function getSO()
    {
        $userAgent = HelperString::toLower(
            HelperServer::getValue('HTTP_USER_AGENT')
        );

        if (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'Mac';
        } elseif (preg_match('/windows|win32/i', $userAgent)) {
            $platform = 'Windows';
        } else {
            $platform = 'Otro';
        }

        return $platform;
    }

    /**
     * Función que devuelve el nombre del navegador utilizado por el 
     * cliente
     * @return string
     */
    public static function getNavigator()
    {
        $userAgent = HelperString::toLower(
            HelperServer::getValue('HTTP_USER_AGENT')
        );

        if (strpos($userAgent, 'opera') || strpos($userAgent, 'opr/')) {
            return 'Opera';
        } elseif (stripos($userAgent, 'edge') !== false) {
            return 'Edge';
        } elseif (stripos($userAgent, 'chrome') !== false) {
            return 'Chrome';
        } elseif (stripos($userAgent, 'safari') !== false) {
            return 'Safari';
        } elseif (stripos($userAgent, 'firefox') !== false) {
            return 'Firefox';
        } elseif (
            stripos($userAgent, 'msie') !== false ||
            stripos($userAgent, 'trident/7') !== false
        ) {
            return 'IE';
        } elseif (stripos($userAgent, 'ipod') !== false) {
            return 'iPod';
        } elseif (stripos($userAgent, 'iphone') !== false) {
            return 'iPhone';
        } elseif (stripos($userAgent, 'ipad') !== false) {
            return 'iPad';
        } elseif (stripos($userAgent, 'android') !== false) {
            return 'Android';
        } elseif (stripos($userAgent, 'webos') !== false) {
            return 'WebOS';
        } elseif (stripos($userAgent, 'blackberry') !== false) {
            return 'Blackberry';
        }

        return 'Otro';
    }

    /**
     * Devuelve la ip del cliente o null si no puede detectarla
     *
     * @return string
     */
    public static function getIp()
    {
        foreach (array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ) as $key) {

            $value = HelperServer::getValue($key);

            if (!HelperValidate::isEmpty($value)) {
                foreach (explode(',', $value) as $ip) {
                    $ip = trim($ip);

                    if (filter_var(
                        $ip,
                        FILTER_VALIDATE_IP,
                        FILTER_FLAG_NO_PRIV_RANGE |
                            FILTER_FLAG_NO_RES_RANGE
                    ) !== false) {

                        return $ip;
                    }
                }
            }
        }
        return null;
    }

    /**
     * Devuelve el idioma configurado en al dispositivo 
     * HTTP_ACCEPT_LANGUAGE
     *
     * @param $languageDefault
     *
     * @return string
     */
    public static function getLanguageBrowser($languageDefault)
    {

        $httpAccept = HelperString::toLower(
            HelperServer::getValue('HTTP_ACCEPT_LANGUAGE')
        );

        if (HelperValidate::isEmpty($httpAccept)) {
            return $languageDefault;
        }

        // dividir los posibles idiomas en un array
        $arrayAccept = explode(",", $httpAccept);

        $languages = [];
        foreach ($arrayAccept as $val) {

            // comprovar el valor q y crear un array asociativo. 
            // Si no existe el valor q, es por defecto 1
            if (preg_match(
                "/(.*);q=([0-1]{0,1}.\d{0,4})/i",
                $val,
                $matches
            )) {
                $languages[$matches[1]] = (float)$matches[2];
            } else {

                $languages[$val] = 1.0;
            }
        }

        // Eel idioma por defecto el cual es el valor q más alto
        $qval = 0.0;

        foreach ($languages as $key => $value) {

            if ($value > $qval) {
                $qval = (float)$value;

                $languageDefault = $key;
            }
        }
        return HelperString::toLower($languageDefault);
    }
}
