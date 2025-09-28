<?php

namespace Core;

use Core\Interfaces\ControllerInterface;

/**
 * Clase Controller
 *
 * Esta clase representa un controlador base en el framework BuriPHP. Proporciona métodos para inicializar
 * servicios y vistas, compartir controladores y servicios, y obtener parámetros de solicitudes HTTP.
 * 
 * @package BuriPHP
 * @author Kiske
 * @since 0.0.1
 * @version 2.8
 * @license You can see LICENSE.txt
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */
class Controller implements ControllerInterface
{
    /**
     * Parámetros de ruta del controlador
     * @var array
     */
    protected $routeParams = [];

    public function __construct() {}

    /**
     * Establece los parámetros de ruta para el controlador
     * 
     * @param array $params Parámetros de ruta a establecer
     * @return void
     */
    public function setRouteParams(array $params)
    {
        $this->routeParams = $params;
    }

    /**
     * Obtiene los parámetros de consulta de la solicitud HTTP
     * 
     * @return array Parámetros de consulta
     */
    public function queryParams(): array
    {
        return $_GET ?? [];
    }

    /**
     * Obtiene el payload completo de la solicitud HTTP, incluyendo datos POST, archivos y datos sin procesar
     * 
     * @return array Payload completo de la solicitud
     */
    public function payload(): array
    {
        $request = [];

        $rawData = self::parseRawData();
        if (!empty($rawData)) {
            $request = array_merge($request, $rawData);
        }

        if (!empty($_POST)) {
            $request = array_merge($request, $_POST);
        }

        if (isset($_FILES) && !empty($_FILES)) {
            if (is_array($_FILES) && count($_FILES) >= 1) {
                foreach ($_FILES as $key => $value) {
                    if (is_array($_FILES[$key]['name']) && count($_FILES[$key]['name']) >= 1) {
                        $_FILES[$key] = $this->reArrayFiles($_FILES[$key]);
                    }
                }
            }

            $request = array_merge($request, $_FILES);
        }

        return $request;
    }

    /**
     * Analiza los datos sin procesar de la solicitud HTTP
     * 
     * @return array Datos analizados
     */
    private static function parseRawData(): array
    {
        $_raw_data = fopen("php://input", "r");
        $raw_data = '';

        /* Leer los datos 1 KB a la vez */
        while ($chunk = fread($_raw_data, 1024))
            $raw_data .= $chunk;

        /* Cerrar los streams */
        fclose($_raw_data);

        // Obtener el contenido y determinar el límite
        $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

        if (empty($boundary)) {
            json_decode($raw_data);
            if (json_last_error() === JSON_ERROR_NONE) {
                return json_decode($raw_data, true);
            } else {
                parse_str($raw_data, $data);
                return $data;
            }
        }

        // Obtener cada parte
        $parts = array_slice(explode($boundary, $raw_data), 1);
        $data = array();

        foreach ($parts as $part) {
            // Si esta es la última parte, salir
            if ($part == "--\r\n") break;

            // Separar contenido de encabezados
            $part = ltrim($part, "\r\n");
            list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

            // Analizar la lista de encabezados
            $raw_headers = explode("\r\n", $raw_headers);
            $headers = array();
            foreach ($raw_headers as $header) {
                list($name, $value) = explode(':', $header);
                $headers[strtolower($name)] = ltrim($value, ' ');
            }

            // Analizar el Content-Disposition para obtener el nombre del campo, etc.
            if (isset($headers['content-disposition'])) {
                $filename = null;
                $tmp_name = null;
                preg_match(
                    '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/',
                    $headers['content-disposition'],
                    $matches
                );
                list(, $type, $name) = $matches;

                // Analizar archivo
                if (isset($matches[4])) {
                    // Si se etiqueta igual que el anterior, omitir
                    if (isset($_FILES[$matches[2]])) {
                        continue;
                    }

                    // Obtener nombre del archivo
                    $filename = $matches[4];

                    // Obtener nombre temporal
                    $filename_parts = pathinfo($filename);
                    $tmp_name = tempnam(ini_get('upload_tmp_dir'), $filename_parts['filename']);

                    // Poner en $_FILES con información
                    $_FILES[$matches[2]] = array(
                        'error' => 0,
                        'name' => $filename,
                        'tmp_name' => $tmp_name,
                        'size' => strlen($body),
                        'type' => $value
                    );

                    // Colocar en el directorio temporal
                    file_put_contents($tmp_name, $body);
                }
                // Analizar campo
                else {
                    $data[$name] = substr($body, 0, strlen($body) - 2);
                }
            }
        }
        return $data;
    }

    /**
     * Reorganiza un array de archivos para un formato más manejable
     * 
     * @param array &$file_post Referencia al array de archivos a reorganizar
     * @return array Array de archivos reorganizado
     */
    private static function reArrayFiles(&$file_post): array
    {
        $file_array = [];

        foreach ($file_post as $key => $value) {
            foreach ($value as $k => $v) {
                $file_array[$k][$key] = $v;
            }
        }

        return $file_array;
    }
}
