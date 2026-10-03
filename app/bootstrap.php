<?php
/**
 * KDMS Bootstrap
 */

declare(strict_types=1);

// Load .env if present
$envFile = dirname(__DIR__) . '/.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        $v = trim($v, " \t\"'");
        $_ENV[$k] = $v;
        putenv("$k=$v");
    }
}

$config = require dirname(__DIR__) . '/config/app.php';
date_default_timezone_set($config['timezone']);

ini_set('display_errors', $config['debug'] ? '1' : '0');
error_reporting($config['debug'] ? E_ALL : E_ALL & ~E_NOTICE & ~E_DEPRECATED);

// Sessions
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name($config['session_name']);
    session_set_cookie_params([
        'lifetime' => $config['session_lifetime'],
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Autoload
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = dirname(__DIR__) . '/app/' . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require dirname(__DIR__) . '/app/Helpers/functions.php';

// Ensure install completed (except installer routes)
$installedLock = dirname(__DIR__) . '/storage/installed.lock';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$isInstall = str_starts_with($uri, '/install');

if (!$isInstall && !is_file($installedLock)) {
    // Auto-install SQLite demo if no lock and sqlite driver
    $dbCfg = require dirname(__DIR__) . '/config/database.php';
    if (($dbCfg['driver'] ?? '') === 'sqlite') {
        require dirname(__DIR__) . '/database/Installer.php';
        \Database\Installer::runSqliteDemo();
    } else {
        header('Location: /install');
        exit;
    }
}
