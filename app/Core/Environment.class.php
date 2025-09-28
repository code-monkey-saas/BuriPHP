<?php

namespace Core;

/**
 * Clase Environment
 * 
 * Esta clase gestiona la configuración del entorno de la aplicación, cargando variables desde archivos .env.
 * Implementa el patrón Singleton para asegurar una única instancia global.
 * 
 * @package BuriPHP
 * @author Kiske
 * @since 3.0
 * @version 1.4
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
class Environment
{
    private static $instance = null;
    private $env = [];

    private function __construct()
    {
        $this->loadEnvironment();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadEnvironment()
    {
        // Cargar el archivo .env principal
        $mainEnvFile = PATH_ROOT . DS . '.env';
        if (file_exists($mainEnvFile)) {
            $this->parseEnvFile($mainEnvFile);
        }

        // Determinar el entorno actual
        $environment = $this->get('ENVIRONMENT', 'DEVELOPMENT');

        // Cargar el archivo de entorno específico
        $envFile = PATH_ROOT . DS . 'environments' . DS . strtolower($environment) . '.env';
        if (file_exists($envFile)) {
            $this->parseEnvFile($envFile);
        } else {
            throw new \RuntimeException("El archivo de entorno específico no existe: " . $envFile);
        }
    }

    private function parseEnvFile($file)
    {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) continue; // Ignorar comentarios

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Eliminar comillas si existen
            $value = trim($value, '"\'');

            $this->env[$name] = $value;
        }
    }

    public function get($key, $default = null)
    {
        return $this->env[$key] ?? $default;
    }

    public function isDevelopment()
    {
        return $this->get('ENVIRONMENT') === 'DEVELOPMENT';
    }

    public function isProduction()
    {
        return $this->get('ENVIRONMENT') === 'PRODUCTION';
    }
}
