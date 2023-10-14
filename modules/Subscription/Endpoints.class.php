<?php

namespace Subscription\Endpoints;

use Libraries\BuriPHP\Router;

class Endpoints extends Router
{
    public function endpoints()
    {
        $this->useVersion(1)
            ->addGroup('/api/__VERSION__/subscriptions')
            ->useController('Subscription')
            ->post('/', 'create', ['Auth' => 'required', 'ContentType' => 'json']) // POST:/api/v1/subscriptions -> Nueva suscripción
            ->get('/', 'list', ['Auth' => 'required', 'ContentType' => 'json']) // GET:/api/v1/subscriptions -> Lista de suscripciones
            ->get('/{user}', 'get', ['Auth' => 'required', 'ContentType' => 'json']) // GET:/api/v1/subscriptions/{user} -> Obtener suscripción por id/username
            ->put('/{user}', 'update', ['Auth' => 'required', 'ContentType' => 'json']) // UPDATE:/api/v1/subscriptions/{user} -> Actualizar suscripción por id/username
            ->patch('/{user}', 'update', ['Auth' => 'required', 'ContentType' => 'json']) // UPDATE:/api/v1/subscriptions/{user} -> Actualizar suscripción por id/username
            ->delete('/{user}', 'delete', ['Auth' => 'required', 'ContentType' => 'json']) // DELETE:/api/v1/subscriptions/{user} -> Eliminar suscripción por id/username
            ->assign();
    }
}
