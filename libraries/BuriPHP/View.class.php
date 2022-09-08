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

class View
{
	public $dependencies;
	public $security;
	public $format;

	/**
	 * Constructor.
	 *
	 * @return  void
	 */
	public function __construct()
	{
		$this->dependencies = new Dependencies();
		$this->security = new Security();
		$this->format = new Format();
	}

	/**
	 * Renderiza el html
	 *
	 * @param	object    $controller    Controlador principal.
	 * @param	mixed     $layouts		 Informacion de los layouts a mostrar.
	 *
	 * @return  string
	 */
	public function render($body = null, $base = null): bool | string
	{
		foreach ($GLOBALS as $key => $value) {
			if (
				$key != 'GLOBALS' ||
				$key != '_SERVER' ||
				$key != '_GET' ||
				$key != '_POST' ||
				$key != '_FILES' ||
				$key != '_COOKIE' ||
				$key != '_SESSION' ||
				$key != '_REQUEST' ||
				$key != '_ENV'
			) {
				global ${$key};
			}
		}

		if (is_null($body))
			return false;

		// Get file body
		ob_start();
		require Security::DS($body);
		$renderBody = ob_get_contents();
		ob_end_clean();

		// Get file base html
		ob_start();

		if (is_null($base)) {
			require Security::DS((Format::check_path_admin() ? PATH_ADMINISTRATOR_LAYOUTS : PATH_LAYOUTS) . "/base.php");
		}

		if ($base != false && !is_null($base)) {
			require Security::DS($base);
		}

		$renderBase = ob_get_contents();

		ob_end_clean();

		if ($base === false) {
			return $renderBody;
		} else {
			return str_replace('{{renderView}}', $renderBody, $renderBase);
		}
	}
}
