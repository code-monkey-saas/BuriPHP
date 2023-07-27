<?php

/**
 * @package BuriPHP.Libraries
 *
 * @since 1.0
 * @version 2.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 * 
 * @deprecated
 */

namespace Libraries\BuriPHP;

use Libraries\BuriPHP\Helpers\HelperDateTime;
use Libraries\BuriPHP\Helpers\HelperServer;
use Libraries\BuriPHP\Helpers\HelperValidate;

class Format
{
	/**
	 * Establece la zona horaria.
	 *
	 * @static
	 * 
	 * @deprecated
	 * @see HelperDateTime::setLocateTimeZone()
	 */
	public static function set_time_zone()
	{
		trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

		HelperDateTime::setLocateTimeZone();
	}

	/**
	 * Obtiene la fecha y hora del servidor.
	 *
	 * @static
	 *
	 * @deprecated
	 * @see HelperDateTime::getNow()
	 */
	public static function get_date_hour()
	{
		trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

		return HelperDateTime::getNow();
	}

	/**
	 * Verifica si el path es del administrador.
	 *
	 * @static
	 * 
	 * @deprecated
	 *
	 * @return boolean
	 */
	public static function check_path_admin()
	{
		trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

		return false;
	}

	/**
	 * Obtiene la base de la URL.
	 * 
	 * @deprecated
	 * @see HelperServer::getDomainHttp()
	 * 
	 * @return string
	 */
	public function baseurl()
	{
		trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

		return HelperServer::getDomainHttp();
	}

	/**
	 * Verifica si existe una peticion AJAX.
	 *
	 * @deprecated
	 * @see HelperServer::getDomainHttp()
	 * 
	 * @return boolean
	 */
	public static function exist_ajax_request()
	{
		trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

		HelperValidate::ajaxRequest();
	}

	/**
	 * No permitir peticiones por AJAX.
	 *
	 * @deprecated
	 * 
	 * @return void
	 */
	public static function no_ajax()
	{
		trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

		if (self::exist_ajax_request()) die();
	}

	/**
	 * Remplaza en un string, los key por los valores de un array.
	 *
	 * @static
	 *
	 * @param array $arr
	 * @param string $string
	 * 
	 * @deprecated
	 *
	 * @return string
	 */
	public static function replace($arr, $string)
	{
		trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

		return str_replace(array_keys($arr), array_values($arr), $string);
	}

	/**
	 * Remplaza la llamada de un fichero, por el fichero.
	 *
	 * @param string $buffer
	 * @param string $name_file
	 * @param string $path
	 * 
	 * @deprecated
	 *
	 * @return mixed
	 */
	public function include_file($buffer, $name_file, $path)
	{
		trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

		$route = "$path/$name_file.php";

		if (file_exists($route)) {
			ob_start();

			if (strpos($buffer, "%{{$name_file}}%") !== false) {
				require_once $route;
			}

			$new_file = ob_get_contents();

			ob_end_clean();

			return str_replace("%{{$name_file}}%", $new_file, $buffer);
		}
	}

	/**
	 * Obtiene un fichero.
	 *
	 * @param string $file
	 * 
	 * @deprecated
	 *
	 * @return mixed
	 */
	public function get_file($file, $arr = null)
	{
		trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

		if (!file_exists($file)) {
			return false;
		}

		if (!is_null($arr)) {
			foreach ($arr as $key => $value) global ${$key};
		}

		ob_start();

		require $file;

		$buffer = ob_get_contents();

		ob_end_clean();

		return $buffer;
	}

	/**
	 * Importa un fichero.
	 *
	 * @param string $path
	 * @param string $file_name
	 * @param string $file_type
	 * 
	 * @deprecated
	 * @see HelperFile::getAllContent()
	 *
	 * @return mixed
	 */
	public function import_file($path, $file_name, $file_type)
	{
		trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

		$supported_file_type = ['ini', 'php', 'html', 'json'];

		if (in_array($file_type, $supported_file_type)) {
			$file = "{$path}/{$file_name}.{$file_type}";

			if (file_exists($file)) {
				switch ($file_type) {
					case 'ini':
						return parse_ini_file($file, true);
						break;

					case 'php':
						require $file;
						break;

					case 'html':
						return $this->get_file($file);
						break;

					case 'json':
						return json_decode(file_get_contents($file), true);
						break;
				}
			}
		}
	}

	/**
	 * Importa un componente.
	 *
	 * @param array $argv
	 * 
	 * @deprecated
	 *
	 * @return bool
	 */
	public function import_component($argv = [])
	{
		trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);

		return false;
	}
}
