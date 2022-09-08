<?php namespace Libraries;

defined('_EXEC') or die;

use \BuriPHP\System\Libraries\Session;

class Functions
{
    public static function check_session()
    {
        if (isset($_COOKIE['__token']) && !empty($_COOKIE['__token'])) {
            if (Session::exists_var('__token')
                && Session::exists_var('__id_user')
                && Session::exists_var('__user')
                && Session::exists_var('__last_access')
                && Session::exists_var('__level')) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public static function put_button_hamburger_menu($id = null)
    {
        $id = (is_null($id)) ? 'trigger-nav-mobile' : $id;
        return "<button id='{$id}' class='button-hamburger-menu menu-wrapper'> <div class='hamburger-menu'></div> </button>";
    }
}
