<?php

namespace Controllers;

use Libraries\BuriPHP\Controller;
use Libraries\Responses;

class User extends Controller
{
    public function create()
    {
        $request = $this->getPost();

        $response = $this->service->create($request);

        return Responses::response($response[0], $response[1]);
    }

    public function list()
    {
        $response = $this->service->getUsers();

        return Responses::response($response[0], $response[1]);
    }

    public function getUser()
    {
        $user = $this->getParams()['user'];
        $by = isset($this->getGet()['by']) ? $this->getGet()['by'] : 'id';

        $response = $this->service->getUser($user, $by);

        return Responses::response($response[0], $response[1]);
    }

    public function update()
    {
        $user = $this->getParams()['user'];
        $by = isset($this->getGet()['by']) ? $this->getGet()['by'] : 'id';

        $response = $this->service->update($this->getPayload(), $user, $by);

        return Responses::response($response[0], $response[1]);
    }

    public function delete()
    {
        $user = $this->getParams()['user'];
        $by = isset($this->getGet()['by']) ? $this->getGet()['by'] : 'id';

        $response = $this->service->delete($user, $by);

        return Responses::response($response[0], $response[1]);
    }
}