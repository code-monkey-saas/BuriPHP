<?php

namespace Core;

/**
 * Autoloader para cargar automáticamente las clases PHP.
 *
 * Este autoloader se encarga de cargar todas las clases
 * desde los directorios especificados en el sistema.
 *
 * @package BuriPHP
 * @author Kiske
 * @since 2.1
 * @version 2.0
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
final class Autoloader
{
    /**
     * Instancia única del Autoloader.
     * 
     * Esta propiedad está diseñada para implementar el patrón Singleton, asegurando que
     * solo haya una instancia del Autoloader en toda la aplicación. Se inicializa como
     * null y se crea una nueva instancia cuando se solicita por primera vez.
     * 
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * Constructor privado de la clase Autoloader.
     * 
     * Este constructor está definido como privado para evitar la creación de instancias
     * de la clase Autoloader desde fuera de la clase. Esto es parte del patrón Singleton,
     * que asegura que solo se pueda acceder a la instancia a través del método getInstance().
     */
    private function __construct() {}

    /**
     * Método privado para evitar la clonación de la instancia.
     * 
     * Este método está definido como privado para prevenir que se creen copias de la
     * instancia del Autoloader. Esto es parte del patrón Singleton, que asegura que
     * solo haya una única instancia de la clase.
     */
    private function __clone() {}

    /**
     * Método privado para evitar la deserialización de la instancia.
     * 
     * Este método está definido como privado para prevenir que se deserialice la
     * instancia del Autoloader, lo que podría crear una nueva instancia y romper el
     * patrón Singleton.
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot deserialize a singleton instance.");
    }

    /**
     * Obtiene la instancia única del Autoloader
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Registra el autoloader
     *
     * @return void
     */
    public static function register(): void
    {
        spl_autoload_register([self::getInstance(), 'loadClass']);
    }

    /**
     * Carga la clase solicitada
     *
     * @param string $className Nombre completo de la clase (incluyendo namespace)
     * @return bool
     */
    public function loadClass(string $className): bool
    {
        // Normaliza el nombre de la clase y elimina el namespace raíz
        $className = explode('\\', ltrim($className, '\\'));
        $file = '';

        if ($className[0] === 'Core') {
            $relativeClass = implode('/', array_slice($className, 1));
            $file = PATH_CORE . str_replace(["\\", "/"], DS, $relativeClass) . CLASS_PHP;
        }

        if ($className[0] === 'Modules') {
            $lastIdx = count($className) - 1;

            if ($lastIdx > 0 && preg_match('/Router$/', $className[$lastIdx])) {
                $className[$lastIdx] = preg_replace('/Router$/', '', $className[$lastIdx]);
                $relativeClass = implode('/', array_slice($className, 1));
                $file = PATH_MODULES . str_replace(["\\", "/"], DS, $relativeClass) . ROUTER_PHP;
            }

            if ($lastIdx > 0 && preg_match('/Interface$/', $className[$lastIdx])) {
                $className[$lastIdx] = preg_replace('/Interface$/', '', $className[$lastIdx]);
                $relativeClass = implode('/', array_slice($className, 1)) . '/' . $className[$lastIdx];
                $file = PATH_MODULES . str_replace(["\\", "/"], DS, $relativeClass) . INTERFACE_PHP;
            }

            if ($lastIdx > 0 && preg_match('/Controller$/', $className[$lastIdx])) {
                $className[$lastIdx] = preg_replace('/Controller$/', '', $className[$lastIdx]);
                $relativeClass = implode('/', array_slice($className, 1)) . '/' . $className[$lastIdx];
                $file = PATH_MODULES . str_replace(["\\", "/"], DS, $relativeClass) . CONTROLLER_PHP;
            }

            if ($lastIdx > 0 && preg_match('/Service$/', $className[$lastIdx])) {
                $className[$lastIdx] = preg_replace('/Service$/', '', $className[$lastIdx]);
                $relativeClass = implode('/', array_slice($className, 1)) . '/' . $className[$lastIdx];
                $file = PATH_MODULES . str_replace(["\\", "/"], DS, $relativeClass) . SERVICE_PHP;
            }

            if ($lastIdx > 0 && preg_match('/Repository$/', $className[$lastIdx])) {
                $className[$lastIdx] = preg_replace('/Repository$/', '', $className[$lastIdx]);
                $relativeClass = implode('/', array_slice($className, 1)) . '/' . $className[$lastIdx];
                $file = PATH_MODULES . str_replace(["\\", "/"], DS, $relativeClass) . REPOSITORY_PHP;
            }

            if ($lastIdx > 0 && preg_match('/Model$/', $className[$lastIdx])) {
                $className[$lastIdx] = preg_replace('/Model$/', '', $className[$lastIdx]);
                $relativeClass = implode('/', array_slice($className, 1)) . '/' . $className[$lastIdx];
                $file = PATH_MODULES . str_replace(["\\", "/"], DS, $relativeClass) . MODEL_PHP;
            }
        }

        if ($className[0] === 'Shared') {
            $relativeClass = implode('/', array_slice($className, 1));
            $file = PATH_SHARED . str_replace(["\\", "/"], DS, $relativeClass) . CLASS_PHP;
        }

        if ($className[0] === 'Bootstrap') {
            $relativeClass = implode('/', array_slice($className, 1));
            $file = PATH_BOOTSTRAP . str_replace(["\\", "/"], DS, $relativeClass) . CLASS_PHP;
        }

        if ($className[0] === 'Config') {
            $relativeClass = implode('/', array_slice($className, 1));
            $file = PATH_CONFIG . str_replace(["\\", "/"], DS, $relativeClass) . '.php';
        }

        // Si el archivo existe, lo carga
        if ($file && file_exists($file)) {
            require_once $file;
            return true;
        }
        return false;
    }
}
