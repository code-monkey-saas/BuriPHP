<?php

namespace Pages\Endpoints;

use Libraries\BuriPHP\Router;

class Endpoints extends Router
{
    public function endpoints()
    {
        $this->useController('Pages')
            ->get('/', 'home')
            ->assign();
    }
}
