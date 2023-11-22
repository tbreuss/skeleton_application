<?php

namespace app;

use aint\common;
use aint\mvc\dispatching;

/**
 * Namespace for application action-functions
 */
const actions_namespace = 'app\actions';

/**
 * Function to handle errors happening during dispatching process
 */
const error_handler = 'app\actions\errors\error_action';

/**
 * Application configuration
 */
const app_config = '/configs/app.inc',
      app_local_config = '/configs/app.local.inc';

/**
 * Localization parameters
 */
const default_locale = 'en_US',
      languages_path = 'app/languages/',
      locale_file_ext = '.inc';

/**
 * Returns main application configuration
 * (merged with app.local.ini if one exists)
 */
function config(): array
{
    static $config;
    if ($config === null) {
        $app_dir = dirname(__FILE__) . '/app/';
        $config = require $app_dir . app_config;
        if (is_readable($local_config = $app_dir . app_local_config))
            $config = common\merge_recursive($config, require $local_config);
    }
    return $config;
}

/**
 * Translates a string using a language file for the locale specified
 * returns the string itself if no translation is found
 */
function translate(string $text, string $locale = default_locale): string
{
    static $languages = [];
    if (!isset($languages[$locale]))
        $languages[$locale] = require languages_path . $locale . locale_file_ext;
    return (string)common\get_param($languages[$locale], $text, $text);
}

/**
 * Runs the app, calls default dispatching strategy provided
 * by aint framework.
 */
function run(): void {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    ini_set('ignore_repeated_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', dirname(__FILE__, 2) . '/log/error.log');

    set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__, 2) . '/src'));

    dispatching\dispatch_http_default_router(actions_namespace, error_handler);
}
