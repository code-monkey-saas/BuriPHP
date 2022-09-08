<?php

namespace BuriPHP\Core\Controllers;

defined('_EXEC') or die;

class Pages
{
	use \BuriPHP\System\Libraries\Controller;

	public function index()
	{
		define('_title', 'Inicio - ' . \BuriPHP\Configuration::$web_page);
		return $this->view->render(PATH_LAYOUTS . 'Pages/index.php');
	}
}