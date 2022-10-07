<?php

/**
 * @package BuriPHP.Libraries
 *
 * @since 2.0Alpha
 * @version 1.1
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

namespace Libraries\BuriPHP;

use BuriPHP\Settings;

class Repository
{
    public $database;

    /**
     * Verifica si en la configuracion esta activa la base de datos.
     * Si es asi, la inicializa.
     */
    final public function __construct()
    {
        if (method_exists($this, '__init')) {
            call_user_func_array(array($this, '__init'), []);
        }

        if (Settings::$useDatabase) {
            $this->database = (new Database())->newInstance();
        }
    }

    /**
     * Crea una nueva instancia de base de datos.
     * 
     * @param array $arguments
     * 
     * @return mixed
     */
    final public function newInstance($arguments)
    {
        return (new Database())->newInstance($arguments);
    }
}
