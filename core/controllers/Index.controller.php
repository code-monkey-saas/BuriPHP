<?php namespace BuriPHP\Core\Controllers;

defined('_EXEC') or die;

class Index
{
	use \BuriPHP\System\Libraries\Controller;

	public function init()
	{
		return $this->view->render(PATH_LAYOUTS . 'Index/index.php');
	}
}
