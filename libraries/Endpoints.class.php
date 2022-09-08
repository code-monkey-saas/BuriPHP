<?php

namespace Libraries\Endpoints;

use Libraries\BuriPHP\Router;

class Endpoints extends Router
{
    public function endpoints()
    {
        $this->addForModule('Pages');
    }
}
