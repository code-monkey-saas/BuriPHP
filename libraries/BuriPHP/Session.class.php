<?php

namespace BuriPHP\System\Libraries;

/**
 *
 * @package BuriPHP.Libraries
 *
 * @since 1.0.0
 * @version 1.1.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

defined('_EXEC') or die;

class Session
{
	/**
	 * Crean el nombre de la sesion.
	 *
	 * @static
	 *
	 * @param	string    $str    Nombre de la sesion.
	 *
	 * @return  void
	 */
	static function name($str = 'BuriPHP'): void
	{
		if (empty(ini_get('session.name')))
			session_name($str);
	}

	/**
	 * Inicia la sesion.
	 *
	 * @static
	 *
	 * @param	array    $array    Parametros de sesion.
	 *
	 * @return  void
	 */
	static function init($array = false): void
	{
		if ($array == false) $array = ['cookie_lifetime' => \BuriPHP\Configuration::$cookie_lifetime];

		@session_start($array);
	}

	/**
	 * Destruye la sesion.
	 *
	 * @static
	 *
	 * @return  void
	 */
	static function destroy(): void
	{
		@session_destroy();
	}

	/**
	 * Obtiene una variable de sesion.
	 *
	 * @static
	 *
	 * @param	string    $str    Nombre de la variable.
	 *
	 * @return  void
	 */
	static function get_value($str): string | array
	{
		return !empty($_SESSION[$str]) ? $_SESSION[$str] : [];
	}

	/**
	 * Agrega una variable de sesion.
	 *
	 * @static
	 *
	 * @param	string    $str    Nombre de la variable.
	 * @param	string    $value  Valor de la variable.
	 *
	 * @return  void
	 */
	static function set_value($str, $value): void
	{
		$_SESSION[$str] = $value;
	}

	/**
	 * Destruye una variable de sesion.
	 *
	 * @static
	 *
	 * @param	string    $str    Nombre de la variable.
	 *
	 * @return  void
	 */
	static function unset_value($str): void
	{
		if (isset($_SESSION[$str])) unset($_SESSION[$str]);
	}

	/**
	 * Verifica si existe una variable de sesion.
	 *
	 * @static
	 *
	 * @param	string    $str    Nombre de la variable.
	 *
	 * @return  void
	 */
	static function exists_var($str): bool
	{
		return (isset($_SESSION[$str]) && !empty($_SESSION[$str])) ? true : false;
	}

	/**
	 * Verifica si existe una sesion.
	 *
	 * @static
	 *
	 * @return  void
	 */
	static function exists(): bool
	{
		return (sizeof($_SESSION) > 0) ? true : false;
	}

	/**
	 * Crea las variables de sesion
	 *
	 * @static
	 *
	 * @param	array    $arr    Contenido de las variables.
	 *
	 * @return  boolean
	 */
	static function create_session_login($arr = []): bool
	{
		self::set_value('__token', $arr['token']);
		self::set_value('__id_user', $arr['id_user']);
		self::set_value('__user', $arr['user']);
		self::set_value('__last_access', Format::get_date_hour());
		self::set_value('__level', $arr['level']);

		return true;
	}
}
