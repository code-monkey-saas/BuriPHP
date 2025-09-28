<?php

namespace Modules\Init;

use Core\RouterRegistry;

class InitRouter extends RouterRegistry
{
    public function registerRoutes()
    {
        // TODO: Obtener una lista
        $this->router->get('/init', 'List', 'getList');

        // TODO: Insertar un elemento a la lista, requiere autenticación
        $this->router->post('/init', 'List', 'insertElement', ['requireAuth' => true]);

        // TODO: Obtener un elemento de una lista por :id
        $this->router->get('/init/:id', 'List', 'getElement');

        // TODO: Actualizar un elemento de una lista por :id, requiere autenticación
        $this->router->put('/init/:id', 'List', 'updateElement', ['requireAuth' => true]);

        // TODO: Eliminar un elemento de una lista por :id, requiere autenticación
        $this->router->delete('/init/:id', 'List', 'deleteElement', ['requireAuth' => true]);
    }
}
