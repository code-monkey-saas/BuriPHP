<?php namespace BuriPHP\Administrator\Components\PlatformAccess\Controllers;

defined('_EXEC') or die;

use \BuriPHP\Administrator\Components\PlatformAccess\{Component};
use \BuriPHP\System\Libraries\{Session};

class Login
{
	use \BuriPHP\System\Libraries\Controller;

	public function init()
	{
		/* Action Ajax ------------------------------------------------------ */
		if ( $this->format->exist_ajax_request() )
		{
			header('Content-type: application/json');

			$post['username'] = ( isset($_POST['username']) && !empty($_POST['username']) ) ? $_POST['username'] : null;
			$post['password'] = ( isset($_POST['password']) && !empty($_POST['password']) ) ? $_POST['password'] : null;

			$labels = [];

			if ( is_null($post['username']) )
				array_push($labels, ['username', 'Por favor, escriba el correo electrónico con el que está registrado.']);

			if ( is_null($post['password']) )
				array_push($labels, ['password', 'Por favor, escriba una contraseña.']);

			if ( !empty($labels) )
			{
				echo json_encode([
					'status' => 'error',
					'labels' => $labels,
					'message' => 'Por favor, inicie sesión.'
				], JSON_PRETTY_PRINT);
			}
			else
			{
				$user = $this->model->get_user($post);

				if ( is_null($user) )
				{
					echo json_encode([
						'status' => 'error',
						'labels' => [
							['username', 'El correo electrónico no se encuentra registrado.']
						]
					], JSON_PRETTY_PRINT);
				}
				else
				{
					$crypto = explode(':', $user['password']);
					$check_password = ( $this->security->create_hash('sha1', $post['password'] . $crypto[1]) === $crypto[0] ) ? true : false;

					if ( $check_password == false )
					{
						echo json_encode([
							'status' => 'error',
							'labels' => [
								['password', 'La contraseña es incorrecta.']
							],
						], JSON_PRETTY_PRINT);
					}
					else
					{
						$token = $this->security->random_string(128);

						if ( (new Component())->log_session([ 'id_user' => $user['id'], 'token' => $token ]) )
						{
							setcookie('__token', $user['id'] .':'. $this->security->random_string(4) .':'. $token, time() + (\BuriPHP\Configuration::$cookie_lifetime * 30), "/");

							Session::create_session_login([
								'token' => $token,
								'id_user' => $user['id'],
								'user' => $user['username'],
								'last_access' => $this->format->get_date_hour(),
								'level' => $user['code']
							]);

							Session::set_value('session_permissions', $user['permissions']);
						}

						echo json_encode([
							"status" => "OK",
							"redirect" => 'index.php'
						], JSON_PRETTY_PRINT);
					}
				}
			}
		}
		else
		{
			define('_title', 'Iniciar Sesion - Panel de Control - '. \BuriPHP\Configuration::$web_page);
			echo $this->view->render(Component::LAYOUTS . 'login.php', false, false);
		}
	}
}
