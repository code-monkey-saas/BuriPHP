<?php

namespace Controllers;

use Libraries\BuriPHP\Controller;
use Libraries\BuriPHP\Helpers\HelperSession;
use Libraries\Responses;

class Translate extends Controller
{
    public function change()
    {
        HelperSession::setValue('set-language', $this->getParams()['lang']);

        return Responses::response(200);
    }
}
