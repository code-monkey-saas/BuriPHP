<?php

namespace Core;

/**
 * Clase que gestiona el registro de módulos en el sistema.
 * 
 * Esta clase proporciona la funcionalidad base para registrar y gestionar módulos
 * en la aplicación. Las clases hijas deben implementar el método registerModules()
 * para definir la lógica específica de registro.
 * 
 * @package Core
 * @author Kiske
 * @since 2.1
 * @version 0.2
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
class ModuleRegistry
{
    /**
     * Array que almacena los módulos registrados.
     * 
     * @var array
     */
    public array $modules = [];

    /**
     * Constructor de la clase ModuleRegistry.
     */
    public function __construct() {}

    /**
     * Añade un módulo al registro.
     * 
     * @param string $module Nombre o identificador del módulo a añadir
     * @return void
     */
    public function addModule(string $module)
    {
        $this->modules[] = $module;
    }

    /**
     * Método que debe ser implementado por las clases hijas para definir
     * la lógica específica de registro de módulos.
     * 
     * @return void
     */
    public function registerModules()
    {
        // Este método debe ser implementado por las clases hijas
    }
}
