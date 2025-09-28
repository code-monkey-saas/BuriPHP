<?php

namespace Core\Interfaces;

/**
 * Interface ServiceInterface
 *
 * Esta interfaz define los métodos que debe implementar un servicio en BuriPHP.
 * 
 * @package BuriPHP\Interfaces
 * @author Kiske
 * @since 2.0Alpha
 * @version 1.1
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
interface ServiceInterface
{
    /**
     * Constructor del servicio.
     * Este método se utiliza para inicializar el servicio.
     * 
     * Las implementaciones específicas del servicio deben definir su lógica de inicialización aquí.
     */
    public function __construct();
}
