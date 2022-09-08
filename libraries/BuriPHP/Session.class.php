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

use Libraries\BuriPHP\Helpers\HelperSession;

class Session
{
	/**
	 * Crean el nombre de la sesion.
	 *
	 * @static
	 *
	 * @param string $str
	 * 
	 * @deprecated
	 *
	 * @return void
	 */
	static function name($str = 'BuriPHP')
	{
		if (empty(ini_get('session.name')))
			session_name($str);
	}

	/**
	 * Inicia la sesion.
	 *
	 * @static
	 * 
	 * @deprecated
	 * @see HelperSession::init()
	 *
	 * @return void
	 */
	static function init()
	{
		HelperSession::init();
	}

	/**
	 * Destruye la sesion.
	 *
	 * @static
	 * 
	 * @deprecated
	 * @see HelperSession::destroy()
	 *
	 * @return  void
	 */
	static function destroy()
	{
		HelperSession::destroy();
	}

	/**
	 * Obtiene una variable de sesion.
	 *
	 * @static
	 *
	 * @param string $str
	 * 
	 * @deprecated
	 * @see HelperSession::getValue()
	 *
	 * @return void
	 */
	static function get_value($str)
	{
		return HelperSession::getValue($str);
	}

	/**
	 * Agrega una variable de sesion.
	 *
	 * @static
	 *
	 * @param string $str
	 * @param string $value
	 * 
	 * @deprecated
	 * @see HelperSession::setValue()
	 *
	 * @return  void
	 */
	static function set_value($str, $value)
	{
		HelperSession::setValue($str, $value);
	}

	/**
	 * Destruye una variable de sesion.
	 *
	 * @static
	 *
	 * @param string $str
	 * 
	 * @deprecated
	 * @see HelperSession::removeValue()
	 *
	 * @return void
	 */
	static function unset_value($str): void
	{
		HelperSession::removeValue($str);
	}

	/**
	 * Verifica si existe una variable de sesion.
	 *
	 * @static
	 *
	 * @param string $str
	 * 
	 * @deprecated
	 * @see HelperSession::existsValue()
	 *
	 * @return void
	 */
	static function exists_var($str)
	{
		return HelperSession::existsValue($str);
	}

	/**
	 * Verifica si existe una sesion.
	 *
	 * @static
	 * 
	 * @deprecated
	 * @see HelperSession::isActive()
	 *
	 * @return void
	 */
	static function exists()
	{
		return HelperSession::isActive();
	}

	/**
	 * Crea las variables de sesion
	 *
	 * @static
	 *
	 * @param array $arr
	 * 
	 * @deprecated
	 *
	 * @return boolean
	 */
	static function create_session_login($arr = [])
	{
		self::set_value('__token', $arr['token']);
		self::set_value('__id_user', $arr['id_user']);
		self::set_value('__user', $arr['user']);
		self::set_value('__last_access', Format::get_date_hour());
		self::set_value('__level', $arr['level']);

		return true;
	}
}
