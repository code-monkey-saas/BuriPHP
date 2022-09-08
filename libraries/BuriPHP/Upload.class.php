<?php

namespace BuriPHP\System\Libraries;

/**
 *
 * @package BuriPHP.Libraries
 *
 * @since 1.0.0
 * @version 2.0.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

defined('_EXEC') or die;

class Upload
{
    /**
     *
     * @var string
     */
    private $valid_extensions;

    /**
     *
     * @var integer
     */
    private $maximum_file_size;

    /**
     * Constructor.
     *
     * @return  void
     */
    public function __construct()
    {
        $this->valid_extensions = ['jpg', 'jpeg', 'png', 'bmp', 'svg', 'pdf', 'doc', 'txt'];
        $this->maximum_file_size = $this->get_maximum_file_size();
    }

    /**
     * Ordena un array multidimensional, por fichero.
     *
     * @static
     *
     * @param   array    $argv    Arreglo de ficheros.
     *
     * @return   array
     */
    public static function order_array($argv = null): array
    {
        if (is_null($argv))
            return [];

        $response = [];

        if (isset($argv['name']) && is_array($argv['name'])) {
            for ($i = 0; $i < count($argv['name']); $i++) {
                $response[$i] = [
                    'name' => $argv['name'][$i],
                    'type' => $argv['type'][$i],
                    'tmp_name' => $argv['tmp_name'][$i],
                    'error' => $argv['error'][$i],
                    'size' => $argv['size'][$i]
                ];
            }
        }

        return $response;
    }

    /**
     * Valida un fichero.
     *
     * @static
     *
     * @param   array    $file    Arreglo del fichero.
     * @param   array    $valid_extensions    Arreglo con las extensiones de aplicaciones permitidas.
     *
     * @return   array
     */
    public static function validate_file($file = null, $valid_extensions = null): array
    {
        if (is_null($valid_extensions) || !is_array($valid_extensions) || empty($valid_extensions))
            $valid_extensions = (new self)->valid_extensions;

        if (is_null($file))
            return (new self)->get_code_errors('FILE_EMPTY');

        if ($file['error'] >= 1)
            return (new self)->get_code_errors($file['error'], $file['name']);

        if (!in_array(explode('/', $file['type'])[1], $valid_extensions, true))
            return (new self)->get_code_errors('FILE_EXTENSION', $file['name']);

        if ($file['size'] > (new self)->maximum_file_size)
            return (new self)->get_code_errors('FILE_SIZE', $file['name']);

        return ['status' => 'OK'];
    }

    /**
     * Valida un conjunto de ficheros.
     *
     * @static
     *
     * @param   array    $files    Arreglo de ficheros.
     * @param   array    $valid_extensions    Arreglo con las extensiones de aplicaciones permitidas.
     *
     * @return   array
     */
    public static function validate_array($files = null, $valid_extensions = null): array
    {
        if (is_null($valid_extensions) || !is_array($valid_extensions) || empty($valid_extensions))
            $valid_extensions = (new self)->valid_extensions;

        if (is_null($files))
            return array_merge((new self)->get_code_errors('FILE_EMPTY'), ['labels' => []]);

        $labels = [];

        foreach ($files as $key => $value) {
            $response = (new self)->validate_file($value, $valid_extensions);

            if ($response['status'] === 'ERROR')
                $labels[$key] = $response;
        }

        if (!empty($labels))
            return array_merge((new self)->get_code_errors('FILES_ERROR'), ['labels' => $labels]);

        return ['status' => 'OK'];
    }

    /**
     * Copia un fichero al servidor.
     *
     * @static
     *
     * @param   array    $file    Arreglo del fichero.
     * @param   array    $settings    Configuraciones para copiar el fichero.
     *
     * @return   array
     */
    public static function upload_file($file = null, $settings = []): array
    {
        $settings['path_uploads'] = (!isset($settings['path_uploads']) || empty($settings['path_uploads'])) ? PATH_UPLOADS : $settings['path_uploads'];
        $settings['set_name'] = (!isset($settings['set_name']) || empty($settings['set_name'])) ? 'FILE_NAME' : $settings['set_name'];

        if (is_null($file))
            return (new self)->get_code_errors('FILE_EMPTY');

        $file['name'] = (new self)->set_file_name($file['name'], $settings['set_name']);

        $file_path = Security::DS($settings['path_uploads'] . '/' . $file['name']);

        if (@copy($file['tmp_name'], $file_path)) {
            if (isset($settings['image_compress']) && $settings['image_compress'] == true) {
                switch ($file['type']) {
                    case 'image/jpeg':
                    case 'image/jpg':
                    case 'image/png':
                    case 'image/gif':
                        (new self)->image_compress($file_path);
                        break;
                }
            }

            return [
                'status' => 'OK',
                'file' => $file['name'],
                'size' => $file['size'],
                'size_compress' => filesize($file_path)
            ];
        } else
            return (new self)->get_code_errors('SYSTEM');
    }

    /**
     * Copia un conjunto de ficheros al servidor.
     *
     * @static
     *
     * @param   array    $file    Arreglo del fichero.
     * @param   array    $settings    Configuraciones para copiar el fichero.
     *
     * @return   array
     */
    public static function upload_array($files = null, $settings = []): array
    {
        if (is_null($files))
            return array_merge((new self)->get_code_errors('FILE_EMPTY'), ['labels' => []]);

        $response = [];

        foreach ($files as $key => $value)
            $response[$key] = (new self)->upload_file($value, $settings);

        return $response;
    }

    /**
     * Reduce el peso de una imágen.
     *
     * @param   string    $source    Imágen que será reducida.
     *
     * @return   void
     */
    private function image_compress($source = null): void
    {
        if (is_null($source))
            return;

        $image = getimagesize($source);

        switch ($image['mime']) {
            default:
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;

            case 'image/png':
                $image = imagecreatefrompng($source);
                break;

            case 'image/gif':
                $image = imagecreatefromgif($source);
                break;
        }

        imagejpeg($image, $source, 60);
    }

    /**
     * Crea un nombre para un fichero.
     *
     * @param   string    $argv    Nombre original del fichero.
     * @param   string    $name_formation    Formato que se usara para crear el nombre del fichero.
     *
     * @return   void
     */
    private function set_file_name($argv = null, $name_formation = null): string
    {
        switch (strtoupper($name_formation)) {
            default:
            case 'FILE_NAME':
                return $argv;
                break;

            case 'RANDOM':
                return (new Security())->random_string(16) . '.' . pathinfo($argv, PATHINFO_EXTENSION);
                break;

            case 'FILE_NAME_LAST_RANDOM':
                return pathinfo($argv, PATHINFO_FILENAME) . '_' . (new Security())->random_string(8) . '.' . pathinfo($argv, PATHINFO_EXTENSION);
                break;

            case 'FILE_NAME_FIRST_RANDOM':
                return (new Security())->random_string(8) . '_' . pathinfo($argv, PATHINFO_FILENAME) . '.' . pathinfo($argv, PATHINFO_EXTENSION);
                break;
        }
    }

    /**
     * Obtiene el tamaño máximo permitido de la directiva en el servidor.
     *
     * @return   float
     */
    private function get_maximum_file_size(): float
    {
        $post_max_size = $this->str_to_bytes(ini_get('post_max_size'));
        $upload_max_filesize = $this->str_to_bytes(ini_get('upload_max_filesize'));
        $memory_limit = $this->str_to_bytes(ini_get('memory_limit'));

        if (empty($post_max_size) && empty($upload_max_filesize) && empty($memory_limit))
            return false;

        return min($post_max_size, $upload_max_filesize, $memory_limit);
    }

    /**
     * Convierte un número string a bytes.
     *
     * @param   string    $value    Número en string.
     *
     * @return   float
     */
    private function str_to_bytes($value): ?float
    {
        // only string
        $unit_byte = strtolower(preg_replace('/[^a-zA-Z]/', '', $value));

        // only number (allow decimal point)
        $num_val = (int) $value;

        switch ($unit_byte) {
            case 'p':    // petabyte
            case 'pb':
                $num_val *= 1024;

            case 't':    // terabyte
            case 'tb':
                $num_val *= 1024;

            case 'g':    // gigabyte
            case 'gb':
                $num_val *= 1024;

            case 'm':    // megabyte
            case 'mb':
                $num_val *= 1024;

            case 'k':    // kilobyte
            case 'kb':
                $num_val *= 1024;

            case 'b':    // byte
                return $num_val *= 1;
                break; // make sure

            default:
                return false;
                break;
        }

        return false;
    }

    /**
     * Obtiene un mensaje de error a partir de un código.
     *
     * @param   string    $code_error    Código de error.
     * @param   string    $flags    Flags a convertir en el string.
     *
     * @return   array
     */
    private function get_code_errors($code_error = null, $flags = null): array
    {
        if (is_string($code_error))
            $code_error = strtoupper($code_error);

        switch ($code_error) {
            case 1:
            case 2:
            case 'FILE_SIZE':
                $code_error = "UPLOAD_ERR_INI_SIZE";
                break;

            case 3:
                $code_error = "UPLOAD_ERR_PARTIAL";
                break;

            case 4:
            case 'FILE_EMPTY':
                $code_error = "UPLOAD_ERR_NO_FILE";
                break;

            case 6:
                $code_error = "UPLOAD_ERR_NO_TMP_DIR";
                break;

            case 'FILE_EXTENSION':
                $code_error = "UPLOAD_ERR_EXTENSION";
                break;

            case 'FILES_ERROR':
                $code_error = "UPLOAD_ERR_ARR";
                break;

            case 'SYSTEM':
                $code_error = "UPLOAD_ERR_CANT_WRITE";
                break;

            default:
                $code_error = "Unknow";
                break;
        }

        $ini = (new Format())->import_file(PATH_LANGUAGE, Session::get_value('_lang') . '_uploads_class', 'ini');

        if (!empty($ini)) {
            if (!is_null($flags))
                $message = vsprintf($ini[$code_error], explode(';', $flags));
            else
                $message = $ini[$code_error];
        } else
            $message = "{{$code_error}}";

        return ['status' => 'ERROR', 'message' => $message];
    }
}