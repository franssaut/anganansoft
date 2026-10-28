<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

define('LARAVEL_START', microtime(true));

// 1. Prepare writable directory tree in /tmp for Vercel serverless functions
$tmpDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($tmpDirs as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Point Laravel caches and views to writable /tmp
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');

// 3. Setup SQLite fallback in /tmp if external database (TiDB / MySQL) is not specified
$dbConnection = getenv('DB_CONNECTION');
$dbHost = getenv('DB_HOST');

if ($dbConnection === 'sqlite' || (! $dbConnection && ! $dbHost)) {
    $dbPath = '/tmp/database.sqlite';
    if (! file_exists($dbPath) || filesize($dbPath) === 0) {
        @touch($dbPath);
    }
    putenv('DB_CONNECTION=sqlite');
    putenv('DB_DATABASE='.$dbPath);
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_ENV['DB_DATABASE'] = $dbPath;
    $_SERVER['DB_CONNECTION'] = 'sqlite';
    $_SERVER['DB_DATABASE'] = $dbPath;
}

// 4. Register Composer autoloader
require __DIR__.'/../vendor/autoload.php';

// 5. Bootstrap Laravel
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// 6. Automatically initialize database (migrate & seed) on first run if tables are missing
if (! file_exists('/tmp/.db_initialized')) {
    try {
        if (! Schema::hasTable('posts')) {
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
        }
        @touch('/tmp/.db_initialized');
    } catch (Throwable $e) {
        // If DB is temporarily unreachable during warmup, continue so Laravel handles request or error gracefully
    }
}

// 7. Handle incoming HTTP request
$app->handleRequest(Request::capture());
