<?php

use Illuminate\Support\Facades\Artisan;

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

// 3. Setup SQLite in /tmp if external database is not specified
$needsInit = false;
$dbConnection = getenv('DB_CONNECTION');
if ($dbConnection === 'sqlite' || (! $dbConnection && ! getenv('DB_HOST'))) {
    $dbPath = '/tmp/database.sqlite';
    if (! file_exists($dbPath) || filesize($dbPath) === 0) {
        @touch($dbPath);
        $needsInit = true;
    }
    putenv('DB_DATABASE='.$dbPath);
    $_ENV['DB_DATABASE'] = $dbPath;
    $_SERVER['DB_DATABASE'] = $dbPath;
}

// 4. Bootstrap Laravel
require __DIR__.'/../public/index.php';

// 5. Auto-migrate and seed initial database on first boot if newly created in /tmp
if ($needsInit && class_exists(Artisan::class)) {
    try {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
    } catch (Throwable $e) {
        // Log silently
    }
}
