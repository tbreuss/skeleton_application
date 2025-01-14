<?php
/**
 * SQLite platform specific functions for composing valid SQL queries
 */
namespace aint\db\platform\sqlite;

/**
 * Quotes an identifier such as a column name
 */
function quote_identifier(string $identifier): string {
    return '"' . str_replace('"', '\\' . '"', $identifier) . '"';
}

/**
 * Quotes a value such as a column value
 */
function quote_value(array|string $value): string {
    $value = str_replace('\'', '\\' . '\'', $value);
    if (is_array($value))
        $value = implode('\', \'', $value);
    return '\'' . $value . '\'';
}

/**
 * Quotes all values in the string, presented by placeholders
 * (using `sprintf`)
 */
function quote_into(): string {
    $args = func_get_args();
    $query = array_shift($args);
    $params = array_map(__NAMESPACE__ . '\quote_value', $args);
    array_unshift($params, $query);
    return call_user_func_array('sprintf', $params);
}
