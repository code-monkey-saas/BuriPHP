<?php namespace BuriPHP\Administrator;

/**
 *
 * @package BuriPHP.Administrator
 *
 * @since 1.0.0
 * @version 1.0.0
 * @license    You can see LICENSE.txt
 *
 * @author     David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright  Copyright (C) CodeMonkey - BuriPHP. All Rights Reserved.
 */

define('_EXEC', 1);

if ( version_compare(PHP_VERSION, '7.0', '<') )
    die('Your host needs to use PHP 7.0 or higher to run this version of BuriPHP.');

if ( !defined('_DEFINES') )
{
    define('PATH_ROOT', dirname(__DIR__));
    require_once PATH_ROOT . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'defines.php';
}

spl_autoload_register(function ( $namespace )
{
    $namespace = explode('\\', $namespace);
    $library = end( $namespace ) . CLASS_PHP;

    if ( file_exists(PATH_BURIPHP_LIBRARIES . $library) )
    {
        require_once PATH_BURIPHP_LIBRARIES . $library;
    }

    if ( file_exists(PATH_ADMINISTRATOR_BURIPHP_LIBRARIES . $library) )
    {
        require_once PATH_ADMINISTRATOR_BURIPHP_LIBRARIES . $library;
    }
    if ( file_exists(PATH_ADMINISTRATOR_LIBRARIES . $library) )
    {
        require_once PATH_ADMINISTRATOR_LIBRARIES . $library;
    }
});

echo (new \BuriPHP\Administrator\System\Libraries\Layout())->execute();
