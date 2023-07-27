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
use Libraries\BuriPHP\Helpers\HelperString;
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

    /**
     * Conecta un servicio de otro módulo.
     */
    final public function serviceShared($module, $service)
    {
        $serviceShared = new Service();
        return $serviceShared->serviceShared($module, $service);
    }

    /**
     * Obtiene los parametros enviados en la url.
     */
    final protected function getParams()
    {
        return Router::getEndpoint()[1]['PARAMS'];
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

                if (!is_null($value)) {
                    $headers[strtolower($name)] = ltrim($value, ' ');
                }
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
     * Obtiene la data e imagenes enviada por patch.
     */
    final public function getPatch()
    {
        $request = [];

        // when using application/json as the HTTP Content-Type in the request 
        $patch = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() == JSON_ERROR_NONE) {
            $request = array_merge($request, $patch);
        }

        if (isset($_FILES) && !empty($_FILES)) {
            $request = array_merge($request, $_FILES);
        }

        return $request;
    }

    /**
     * Obtiene la data e imagenes enviada general.
     */
    final public function getPayload()
    {
        $result = self::processPayload();

        return array_merge($result['variables'], $result['files']);
    }

    private static function processPayload()
    {
        $content_parts = explode(';', $_SERVER['CONTENT_TYPE'] ?? 'application/x-www-form-urlencoded');

        $boundary = '';
        $encoding = '';

        $content_type = array_shift($content_parts);

        foreach ($content_parts as $part) {
            if (strpos($part, 'boundary') !== false) {
                $part = explode('=', $part, 2);
                if (!empty($part[1])) {
                    $boundary = '--' . $part[1];
                }
            } elseif (strpos($part, 'charset') !== false) {
                $part = explode('=', $part, 2);
                if (!empty($part[1])) {
                    $encoding = $part[1];
                }
            }
            if ($boundary !== '' && $encoding !== '') {
                break;
            }
        }

        if ($content_type == 'multipart/form-data') {
            return self::fetchFromMultipart($boundary);
        }

        // can be handled by built in PHP functionality
        $content = file_get_contents('php://input');

        $variables = json_decode($content);

        if (empty($variables)) {
            parse_str($content, $variables);
        }

        return ['variables' => $variables, 'files' => []];
    }

    private static function fetchFromMultipart(string $boundary)
    {
        $result = ['variables' => [], 'files' => []];

        $stream = fopen('php://input', 'rb');

        $sanity = fgets($stream, strlen($boundary) + 5);

        // malformed file, boundary should be first item
        if (rtrim($sanity) !== $boundary) {
            return $result;
        }

        $raw_headers = '';

        while (($chunk = fgets($stream)) !== false) {
            if ($chunk === $boundary) {
                continue;
            }

            if (!empty(trim($chunk))) {
                $raw_headers .= $chunk;
                continue;
            }

            $result = self::parseRawHeader($stream, $raw_headers, $boundary, $result);
            $raw_headers = '';
        }

        fclose($stream);

        return $result;
    }

    private static function parseRawHeader($stream, string $raw_headers, string $boundary, array $result)
    {
        $variables = $result['variables'];
        $files     = $result['files'];

        $headers = [];

        foreach (explode("\r\n", $raw_headers) as $header) {
            if (strpos($header, ':') === false) {
                continue;
            }
            list($name, $value) = explode(':', $header, 2);
            $headers[strtolower($name)] = ltrim($value, ' ');
        }

        if (!isset($headers['content-disposition'])) {
            return ['variables' => $variables, 'files' => $files];
        }

        if (!preg_match('/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/', $headers['content-disposition'], $matches)) {
            return ['variables' => $variables, 'files' => $files];
        }

        $name     = $matches[2];
        $filename = $matches[4] ?? '';

        if (!empty($filename)) {
            $files[$name] = self::fetchFileData($stream, $boundary, $headers, $filename);
            return ['variables' => $variables, 'files' => $files];
        } else {
            $variables = self::fetchVariables($stream, $boundary, $name, $variables);
        }

        return ['variables' => $variables, 'files' => $files];
    }

    private static function fetchFileData($stream, string $boundary, array $headers, string $filename)
    {
        $error = UPLOAD_ERR_OK;

        if (isset($headers['content-type'])) {
            $tmp = explode(';', $headers['content-type']);
            $contentType = $tmp[0];
        } else {
            $contentType = 'unknown';
        }

        $tmpnam     = tempnam(ini_get('upload_tmp_dir'), 'php');
        $fileHandle = fopen($tmpnam, 'wb');

        if ($fileHandle === false) {
            $error = UPLOAD_ERR_CANT_WRITE;
        } else {
            $lastLine = NULL;
            while (($chunk = fgets($stream, 8096)) !== false && strpos($chunk, $boundary) !== 0) {
                if ($lastLine !== NULL) {
                    if (fwrite($fileHandle, $lastLine) === false) {
                        $error = UPLOAD_ERR_CANT_WRITE;
                        break;
                    }
                }
                $lastLine = $chunk;
            }

            if ($lastLine !== NULL && $error !== UPLOAD_ERR_CANT_WRITE) {
                if (fwrite($fileHandle, rtrim($lastLine, "\r\n")) === false) {
                    $error = UPLOAD_ERR_CANT_WRITE;
                }
            }
        }

        return [
            'name'     => $filename,
            'type'     => $contentType,
            'tmp_name' => $tmpnam,
            'error'    => $error,
            'size'     => filesize($tmpnam)
        ];
    }

    private static function fetchVariables($stream, string $boundary, string $name, array $variables)
    {
        $fullValue = '';
        $lastLine = NULL;

        while (($chunk = fgets($stream)) !== false && strpos($chunk, $boundary) !== 0) {
            if ($lastLine !== NULL) {
                $fullValue .= $lastLine;
            }

            $lastLine = $chunk;
        }

        if ($lastLine !== NULL) {
            $fullValue .= rtrim($lastLine, "\r\n");
        }

        if (isset($headers['content-type'])) {
            $encoding = '';

            foreach (explode(';', $headers['content-type']) as $part) {
                if (strpos($part, 'charset') !== false) {
                    $part = explode($part, '=', 2);
                    if (isset($part[1])) {
                        $encoding = $part[1];
                    }
                    break;
                }
            }

            if ($encoding !== '' && strtoupper($encoding) !== 'UTF-8' && strtoupper($encoding) !== 'UTF8') {
                $tmp = mb_convert_encoding($fullValue, 'UTF-8', $encoding);
                if ($tmp !== false) {
                    $fullValue = $tmp;
                }
            }
        }

        $fullValue = $name . '=' . $fullValue;

        $tmp = [];
        // parse_str($fullValue, $tmp);
        $tmp[HelperString::getLeftString($fullValue, '=')] = HelperString::getRightStringBack($fullValue, '=');

        return self::expandVariables(explode('[', $name), $variables, $tmp);
    }

    private static function expandVariables(array $names, $variables, array $values)
    {
        if (!is_array($variables)) {
            return $values;
        }

        $name = rtrim(array_shift($names), ']');
        if ($name !== '') {
            $name = $name . '=p';

            $tmp = [];
            parse_str($name, $tmp);

            $tmp  = array_keys($tmp);
            $name = reset($tmp);
        }

        if ($name === '') {
            $variables[] = reset($values);
        } elseif (isset($variables[$name]) && isset($values[$name])) {
            $variables[$name] = self::expandVariables($names, $variables[$name], $values[$name]);
        } elseif (isset($values[$name])) {
            $variables[$name] = $values[$name];
        }

        return $variables;
    }
}
