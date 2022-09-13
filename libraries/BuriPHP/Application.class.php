<?php

/**
 * @package BuriPHP.Libraries
 *
 * @since 2.0Alpha
 * @version 1.4
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

namespace Libraries\BuriPHP;

use BuriPHP\Settings;
use Libraries\BuriPHP\Helpers\HelperArray;
use Libraries\BuriPHP\Helpers\HelperConvert;
use Libraries\BuriPHP\Helpers\HelperDateTime;
use Libraries\BuriPHP\Helpers\HelperFile;
use Libraries\BuriPHP\Helpers\HelperHeader;
use Libraries\BuriPHP\Helpers\HelperLog;
use Libraries\BuriPHP\Helpers\HelperServer;
use Libraries\BuriPHP\Helpers\HelperValidate;
use Libraries\Endpoints\Endpoints;

/**
 * @access public
 * 
 * @final
 */
final class Application
{
    public function __construct()
    {
        HelperServer::errorReporting(Settings::$errorReporting);
        HelperDateTime::setLocateTimeZone();
    }

    /**
     * Ejecuta la aplicación.
     */
    public function exec()
    {
        /**
         * Manda a ejecutar todos los endpoints.
         */
        $this->triggerEndpoints();

        /**
         * Traza el endpoint actual.
         */
        $this->traceEndpoint();
        $trace = Router::explodeEndpoint($GLOBALS['_APP']['ENDPOINT']);

        /**
         * Verifica si el endpoint esta vacío.
         * Si esta vacío, lanza un error 404.
         */
        if (
            HelperValidate::isEmpty($GLOBALS['_APP']['ENDPOINT']) &&
            HelperValidate::isEmpty($GLOBALS['_APP']['ALLOWED_METHODS'])
        ) {
            HelperHeader::setStatusCode(404);

            return false;
        }

        /**
         * Verifica si esta permitido el REQUEST_METHOD.
         */
        if (
            !HelperArray::existsValue(
                $GLOBALS['_APP']['ALLOWED_METHODS'],
                HelperServer::getValue('REQUEST_METHOD')
            )
        ) {
            HelperHeader::setContentType('json');
            HelperHeader::setStatusCode(405);

            echo json_encode([
                'status' => 405,
                'message' => 'Method Not Allowed'
            ], JSON_PRETTY_PRINT);

            return false;
        }

        try {
            /**
             * Verifica el nombre del módulo en el endpoint.
             */
            if (HelperValidate::isEmpty($trace['MODULE'])) {
                $exceptionMsg = "No se estableció el module para el endpoint: " . implode(':', $trace);

                HelperHeader::setStatusCode(500);
                HelperLog::saveError($exceptionMsg);
                throw new \Exception($exceptionMsg);
            }

            /**
             * Verifica el nombre del controlador en el endpoint.
             */
            if (HelperValidate::isEmpty($trace['CONTROLLER'])) {
                $exceptionMsg = "No se estableció el controller para el endpoint: " . implode(':', $trace);

                HelperHeader::setStatusCode(500);
                HelperLog::saveError($exceptionMsg);
                throw new \Exception($exceptionMsg);
            }

            /**
             * Verifica el nombre del método en el endpoint.
             */
            if (HelperValidate::isEmpty($trace['METHOD'])) {
                $exceptionMsg = "No se estableció el method para el endpoint: " . implode(':', $trace);

                HelperHeader::setStatusCode(500);
                HelperLog::saveError($exceptionMsg);
                throw new \Exception($exceptionMsg);
            }

            /**
             * Verifica que exista el módulo.
             */
            if (!HelperValidate::isDir(PATH_MODULES . $trace['MODULE'])) {
                $exceptionMsg = "No existe el module: " . PATH_MODULES . $trace['MODULE'];

                HelperHeader::setStatusCode(500);
                HelperLog::saveError($exceptionMsg);
                throw new \Exception($exceptionMsg);
            }

            /**
             * Verifica que exista el controlador.
             */
            if (!HelperFile::exists(PATH_MODULES . $trace['MODULE'] . DS . $trace['CONTROLLER'] . CONTROLLER_PHP)) {
                $exceptionMsg = "No existe el controller: " . PATH_MODULES . $trace['MODULE'] . DS . $trace['CONTROLLER'] . CONTROLLER_PHP;

                HelperHeader::setStatusCode(500);
                HelperLog::saveError($exceptionMsg);
                throw new \Exception($exceptionMsg);
            } else {
                require_once PATH_MODULES . $trace['MODULE'] . DS . $trace['CONTROLLER'] . CONTROLLER_PHP;
            }

            /**
             * Verifica que exista la clase del controlador.
             * Si existe, la inicializa.
             */
            $namespace = '\Controllers\\' . $trace['CONTROLLER'];

            if (!class_exists($namespace)) {
                $exceptionMsg = "No existe la class: " . $namespace . " en: " . PATH_MODULES . $trace['MODULE'] . DS . $trace['CONTROLLER'] . CONTROLLER_PHP;

                HelperHeader::setStatusCode(500);
                HelperLog::saveError($exceptionMsg);
                throw new \Exception($exceptionMsg);
            } else {
                HelperHeader::setContentType($trace['CONTENT_TYPE']);

                if (class_exists('\\Libraries\Build\Build')) {
                    $build = new \Libraries\Build\Build($trace);

                    if (method_exists($build, 'startup')) {
                        $buildStartup = $build->startup();

                        if (false == $buildStartup) {
                            return false;
                        }
                    }
                }

                $controller = new $namespace();
            }

            /**
             * Verifica que exista el método.
             * Si existe, ejecuta el método.
             */
            if (!method_exists($controller, $trace['METHOD'])) {
                $exceptionMsg = "No existe el method: " . $trace['METHOD'] . "() en: " . PATH_MODULES . $trace['MODULE'] . DS . $trace['CONTROLLER'] . CONTROLLER_PHP;

                HelperHeader::setStatusCode(500);
                HelperLog::saveError($exceptionMsg);
                throw new \Exception($exceptionMsg);
            } else {
                $app = $controller->{$trace['METHOD']}();

                if (class_exists('\\Libraries\Build\Build') && method_exists($build, 'wakeup')) {
                    $build->wakeup();
                }

                if (is_array($app)) {
                    echo json_encode($app, JSON_PRETTY_PRINT);
                } else if (is_string($app)) {
                    echo $app;
                }
            }
        } catch (\Throwable $th) {
            throw $th;

            return false;
        }
    }

