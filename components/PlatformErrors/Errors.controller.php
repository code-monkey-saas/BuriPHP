<?php namespace BuriPHP\Components\PlatformErrors\Controllers;

defined('_EXEC') or die;

use \BuriPHP\Components\PlatformErrors\{Component};

class Errors
{
	use \BuriPHP\System\Libraries\Controller;

	public function not_found()
	{
		header("HTTP/1.0 404 Not Found");

		define('_title', 'Error 404 - Panel de Control - '. \BuriPHP\Configuration::$web_page);
		echo $this->view->render(Component::LAYOUTS . 'not_found.php');
	}
}
