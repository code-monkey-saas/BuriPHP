<?php

namespace Core;

/**
 * Clase Router
 * 
 * Gestiona el enrutamiento de peticiones HTTP en la aplicación.
 * Permite registrar rutas, módulos y manejar las peticiones entrantes.
 * 
 * @package Core
 * @author Kiske
 * @since 2.0Alpha
 * @version 1.7
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
class Router
{
    /** @var array Almacena las rutas registradas */
    private $routes = [];

    /** @var array Almacena las configuraciones de las rutas */
    private $routeConfigs = [];

    /** @var array Almacena los parámetros de la ruta actual */
    private $params = [];

    /** @var array Almacena los módulos registrados */
    private $modules = [];

    /** @var string Almacena el módulo actual */
    private $currentModule = '';

    /**
     * Establece el módulo actual
     * 
     * @param string $module Nombre del módulo
     * @return void
     */
    public function currentModule($module)
    {
        $this->currentModule = $module;
    }

    /**
     * Agrega una nueva ruta al router
     * 
     * @param string $httpMethod Método HTTP (GET, POST, etc.)
     * @param string $path Ruta de la URL
     * @param string $controller Nombre del controlador
     * @param string $method Método del controlador
     * @param array $config Configuraciones adicionales de la ruta
     * @return void
     */
    private function addRoute($httpMethod, $path, $controller, $method, $config = [])
    {
        // Agregar la ruta al array de rutas
        $this->routes["[" . $httpMethod . "]" . $path] = [
            'controller' => $controller,
            'method' => $method,
        ];

        // Configuraciones por defecto
        $defaultConfig = [
            'requireAuth' => false,  // Por defecto no requiere autenticación
            'responseFormat' => 'json' // Por defecto responde en JSON
        ];

        // Combinar configuraciones por defecto con las proporcionadas
        $this->routeConfigs["[" . $httpMethod . "]" . $path] = array_merge($defaultConfig, $config);
    }

    /**
     * Registra una ruta GET
     * 
     * @param string $path Ruta de la URL
     * @param string $controller Nombre del controlador
     * @param string $method Método del controlador
     * @param array $config Configuraciones adicionales
     * @return void
     */
    public function get($path, $controller, $method, $config = [])
    {
        // Agregar la ruta al array de rutas
        $this->addRoute('GET', $path, $this->currentModule . '\\' . $controller . 'Controller', $method, $config);
    }

    /**
     * Registra una ruta POST
     * 
     * @param string $path Ruta de la URL
     * @param string $controller Nombre del controlador
     * @param string $method Método del controlador
     * @param array $config Configuraciones adicionales
     * @return void
     */
    public function post($path, $controller, $method, $config = [])
    {
        // Agregar la ruta al array de rutas
        $this->addRoute('POST', $path, $this->currentModule . '\\' . $controller . 'Controller', $method, $config);
    }

    /**
     * Registra una ruta PUT
     * 
     * @param string $path Ruta de la URL
     * @param string $controller Nombre del controlador
     * @param string $method Método del controlador
     * @param array $config Configuraciones adicionales
     * @return void
     */
    public function put($path, $controller, $method, $config = [])
    {
        // Agregar la ruta al array de rutas
        $this->addRoute('PUT', $path, $this->currentModule . '\\' . $controller . 'Controller', $method, $config);
    }

    /**
     * Registra una ruta PATCH
     * 
     * @param string $path Ruta de la URL
     * @param string $controller Nombre del controlador
     * @param string $method Método del controlador
     * @param array $config Configuraciones adicionales
     * @return void
     */
    public function patch($path, $controller, $method, $config = [])
    {
        // Agregar la ruta al array de rutas
        $this->addRoute('PATCH', $path, $this->currentModule . '\\' . $controller . 'Controller', $method, $config);
    }

    /**
     * Registra una ruta DELETE
     * 
     * @param string $path Ruta de la URL
     * @param string $controller Nombre del controlador
     * @param string $method Método del controlador
     * @param array $config Configuraciones adicionales
     * @return void
     */
    public function delete($path, $controller, $method, $config = [])
    {
        // Agregar la ruta al array de rutas
        $this->addRoute('DELETE', $path, $this->currentModule . '\\' . $controller . 'Controller', $method, $config);
    }

    /**
     * Registra un nuevo módulo
     * 
     * @param string $module Nombre del módulo
     * @return void
     */
    public function registerModule($module)
    {
        // Agregar el módulo al array de módulos
        $this->modules[] = $module;
    }

    /**
     * Registra todas las rutas de los módulos
     * 
     * @return void
     */
    public function registerAllRoutes()
    {
        // Recorrer todos los módulos
        foreach ($this->modules as $module) {
            // Obtener el nombre de la clase del router
            $className = $module . "Router";
            // Obtener la ruta del archivo del router
            $routeFile = PATH_MODULES . $module . DS . $module . ROUTER_PHP;

            // Verificar si el archivo del router existe
            if (file_exists($routeFile)) {
                // Obtener el nombre de la clase del router
                $routeClassName = '\Modules\\' . $module . '\\' . $className;

                // Verificar si la clase del router existe
                if (class_exists($routeClassName)) {
                    // Asignar el módulo actual
                    $this->currentModule(str_replace('\\' . $className, '', $routeClassName));
                    // Crear una instancia del router
                    $routeRegistry = new $routeClassName($this);
                    // Registrar las rutas
                    $routeRegistry->registerRoutes();
                }
            }
        }
    }

    /**
     * Verifica si una ruta coincide con un patrón
     * 
     * @param string $pattern Patrón de la ruta
     * @param string $path Ruta a verificar
     * @return bool
     */
    private function matchRoute($pattern, $path)
    {
        // Resetear parámetros
        $this->params = [];

        // Guardar patrón original
        $patternOriginal = $pattern;

        // Convertir el patrón a expresión regular
        // Escapar los corchetes del método HTTP
        $pattern = preg_replace('/\[([A-Z]+)\]/', '\[$1\]', $pattern);
        // Convertir los parámetros variables a expresiones regulares
        $pattern = preg_replace('/:[a-zA-Z0-9]+/', '([^/]+)', $pattern);
        // Convertir el patrón a un patrón válido
        $pattern = '#^' . $pattern . '$#';

        // Verificar si la ruta coincide con el patrón
        if (preg_match($pattern, $path, $matches)) {
            // Extraer nombres de parámetros del patrón original
            preg_match_all('/:([a-zA-Z0-9]+)/', $patternOriginal, $paramNames);

            // Guardar valores de parámetros
            array_shift($matches); // Eliminar la coincidencia completa

            // Asignar valores a los parámetros
            foreach ($paramNames[1] as $index => $name) {
                // Verificar si el valor existe en la coincidencia
                if (isset($matches[$index])) {
                    // Asignar el valor al parámetro
                    $this->params[$name] = $matches[$index];
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Maneja la petición HTTP entrante
     * 
     * @param string $path Ruta de la URL
     * @param string $httpMethod Método HTTP
     * @return void
     */
    public function handleRequest($path, $httpMethod)
    {
        // Convertir la ruta a un patrón válido
        $path = "[" . $httpMethod . "]" . $path;

        // Primero verificar rutas exactas
        if (isset($this->routes[$path])) {
            // Procesar la ruta
            $this->processRoute($path, $this->routes[$path]['controller'], $this->routes[$path]['method']);
            return;
        }

        // Luego verificar rutas con parámetros
        foreach ($this->routes as $pattern => $value) {
            // Verificar si el patrón contiene parámetros variables
            if (strpos($pattern, ':') !== false) {
                // Verificar si la ruta coincide con el patrón
                if ($this->matchRoute($pattern, $path)) {
                    // Procesar la ruta
                    $this->processRoute($pattern, $value['controller'], $value['method']);
                    return;
                }
            }
        }

        // Si no se encontró ninguna ruta
        Response::sendResponse(statusCode: 404);
    }

    /**
     * Procesa una ruta y ejecuta el controlador correspondiente
     * 
     * @param string $pattern Patrón de la ruta
     * @param string $controller Nombre del controlador
     * @param string $method Método del controlador
     * @return void
     */
    private function processRoute($pattern, $controller, $method)
    {
        try {
            // Obtener configuración de la ruta
            $config = isset($this->routeConfigs[$pattern]) ? $this->routeConfigs[$pattern] : [];

            // Verificar si requiere autenticación
            if (isset($config['requireAuth']) && $config['requireAuth']) {
                if (class_exists('\Shared\Authorization')) {
                    $authorization = new \Shared\Authorization();

                    if (method_exists($authorization, 'isAuthenticated')) {
                        $authorization->isAuthenticated(preg_replace('/\[.*?\]/', '', $pattern), $config);
                    } else {
                        throw new \RuntimeException('isAuthenticated method not found in Authorization class');
                    }
                } else {
                    throw new \RuntimeException('Authorization class not found');
                }
            }

            // Verificar si el controlador existe
            if (!class_exists($controller)) {
                // Obtener el nombre del controlador
                $controllerName = explode('\\', $controller);
                $controllerName = end($controllerName);
                throw new \RuntimeException(sprintf('Controller \'%s\' not found', $controllerName));
            }

            // Crear una instancia del controlador
            $controllerClass = new $controller();

            // Asignar parámetros de la ruta al controlador
            if (method_exists($controller, 'setRouteParams')) {
                $controllerClass->setRouteParams($this->params);
            }

            // Verificar si el método existe en el controlador
            if (!method_exists($controllerClass, $method)) {
                // Obtener el nombre del controlador
                $controllerName = explode('\\', $controller);
                $controllerName = end($controllerName);
                throw new \RuntimeException(sprintf('Method \'%s\' in controller \'%s\' not found', $method, $controllerName));
            }

            // Ejecutar el método del controlador
            $controllerClass->$method();
        } catch (\Throwable $e) {
            Response::sendResponse(statusCode: 500, message: $e->getMessage());
        }
    }
}