    /**
     * Ejecuta los endpoints
     */
    private function triggerEndpoints(): void
    {
        (new Endpoints())->endpoints();
    }

    /**
     * Busca el endpoint actual.
     */
    private function traceEndpoint()
    {
        $endpoint = (string) "";
        $allowedMethods = HelperConvert::toArray("");

        if (HelperServer::getCurrentPathInfo()[0] == '/') {
            foreach ($GLOBALS['_APP']['ENDPOINTS'] as $key => $value) {
                $arrEndpoint = explode(':', $value);

                if ($arrEndpoint[1] == '/') {
                    $allowedMethods = HelperArray::append($allowedMethods, $arrEndpoint[0]);

                    if (HelperServer::getValue('REQUEST_METHOD') === $arrEndpoint[0]) {
                        $endpoint = $GLOBALS['_APP']['ENDPOINTS'][$key];
                    }
                }
            }
        } else {
            foreach ($GLOBALS['_APP']['ENDPOINTS'] as $key => $value) {
                $currentUri = HelperServer::getCurrentPathInfo();
                $arrEndpoint = explode(':', $value);
                $arrEndpoint[1] = HelperArray::compact(explode('/', $arrEndpoint[1]));

                if (count($currentUri) == count($arrEndpoint[1])) {
                    foreach (json_decode($arrEndpoint[5], true) as $_value) {
                        $currentUri[$_value[0]] = "{" . $_value[1] . "}";
                    }

                    if (HelperValidate::isEmpty(HelperArray::dif($currentUri, $arrEndpoint[1]))) {
                        $allowedMethods = HelperArray::append($allowedMethods, $arrEndpoint[0]);

                        if (HelperServer::getValue('REQUEST_METHOD') === $arrEndpoint[0]) {
                            $endpoint = $GLOBALS['_APP']['ENDPOINTS'][$key];
                        }
                    }
                }
            }
        }

        $GLOBALS['_APP']['ENDPOINT'] = $endpoint;
        $GLOBALS['_APP']['ALLOWED_METHODS'] = $allowedMethods;
    }
}
