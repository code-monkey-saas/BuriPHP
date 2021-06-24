<?php namespace BuriPHP\Administrator\System\Libraries;

/**
 *
 * @package BuriPHP.Administrator.Libraries
 *
 * @since 1.0.0
 * @version 1.1.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

defined('_EXEC') or die;

use \BuriPHP\System\Libraries\{Security,Database,Session};
use \BuriPHP\Administrator\Libraries\{Set_users_permissions};

class System
{
    /**
    *
    * @var object
    */
    private $security;

    /**
    * Constructor.
    *
    * @return  void
    */
    public function __construct()
    {
        $this->security = new Security();
        $this->database = new Database();
    }

    /**
    * Verifica el status de la sesion.
    *
    * @return  boolean
    */
    public function exists_session()
    {
        if ( isset($_COOKIE['__token']) && !empty($_COOKIE['__token']) )
        {
            if ( Session::exists_var('__token') && Session::exists_var('__id_user') && Session::exists_var('__user') && Session::exists_var('__last_access') && Session::exists_var('__level') && Session::exists_var('session_permissions') )
            {
                return true;
            }
            else
            {
                $token = explode(':', $_COOKIE['__token']);

                $response = $this->database->select("sessions", [
                    "[>]users" => [
                        "id_user" => "id"
                    ],
                    "[>]levels" => [
                        "users.id_level" => "id"
                    ]
                ], [
                    "sessions.logout_date",
                    "users.id",
                    "users.username",
                    "users.permissions [Object]",
                    "levels.code"
                ], [
                    "AND" => [
                        'id_user' => $token[0],
                        'token' => $token[2],
                        "sessions.id_user[=]users.id",
                        "users.id_level[=]levels.id"
                    ]
                ]);

                if ( !isset($response[0]) || is_null($response[0]) )
                {
                    setcookie("__token", null, -1, '/');
                    return false;
                }

                else if ( !is_null($response[0]['logout_date']) )
                {
                    setcookie("__token", null, -1, '/');
                    return false;
                }

                else
                {
                    setcookie('__token', $response[0]['id'] .':'. $this->security->random_string(4) .':'. $token[2], time() + (Configuration::$cookie_lifetime * 30), "/");

                    Session::create_session_login([
                        'token' => $token,
                        'id_user' => $response[0]['id'],
                        'user' => $response[0]['username'],
                        'last_access' => Format::get_date_hour(),
                        'level' => $response[0]['code']
                    ]);

                    Session::set_value('session_permissions', $response[0]['permissions']);

                    return true;
                }
            }
        }
        else // No hay session
        {
            return false;
        }
    }
}
