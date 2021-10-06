<?php namespace BuriPHP\Administrator\Components\Users;

defined('_EXEC') or die;

class Component
{
    const NAME = 'Users';
    const PATH = PATH_ADMINISTRATOR_COMPONENTS . self::NAME . DIRECTORY_SEPARATOR;
    const LAYOUTS = self::PATH .'layouts'. DIRECTORY_SEPARATOR;

    public static function urls()
    {
        // Linea para importar las urls del componente al desarollo
        // $urls = array_merge($urls, System::import_urls_component('Users'));
        
        return [
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
