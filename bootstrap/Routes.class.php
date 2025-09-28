<?php

namespace Bootstrap;

use Core\ModuleRegistry;

class Routes extends ModuleRegistry
{
    /**
     * Registra los módulos de la aplicación
     * 
     * @return void
     */
    public function registerModules()
    {
        $this->addModule('Init');
    }
}
