<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Auto-detect base directory (handles Alwaysdata nested 'laravel' directory and standard layout)
$baseDir = file_exists(__DIR__ . '/../laravel/vendor/autoload.php') ? __DIR__ . '/../laravel' : __DIR__ . '/..';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $baseDir . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $baseDir . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $baseDir . '/bootstrap/app.php';

$app->handleRequest(Request::capture());
