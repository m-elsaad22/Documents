<?php
namespace App\Controllers;

use App\Core\View;
use Database\Installer;

class InstallController
{
    public function index(): void
    {
        if (is_file(storage_path('installed.lock'))) {
            redirect('/login');
        }
        View::render('auth/install', ['title' => 'تثبيت النظام'], 'layouts/auth');
    }

    public function run(): void
    {
        if (is_file(storage_path('installed.lock'))) {
            redirect('/login');
        }
        $driver = input('db_driver', 'sqlite');
        if ($driver === 'sqlite') {
            // write env
            $env = "APP_URL=" . (input('app_url') ?: 'http://localhost:8080') . "\nAPP_DEBUG=false\nDB_DRIVER=sqlite\n";
            file_put_contents(base_path('.env'), $env);
            putenv('DB_DRIVER=sqlite');
            require_once base_path('database/Installer.php');
            Installer::runSqliteDemo();
        } else {
            $env = "APP_URL=" . (input('app_url') ?: '') . "\nAPP_DEBUG=false\nDB_DRIVER=mysql\n"
                . "DB_HOST=" . input('db_host','localhost') . "\n"
                . "DB_PORT=" . input('db_port','3306') . "\n"
                . "DB_NAME=" . input('db_name','kdms') . "\n"
                . "DB_USER=" . input('db_user','root') . "\n"
                . "DB_PASS=" . input('db_pass','') . "\n";
            file_put_contents(base_path('.env'), $env);
            foreach (explode("\n", $env) as $line) {
                if (str_contains($line, '=')) {
                    [$k,$v] = explode('=', $line, 2);
                    putenv(trim($k).'='.trim($v));
                }
            }
            require_once base_path('database/Installer.php');
            Installer::runMysql([
                'host' => input('db_host','localhost'),
                'port' => input('db_port','3306'),
                'database' => input('db_name','kdms'),
                'username' => input('db_user','root'),
                'password' => input('db_pass',''),
            ]);
        }
        flash('success', 'تم التثبيت بنجاح. بيانات الدخول: admin@kdms.local / admin123');
        redirect('/login');
    }
}
