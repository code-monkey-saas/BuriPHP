<?php

namespace Libraries\Endpoints;

use Libraries\BuriPHP\Router;

class Endpoints extends Router
{
    public function endpoints()
    {
        // $this->addForModule('SetSettings');
        $this->addForModule('Authentication');
        $this->addForModule('Translate');
        $this->addForModule('User');
        $this->addForModule('Subscription');
        $this->addForModule('Pages');
    }
}
