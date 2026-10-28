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

// 3. Detect Database Connection: TiDB Cloud (MySQL) or SQLite fallback
$dbHost = getenv('DB_HOST');
$dbConnection = getenv('DB_CONNECTION');
$dbDatabase = getenv('DB_DATABASE');

if ($dbHost && (! $dbConnection || $dbConnection === 'sqlite')) {
    putenv('DB_CONNECTION=mysql');
    $_ENV['DB_CONNECTION'] = 'mysql';
    $_SERVER['DB_CONNECTION'] = 'mysql';
    $dbConnection = 'mysql';
}

// In TiDB / MySQL, 'sys' is an internal system performance schema where table creation is forbidden.
// If DB_DATABASE is 'sys' or empty on MySQL, redirect to the default user database 'test'.
if ($dbConnection === 'mysql' && (! $dbDatabase || $dbDatabase === 'sys')) {
    putenv('DB_DATABASE=test');
    $_ENV['DB_DATABASE'] = 'test';
    $_SERVER['DB_DATABASE'] = 'test';
}

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

// Ensure database connection config is correctly selected
if ($useSqlite) {
    config([
        'database.default' => 'sqlite',
        'database.connections.sqlite.database' => $sqlitePath,
    ]);
    DB::purge('sqlite');
    DB::reconnect('sqlite');
} else {
    $dbName = config('database.connections.mysql.database');
    if (! $dbName || $dbName === 'sys') {
        config(['database.connections.mysql.database' => 'test']);
    }
    config([
        'database.default' => 'mysql',
    ]);
    DB::purge('mysql');
    DB::reconnect('mysql');
}

// 6. Diagnostic endpoint for TiDB Cloud connection
if (isset($_GET['check_db'])) {
    header('Content-Type: text/plain');
    try {
        echo "Testing DB connection...\n";
        echo 'DB_CONNECTION: '.config('database.default')."\n";
        echo 'DB_HOST: '.config('database.connections.mysql.host')."\n";
        echo 'DB_PORT: '.config('database.connections.mysql.port')."\n";
        echo 'DB_DATABASE: '.config('database.connections.mysql.database')."\n";
        echo 'DB_USERNAME: '.config('database.connections.mysql.username')."\n";
        $caPath = config('database.connections.mysql.options')[PDO::MYSQL_ATTR_SSL_CA] ?? 'none';
        echo 'SSL_CA: '.$caPath."\n";
        echo 'SSL_CA file exists: '.(file_exists($caPath) ? 'YES' : 'NO')."\n";

        DB::connection()->getPdo();
        echo "CONNECTED TO PDO SUCCESSFULLY!\n";
        $dbs = DB::select('SHOW DATABASES');
        echo "AVAILABLE DATABASES:\n";
        print_r($dbs);

        try {
            DB::statement('CREATE DATABASE IF NOT EXISTS anganansoft');
            echo "DATABASE anganansoft created or exists successfully!\n";
        } catch (Throwable $e) {
            echo 'CREATE DATABASE ERROR: '.$e->getMessage()."\n";
        }

        try {
            echo "Running migrate...\n";
            Artisan::call('migrate', ['--force' => true]);
            echo "MIGRATE OUTPUT:\n".Artisan::output()."\n";
            echo 'POSTS COUNT: '.Post::count()."\n";
            if (Post::count() === 0) {
                echo "Running seed...\n";
                (new DatabaseSeeder)->run();
                echo 'SEEDED! POSTS COUNT: '.Post::count()."\n";
            }
        } catch (Throwable $e) {
            echo 'MIGRATE/SEED FAILED: '.$e->getMessage()."\n";
            echo 'File: '.$e->getFile().':'.$e->getLine()."\n";
        }
    } catch (Throwable $e) {
        echo "CONNECTION FAILED:\n";
        echo $e->getMessage()."\n";
        echo 'File: '.$e->getFile().':'.$e->getLine()."\n";
    }
    exit;
}

// 7. Ensure database schema and initial seed data exist
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

// 8. Handle incoming HTTP request
$app->handleRequest(Request::capture());
