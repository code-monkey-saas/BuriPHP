<?php namespace BuriPHP\Administrator\Libraries;

/**
 *
 * @package BuriPHP.Libraries
 *
 * @since 1.0.0
 * @version 1.5.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

defined('_EXEC') or die;

use \BuriPHP\Administrator\System\Libraries\System;

class Urls_registered
{
    static public $default = '/';

    static public function urls()
    {
        $urls = [
            '/' => [
                'controller' => 'Index',
                'method' => 'index',
            ],
            '/help' => [
                'controller' => 'Index',
                'method' => 'help'
            ]
        ];

        $urls = array_merge($urls, System::import_urls_component('Users'));

        return $urls;
    }
}
