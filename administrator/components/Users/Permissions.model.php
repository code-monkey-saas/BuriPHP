<?php namespace BuriPHP\Administrator\Components\Users\Models;

defined('_EXEC') or die;

class Permissions
{
    use \BuriPHP\System\Libraries\Model;

    public function create_permission( $data = null )
	{
		if ( is_null($data) )
			return null;

		$this->database->insert('permissions', [
			'code' => $data['code'],
			'title' => $data['title']
		]);
	}

    public function delete_permission( $data = null )
	{
		if ( is_null($data) )
			return null;

		$this->database->delete('permissions', [
			'id' => $data['id']
		]);
	}
}
