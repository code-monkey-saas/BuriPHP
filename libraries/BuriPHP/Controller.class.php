<?php

/**
 * @package BuriPHP.Libraries
 *
 * @since 1.0
 * @version 2.3
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

namespace Libraries\BuriPHP;

use Libraries\BuriPHP\Helpers\HelperArray;
use Libraries\BuriPHP\Helpers\HelperFile;
use Libraries\BuriPHP\Helpers\HelperLog;
use Libraries\BuriPHP\Helpers\HelperValidate;
use Libraries\BuriPHP\Interfaces\iController;

class Controller implements iController
{
    public $service;
    public $view;

    /**
     * Busca si existe el service del controller.
     * Si existe, lo inicializa.
     */
    final public function __construct(...$args)
    {
        if (method_exists($this, '__init')) {
            call_user_func_array(array($this, '__init'), []);
        }

        $controller = explode('\\', get_called_class());
        $controller = HelperArray::getLast($controller);

        $module = (isset($args['module']) && !empty($args['module'])) ? $args['module'] : Router::getEndpoint()[1]['MODULE'];

        if (HelperFile::exists(PATH_MODULES . $module . DS . $controller . SERVICE_PHP)) {
            require_once PATH_MODULES . $module . DS . $controller . SERVICE_PHP;

            $service = '\Services\\' . $controller;

            if (isset($args['module']) && !empty($args['module'])) {
                $this->service = new $service(module: $args['module']);
            } else {
                $this->service = new $service();
            }
        }

        $this->view = new View();
    }

    /**
     * Obtiene los parametros enviados en la url.
     */
    final protected function getParams()
    {
        return Router::getEndpoint()[1]['PARAMS'];
    }

    /**
     * Obtiene la data e imagenes enviada por post.
     */
    final public function getPost()
    {
        $request = [];

        if (!empty($_POST)) {
            // when using application/x-www-form-urlencoded or multipart/form-data as the HTTP Content-Type in the request
            // NOTE: if this is the case and $_POST is empty, check the variables_order in php.ini! - it must contain the letter P
            $request = array_merge($request, $_POST);
        }

        // when using application/json as the HTTP Content-Type in the request 
        $post = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() == JSON_ERROR_NONE) {
            $request = array_merge($request, $post);
        }

        if (isset($_FILES) && !empty($_FILES)) {
            $request = array_merge($request, $_FILES);
        }

        return $request;
    }

    /**
     * Obtiene la data enviada por get.
     */
    final public function getGet()
    {
        $request = [];

        if (!empty($_GET)) {
            // when using application/x-www-form-urlencoded or multipart/form-data as the HTTP Content-Type in the request
            $request = array_merge($request, $_GET);
        }

        return $request;
    }

    /**
     * Obtiene la data enviada por put.
     */
    final public function getPut()
    {
        // Fetch content and determine boundary
        $raw_data = file_get_contents('php://input');
        $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

        if (empty($boundary)) {
            return json_decode($boundary, true);
        }

        // Fetch each part
        $parts = array_slice(explode($boundary, $raw_data), 1);
        $data = array();

        foreach ($parts as $part) {
            // If this is the last part, break
            if ($part == "--\r\n") break;

            // Separate content from headers
            $part = ltrim($part, "\r\n");
            list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

            // Parse the headers list
            $raw_headers = explode("\r\n", $raw_headers);
            $headers = array();
            foreach ($raw_headers as $header) {
                list($name, $value) = explode(':', $header);
                $headers[strtolower($name)] = ltrim($value, ' ');
            }

            // Parse the Content-Disposition to get the field name, etc.
            if (isset($headers['content-disposition'])) {
                $filename = null;
                preg_match(
                    '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/',
                    $headers['content-disposition'],
                    $matches
                );
                list(, $type, $name) = $matches;
                isset($matches[4]) and $filename = $matches[4];

                // handle your fields here
                switch ($name) {
                        // this is a file upload
                    case 'userfile':
                        file_put_contents($filename, $body);
                        break;

                        // default for all other files is to populate $data
                    default:
                        $data[$name] = substr($body, 0, strlen($body) - 2);
                        break;
                }
            }
        }

        return $data;
    }

    /**
     * Conecta un controlador de otro módulo.
     */
    final public function controllerShared($module, $controller)
    {
        try {
            /**
             * Verifica que exista el módulo.
             */
            if (!HelperValidate::isDir(PATH_MODULES . $module)) {
                $exceptionMsg = "No existe el module: " . PATH_MODULES . $module;

                HelperLog::saveError($exceptionMsg);
                throw new \Exception($exceptionMsg);
            }

            /**
             * Verifica que exista el controlador.
             */
            if (!HelperFile::exists(PATH_MODULES . $module . DS . $controller . CONTROLLER_PHP)) {
                $exceptionMsg = "No existe el controller: " . PATH_MODULES . $module . DS . $controller . CONTROLLER_PHP;

                HelperLog::saveError($exceptionMsg);
                throw new \Exception($exceptionMsg);
            } else {
                require PATH_MODULES . $module . DS . $controller . CONTROLLER_PHP;

                $controller = '\Controllers\\' . $controller;

                return new $controller(module: $module);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
