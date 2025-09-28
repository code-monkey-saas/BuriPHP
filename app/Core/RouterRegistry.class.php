<?php

namespace Core;

/**
 * Clase base para el registro de rutas en la aplicación.
 * 
 * Esta clase proporciona una estructura base para registrar rutas en el sistema.
 * Las clases hijas deben implementar el método registerRoutes() para definir
 * las rutas específicas de su aplicación.
 * 
 * @package Core
 * @author Kiske
 * @since 2.1
 * @version 0.2
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
class RouterRegistry
{
    /**
     * Instancia del router que maneja las rutas.
     * 
     * @var Router
     */
    protected Router $router;

    /**
     * Constructor de la clase RouterRegistry.
     * 
     * @param Router $router Instancia del router que se utilizará para registrar las rutas
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Método para registrar las rutas de la aplicación.
     * 
     * Este método debe ser implementado por las clases hijas para definir
     * las rutas específicas de su aplicación.
     * 
     * @return void
     */
    public function registerRoutes()
    {
        // Este método debe ser implementado por las clases hijas
    }
}
