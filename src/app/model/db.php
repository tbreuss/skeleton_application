<?php

namespace app\model\db;

use app\model;

const driver = 'aint\db\driver\pdo';
const platform = 'aint\db\platform\sqlite';

function db_connect(): \PDO {
    static $resource;
    if ($resource === null) { // we'll only connect to the db once
        $db_connect = driver . '\db_connect';
        $resource = $db_connect(model\get_app_config()['db']);
    }
    return $resource;
}
