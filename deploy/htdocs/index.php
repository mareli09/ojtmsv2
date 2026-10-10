<?php

/**
 * OJTMS front controller for shared hosting (InfinityFree).
 *
 * On InfinityFree the web root is htdocs/, so the contents of Laravel's
 * public/ folder live here and the rest of the application sits in
 * htdocs/ojtms_app/ (blocked from the web by its own .htaccess).
 *
 * The only difference from Laravel's stock public/index.php is these paths.
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$app = __DIR__ . '/ojtms_app';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $app . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $app . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once $app . '/bootstrap/app.php')
    ->handleRequest(Request::capture());
