<?php

namespace Core;

use Core\Helpers\HelperDate;
use Core\Helpers\HelperServer;

/**
 * Clase principal de la aplicación BuriPHP
 * Implementa el patrón Singleton para asegurar una única instancia de la aplicación
 * 
 * @package Core
 * @author Kiske
 * @since 2.0Alpha
 * @version 1.8
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 * @final
 */
final class Application
{
    /** @var self|null Instancia única de la clase */
    private static ?self $instance = null;

    /** @var string Versión mínima requerida de PHP */
    private const MIN_PHP_VERSION = '8.0';

    /**
     * Constructor privado para prevenir la instanciación directa
     */
    private function __construct() {}

    /**
     * Método privado para prevenir la clonación de la instancia
     */
    private function __clone() {}

    /**
     * Previene la deserialización de la instancia singleton
     * 
     * @throws \Exception Siempre lanza una excepción
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot deserialize a singleton instance.");
    }

    /**
     * Obtiene la instancia única de la aplicación
     * 
     * @return self Instancia de la aplicación
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Inicializa la aplicación
     * Configura el entorno, carga las dependencias y ejecuta el framework
     * 
     * @throws \Exception Si ocurre un error durante la inicialización
     * @return void
     */
    public function initialize(): void
    {
        try {
            if (version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<')) {
                throw new \RuntimeException(sprintf('Su servidor necesita usar PHP %s o superior para ejecutar esta versión de BuriPHP.', self::MIN_PHP_VERSION));
            }

            $this->loadDependencies();
            $this->setEnvironment();
            $this->setLocateTimeZone();

            $framework = Framework::getInstance();
            $framework->cors();

            if (class_exists('\Bootstrap\Routes')) {
                $routes = new \Bootstrap\Routes();
                $routes->registerModules();

                $framework->registerListModules($routes->modules);
            }

            $framework->run();
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Carga las dependencias necesarias para la aplicación
     * Incluye archivos de configuración y el autoloader de Composer
     * 
     * @return void
     */
    private function loadDependencies(): void
    {
        if (file_exists(PATH_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php')) {
            require_once PATH_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        }
    }

    /**
     * Configura el entorno de la aplicación
     * Establece el nivel de reporte de errores según la configuración definida en Settings
     * 
     * @return void
     */
    public function setEnvironment()
    {
        $environment = Environment::getInstance();

        // default, none, simple, maximum, development
        if ($environment->isProduction()) {
            HelperServer::errorReporting('none');
        } else if ($environment->isDevelopment()) {
            HelperServer::errorReporting('development');
        }
    }

    /**
     * Configura la zona horaria y el locale para la aplicación
     * Establece la zona horaria predeterminada y el locale para el manejo de fechas
     * según la configuración definida en Settings
     * 
     * @return void
     */
    public function setLocateTimeZone()
    {
        HelperDate::setLocateTimeZone();
    }
}
