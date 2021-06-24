<?php namespace BuriPHP\System\Libraries;

/**
 *
 * @package BuriPHP.Libraries
 *
 * @since 1.1.0
 * @version 1.0.0
 * @license You can see LICENSE.txt
 *
 * @author David Miguel Gómez Macías < davidgomezmacias@gmail.com >
 * @copyright Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 */

defined('_EXEC') or die;

use \Medoo\Medoo;

class Database extends Medoo
{
	public function init( $options = [] ): array
	{
		return [
			// [required]
			'type' => \BuriPHP\Configuration::$db_type,
			'host' => \BuriPHP\Configuration::$db_host,
			'database' => \BuriPHP\Configuration::$db_name,
			'username' => \BuriPHP\Configuration::$db_user,
			'password' => \BuriPHP\Configuration::$db_pass,

			// [optional]
			'charset' => \BuriPHP\Configuration::$db_charset,
			'port' => \BuriPHP\Configuration::$db_port,
			'prefix' => \BuriPHP\Configuration::$db_prefix
		];
	}
}
