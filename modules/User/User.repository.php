<?php

namespace Repositories;

use Libraries\BuriPHP\Database;
use Libraries\BuriPHP\Helpers\HelperDate;
use Libraries\BuriPHP\Helpers\HelperDateTime;
use Libraries\BuriPHP\Repository;

class User extends Repository
{
    private $table = 'USER';
    private $object = [
        'ID (USER_ID)',
        'EMAIL',
        'USERNAME',
        'NAME',
        'PHONE [Int]',
        'PERMISSION_ID [Int]',
        'DATE_CREATED',
    ];

    /**
     * Create user
     */
    public function create($data)
    {
        $data['DATE_CREATED'] = HelperDateTime::getNowTimezone();
        $this->database->insert($this->table, Database::camelToSnake($data));

        return $this->database->id();
    }

    /**
     * Read user
     * 
     * @param array $args options availble id(int), object(array), where(array)
     */
    public function read(...$args)
    {
        $response = [];
        $object = $this->object;
        $where = [];

        if (isset($args['object']) && is_array($args['object'])) {
            foreach ($args['object'] as $value) {
                $object[] = "{$this->table}." . Database::camelToSnake($value);
            }
        }

        if (isset($args['where']) && is_array($args['where'])) {
            foreach ($args['where'] as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $_key => $_value) {
                        if (!in_array(strtoupper($key), ["ORDER", "AND", "OR"])) {
                            $key = "{$this->table}." . Database::camelToSnake($key);
                        } else {
                            $key = Database::camelToSnake($key);
                        }

                        $where[$key]["{$this->table}." . Database::camelToSnake($_key)] = $_value;
                    }
                } else {
                    $where["{$this->table}." . Database::camelToSnake($key)] = $value;
                }
            }
        }

        if (isset($args['id'])) {
            $where['ID'] = (int) $args['id'];
        }

        $this->database->select($this->table, $object, $where, function ($data) use (&$response) {
            if (array_key_exists('DATE_CREATED', $data)) {
                $data['DATE_CREATED'] = [
                    'ISO8601' => date(DATE_ISO8601, strtotime($data['DATE_CREATED'])),
                    'formatted' => HelperDate::getDate($data['DATE_CREATED'], true),
                    'date' => HelperDateTime::getOnlyDate($data['DATE_CREATED']),
                    'time' => HelperDateTime::getOnlyTime($data['DATE_CREATED'])
                ];
            }

            $response[] = $data;
        });

        if (isset($args['id'])) {
            return (isset($response[0])) ? Database::snakeToCamel($response)[0] : [];
        } else {
            return Database::snakeToCamel($response);
        }
    }

    /**
     * Update user
     * 
     * @param array $data
     * @param string $whereValue
     * @param string $whereRow default ID
     */
    public function update($data, $whereValue, $whereRow = "ID")
    {
        $response = $this->database->update($this->table, Database::camelToSnake($data), [
            Database::camelToSnake($whereRow) => $whereValue
        ]);

        return $response->rowCount();
    }

    /**
     * Delete user
     * 
     * @param string $whereValue
     * @param string $whereRow default ID
     */
    public function delete($whereValue, $whereRow = "ID")
    {
        $response = $this->database->delete($this->table, [
            Database::camelToSnake($whereRow) => $whereValue
        ]);

        return $response->rowCount();
    }
}
