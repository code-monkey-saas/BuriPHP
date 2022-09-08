<?php namespace BuriPHP\Components\PlatformAccess\Controllers;

defined('_EXEC') or die;

use \BuriPHP\Components\PlatformAccess\{Component};
use \BuriPHP\System\Libraries\{Session};

class Logout
{
	use \BuriPHP\System\Libraries\Controller;

	public function init()
	{
		(new Component())->log_session([ 'id_user' => Session::get_value('__id_user'), 'token' => Session::get_value('__token') ], 'logout');

		setcookie("__token", null, -1, '/');
		Session::destroy();

		header("Location: /iniciar-sesion");
	}
}
