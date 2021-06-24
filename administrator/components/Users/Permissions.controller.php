<?php namespace BuriPHP\Administrator\Components\Users\Controllers;

defined('_EXEC') or die;

class Permissions
{
	use \BuriPHP\System\Libraries\Controller;

	public function create_permission()
	{
		$post['code'] = ( isset($_POST['code']) && !empty($_POST['code']) ) ? $_POST['code'] : null;
		$post['title'] = ( isset($_POST['title']) && !empty($_POST['title']) ) ? $_POST['title'] : null;

		$labels = [];

		if ( is_null($post['code']) )
			array_push($labels, ['code', 'Debes escribir el código del permiso.']);

		if ( is_null($post['title']) )
			array_push($labels, ['title', 'Escribe una pequeña descripción.']);

		if ( !empty($labels) )
		{
			echo json_encode([
				'status' => 'error',
				'labels' => $labels
			], JSON_PRETTY_PRINT);
		}
		else
		{
			$this->model->create_permission($post);

			echo json_encode([
				'status' => 'OK',
				'redirect' => 'index.php/users'
			], JSON_PRETTY_PRINT);
		}
	}

	public function delete_permission()
	{
		header('Content-type: application/json');

		$post['id'] = ( isset($_POST['id']) && !empty($_POST['id']) ) ? $_POST['id'] : null;

		if ( is_null($post['id']) )
		{
			echo json_encode([
				'status' => 'error',
				'message' => 'Debes elegir un permiso.'
			], JSON_PRETTY_PRINT);
		}
		else
		{
			$this->model->delete_permission($post);

			echo json_encode([
				'status' => 'OK',
				'redirect' => 'index.php?c=users'
			], JSON_PRETTY_PRINT);
		}
	}
}
