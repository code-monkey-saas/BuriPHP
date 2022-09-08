<?php

namespace BuriPHP\Components\PlatformAccess;

defined('_EXEC') or die;

use \BuriPHP\System\Libraries\{Database, Security, Session, Format};

class Component
{
	const NAME = 'PlatformAccess';
	const PATH = PATH_COMPONENTS . Self::NAME . DIRECTORY_SEPARATOR;
	const LAYOUTS = Self::PATH . 'layouts' . DIRECTORY_SEPARATOR;

	private $database;
	private $security;
	private $format;

	public function __construct()
	{
		$this->database = new Database([
			// [required]
			'type' => \BuriPHP\Configuration::$db_type,
			'host' => \BuriPHP\Configuration::$db_host,
			'database' => \BuriPHP\Configuration::$db_name,
			'username' => \BuriPHP\Configuration::$db_user,
			'password' => \BuriPHP\Configuration::$db_pass,

			// [optional]
			'charset' => \BuriPHP\Configuration::$db_charset,
			'port' => \BuriPHP\Configuration::$db_port,
			'prefix' => \BuriPHP\Configuration::$db_prefix
		]);
		$this->security = new Security();
		$this->format = new Format();
	}

	public function get_user($data = [])
	{
		if (is_null($data))
			return false;

		$query = $this->database->select("users", [
			"[>]levels" => [
				"id_level" => "id"
			]
		], [
			"users.id",
			"users.username",
			"users.name",
			"users.email",
			"users.phone",
			"users.password",
			"users.permissions [Object]",
			"levels.code"
		], [
			"AND" => [
				"users.email" => $data['email']
			]
		]);

		return (isset($query[0]) && !empty($query[0])) ? $query[0] : null;
	}

	public function create_user($data = null)
	{
		if (is_null($data))
			return null;

		$this->database->insert('users', [
			'username' => $data['username'],
			'name' => $data['name'],
			'email' => $data['email'],
			'phone' => $data['phone'],
			'password' => $this->security->create_password($data['password']),
			'id_level' => 11,
			'permissions' => null
		]);

		if ($this->database->id())
			return true;
		else
			return null;
	}

	public function create_session($user = null)
	{
		if (is_null($user))
			return null;

		$token = $this->security->random_string(128);

		if ($this->log_session(['id_user' => $user['id'], 'token' => $token])) {
			setcookie('__token', $user['id'] . ':' . $this->security->random_string(4) . ':' . $token, time() + (\BuriPHP\Configuration::$cookie_lifetime * 30), "/");

			Session::create_session_login([
				'token' => $token,
				'id_user' => $user['id'],
				'user' => $user['username'],
				'last_access' => $this->format->get_date_hour(),
				'level' => $user['code']
			]);

			Session::set_value('session_permissions', $user['permissions']);
		}

		return true;
	}

	public function log_session($data = null, $action = 'login')
	{
		if (is_null($data))
			return false;

		switch ($action) {
			case 'login':
			default:
				$this->database->insert('sessions', [
					'id_user' => $data['id_user'],
					'token' => $data['token'],
					'login_date' => date('Y-m-d H:i:s'),
					'connection' => $this->security->get_client_info()
				]);

				if ($this->database->id())
					return true;
				else
					return false;
				break;

			case 'logout':
				$this->database->update('sessions', [
					'logout_date' => date('Y-m-d H:i:s')
				], [
					'AND' => [
						'id_user' => $data['id_user'],
						'token' => $data['token']
					]
				]);

				return true;
				break;
		}
	}

	public static function urls()
	{
		// Linea para importar las urls del componente al desarollo
		// $urls = array_merge($urls, Component::urls('PlatformAccess'));

		return [
			'/login' => [
				'component' => 'PlatformAccess',
				'controller' => 'Login',
				'method' => 'init',
				'onSession' => 'hidden'
			],
			'/register' => [
				'component' => 'PlatformAccess',
				'controller' => 'Register',
				'method' => 'init',
				'onSession' => 'hidden'
			],
			'/logout' => [
				'component' => 'PlatformAccess',
				'controller' => 'Logout',
				'method' => 'init',
				'private' => true
			]
		];
	}
}
