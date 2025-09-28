<?php

namespace Modules\Init;

use Core\Controller;
use Core\Response;

class ListController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new ListService();
    }

    public function getList()
    {
        $response = $this->service->list();

        return Response::sendResponse(statusCode: 200, data: $response);
    }

    public function insertElement()
    {
        $response = $this->service->insert($this->payload());

        if (isset($response['status']) && $response['status'] === 'error') {
            unset($response['status']);
            return Response::sendResponse(statusCode: 400, data: $response);
        }

        return Response::sendResponse(statusCode: 201, data: $response);
    }

    public function getElement()
    {
        $response = $this->service->get($this->routeParams['id']);

        if (is_null($response)) {
            return Response::sendResponse(statusCode: 404, message: 'No se encontro el elemento');
        }

        return Response::sendResponse(statusCode: 200, data: $response);
    }

    public function updateElement()
    {
        $response = $this->service->update($this->routeParams['id'], $this->payload());

        if (isset($response['status']) && $response['status'] === 'error') {
            unset($response['status']);
            return Response::sendResponse(statusCode: 400, data: $response);
        }

        return Response::sendResponse(statusCode: 200);
    }

    public function deleteElement()
    {
        $response = $this->service->delete($this->routeParams['id']);

        return Response::sendResponse(statusCode: 200, data: $response);
    }
}
