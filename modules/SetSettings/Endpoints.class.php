<?php

namespace SetSettings\Endpoints;

use Libraries\BuriPHP\Router;

class Endpoints extends Router
{
    public function endpoints()
    {
        $this->useController('Init')
            ->get('/setsettings', 'init')
            ->assign();
    }
}
