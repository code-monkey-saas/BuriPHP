<?php

namespace Modules\Init;

use Core\Model;

class ListModel extends Model
{
    public string $table = 'LIST';
    public string $primaryKey = 'ID';

    public $columns = [
        'ID (LIST_ID)' => ["BIGINT UNSIGNED", "AUTO_INCREMENT", "PRIMARY KEY"],
        'EMAIL' => ["TEXT", "NULL", "UNIQUE"],
        'USERNAME' => ["TEXT", "NULL"],
        'STATUS' => ["SET('ACTIVE', 'INACTIVE', 'DELETED')", "NOT NULL", "DEFAULT 'ACTIVE'"],
        'CREATED_AT' => ["DATETIME", "NOT NULL", "DEFAULT CURRENT_TIMESTAMP"],
    ];
}
