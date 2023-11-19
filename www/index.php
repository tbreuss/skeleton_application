<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('ignore_repeated_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__, 2) . '/log/error.log');

set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__, 2) . '/src'));

require dirname(__DIR__, 1) . '/src/app/autoload.php';

app\controller\run();
