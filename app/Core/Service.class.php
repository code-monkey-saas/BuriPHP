<?php

namespace Core;

use Core\Interfaces\ServiceInterface;

/**
 * Clase Service
 * 
 * Esta clase proporciona servicios esenciales para la aplicación.
 * 
 * @package BuriPHP
 * @author Kiske
 * @since 2.0Alpha
 * @version 1.8
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
class Service implements ServiceInterface
{
    /**
     * Constructor de la clase Service.
     * 
     * Inicializa una nueva instancia de la clase Service.
     * Los servicios específicos deben ser definidos en las clases que hereden de esta.
     */
    public function __construct() {}
}
