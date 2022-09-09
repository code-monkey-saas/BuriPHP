<?php

namespace BuriPHP;

/**
 *
 * @package BuriPHP
 *
 * @since 1.0
 * @version 2.0
 * @license    You can see LICENSE.txt
 *
 * @author     David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright  Copyright (C) CodeMonkey - BuriPHP. All Rights Reserved.
 */

if (version_compare(PHP_VERSION, '8.0', '<'))
    die('Your host needs to use PHP 8.0 or higher to run this version of BuriPHP.');

require_once __DIR__ . DIRECTORY_SEPARATOR . 'defines.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'AppSettings.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Settings.php';

spl_autoload_register(function ($str) {
    $arr = explode('\\', $str);
    $resource = end($arr);

    if (isset($arr[1]) && $arr[1] == 'BuriPHP') {
        if (in_array($arr[2], ['Helpers', 'Interfaces'])) { // Incluimos los helpers e interfaces
            if (file_exists(PATH_BURIPHP_HELPERS . $resource . CLASS_PHP)) { // Helpers
                require_once PATH_BURIPHP_HELPERS . $resource . CLASS_PHP;
            }

            if (file_exists(PATH_BURIPHP_INTERFACES . $resource . INTERFACE_PHP)) { // Interfaces
                require_once PATH_BURIPHP_INTERFACES . $resource . INTERFACE_PHP;
            }
        } else {
            if (file_exists(PATH_BURIPHP_LIBRARIES . $resource . CLASS_PHP)) { // Librerias de BuriPHP
                require_once PATH_BURIPHP_LIBRARIES . $resource . CLASS_PHP;
            }
        }
    } else {
        if (file_exists(PATH_LIBRARIES . $resource . CLASS_PHP)) { // Librerias externas
            require_once PATH_LIBRARIES . $resource . CLASS_PHP;
        }
    }
});

(new \Libraries\BuriPHP\Application())->exec();
