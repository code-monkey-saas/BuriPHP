<?php namespace BuriPHP\Administrator\Components\PlatformAccess;

use \BuriPHP\System\Libraries\{Database,Security};

defined('_EXEC') or die;

class Component
{
    const NAME = 'PlatformAccess';
    const PATH = PATH_ADMINISTRATOR_COMPONENTS . Self::NAME . DIRECTORY_SEPARATOR;
    const LAYOUTS = Self::PATH .'layouts'. DIRECTORY_SEPARATOR;

    private $database;
    private $security;

    public function __construct()
    {
        $this->database = new Database();
        $this->security = new Security();
    }

    public function log_session( $data = null, $action = 'login' )
	{
		if ( is_null($data) )
			return false;

		switch ( $action )
		{
			case 'login':
			default:
				$this->database->insert('sessions', [
					'id_user' => $data['id_user'],
					'token' => $data['token'],
					'login_date' => date('Y-m-d H:i:s'),
					'connection' => $this->security->get_client_info()
				]);

				if ( $this->database->id() )
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
}
