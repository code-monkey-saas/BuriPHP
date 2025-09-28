<?php

/**
 * Definiciones de constantes globales para la configuración y estructura del proyecto.
 *
 * Este archivo contiene todas las constantes de extensiones de archivos y rutas absolutas
 * utilizadas en la plataforma, facilitando la gestión centralizada y el versionado.
 *
 * @package Application
 * @author Kiske
 * @since 0.0.1
 * @version 2.0
 * @license Ver LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

/**
 * Extensión para archivos de clase PHP.
 * @const string CLASS_PHP
 */
define('CLASS_PHP', '.class.php');

/**
 * Extensión para archivos de controlador PHP.
 * @const string CONTROLLER_PHP
 */
define('CONTROLLER_PHP', '.controller.php');

/**
 * Extensión para archivos de modelo PHP.
 * @const string MODEL_PHP
 */
define('MODEL_PHP', '.model.php');

/**
 * Extensión para archivos de servicio PHP.
 * @const string SERVICE_PHP
 */
define('SERVICE_PHP', '.service.php');

/**
 * Extensión para archivos de repositorio PHP.
 * @const string REPOSITORY_PHP
 */
define('REPOSITORY_PHP', '.repository.php');

/**
 * Extensión para archivos de interfaz PHP.
 * @const string INTERFACE_PHP
 */
define('INTERFACE_PHP', '.interface.php');

/**
 * Extensión para archivos de rutas PHP.
 * @const string ROUTER_PHP
 */
define('ROUTER_PHP', '.router.php');

/**
 * Ruta raíz absoluta del proyecto.
 * @const string PATH_ROOT
 */
define('PATH_ROOT', dirname(__DIR__));

/**
 * Separador de directorios del sistema operativo.
 * @const string DS
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Ruta absoluta al directorio principal de la aplicación.
 * @const string PATH_APP
 */
define('PATH_APP', PATH_ROOT . DS . 'app' . DS);

/**
 * Ruta absoluta al directorio 'Core' dentro de la aplicación.
 * @const string PATH_CORE
 */
define('PATH_CORE', PATH_APP . 'Core' . DS);

/**
 * Ruta absoluta al directorio de 'Helpers' dentro de 'Core'.
 * @const string PATH_CORE_HELPERS
 */
define('PATH_CORE_HELPERS', PATH_CORE . 'Helpers' . DS);

/**
 * Ruta absoluta al directorio de 'Interfaces' dentro de 'Core'.
 * @const string PATH_CORE_INTERFACES
 */
define('PATH_CORE_INTERFACES', PATH_CORE . 'Interfaces' . DS);

/**
 * Ruta absoluta al directorio de módulos de la aplicación.
 * @const string PATH_MODULES
 */
define('PATH_MODULES', PATH_APP . 'Modules' . DS);

/**
 * Ruta absoluta al directorio 'Shared' de la aplicación.
 * @const string PATH_SHARED
 */
define('PATH_SHARED', PATH_APP . 'Shared' . DS);

/**
 * Ruta absoluta al directorio 'bootstrap' del proyecto.
 * @const string PATH_BOOTSTRAP
 */
define('PATH_BOOTSTRAP', PATH_ROOT . DS . 'bootstrap' . DS);

/**
 * Ruta absoluta al directorio de configuración del proyecto.
 * @const string PATH_CONFIG
 */
define('PATH_CONFIG', PATH_ROOT . DS . 'config' . DS);

/**
 * Ruta absoluta al directorio de entornos del proyecto.
 * @const string PATH_ENVIRONMENTS
 */
define('PATH_ENVIRONMENTS', PATH_ROOT . DS . 'environments' . DS);

/**
 * Ruta absoluta al directorio público del proyecto.
 * @const string PATH_PUBLIC
 */
define('PATH_PUBLIC', PATH_ROOT . DS . 'public' . DS);

/**
 * Ruta absoluta al directorio de almacenamiento del proyecto.
 * @const string PATH_STORAGE
 */
define('PATH_STORAGE', PATH_ROOT . DS . 'storage' . DS);

/**
 * Ruta absoluta al directorio de logs dentro de almacenamiento.
 * @const string PATH_LOGS
 */
define('PATH_LOGS', PATH_STORAGE . DS . 'logs' . DS);

/**
 * Ruta absoluta al directorio de subidas dentro de almacenamiento.
 * @const string PATH_UPLOADS
 */
define('PATH_UPLOADS', PATH_STORAGE . DS . 'uploads' . DS);
