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

class Urls_registered
{
    static public $default = '/';

    static public function urls()
    {
        return [
            '/' => [
                'controller' => 'Index',
                'method' => 'index',
            ],
            '/help' => [
                'controller' => 'Index',
                'method' => 'help'
            ],

            '/users' => [
                'component' => 'Users',
                'controller' => 'Users',
                'method' => 'read',
                'petition' => 'http'
            ],
            '/users/read_user' => [
                'component' => 'Users',
                'controller' => 'Users',
                'method' => 'read_user',
                'petition' => 'ajax'
            ],
            '/users/create_user' => [
                'component' => 'Users',
                'controller' => 'Users',
                'method' => 'create_user',
                'petition' => 'ajax'
            ],
            '/users/update_user' => [
                'component' => 'Users',
                'controller' => 'Users',
                'method' => 'update_user',
                'petition' => 'ajax'
            ],
            '/users/delete_user' => [
                'component' => 'Users',
                'controller' => 'Users',
                'method' => 'delete_user',
                'petition' => 'ajax'
            ],
            '/users/create_permission' => [
                'component' => 'Users',
                'controller' => 'Permissions',
                'method' => 'create_permission',
                'petition' => 'ajax'
            ],
            '/users/delete_permission' => [
                'component' => 'Users',
                'controller' => 'Permissions',
                'method' => 'delete_permission',
                'petition' => 'ajax'
            ],
        ];
    }
}
