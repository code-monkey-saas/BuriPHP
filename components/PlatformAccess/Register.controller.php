<?php namespace BuriPHP\Components\PlatformAccess\Controllers;

defined('_EXEC') or die;

use \BuriPHP\Components\PlatformAccess\{Component};

class Register
{
	use \BuriPHP\System\Libraries\Controller;

	private $component;

	public function ___construct()
	{
		$this->component = new Component();
	}

	public function init()
	{
		if ( $this->format->exist_ajax_request() )
		{
			header('Content-type: application/json');

			$post['username'] = strtoupper($this->security->random_string(6));
			$post['name'] = ( isset($_POST['name']) && !empty($_POST['name']) ) ? $_POST['name'] : null;
			$post['email'] = ( isset($_POST['email']) && !empty($_POST['email']) ) ? $_POST['email'] : null;
			$post['phone'] = ( isset($_POST['phone']) && !empty($_POST['phone']) ) ? str_replace(['(',')','-',' '], '', $_POST['prefix'] . $_POST['phone']) : null;
			$post['password'] = ( isset($_POST['password']) && !empty($_POST['password']) ) ? $_POST['password'] : null;
			$post['r-password'] = ( isset($_POST['r-password']) && !empty($_POST['r-password']) ) ? $_POST['r-password'] : null;

			$labels = [];

			if ( is_null($post['name']) ) array_push($labels, ['name', '']);
			if ( is_null($post['email']) ) array_push($labels, ['email', '']);
			if ( is_null($post['phone']) ) array_push($labels, ['phone', '']);
			if ( strlen($post['password']) < 8 ) array_push($labels, ['password', 'Tu contraseña debe ser mayor a 8 caracteres.']);
			if ( $post['password'] !== $post['r-password'] ) array_push($labels, ['r-password', 'Las contraseñas no son iguales.']);
			if ( !empty($this->component->get_user($post)) ) array_push($labels, ['email', 'Este correo electrónico ya se encuentra registrado.']);

			if ( empty($labels) )
			{
				if ( $this->component->create_user($post) )
				{
					if ( $this->component->create_session( $this->component->get_user($post) ) )
					{
						echo json_encode([
							"status" => "OK",
							"redirect" => '/dashboard'
						], JSON_PRETTY_PRINT);
					}
					else
					{
						echo json_encode([
							'status' => 'fatal_error',
							'message' => 'Se produjo un error desconocido, vuelve a intentarlo.'
						], JSON_PRETTY_PRINT);
					}
				}
				else
				{
					echo json_encode([
						'status' => 'fatal_error',
						'message' => 'Se produjo un error desconocido, vuelve a intentarlo.'
					], JSON_PRETTY_PRINT);
				}
			}
			else
			{
				echo json_encode([
					'status' => 'error',
					'labels' => $labels
				], JSON_PRETTY_PRINT);
			}
		}
		else
		{
			global $ladas;

			$ladas = $this->format->import_file(PATH_INCLUDES, 'codes_countries_iso', 'json');

			define('_title', 'Registrarme en {$_webpage}');
			return $this->view->render(Component::LAYOUTS.'register.php');
		}
	}
}
