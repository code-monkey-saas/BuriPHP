<?php namespace BuriPHP\Administrator\Libraries;

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

defined('_EXEC') or die;

use \BuriPHP\System\Libraries\{Session};

class Dashboard
{
    static public function main_menu( $menu = [] ) : array
    {
        $menu[] = [
            'name' => 'Dashboard',
            'url' => 'index.php/',
            'icon' => 'dripicons-home'
        ];

        return $menu;
    }
}
