<?php
/**
 * KDMS — Database Configuration
 * Supports MySQL (cPanel production) and SQLite (local demo)
 */

$driver = getenv('DB_DRIVER') ?: 'sqlite';

if ($driver === 'sqlite') {
    return [
        'driver'   => 'sqlite',
        'database' => dirname(__DIR__) . '/storage/kdms.sqlite',
        'charset'  => 'utf8',
    ];
}

return [
    'driver'   => 'mysql',
    'host'     => getenv('DB_HOST') ?: 'localhost',
    'port'     => getenv('DB_PORT') ?: '3306',
    'database' => getenv('DB_NAME') ?: 'kdms',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
    'charset'  => 'utf8mb4',
    'collation'=> 'utf8mb4_unicode_ci',
];
