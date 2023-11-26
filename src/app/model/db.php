<?php

namespace app\model\db;

use aint;

const driver = 'aint\db\driver\pdo';
const platform = 'aint\db\platform\sqlite';

function db_connect(): \PDO {
    static $resource;
    if ($resource === null) { // we'll only connect to the db once
        $db_connect = driver . '\db_connect';
        $resource = $db_connect(aint\config()['db']);
    }
    return $resource;
}
