<?php

namespace Controllers;

use Libraries\BuriPHP\Controller;
use Libraries\BuriPHP\Helpers\HelperSession;
use Libraries\Functions;
use Libraries\Responses;

class Logout extends Controller
{
    public function logout()
    {
        $session = Functions::getSession();

        if ($session) {
            HelperSession::destroy();
        }

        return Responses::response(200);
    }
}
