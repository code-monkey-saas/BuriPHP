<?php

namespace Controllers;

use Libraries\BuriPHP\Controller;
use Libraries\Responses;

class Login extends Controller
{
    public function login()
    {
        $request = $this->getPost();

        if (isset($this->getGet()['redirect']) && !empty($this->getGet()['redirect'])) {
            $request['redirect'] = $this->getGet()['redirect'];
        }

        $response = $this->service->login($request);

        return Responses::response($response[0], $response[1]);
    }
}
