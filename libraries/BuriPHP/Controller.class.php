<?php

/**
 * @package BuriPHP.Libraries
 *
 * @since 1.0
 * @version 2.2
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

namespace Libraries\BuriPHP;

use Libraries\BuriPHP\Helpers\HelperArray;
use Libraries\BuriPHP\Helpers\HelperFile;
use Libraries\BuriPHP\Interfaces\iController;

class Controller implements iController
{
    public $service;
    public $view;

    /**
     * Busca si existe el service del controller.
     * Si existe, lo inicializa.
     */
    final public function __construct()
    {
        $controller = explode('\\', get_called_class());
        $controller = HelperArray::getLast($controller);

        if (HelperFile::exists(PATH_MODULES . Router::getEndpoint()[1]['MODULE'] . DS . $controller . SERVICE_PHP)) {
            require_once PATH_MODULES . Router::getEndpoint()[1]['MODULE'] . DS . $controller . SERVICE_PHP;

            $service = '\Services\\' . $controller;
            $this->service = new $service();
        }

        $this->view = new View();
    }

    /**
     * Obtiene los parametros enviados en la url.
     */
    final protected function getParams()
    {
        return Router::getEndpoint()[1]['PARAMS'];
    }
}
