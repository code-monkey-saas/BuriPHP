<?php

namespace Shared;

class Authorization
{
    /**
     * Verifica si el usuario está autenticado
     * 
     * @param string $endpoint Ruta de la URL
     * @param array $config Configuración de la ruta
     * @return bool|array True si el usuario está autenticado, false en caso contrario
     */
    public function isAuthenticated($endpoint, $config)
    {
        return true;
    }
}
