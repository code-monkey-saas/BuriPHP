<?php

namespace Modules\Init;

use Config\Settings;
use Core\Helpers\HelperDate;

class ListService
{
    protected $list;
    protected $repository;

    public function __construct()
    {
        $this->list = new ListModel();
        $this->repository = new ListRepository($this->list);
    }

    public function list()
    {
        $result = $this->repository->fetchAll();

        return $result ?: [];
    }

    public function insert($payload)
    {

        $this->list->email = $payload['email'];
        $this->list->username = $payload['username'];
        $this->list->status = $payload['status'];
        $this->list->createdAt = HelperDate::getCurrentDateWithTimezone(Settings::$timeZone, true);

        $this->list->listId = $this->repository->create(DATA: $this->list->toArray());

        if ($this->list->listId === false) {
            return null;
        }

        return $this->list->toArray();
    }

    public function get($listId)
    {
        $result = $this->repository->fetchByColumn(
            column: 'id',
            value: $listId
        );

        return $result ?: null;
    }

    public function update($listId, $payload)
    {
        $result = $this->repository->update(DATA: [
            'email' => trim($payload['email']),
            'username' => trim($payload['username']),
        ], WHERE: [
            'id' => $listId
        ]);

        if ($result === false) {
            return [
                'status' => 'error',
                'message' => 'Error al actualizar la lista.',
                'data' => $result
            ];
        }

        return ['status' => 'success'];
    }

    public function delete($listId)
    {
        $result = $this->repository->delete(WHERE: [
            'AND' => [
                'id' => $listId,
            ]
        ]);

        return $result ?: false;
    }
}
