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

        try {
            DB::statement('ALTER TABLE comments ADD COLUMN status VARCHAR(255) NOT NULL DEFAULT "approved"');
        } catch (Throwable $ignored) {
        }

        $step = $_GET['step'] ?? null;
        if ($step === 'init' || $step === 'fresh') {
            echo "Initializing tables with CREATE TABLE IF NOT EXISTS...\n";
            DB::statement('CREATE TABLE IF NOT EXISTS users (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                email_verified_at TIMESTAMP NULL,
                password VARCHAR(255) NOT NULL,
                remember_token VARCHAR(100) NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )');

            DB::statement('CREATE TABLE IF NOT EXISTS password_reset_tokens (
                email VARCHAR(255) PRIMARY KEY,
                token VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL
            )');

            DB::statement('CREATE TABLE IF NOT EXISTS sessions (
                id VARCHAR(255) PRIMARY KEY,
                user_id BIGINT UNSIGNED NULL,
                ip_address VARCHAR(45) NULL,
                user_agent TEXT NULL,
                payload LONGTEXT NOT NULL,
                last_activity INT NOT NULL,
                INDEX sessions_user_id_index (user_id),
                INDEX sessions_last_activity_index (last_activity)
            )');

            DB::statement('CREATE TABLE IF NOT EXISTS cache (
                `key` VARCHAR(255) PRIMARY KEY,
                `value` MEDIUMTEXT NOT NULL,
                expiration BIGINT NOT NULL
            )');

            DB::statement('CREATE TABLE IF NOT EXISTS cache_locks (
                `key` VARCHAR(255) PRIMARY KEY,
                owner VARCHAR(255) NOT NULL,
                expiration BIGINT NOT NULL
            )');

            DB::statement('CREATE TABLE IF NOT EXISTS jobs (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                queue VARCHAR(255) NOT NULL,
                payload LONGTEXT NOT NULL,
                attempts TINYINT UNSIGNED NOT NULL,
                reserved_at INT UNSIGNED NULL,
                available_at INT UNSIGNED NOT NULL,
                created_at INT UNSIGNED NOT NULL,
                INDEX jobs_queue_index (queue)
            )');

            DB::statement('CREATE TABLE IF NOT EXISTS job_batches (
                id VARCHAR(255) PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                total_jobs INT NOT NULL,
                pending_jobs INT NOT NULL,
                failed_jobs INT NOT NULL,
                failed_job_ids LONGTEXT NOT NULL,
                options MEDIUMTEXT NULL,
                cancelled_at INT NULL,
                created_at INT NOT NULL,
                finished_at INT NULL
            )');

            DB::statement('CREATE TABLE IF NOT EXISTS failed_jobs (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                uuid VARCHAR(255) NOT NULL UNIQUE,
                connection TEXT NOT NULL,
                queue TEXT NOT NULL,
                payload LONGTEXT NOT NULL,
                exception LONGTEXT NOT NULL,
                failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )');

            DB::statement('CREATE TABLE IF NOT EXISTS categories (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                slug VARCHAR(255) NOT NULL UNIQUE,
                color VARCHAR(255) NOT NULL DEFAULT "#e91e63",
                description TEXT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )');

            DB::statement('CREATE TABLE IF NOT EXISTS posts (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NULL,
                category_id BIGINT UNSIGNED NOT NULL,
                title VARCHAR(255) NOT NULL,
                slug VARCHAR(255) NOT NULL UNIQUE,
                excerpt TEXT NULL,
                content LONGTEXT NOT NULL,
                featured_image LONGTEXT NULL,
                software_version VARCHAR(255) NULL,
                file_size VARCHAR(255) NULL,
                license_type VARCHAR(255) NULL DEFAULT "Full Version",
                os_support VARCHAR(255) NULL DEFAULT "Windows 11 / 10 / 8 / 7",
                password_archive VARCHAR(255) NULL DEFAULT "anganansoft",
                download_links JSON NULL,
                system_requirements TEXT NULL,
                installation_guide TEXT NULL,
                views BIGINT UNSIGNED NOT NULL DEFAULT 0,
                is_featured TINYINT(1) NOT NULL DEFAULT 0,
                status VARCHAR(255) NOT NULL DEFAULT "published",
                published_at TIMESTAMP NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )');

            DB::statement('CREATE TABLE IF NOT EXISTS tags (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                slug VARCHAR(255) NOT NULL UNIQUE,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )');

            DB::statement('CREATE TABLE IF NOT EXISTS comments (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                post_id BIGINT UNSIGNED NOT NULL,
                author_name VARCHAR(255) NOT NULL,
                author_email VARCHAR(255) NULL,
                content TEXT NOT NULL,
                status VARCHAR(255) NOT NULL DEFAULT "approved",
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )');

            try {
                DB::statement('ALTER TABLE comments ADD COLUMN IF NOT EXISTS status VARCHAR(255) NOT NULL DEFAULT "approved"');
            } catch (Throwable $ignored) {
            }

            DB::statement('CREATE TABLE IF NOT EXISTS settings (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `key` VARCHAR(255) NOT NULL UNIQUE,
                `value` LONGTEXT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )');

            DB::statement('CREATE TABLE IF NOT EXISTS post_tag (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                post_id BIGINT UNSIGNED NOT NULL,
                tag_id BIGINT UNSIGNED NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                UNIQUE KEY post_tag_unique (post_id, tag_id)
            )');

            DB::statement('CREATE TABLE IF NOT EXISTS migrations (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL
            )');

            $allMigrations = [
                '0001_01_01_000000_create_users_table',
                '0001_01_01_000001_create_cache_table',
                '0001_01_01_000002_create_jobs_table',
                '2026_10_27_153651_create_categories_table',
                '2026_10_27_153653_create_posts_table',
                '2026_10_27_153654_create_tags_table',
                '2026_10_27_153656_create_comments_table',
                '2026_10_27_153657_create_settings_table',
                '2026_10_27_153707_create_post_tag_table',
                '2026_10_28_084749_change_featured_image_to_longtext_in_posts_table',
            ];

            foreach ($allMigrations as $m) {
                if (! DB::table('migrations')->where('migration', $m)->exists()) {
                    DB::table('migrations')->insert(['migration' => $m, 'batch' => 1]);
                }
            }

            echo "TABLES INITIALIZED SUCCESSFULLY!\n";

        } elseif ($step === 'seed_meta') {
            echo "Seeding Admin, Settings, Categories, Tags...\n";
            (new DatabaseSeeder)->seedMeta();
            echo "META SEEDING COMPLETED!\n";
        } elseif ($step === 'seed_posts') {
            $offset = (int) ($_GET['offset'] ?? 0);
            $limit = (int) ($_GET['limit'] ?? 4);
            echo "Seeding posts from offset {$offset} (limit {$limit})...\n";
            (new DatabaseSeeder)->seedPosts($offset, $limit);
            echo 'POSTS SEEDING COMPLETED! POSTS COUNT: '.Post::count()."\n";
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

// 7. Ensure database schema and initial seed data exist for ephemeral SQLite
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
}

// 8. Handle incoming HTTP request
$app->handleRequest(Request::capture());
