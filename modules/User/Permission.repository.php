<?php

namespace Repositories;

use Libraries\BuriPHP\Database;
use Libraries\BuriPHP\Repository;

class Permission extends Repository
{
    private $table = 'PERMISSION';
    private $object = [
        'ID (PERMISSION_ID)',
        'PERMISSION',
        'DESCRIPTION',
        'VALUE'
    ];

    public function getPermission(...$args)
    {
        $where = [];

        if (isset($args['id'])) {
            $where['ID'] = (int) $args['id'];
        }

        $response = $this->database->select($this->table, $this->object, $where);

        if (isset($args['id'])) {
            return (isset($response[0])) ? Database::snakeToCamel($response)[0] : null;
        } else {
            return Database::snakeToCamel($response);
        }
    }
}
