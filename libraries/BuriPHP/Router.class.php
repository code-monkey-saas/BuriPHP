<?php

/**
 * @package BuriPHP.Libraries
 *
 * @since 2.0Alpha
 * @version 1.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

namespace Libraries\BuriPHP;

use Libraries\BuriPHP\Interfaces\iRouter;
use Libraries\BuriPHP\Helpers\HelperArray;
use Libraries\BuriPHP\Helpers\HelperConvert;
use Libraries\BuriPHP\Helpers\HelperFile;
use Libraries\BuriPHP\Helpers\HelperString;
use Libraries\BuriPHP\Helpers\HelperValidate;

class Router implements iRouter
{
    private array $urls = [];
    private int $useVersion = 1;
    private string $currentEndpoint = "";
    private string $useModule = "";
    private string $useController = "";

    /**
     * Inicializa los endpoints globales.
     */
    final public function __construct()
    {
        if (!isset($GLOBALS['_APP']['ENDPOINTS'])) {
            $GLOBALS['_APP']['ENDPOINTS'] = HelperConvert::toArray("");
        }

        $this->reset();
    }

    /**
     * Reinicia las variables.
     */
    private function reset()
    {
        $this->useVersion = 1;
        $this->currentEndpoint = "";
        $this->useModule = "";
        $this->useController = "";
    }

    /**
     * Genera un enpoint vacío.
     * 
     * @return array
     */
    public function endpoints()
    {
        return HelperConvert::toArray([]);
    }

    /**
     * Asigna la version que se usará para el endpoint.
     * 
     * @param int $int
     * 
     * @return mixed
     */
    final public function useVersion($int)
    {
        $this->useVersion = (int) $int;

        return $this;
    }

    /**
     * Crea un grupo de endpoint.
     * 
     * @param string $str
     * 
     * @return mixed
     */
    final public function addGroup($str)
    {
        if (HelperValidate::existWord($str, '__VERSION__')) {
            $this->currentEndpoint = HelperString::replaceAll($str, '__VERSION__', 'v' . $this->useVersion);
        } else {
            $this->currentEndpoint = $str;
        }

        return $this;
    }

    /**
     * Asigna el modulo.
     * 
     * @param string $str
     * 
     * @return mixed
     */
    final public function useModule($str)
    {
        $this->useModule = $str;

        return $this;
    }

    /**
     * Asigna el controlador.
     * 
     * @param string $str
     * 
     * @return mixed
     */
    final public function useController($str)
    {
        $this->useController = $str;

        return $this;
    }

    /**
     * Asigna usar el metodo GET
     * 
     * @param string $str
     * @param string $method
     * @param string $contentType
     * 
     * @return mixed
     */
    final public function get($str, $method, $contentType = 'html')
    {
        $this->join($this->currentEndpoint . $str, 'GET', $method, $contentType);

        return $this;
    }

    /**
     * Asigna usar el metodo POST
     * 
     * @param string $str
     * @param string $method
     * @param string $contentType
     * 
     * @return mixed
     */
    final public function post($str, $method, $contentType = 'json')
    {
        $this->join($this->currentEndpoint . $str, 'POST', $method, $contentType);

        return $this;
    }

    /**
     * Asigna usar el metodo PUT
     * 
     * @param string $str
     * @param string $method
     * @param string $contentType
     * 
     * @return mixed
     */
    final public function put($str, $method, $contentType = 'json')
    {
        $this->join($this->currentEndpoint . $str, 'PUT', $method, $contentType);

        return $this;
    }

    /**
     * Asigna usar el metodo UPDATE
     * 
     * @param string $str
     * @param string $method
     * @param string $contentType
     * 
     * @return mixed
     */
    final public function update($str, $method, $contentType = 'json')
    {
        $this->join($this->currentEndpoint . $str, 'UPDATE', $method, $contentType);

        return $this;
    }

    /**
     * Asigna usar el metodo DELETE
     * 
     * @param string $str
     * @param string $method
     * @param string $contentType
     * 
     * @return mixed
     */
    final public function delete($str, $method, $contentType = 'json')
    {
        $this->join($this->currentEndpoint . $str, 'DELETE', $method, $contentType);

        return $this;
    }

    /**
     * Crea un string con todos los parametros del endpoint.
     * 
     * @param string $url
     * @param string $requestMethod
     * @param string $method
     * @param string $contentType
     */
    private function join($url, $requestMethod, $method, $contentType)
    {
        $url = explode('/', $url);
        $url = HelperArray::compact($url);

        $arrParams = [];

        foreach ($url as $key => $value) {
            if (
                HelperString::getLeftNum($value, 1) === '{' &&
                HelperString::getRightNum($value, 1) === '}'
            ) {
                $arrParams[] = [
                    $key,
                    HelperString::getBetween($value, '{', '}')
                ];
            }
        }

        if (get_called_class() != 'Libraries\Endpoints\Endpoints') {
            $this->useModule(explode('\\', get_called_class())[0]);
        }

        $arrParams = json_encode($arrParams);
        $url = HelperArray::joinValues($url, '/');

        $this->urls = HelperArray::append(
            $this->urls,
            "$requestMethod:/$url:$this->useModule:$this->useController:$method:$arrParams:$contentType:[]"
        );
    }

    /**
     * Establece los endpoints
     */
    final public function assign()
    {
        $GLOBALS['_APP']['ENDPOINTS'] = HelperArray::combine($GLOBALS['_APP']['ENDPOINTS'], $this->urls);
        $GLOBALS['_APP']['ENDPOINTS'] = HelperArray::removeDuplicates($GLOBALS['_APP']['ENDPOINTS']);

        $this->reset();
    }

    /**
     * Agrega los endpoints desde un módulo.
     * 
     * @param string $module
     */
    final public function addForModule($module)
    {
        try {
            if (HelperFile::exists(PATH_MODULES . $module . DS . 'Endpoints' . CLASS_PHP)) {
                include_once(PATH_MODULES . $module . DS . 'Endpoints' . CLASS_PHP);

                $namespaceModuleEndpoints = '\\' . $module . '\Endpoints\Endpoints';

                (new $namespaceModuleEndpoints())->endpoints();
            } else {
                throw new \Exception("No sé encontró Endpoints.class.php en el módulo $module");
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Divide el endpoint en parametros.
     * 
     * @param string $endpoint
     */
    final public static function explodeEndpoint($endpoint)
    {
        if (HelperValidate::isEmpty($endpoint)) {
            return [];
        }

        $endpoint = explode(':', $endpoint);

        return [
            'REQUEST_METHOD' => $endpoint[0],
            'REQUEST_URI' => $endpoint[1],
            'MODULE' => $endpoint[2],
            'CONTROLLER' => $endpoint[3],
            'METHOD' => $endpoint[4],
            'PARAMS' => json_decode($endpoint[5], true),
            'CONTENT_TYPE' => $endpoint[6],
            'SETTINGS' => $endpoint[7]
        ];
    }

    /**
     * Devuelve el endpoint actual.
     */
    final public static function getEndpoint()
    {
        return [
            $GLOBALS['_APP']['ENDPOINT'],
            self::explodeEndpoint($GLOBALS['_APP']['ENDPOINT'])
        ];
    }
}