<?php namespace BuriPHP\Administrator\Components\PlatformAccess\Models;

defined('_EXEC') or die;

class Login
{
    use \BuriPHP\System\Libraries\Model;

    public function get_user( $data = null )
	{
		if ( is_null($data) )
			return false;

		$query = $this->database->select("users", [
			"[>]levels" => [
				"id_level" => "id"
			]
		], [
			"users.id",
			"users.username",
			"users.email",
			"users.password",
			"users.permissions [Object]",
			"levels.code"
		], [
			"AND" => [
				'OR' => [
					"users.email" => $data['username'],
					"users.username" => $data['username']
				]
			]
		]);

		return ( isset($query[0]) && !empty($query[0]) ) ? $query[0] : null;
	}
}
