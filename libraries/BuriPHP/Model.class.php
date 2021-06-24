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

trait Model
{
	/**
	*
	* @var object
	*/
	public $database;

	/**
	*
	* @var object
	*/
	public $security;

	/**
	*
	* @var object
	*/
	public $format;

	/**
	*
	* @var string
	*/
	public $_lang;

	/**
	* Constructor.
	*
	* @return  void
	*/
	public function __construct()
	{
		if ( \BuriPHP\Configuration::$db_state === true )
			$this->database  = new Database();

		$this->security  = new Security;
		$this->format  	 = new Format;
		$this->_lang = Session::get_value('_lang');
	}

}
