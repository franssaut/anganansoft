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
$dbHost = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? ($_SERVER['DB_HOST'] ?? null));
$dbConnection = getenv('DB_CONNECTION') ?: ($_ENV['DB_CONNECTION'] ?? ($_SERVER['DB_CONNECTION'] ?? null));
$dbDatabase = getenv('DB_DATABASE') ?: ($_ENV['DB_DATABASE'] ?? ($_SERVER['DB_DATABASE'] ?? null));

if ($dbHost) {
    putenv('DB_CONNECTION=mysql');
    $_ENV['DB_CONNECTION'] = 'mysql';
    $_SERVER['DB_CONNECTION'] = 'mysql';
    $dbConnection = 'mysql';

    if (! $dbDatabase || in_array($dbDatabase, ['sys', 'test', 'laravel'])) {
        putenv('DB_DATABASE=anganansoft');
        $_ENV['DB_DATABASE'] = 'anganansoft';
        $_SERVER['DB_DATABASE'] = 'anganansoft';
        $dbDatabase = 'anganansoft';
    }
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
    if (! $dbName || $dbName === 'sys' || $dbName === 'test') {
        config(['database.connections.mysql.database' => 'anganansoft']);
    }
    config([
        'database.default' => 'mysql',
    ]);
    DB::purge('mysql');
    DB::reconnect('mysql');

    try {
        DB::connection()->getPdo();
    } catch (Throwable $e) {
        if (str_contains($e->getMessage(), 'Unknown database')) {
            config(['database.connections.mysql.database' => null]);
            DB::purge('mysql');
            DB::statement('CREATE DATABASE IF NOT EXISTS anganansoft');
            config(['database.connections.mysql.database' => 'anganansoft']);
            DB::purge('mysql');
            DB::reconnect('mysql');
        }
    }
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

        $step = $_GET['step'] ?? null;
        if ($step === 'fresh') {
            echo "Running migrate:fresh...\n";
            Artisan::call('migrate:fresh', ['--force' => true]);
            echo "FRESH OUTPUT:\n".Artisan::output()."\n";
        } elseif ($step === 'seed') {
            echo "Running DatabaseSeeder...\n";
            (new DatabaseSeeder)->run();
            echo 'SEED COMPLETED! POSTS COUNT: '.Post::count()."\n";
        }

        $tables = DB::select('SHOW TABLES');
        echo 'TABLES IN '.config('database.connections.mysql.database').":\n";
        print_r($tables);

        if (Schema::hasTable('posts')) {
            echo 'POSTS COUNT: '.Post::count()."\n";
        } else {
            echo "posts table not yet created.\n";
        }
    } catch (Throwable $e) {
        echo "CONNECTION/OPERATION FAILED:\n";
        echo $e->getMessage()."\n";
        echo 'File: '.$e->getFile().':'.$e->getLine()."\n";
    }
    exit;
}

// 7. Ensure database schema and initial seed data exist
if ($useSqlite) {
    try {
        if (! Schema::hasTable('posts') || Post::count() === 0) {
            Artisan::call('migrate', ['--force' => true]);
            if (Post::count() === 0) {
                (new DatabaseSeeder)->run();
            }
        }
    } catch (Throwable $e) {
        error_log('SQLite init error: '.$e->getMessage().' in '.$e->getFile().':'.$e->getLine());
    }
} else {
    try {
        if (! Schema::hasTable('posts') || ! Schema::hasTable('settings')) {
            Artisan::call('migrate:fresh', ['--force' => true]);
        }
        if (Schema::hasTable('posts') && Post::count() === 0) {
            (new DatabaseSeeder)->run();
        }
    } catch (Throwable $e) {
        error_log('TiDB init error: '.$e->getMessage().' in '.$e->getFile().':'.$e->getLine());
    }
}

// 8. Handle incoming HTTP request
$app->handleRequest(Request::capture());
