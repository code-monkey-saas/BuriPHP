<?php

namespace Authentication\Endpoints;

use Libraries\BuriPHP\Router;

class Endpoints extends Router
{
    public function endpoints()
    {
        $this->useVersion(1)
            ->addGroup('/api/__VERSION__/register')
            ->useController('Register')
            ->post('/', 'register', ['OnlyPublic' => true, 'ContentType' => 'json']) // POST:/api/v1/register/ -> Registro
            ->assign();

        $this->useVersion(1)
            ->addGroup('/api/__VERSION__/login')
            ->useController('Login')
            ->post('/', 'login', ['OnlyPublic' => true, 'ContentType' => 'json']) // POST:/api/v1/login/ -> Iniciar sesión
            ->assign();

        $this->useVersion(1)
            ->addGroup('/api/__VERSION__/logout')
            ->useController('Logout')
            ->get('/', 'logout', ['Auth' => 'required', 'ContentType' => 'json']) // GET:/api/v1/logout -> Cierre de sesion
            ->assign();
    }
}
