<?php

define('aint\app_dir', dirname(__DIR__) . '/src/app');

preload_files([
    dirname(__DIR__) . '/vendor/aint/framework/library',
    dirname(__DIR__) . '/src/app'
]);

aint\run();
exit;

/**
 * Helpers
 */

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
