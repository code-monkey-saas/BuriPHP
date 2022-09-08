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

use Libraries\BuriPHP\Helpers\HelperCrypt;
use Libraries\BuriPHP\Helpers\HelperFile;
use Libraries\BuriPHP\Helpers\HelperServer;
use Libraries\BuriPHP\Helpers\HelperString;

class Security
{
    /**
     * Obtiene la url dividida en array.
     *
     * @static
     * 
     * @deprecated
     * @see HelperServer::getCurrentPathInfo()
     *
     * @return string
     */
    public static function url()
    {
        return HelperServer::getCurrentPathInfo();
    }

    /**
     * Quita caracteres especiales de un string.
     *
     * @static
     *
     * @param string $str
     * 
     * @deprecated
     * @see HelperString::sanitizeAll()
     *
     * @return string
     */
    public static function clean_string($str)
    {
        return HelperString::sanitizeAll($str);
    }

    /**
     * Remplaza los slashes de un uri por los default del sistema.
     *
     * @static
     * 
     * @deprecated
     * @see HelperFile::getSanitizedPath()
     *
     * @param string $path
     * @return string
     */
    public static function DS($path)
    {
        return HelperFile::getSanitizedPath($path);
    }

    /**
     * Obtiene el protocolo Web en uso.
     *
     * @static
     * 
     * @deprecated
     * @see HelperServer::getProtocol()
     *
     * @return string
     */
    public static function protocol()
    {
        return HelperServer::getProtocol();
    }

    /**
     * Crea un hash encriptado con la clave secreta de la configuración.
     *
     * @param string $algorithm
     * @param string $data
     * 
     * @deprecated
     * @see HelperCrypt::createHash()
     *
     * @return string
     */
    public function create_hash($algorithm, $data)
    {
        return HelperCrypt::createHash($algorithm, $data);
    }

    /**
     * Crea una password encriptada.
     *
     * @param string $string
     * 
     * @deprecated
     * @see HelperCrypt::createPassword()
     *
     * @return string
     */
    public function create_password($string)
    {
        return HelperCrypt::createPassword($string);
    }

    /**
     * Genera un numero dado de bytes.
     *
     * @param integer $length
     * 
     * @deprecated
     * @see HelperCrypt::randomBytes()
     *
     * @return mixed
     */
    public function random_bytes($length = 16)
    {
        return HelperCrypt::randomBytes($length);
    }

    /**
     * Genera un string de caracteres random.
     *
     * @param integer $length
     * 
     * @deprecated
     * @see HelperString::randomString()
     *
     * @return string
     */
    public function random_string($length = 8)
    {
        return HelperString::randomString($length);
    }

    /**
     * Obtiene el navegador del cliente.
     *
     * @deprecated
     * @see HelperDevice::getNavigator()
     * 
     * @return string
     */
    static public function get_browser()
    {
        $browser = "OTHER";

        foreach (["IE", "OPERA", "MOZILLA", "NETSCAPE", "FIREFOX", "SAFARI", "CHROME"] as $parent) {
            $s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
            $f = $s + strlen($parent);
            $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
            $version = preg_replace('/[^0-9,.]/', '', $version);

            if ($s) $browser = $parent;
        }

        return $browser;
    }

    /**
     * Obtiene la IP del cliente.
     *
     * @deprecated
     * @see HelperDevice::getIp()
     * 
     * @return  string
     */
    static public function get_ip()
    {
        if (getenv('HTTP_CLIENT_IP')) return getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR')) return getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED')) return getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR')) return getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED')) return getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR')) return getenv('REMOTE_ADDR');
        else return 'UNKNOWN';
    }

    /**
     * Obtiene el sistema operativo del cliente.
     *
     * @deprecated
     * @see HelperDevice::getSo()
     * 
     * @return  string
     */
    static function get_os()
    {
        $os = 'OTHER';

        foreach (["WIN", "MAC", "LINUX"] as $val) {
            if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $val) !== false) $os = $val;
        }

        return $os;
    }

    /**
     * Obtiene dispositivo movil del cliente.
     *
     * @deprecated
     * @see HelperDevice::getNavigator()
     * 
     * @return  string
     */
    static public function get_mobile_device()
    {
        if (stristr($_SERVER['HTTP_USER_AGENT'], 'ipad')) return "iPad";
        else if (stristr($_SERVER['HTTP_USER_AGENT'], 'iphone') || strstr($_SERVER['HTTP_USER_AGENT'], 'iphone')) return "iPhone";
        else if (stristr($_SERVER['HTTP_USER_AGENT'], 'blackberry')) return "BlackBerry";
        else if (stristr($_SERVER['HTTP_USER_AGENT'], 'android')) return "Android";
        else return "Otro";
    }

    /**
     * Obtiene el tipo de dispositivo del cliente.
     *
     * @deprecated
     * @see HelperDevice::getNavigator()
     * 
     * @return  string
     */
    static public function get_type_device()
    {
        $tablet_browser = 0;
        $mobile_browser = 0;

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) $tablet_browser++;
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) $mobile_browser++;
        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) $mobile_browser++;

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = [
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
            'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
            'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-'
        ];

        if (in_array($mobile_ua, $mobile_agents)) $mobile_browser++;

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') > 0) {
            $mobile_browser++;

            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));

            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) $tablet_browser++;
        }

        if ($tablet_browser > 0) return 'tablet';
        else if ($mobile_browser > 0) return 'mobile';
        else return 'desktop';
    }

    /**
     * Obtiene el toda la información de conexión del cliente.
     *
     * @deprecated
     * 
     * @return  array
     */
    static public function get_client_info()
    {
        $info['ip'] = Self::get_ip();
        $info['browser'] = Self::get_browser();
        $info['device'] = ucfirst(Self::get_type_device());

        if ($info['device'] == ucfirst('tablet') || $info['device'] == ucfirst('mobile')) $info['so'] = Self::get_mobile_device();
        else $info['so'] = Self::get_os();

        $info['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

        return $info;
    }
}
