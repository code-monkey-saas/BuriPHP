<?php

namespace User\Endpoints;

use Libraries\BuriPHP\Router;

class Endpoints extends Router
{
    public function endpoints()
    {
        $this->useVersion(1)
            ->addGroup('/api/__VERSION__/users')
            ->useController('User')
            ->post('/', 'create', ['Auth' => 'required', 'ContentType' => 'json']) // POST:/api/v1/user -> Nuevo usuario
            ->get('/', 'list', ['Auth' => 'required', 'ContentType' => 'json']) // GET:/api/v1/user -> Lista de usuarios
            ->get('/{user}', 'getUser', ['Auth' => 'required', 'ContentType' => 'json']) // GET:/api/v1/user/{user} -> Obtener usuario por id/username
            ->put('/{user}', 'update', ['Auth' => 'required', 'ContentType' => 'json']) // UPDATE:/api/v1/user/{user} -> Actualizar usuario por id/username
            ->patch('/{user}', 'update', ['Auth' => 'required', 'ContentType' => 'json']) // UPDATE:/api/v1/user/{user} -> Actualizar usuario por id/username
            ->delete('/{user}', 'delete', ['Auth' => 'required', 'ContentType' => 'json']) // DELETE:/api/v1/user/{user} -> Eliminar usuario por id/username
            ->assign();
    }
}
