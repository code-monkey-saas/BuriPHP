<?php namespace BuriPHP\System\Libraries;

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

class Framework
{
	/**
	* Framework.
	*
	* @var const
	*/
	const PRODUCT = 'BuriPHP';

	/**
	* Versión del release.
	*
	* @var const
	*/
	const RELEASE = '1.0.0';

	/**
	* Estado del framework.
	*
	* @var const
	*/
	const STATUS = 'Production';

	/**
	* Fecha del release.
	*
	* @var const
	*/
	const RELEASE_DATE = '24 Jun 21';

	/**
	* Hora del release.
	*
	* @var const
	*/
	const RELEASE_TIME = '16:10';

	/**
	* Zona horaria del release.
	*
	* @var const
	*/
	const RELEASE_TIME_ZONE = 'GMT';

	/**
	* Copyright.
	*
	* @var const
	*/
	const COPYRIGHT = 'Copyright (C) CodeMonkey. All Rights Reserved.';

	/**
	* Constructor.
	*
	* @return  void
	*/
	public function __construct()
	{
		$this->file_configuration();
		$this->error_reporting(\BuriPHP\Configuration::$error_reporting);

		Session::name();
		Session::init();
		Format::set_time_zone();
	}

	/**
	* Importa el archivo de configuración.
	*
	* @return  void
	*/
	private function file_configuration()
	{
		if ( !file_exists(PATH_CONFIGURATION) || (filesize(PATH_CONFIGURATION) < 10) )
		{
			Errors::system(null, "<i>configuration.php</i> file not found.");
		}
		else require_once PATH_CONFIGURATION;
	}

	/**
	* Establece cuáles errores de PHP son notificados.
	*
	* @param	string    $str    valor de configuración.
	*
	* @return  integer
	*/
	private function error_reporting( $str )
	{
		$case = [];

		switch ( $str )
		{
			case 'none':
			case '0':
			$case['error'] = '0';
			$case['ini'] = '0';
			break;

			case 'simple':
			$case['error'] = 'E_ERROR | E_WARNING | E_PARSE';
			$case['ini'] = '0';
			break;

			case 'maximum':
			$case['error'] = 'E_ALL';
			$case['ini'] = '1';
			break;

			case 'development':
			$case['error'] = '-1';
			$case['ini'] = '1';
			break;

			case 'default':
			case '-1':
			default:
			$case['error'] = '';
			$case['ini'] = '0';
			break;
		}

		return error_reporting($case['error']) . ini_set('display_errors', $case['ini']);
	}

}
