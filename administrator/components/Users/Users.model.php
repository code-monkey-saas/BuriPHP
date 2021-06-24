<?php namespace BuriPHP\Administrator\Components\Users\Models;

defined('_EXEC') or die;

class Users
{
    use \BuriPHP\System\Libraries\Model;

    public function read_all_users()
	{
		return $this->database->select("users", [
			"[>]levels" => [
				"id_level" => "id"
			]
		], [
			"users.id",
			"users.name",
			"users.username",
			"users.email",
			"levels.title (level)"
		], [
			"ORDER" => [
				'users.id' => 'ASC'
			]
		]);
	}

    public function read_all_levels()
	{
		return $this->database->select("levels", '*');
	}

    public function read_all_permissions()
	{
		return $this->database->select("permissions", '*');
	}

    public function read_user( $id = null )
	{
		$response = $this->database->select("users", [
			"[>]levels" => [
				"id_level" => "id"
			]
		], [
			"users.id",
			"users.username",
			"users.name",
			"users.email",
			"users.phone",
			"levels.id (level)",
			"users.permissions [Object]",
		], [
			"users.id" => $id,
			"ORDER" => [
				'users.id' => 'ASC'
			]
		]);

		if ( isset($response[0]) && !empty($response[0]) )
		{
			$response = $response[0];

			$response['prefix_phone'] = $response['phone'][0].$response['phone'][1];
			$response['phone'] = substr($response['phone'], 2);

			return $response;
		}
		else return [];
	}

    public function create_user( $data = null )
	{
		if ( is_null($data) )
			return null;

		$this->database->insert('users', [
			'username' => $data['username'],
			'name' => $data['name'],
			'email' => $data['email'],
			'phone' => $data['prefix'].$data['phone'],
			'password' => $this->security->create_password($data['password']),
			'id_level' => $data['level'],
			'permissions' => $data['permissions']
		]);
	}

    public function update_user( $data = null )
	{
		if ( is_null($data) )
			return null;

		$update = [
			'username' => $data['username'],
			'name' => $data['name'],
			'email' => $data['email'],
			'phone' => $data['prefix'].$data['phone'],
			'id_level' => $data['level'],
			'permissions' => $data['permissions']
		];

		if ( !is_null($data['password']) )
			$update['password'] = $this->security->create_password($data['password']);

		$this->database->update('users', $update, [
			'id' => $data['id']
		]);
	}

    public function delete_user( $data = null )
	{
		if ( is_null($data) )
			return null;

		$this->database->delete('users', [
			'id' => $data['id']
		]);
	}
}
