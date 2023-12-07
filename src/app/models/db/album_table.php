<?php

namespace app\models\db\album_table;

use app\models\db;

const table = 'albums';

/**
 * Partial application,
 * function delegating calls to aint\db\table package
 * adding platform and driver parameters
 */
function call_table_func(): \PDOStatement|array {
    $args = func_get_args();
    $func = 'aint\db\table\\' . array_shift($args);
    $args = array_merge([db\db_connect(), db\platform, db\driver, table], $args);
    return call_user_func_array($func, $args);
}

function select(array $where = []): array {
    return call_table_func('select', $where);
}

function insert(array $data): \PDOStatement {
    return call_table_func('insert', $data);
}

function update(array $data, array $where = []): \PDOStatement {
    return call_table_func('update', $data, $where);
}

function delete(array $where = []): \PDOStatement {
    return call_table_func('delete', $where);
}
