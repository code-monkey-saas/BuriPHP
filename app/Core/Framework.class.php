<?php

namespace Core;

/**
 * Framework principal de BuriPHP
 * 
 * Esta clase implementa el patrón Singleton y maneja la inicialización
 * y ejecución del framework, incluyendo el enrutamiento y la gestión de CORS.
 * 
 * @package Core
 * @author Kiske
 * @since 2.1
 * @version 0.2
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 * @final
 */
final class Framework
{
    /** @var self|null Instancia única de la clase Framework */
    private static ?self $instance = null;

    /** @var Router Instancia del enrutador */
    private Router $router;

    /** @var array Métodos HTTP permitidos */
    private array $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];

    /**
     * Constructor privado para implementar el patrón Singleton
     */
    private function __construct()
    {
        // Inicializar el enrutador
        $this->router = new Router();
    }

    /**
     * Previene la clonación del objeto (Singleton)
     */
    private function __clone() {}

    /**
     * Previene la deserialización del objeto (Singleton)
     * 
     * @throws \Exception Siempre lanza una excepción
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot deserialize a singleton instance.");
    }

    /**
     * Obtiene la instancia única de Framework
     * 
     * @return self Instancia de Framework
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Configura los encabezados CORS para la aplicación
     * 
     * @param array $origins Lista de orígenes permitidos
     * @param array|null $methods Lista de métodos HTTP permitidos
     * @return self
     */
    public function cors($origins = ['*'], $methods = null)
    {
        // Origen permitido
        if (isset($_SERVER['HTTP_ORIGIN']) && (in_array($_SERVER['HTTP_ORIGIN'], $origins) || in_array('*', $origins))) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        } else if (!isset($_SERVER['HTTP_ORIGIN']) && in_array('*', $origins)) {
            header('Access-Control-Allow-Origin: *');
        }

        // Métodos permitidos
        header('Access-Control-Allow-Methods: ' . implode(', ', is_null($methods) ? $this->allowedMethods : $methods));

        // Otros encabezados
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        // header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Origin, Accept");
        header('Access-Control-Allow-Credentials: false');
        header('Access-Control-Max-Age: 86400'); // 24 horas

        // Si es una petición OPTIONS, terminar aquí
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit(0);
        }

        return $this;
    }

    /**
     * Registra un módulo en el enrutador
     * 
     * @param mixed $module Módulo a registrar
     * @return self
     */
    public function registerModule($module)
    {
        $this->router->registerModule($module);
        return $this;
    }

    public function registerListModules(array $modules)
    {
        foreach ($modules as $module) {
            $this->router->registerModule($module);
        }
    }

    /**
     * Verifica si un método HTTP está permitido
     * 
     * @param string $method Método HTTP a verificar
     * @return bool
     */
    private function isMethodAllowed($method)
    {
        return in_array(strtoupper($method), $this->allowedMethods);
    }

    /**
     * Obtiene la ruta actual de la petición
     * 
     * @return string
     */
    private function getPath()
    {
        return isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
    }

    /**
     * Obtiene el método HTTP de la petición
     * 
     * @return string
     */
    private function getHttpMethod()
    {
        // Para compatibilidad con clientes que no pueden enviar PUT/DELETE directamente
        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        }

        return isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
    }

    /**
     * Ejecuta el framework y maneja la petición actual
     * 
     * @return void
     */
    public function run()
    {
        try {
            // Obtener ruta y método
            $path = $this->getPath();
            $httpMethod = $this->getHttpMethod();

            // if ($method == 'OPTIONS') {
            //     Response::sendResponse(statusCode: 200, message: 'OK');
            //     return;
            // }

            // Verificar si el método está permitido
            if (!$this->isMethodAllowed($httpMethod)) {
                Response::sendResponse(statusCode: 405, message: 'Method not allowed');
                return;
            }

            // Registrar todas las rutas
            $this->router->registerAllRoutes();

            // Manejar la petición
            $this->router->handleRequest($path, $httpMethod);
        } catch (\Exception $e) {
            // Manejar errores
            Response::sendResponse(statusCode: 500, message: 'Internal server error: ' . $e->getMessage());
        }
    }
}
