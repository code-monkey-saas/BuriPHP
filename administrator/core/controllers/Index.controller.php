<?php namespace BuriPHP\Administrator\Core\Controllers;

defined('_EXEC') or die;

class Index
{
	use \BuriPHP\System\Libraries\Controller;

	public function index()
	{
		define('_title', 'Dashboard');
		return $this->view->render(PATH_ADMINISTRATOR_LAYOUTS . 'Pages/index.php');
	}

	public function help()
	{
		define('_title', 'Framework Valkyrie');
		return $this->view->render(PATH_ADMINISTRATOR_LAYOUTS . 'Pages/help.php');
	}
}
