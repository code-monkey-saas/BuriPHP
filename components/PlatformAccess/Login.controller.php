<?php

namespace BuriPHP\Components\PlatformAccess\Controllers;

defined('_EXEC') or die;

use \BuriPHP\Components\PlatformAccess\{Component};

class Login
{
	use \BuriPHP\System\Libraries\Controller;

	private $component;

	public function ___construct()
	{
		$this->component = new Component();
	}

	public function init()
	{
		if ($this->format->exist_ajax_request()) {
			header('Content-type: application/json');

			$post['email'] = (isset($_POST['email']) && !empty($_POST['email'])) ? $_POST['email'] : null;
			$post['password'] = (isset($_POST['password']) && !empty($_POST['password'])) ? $_POST['password'] : null;

			$labels = [];

			if (is_null($post['email'])) array_push($labels, ['email', 'Por favor, escriba el correo electrónico con el que está registrado.']);
			if (is_null($post['password'])) array_push($labels, ['password', 'Por favor, escriba una contraseña.']);

			if (empty($labels)) {
				$user = $this->component->get_user($post);

				if (is_null($user)) {
					return json_encode([
						'status' => 'labels_error',
						'labels' => [
							['email', 'El correo electrónico no se encuentra registrado.']
						]
					], JSON_PRETTY_PRINT);
				} else {
					$crypto = explode(':', $user['password']);
					$check_password = ($this->security->create_hash('sha1', $post['password'] . $crypto[1]) === $crypto[0]) ? true : false;

					if ($check_password == false) {
						return json_encode([
							'status' => 'labels_error',
							'labels' => [
								['password', 'La contraseña es incorrecta.']
							],
						], JSON_PRETTY_PRINT);
					} else {
						if ($this->component->create_session($user)) {
							return json_encode([
								"status" => "OK",
								"redirect" => '/'
							], JSON_PRETTY_PRINT);
						} else {
							return json_encode([
								'status' => 'fatal_error',
								'message' => 'Se produjo un error desconocido, vuelve a intentarlo.'
							], JSON_PRETTY_PRINT);
						}
					}
				}
			} else {
				return json_encode([
					'status' => 'labels_error',
					'labels' => $labels,
					'message' => 'Por favor, inicie sesión.'
				], JSON_PRETTY_PRINT);
			}
		} else {
			define('_title', 'Iniciar sesión en {$_webpage}');
			return $this->view->render(Component::LAYOUTS . 'login.php');
		}
	}
}