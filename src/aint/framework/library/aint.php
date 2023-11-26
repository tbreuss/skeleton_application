<?php

namespace aint;

use aint\web\routing;
use aint\web\dispatching;

define('aint\dir', __DIR__);

/**
 * Error reporting configuration
 */
if (!defined('aint\app_dir')) {
    die('Constant "aint\app_dir" has to be defined in your bootstrap file.');
}

if (!defined('aint\error_reporting')) {
    define('aint\error_reporting', E_ALL);
}

if (!defined('aint\display_errors')) {
    define('aint\display_errors', '0');
}

if (!defined('aint\display_startup_errors')) {
    define('aint\display_startup_errors', '0');
}

if (!defined('aint\ignore_repeated_errors')) {
    define('aint\ignore_repeated_errors', '1');
}

if (!defined('aint\log_errors')) {
    define('aint\log_errors', '1');
}

if (!defined('aint\error_log')) {
    define('aint\error_log', app_dir . '/logs/error.log');
}

/**
 * Application configuration
 */
const app_config = '/configs/app.inc',
      app_local_config = '/configs/app.local.inc';

/**
 * Namespace for application action-functions
 */
const actions_namespace = 'app\actions';

/**
 * Function to handle errors happening during dispatching process
 */
const error_handler = 'app\actions\errors\error_action';

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
        $config = require app_dir . app_config;
        if (is_readable($local_config = app_dir . app_local_config))
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
 * Converts action function name and the parameters list back to URI
 */
function uri(string $route_action, array $route_params = []): string {
    return routing\assemble_segment($route_action, $route_params);
}

/**
 * Runs the app, calls default dispatching strategy provided
 * by aint framework.
 */
function run(): void {
    error_reporting(error_reporting);
    ini_set('display_errors', display_errors);
    ini_set('display_startup_errors', display_startup_errors);
    ini_set('ignore_repeated_errors', ignore_repeated_errors);
    ini_set('log_errors', log_errors);
    ini_set('error_log', error_log);

    set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(app_dir)));

    dispatching\dispatch_http_default_router(actions_namespace, error_handler);
}
