<?php

namespace Translate\Endpoints;

use Libraries\BuriPHP\Router;

class Endpoints extends Router
{
    public function endpoints()
    {
        $this->useController('Translate')
            ->get('/translate/change/i18n/{lang}', 'change', ['ContentType' => 'json'])
            ->assign();
    }
}
