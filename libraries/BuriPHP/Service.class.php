<?php

/**
 * @package BuriPHP.Libraries
 *
 * @since 2.0Alpha
 * @version 1.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

namespace Libraries\BuriPHP;

use Libraries\BuriPHP\Helpers\HelperArray;
use Libraries\BuriPHP\Helpers\HelperFile;
use Libraries\BuriPHP\Interfaces\iService;

class Service implements iService
{
    public $repository;

    /**
     * Busca si existe el repository del controller.
     * Si existe, lo inicializa.
     */
    final public function __construct()
    {
        $service = explode('\\', get_called_class());
        $service = HelperArray::getLast($service);

        if (HelperFile::exists(PATH_MODULES . Router::getEndpoint()[1]['MODULE'] . DS . $service . REPOSITORY_PHP)) {
            $repository = '\Repositories\\' . $service;
            $this->repository = new $repository();
        }
    }
}
