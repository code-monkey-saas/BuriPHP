<?php

namespace Controllers;

use Libraries\BuriPHP\Controller;

class Pages extends Controller
{
    public $translateService;

    public function __init()
    {
        $this->translateService = $this->serviceShared('Translate', 'Translate');
    }

    public function home()
    {
        $this->view->setPageTitle('{{translate|home.hello}}');

        return $this->translateService->i18n($this->view->render(__DIR__ . '/layouts/index.php'), __DIR__);
    }
}
