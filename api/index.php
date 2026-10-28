<?php

use App\Models\Post;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

define('LARAVEL_START', microtime(true));

// 1. Prepare writable directory tree in /tmp for Vercel serverless functions
$tmpDirs = [
    '/tmp/storage/app/public',
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

// 2. Point caches and logs to writable /tmp
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
putenv('LOG_CHANNEL=stderr');
$_ENV['LOG_CHANNEL'] = 'stderr';
$_SERVER['LOG_CHANNEL'] = 'stderr';

// 3. Fallback SQLite in /tmp if external database (TiDB / MySQL) is not configured
$dbConnection = getenv('DB_CONNECTION');
$dbHost = getenv('DB_HOST');
$useSqlite = ($dbConnection === 'sqlite' || (! $dbConnection && ! $dbHost));
$sqlitePath = '/tmp/database.sqlite';

if ($useSqlite) {
    if (! file_exists($sqlitePath) || filesize($sqlitePath) === 0) {
        @touch($sqlitePath);
    }
    putenv('DB_CONNECTION=sqlite');
    putenv('DB_DATABASE='.$sqlitePath);
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_ENV['DB_DATABASE'] = $sqlitePath;
    $_SERVER['DB_CONNECTION'] = 'sqlite';
    $_SERVER['DB_DATABASE'] = $sqlitePath;
}

// 4. Register Composer autoloader
require __DIR__.'/../vendor/autoload.php';

// 5. Bootstrap Laravel
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Direct storage path to /tmp
$app->useStoragePath('/tmp/storage');

// Bootstrap console kernel so Facades and service providers are active
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

// Ensure config points to /tmp/database.sqlite if using SQLite fallback
if ($useSqlite) {
    config([
        'database.default' => 'sqlite',
        'database.connections.sqlite.database' => $sqlitePath,
    ]);
    DB::purge('sqlite');
    DB::reconnect('sqlite');
}

// 6. Ensure database schema and initial seed data exist
try {
    if (! Schema::hasTable('posts') || Post::count() === 0) {
        Artisan::call('migrate', ['--force' => true]);
        if (Post::count() === 0) {
            (new DatabaseSeeder)->run();
        }
    }
} catch (Throwable $e) {
    error_log('Database init error: '.$e->getMessage().' in '.$e->getFile().':'.$e->getLine());
}

// 7. Handle incoming HTTP request
$app->handleRequest(Request::capture());
