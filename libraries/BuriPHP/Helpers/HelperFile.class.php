<?php

/**
 * @package BuriPHP.Libraries.Helpers
 * 
 * @abstract
 *
 * @since 2.0Alpha
 * @version 1.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

namespace Libraries\BuriPHP\Helpers;

abstract class HelperFile
{
    /**
     * Escribe texto en un archivo. Se puede indica un máximo a 
     * escribir.
     * Devuelve los bytes escritos.
     *
     * @param resource $handleFile
     * @param mixed    $txt
     * @param int      $maxBytes
     *
     * @return int
     * @throws Exception
     */
    public static function write($handleFile, $txt, $maxBytes = null)
    {
        if (is_null($maxBytes)) {
            $ret = fwrite($handleFile, $txt);
        } else {
            $ret = fwrite($handleFile, $txt, $maxBytes);
        }
        if ($ret === false) {
            throw new \Exception('No se puede escribir en el archivo');
        }
        self::flush($handleFile);
        return $ret;
    }

    /**
     * Desbloquea un archivo bloqueado.
     * Al hacer un close, también se desbloquea
     *
     * @param resource $handleFile
     *
     * @throws Exception
     */
    public static function unLock($handleFile)
    {
        if (flock($handleFile, LOCK_UN) === false) {
            throw new \Exception('Imposible desbloquear el archivo');
        }
    }

    /**
     * Trunca un archivo a un determinado tamaño
     *
     * @param resource $handleFile
     * @param int      $tamanio
     *
     * @throws Exception
     */
    public static function truncateContent($handleFile, $tamanio)
    {
        if (ftruncate($handleFile, $tamanio) === false) {
            throw new \Exception('No se puede truncar el archivo');
        }
    }

    /**
     * Modifica la fecha del último acceso al archivo
     *
     * @param $filename
     *
     * @throws Exception
     */
    public static function touch($filename)
    {
        if (touch($filename) === false) {
            throw new \Exception("No se puede realizar un touch al archivo {$filename}");
        }
    }

    /**
     * Posiciona el cursor al final del archivo
     *
     * @param resource $handleFile
     *
     * @throws Exception
     */
    public static function seekToEnd($handleFile)
    {
        $ret = fseek($handleFile, 0, SEEK_END);
        if ($ret === false || $ret == -1) {
            throw new \Exception('No se puede desplazar el cursor al final del archivo');
        }
    }

    /**
     * Posiciona el cursor al inicio del archivo
     *
     * @param $handleFile
     *
     * @throws Exception
     */
    public static function seekToBegin($handleFile)
    {
        if (rewind($handleFile) === false) {
            throw new \Exception('No se puede desplazar el cursor al inicio del archivo');
        }
    }

    /**
     * Posiciona el cursor dentro de un archivo partiendo de la
     * posición actual del cursor
     *
     * @param resource $handleFile
     * @param int      $offset
     *
     * @throws Exception
     */
    public static function seekIncrement($handleFile, $offset)
    {
        $ret = fseek($handleFile, $offset, SEEK_CUR);
        if ($ret === false || $ret == -1) {
            throw new \Exception('No se puede desplazar el cursor en el archivo');
        }
    }

    /**
     * Posiciona el cursor dentro de un archivo partiendo desde el 
     * final
     *
     * @param resource $handleFile
     * @param int      $offset
     *
     * @throws Exception
     */
    public static function seekFromEnd($handleFile, $offset)
    {

        $ret = fseek($handleFile, (-1 * $offset), SEEK_END);

        if ($ret === false || $ret == -1) {
            throw new \Exception('No se puede desplazar el cursor desde el final del archivo');
        }
    }

    /**
     * Posiciona el cursor dentro de un archivo partiendo desde el 
     * inicio
     *
     * @param resource $handleFile
     * @param int      $offset
     *
     * @throws Exception
     */
    public static function seekFromBegin($handleFile, $offset)
    {
        $ret = fseek($handleFile, $offset, SEEK_SET);

        if ($ret === false || $ret == -1) {
            throw new \Exception('No se puede desplazar el cursor desde dede el inicio del archivo');
        }
    }

    /**
     * Renombra un archivo a otro nombre. Puede sobreescribir el 
     * destino
     * Por defecto no lo sobreescribe. Lanza una excepción si hay 
     * algún error
     *
     * @param string $sourceFile
     * @param string $targetFile
     * @param bool   $overwrite
     *
     * @throws Exception
     */
    public static function rename($sourceFile, $targetFile, $overwrite = false)
    {
        if (!$overwrite && file_exists($targetFile)) {
            throw new \Exception("El archivo destino {$targetFile} ya existe y no se ha de sobreescribir");
        }
        if (rename($sourceFile, $targetFile) === false) {
            throw new \Exception("No se ha podido sobreescribir el archivo origen {$sourceFile} con el archivo destino {$targetFile}");
        }
    }

    /**
     * Elimina todos los archivos de un directorio que cumplan un 
     * determinado patrón.
     * Lanza excepciones si algún archivo no se ha podido eliminad
     *
     * @param string $pattern
     *
     */
    public static function removeByPattern($pattern)
    {
        array_map(function ($filename) {
            self::remove($filename);
        }, glob($pattern));
    }

    /**
     * Elimina físicamente un archivo.
     * Si no existe, no pasa nada, pero si existe y no se ha podido
     * eliminar, se lanza una excepción.
     *
     * @param $filepath
     *
     * @throws Exception
     */
    public static function remove($filepath)
    {
        if (!file_exists($filepath)) {
            return;
        }
        if (unlink($filepath) === false) {
            throw new \Exception("No se puede eliminar el archivo {$filepath}");
        }
    }

    /**
     * Lee el contenido de un archivo en función de un formato
     *
     * @param resource $handleFile
     * @param string   $format
     *
     * @return array|int
     */
    public static function readScan($handleFile, $format)
    {
        return fscanf($handleFile, $format);
    }

    /**
     * Lee una linea de un archivo abierto. Hasta encontrar un \r\n
     * La línea tambén incluye el salto de línea.
     * Si no puede leer del archivo, lanza una excepción
     * Si el archivo a leer esta vacío, lanza una excepción
     *
     * @param resource $handleFile
     *
     * @return string
     * @throws Exception
     */
    public static function readLine($handleFile)
    {
        $ret = fgets($handleFile);
        if ($ret === false) {
            throw new \Exception('No se puede leer la linea del archivo');
        }
        return $ret;
    }

    /**
     * Lee una cantidad de char de un archivo. Por defecto 1.
     * Saltos de línea (\r\n) son dos chars.
     * Si no puede leer del archivo, lanza una excepción
     * Si el archivo a leer esta vacío, lanza una excepción
     *
     * @param resource $handleFile
     * @param int      $maxBytes
     *
     * @return string
     * @throws Exception
     */
    public static function readChars($handleFile, $maxBytes = 1)
    {
        $ret = fread($handleFile, $maxBytes);
        if ($ret === false) {
            throw new \Exception("Imposible leer {$maxBytes} bytes del archivo");
        }
        return $ret;
    }

    /**
     * Abre una conexión contra un archivo de texto
     * Si se abre, devuelve el handle de la conexion, si hay error 
     * lanza excepción
     * r
     *     Sólo permite leer. No se puede escribir mientras esta 
     *     abierto
     *     Puntero al inicio del archivo al abrir.
     *     Falla si el archivo no existe.
     * r+
     *     Se puede leer i escribit.
     *     Puntero al inicio del archivo al abrir.
     *     Falla si el archivo no existe. Es obligatorio que exista
     * w
     *     Sólo permite escribir. No leer
     *     Puntero al inicio del archivo al abrir.
     *     Si el archivo no existe, se crea. Si está credo 
     *     sobreescribe.
     * w+
     *     Se puede leer i escribit.
     *     Puntero al inicio del archivo al abrir.
     *     Si el archivo no existe, se crea. Si está credo 
     *     sobreescribe.
     * a
     *     Sólo permite escribir. No leer
     *     Puntero al final del archivo al abrir.
     *     Si el archivo no existe, se crea.
     * a+
     *     Se puede leer i escribit.
     *     Puntero al final del archivo al abrir.
     *     Si el archivo no existe, se crea.
     *
     * @param string $filepath
     * @param string $accessMode
     *
     * @return resource
     * @throws Exception
     */
    public static function openText($filepath, $accessMode)
    {
        $handle = @fopen($filepath, $accessMode);
        if (!$handle) {
            throw new \Exception("Imposible abrir archivo {$filepath} en modo {$accessMode}");
        }
        return $handle;
    }

    /**
     * Abre una conexión contra un archivo binario
     * Si se abre, devuelve el handle de la conexion, si hay error 
     * lanza excepción
     * r
     *     Sólo permite leer. No se puede escribir mientras esta 
     *      abierto
     *     Puntero al inicio del archivo al abrir.
     *     Falla si el archivo no existe.
     * r+
     *     Se puede leer i escribit.
     *     Puntero al inicio del archivo al abrir.
     *     Falla si el archivo no existe. Es obligatorio que exista
     * w
     *     Sólo permite escribir. No leer
     *     Puntero al inicio del archivo al abrir.
     *     Si el archivo no existe, se crea. Si está credo 
     *     sobreescribe.
     * w+
     *     Se puede leer i escribit.
     *     Puntero al inicio del archivo al abrir.
     *     Si el archivo no existe, se crea. Si está credo 
     *     sobreescribe.
     * a
     *     Sólo permite escribir. No leer
     *     Puntero al final del archivo al abrir.
     *     Si el archivo no existe, se crea.
     * a+
     *     Se puede leer i escribit.
     *     Puntero al final del archivo al abrir.
     *     Si el archivo no existe, se crea.
     *
     * @param string $filepath
     * @param string $accessMode
     *
     * @return resource
     * @throws Exception
     */
    public static function openBinary($filepath, $accessMode)
    {
        $handle = @fopen($filepath, $accessMode . 'b');
        /* b => Abre un binario */
        if (!$handle) {
            throw new \Exception("Imposible abrir archivo {$filepath} en modo {$accessMode}");
        }
        return $handle;
    }

    /**
     * Bloquea un archivo en modo exclusivo, tanto para leer como para
     * escribir
     *
     * @param resource $handleFile
     *
     * @throws Exception
     */
    public static function lock($handleFile)
    {
        if (flock($handleFile, LOCK_EX) === false) {
            throw new \Exception('Imposible bloquear el archivo origen');
        }
    }

    /**
     * Indica si el archivo se puede escribir.
     * Si el archivo no existe, devuelve false.
     * No es necesario estar abierto.
     *
     * @param string $filename
     *
     * @return bool
     */
    public static function isWritable($filename)
    {
        if (!file_exists($filename)) {
            return false;
        }
        return is_writable($filename);
    }

    /**
     * Indica si el archivo se puede leer.
     * Si el archivo no existe, devuelve false.
     * No es necesario estar abierto.
     *
     * @param string $filename
     *
     * @return bool
     */
    public static function readable($filename)
    {
        if (!file_exists($filename)) {
            return false;
        }
        return is_readable($filename);
    }

    /**
     * Devuelve el tamaño en bytes de un archivo.
     * Si el archivo no existe, devuelve -1
     *
     * @param $filename
     *
     * @return false|int
     * @throws Exception
     */
    public static function getSizeBytes($filename)
    {
        if (!file_exists($filename)) {
            return -1;
        }
        $ret = filesize($filename);
        if ($ret === false) {
            throw new \Exception("No se puede obtener el tamaño del archivo {$filename}");
        }
        return $ret;
    }

    /**
     * Devuelve la posición actual del cursor
     *
     * @param resource $handleFile
     *
     * @return false|int
     * @throws Exception
     */
    public static function getSeekPosition($handleFile)
    {
        $ret = ftell($handleFile);
        if ($ret === false) {
            throw new \Exception('Imposible obtener la posición del cursor de dentro del archivo');
        }
        return $ret;
    }

    /**
     * Reemplaza los separadores de directorio "\\" por "/" y elimina 
     * los innecesarios
     *
     * @param string $filepath
     *
     * @return string
     */
    public static function getSanitizedPath($filepath)
    {
        $path = HelperString::replaceAll($filepath, '\\', '/');
        $path = HelperString::replaceAll($path, '//', '/');
        $path = HelperString::replaceAll($path, '/./', '/');
        $path = HelperArray::compact(explode('/', $path));

        return "/" . HelperArray::joinValues($path, '/');
    }

    /**
     * Función que devuelve sólo el nombre del archivo, sin extensión 
     * ni directorio.
     * Separador de directorio = '/'
     * Si sólo hay un directorio (último carácter es '/') implica que 
     * no hay nombre de archivo y devuelve ''
     *
     * @param string $filepath
     *
     * @return string
     */
    public static function getOnlyFileName($filepath)
    {
        $filepath = self::getSanitizedPath($filepath);
        $lastPos   = strlen($filepath) - 1;
        if ($filepath[$lastPos] == '/') {
            return '';
        }
        return pathinfo($filepath, PATHINFO_FILENAME);
    }

    /**
     * Función que devuelve sólo la extensión de un archivo
     * Si tiene más de una, devuelve la última y si no 
     * tiene devuelve ""
     *
     * @param string $filepath
     *
     * @return string
     */
    public static function getOnlyExtension($filepath)
    {
        return pathinfo($filepath, PATHINFO_EXTENSION);
    }

    /**
     * Función que devuelde sólo el directorio del archivo con path.
     * Si sólo hay el nombre del archivo, el directorio es ""
     * Si sólo hay directorio (sin nombre de archivo) devuelve el 
     * mismo directorio
     *
     * @param string $filepath
     *
     * @return string
     */
    public static function getOnlyDirName($filepath)
    {
        $filepath = self::getSanitizedPath($filepath);
        $lastPos   = strlen($filepath) - 1;
        if ($filepath[$lastPos] == '/') {
            return substr($filepath, 0, $lastPos);
        }
        if ($filepath[0] == '/') {
            $filepath = substr($filepath, 1);
        }

        $dir = pathinfo($filepath, PATHINFO_DIRNAME);

        if ($dir == "\\") {
            $dir = '/';
        } elseif ($dir == '.') {
            $dir = '';
        }
        return $dir;
    }


    /**
     * Devuelve el nombre del archivo al que apunta el link.
     * No es necesario estar abierto.
     * Si no es un link, devuelve ''
     *
     * @param string $nameLink
     *
     * @return string
     */
    public static function getLinkTarget($nameLink)
    {
        if (!is_link($nameLink)) {
            return '';
        }
        return '' . readlink($nameLink);
    }

    /**
     * Devuelve el nombre, path, tamaño y fechas de un archivo
     * Opciones name, server_path, size, date, readable, writable, 
     * executable, fileperms
     *
     * @param string   path to file
     * @param mixed    array or comma separated string of information 
     * returned
     *
     * @return   array
     */
    public static function getInfo($file, $returnedValues = array('name', 'server_path', 'size', 'date'))
    {
        $fileinfo = [];
        if (!file_exists($file)) {
            return $fileinfo;
        }

        if (is_string($returnedValues)) {
            $returnedValues = explode(',', $returnedValues);
        }

        foreach ($returnedValues as $key) {
            switch ($key) {
                case 'name':
                    $fileinfo['name'] = substr(strrchr($file, DS), 1);
                    break;
                case 'server_path':
                    $fileinfo['server_path'] = $file;
                    break;
                case 'size':
                    $fileinfo['size'] = filesize($file);
                    break;
                case 'date':
                    $fileinfo['date'] = filectime($file);
                    break;
                case 'readable':
                    $fileinfo['readable'] = is_readable($file);
                    break;
                case 'writable':
                    // There are known problems using is_weritable on 
                    // IIS.  It may not be reliable - consider 
                    // fileperms()
                    $fileinfo['writable'] = is_writable($file);
                    break;
                case 'executable':
                    $fileinfo['executable'] = is_executable($file);
                    break;
                case 'fileperms':
                    $fileinfo['fileperms'] = fileperms($file);
                    break;
            }
        }

        return $fileinfo;
    }

    /**
     * Crea y devuelve un nombre único de un archivo temporal en el 
     * directorio temporal del sistema
     * Se le puede añadir un prefijo de 3 caracteres como máximo.
     * Lanza una excecpión si no se puede crear
     *
     * @param string $prefix
     *
     * @return string
     * @throws Exception
     */
    public static function getFileTmp($prefix = '')
    {
        $name = tempnam(sys_get_temp_dir(), $prefix);
        if ($name === false) {
            throw new \Exception('Imposible crear archivo temporal');
        }
        return $name;
    }

    /**
     * Función que devuelde el nombre completo (nombre + extensión) 
     * del archivo, sin directorio
     * Si el archivo sólo tiene directorio, devuelve ""
     * SI el archivo sólo contiene nombre de vuelve el mismo nombre
     *
     * @param string $filepath
     *
     * @return string
     */
    public static function getFileNameFull($filepath)
    {
        $filepath = self::getSanitizedPath($filepath);
        $lastPos   = strlen($filepath) - 1;
        if ($filepath[$lastPos] == '/') {
            return '';
        }
        return pathinfo($filepath, PATHINFO_BASENAME);
    }

    /**
     * Devuelve el contenido de un archivo.
     * Se puede indicar un offset y un máximo de bytes a devolver
     * Si el archivo no existe, lanza una excepción.
     * Saltos de línea son \r\n
     * Si hay un offset es obligatorio un máximo de bytes a devolver
     *
     * @param string   $filepath
     * @param int|null $offset
     * @param int|null $maxBytes
     *
     * @return false|string
     * @throws Exception
     */
    public static function getAllContent($filepath, $offset = null, $maxBytes = null)
    {
        if (!is_null($offset) && !is_null($maxBytes)) {
            $ret = file_get_contents(
                $filepath,
                false,
                null,
                $offset,
                $maxBytes
            );
        } else {
            $ret = file_get_contents($filepath);
        }
        if ($ret === false) {
            throw new \Exception("Imposible leer el contenido del archivo {$filepath}");
        }
        return $ret;
    }

    /**
     * Fuerza la escritura del bufer al archivo.
     * Lanza una excepción si no puede realizarlo
     *
     * @param resource $handleFile
     *
     * @throws Exception
     */
    public static function flush($handleFile)
    {
        if (fflush($handleFile) === false) {
            throw new \Exception('No sd puede formazar la escriptura del archivo');
        }
    }

    /**
     * Indica si un archivo existe
     *
     * @param string $filepath
     *
     * @return mixed
     */
    public static function exists($filepath)
    {
        return file_exists($filepath);
    }

    /**
     * Detecta el End Of File de un archivo
     *
     * @param $handleFile
     *
     * @return bool
     */
    public static function eof($handleFile)
    {
        return feof($handleFile);
    }

    /**
     * Crea un link simbólico de un archio. No es necesario que esté 
     * abierto
     * Lanza una excepción si no se ha podido crear
     *
     * @param string $sourceFilename
     * @param string $nameLinkDestination
     *
     * @throws Exception
     */
    public static function createLink($sourceFilename, $nameLinkDestination)
    {
        $ret = symlink($sourceFilename, $nameLinkDestination);
        if ($ret === false) {
            throw new \Exception("Imposible crear link destino {$nameLinkDestination} del archivo origen {$sourceFilename}");
        }
    }

    /**
     * Copa un archivo a otro. Por defecto no se sobreescribe.
     * Lanza una excecpión si no se ha copiado por algún motivo
     * Se puede copiar entre diferentes directorios
     *
     * @param string $sourceFile
     * @param string $targetFile
     * @param bool   $overwrite
     *
     * @throws Exception
     */
    public static function copy($sourceFile, $targetFile, $overwrite = false)
    {
        if (!$overwrite && file_exists($targetFile)) {
            throw new \Exception("El archivo destino {$targetFile} ya existe y no se ha de sobreescribir");
        }
        if (copy($sourceFile, $targetFile) === false) {
            throw new \Exception("No se puede copiar el archivo origen {$sourceFile} en el archivo destino {$targetFile}");
        }
    }

    /**
     * Cierra el fichero
     *
     * @param $handleFile
     */
    public static function close($handleFile)
    {
        @fclose($handleFile);
    }

    /**
     * Cambiamos la extensión de un archivo.
     * Si no tiene extensión, le asignamos la nueva.
     *
     * @param string $filename
     * @param string $newExtension
     *
     * @return string
     */
    public static function changeExtension($filename, $newExtension)
    {
        if (HelperValidate::isEmpty($newExtension)) {
            return $filename;
        }

        if ($newExtension[0] != '.') {
            $newExtension = '.' . $newExtension;
        }

        $dir = self::getOnlyDirName($filename);

        $name = self::getOnlyFileName($filename);

        if ($name == '') {
            return $filename;
        }

        if ($dir == '' && $filename[0] != '/') {
            return $dir . $name . $newExtension;
        }

        return $dir . '/' . $name . $newExtension;
    }
}
