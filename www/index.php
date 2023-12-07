<?php

register_shutdown_function('shut_down');

define('aint\app_dir', dirname(__DIR__) . '/src/app');

preload_files([
    dirname(__DIR__) . '/vendor/aint/framework/library',
    dirname(__DIR__) . '/src/app'
]);

aint\run();
exit;

/**
 * functions
 */

function friendly_error_type(int $type): string
{
    switch ($type) {
        case E_ERROR: // 1 //
            return 'E_ERROR';
        case E_WARNING: // 2 //
            return 'E_WARNING';
        case E_PARSE: // 4 //
            return 'E_PARSE';
        case E_NOTICE: // 8 //
            return 'E_NOTICE';
        case E_CORE_ERROR: // 16 //
            return 'E_CORE_ERROR';
        case E_CORE_WARNING: // 32 //
            return 'E_CORE_WARNING';
        case E_COMPILE_ERROR: // 64 //
            return 'E_COMPILE_ERROR';
        case E_COMPILE_WARNING: // 128 //
            return 'E_COMPILE_WARNING';
        case E_USER_ERROR: // 256 //
            return 'E_USER_ERROR';
        case E_USER_WARNING: // 512 //
            return 'E_USER_WARNING';
        case E_USER_NOTICE: // 1024 //
            return 'E_USER_NOTICE';
        case E_STRICT: // 2048 //
            return 'E_STRICT';
        case E_RECOVERABLE_ERROR: // 4096 //
            return 'E_RECOVERABLE_ERROR';
        case E_DEPRECATED: // 8192 //
            return 'E_DEPRECATED';
        case E_USER_DEPRECATED: // 16384 //
            return 'E_USER_DEPRECATED';
    }
    return 'E_UNKNOWN';
}

function catch_error(int $type, string $message, string $file = '', int $line = 0)
{
    echo '<b>Error</b><br>';
    echo 'Type: ' . friendly_error_type($type) . '<br>';
    echo 'Message: ' . $message . '<br>';
    echo 'File: ' . $file . '<br>';
    echo 'Line Number: ' . $line;
    exit();
}

function shut_down()
{
    $lasterror = error_get_last();
    if (in_array($lasterror['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR, E_CORE_WARNING, E_COMPILE_WARNING, E_PARSE])) {
        catch_error($lasterror['type'], $lasterror['message'], $lasterror['file'], $lasterror['line']);
    }
}

function t(string $text): string
{
    return aint\translate($text);
}

function url(string $route_action, array $route_params = []): string
{
    return aint\uri($route_action, $route_params);
}

function h(string $text): string
{
    return htmlspecialchars($text);
}

function preload_files(array $files)
{
    foreach ($files as $f) {
        if (is_file($f))
            require_once $f;
        else {
            $rdi = new RecursiveDirectoryIterator($f, RecursiveDirectoryIterator::SKIP_DOTS);
            $rii = new RecursiveIteratorIterator($rdi);
            foreach ($rii as $f) {
                if ($f->isDir()) continue;
                if ($f->getExtension() !== 'php') continue;
                require_once $f->getPathname();
            }
        }
    }
}
