<?php

namespace Controllers;

use Libraries\BuriPHP\Controller;

class Pages extends Controller
{
    public function home()
    {
        $this->view->setPageTitle('¡Hola mundo!');

        return $this->view->render(PATH_MODULES . 'Pages/layouts/index.php');
    }
}
