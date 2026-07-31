<?php
/**
 * KDMS — Front Controller
 * Point your cPanel document root here (or public_html copy).
 */

require __DIR__ . '/app/bootstrap.php';

use App\Core\Router;

$router = new Router();

// Auth
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Install
$router->get('/install', 'InstallController@index');
$router->post('/install', 'InstallController@run');

// Admin
$router->get('/', function () {
    if (\App\Core\Auth::check()) {
        redirect('/admin');
    }
    redirect('/login');
});
$router->get('/admin', 'DashboardController@index');

// Companies
$router->get('/admin/companies', 'CompanyController@index');
$router->get('/admin/companies/create', 'CompanyController@create');
$router->post('/admin/companies', 'CompanyController@store');
$router->get('/admin/companies/{id}/edit', 'CompanyController@edit');
$router->post('/admin/companies/{id}', 'CompanyController@update');

// Customers
$router->get('/admin/customers', 'CustomerController@index');
$router->get('/admin/customers/create', 'CustomerController@create');
$router->post('/admin/customers', 'CustomerController@store');
$router->get('/admin/customers/{id}/edit', 'CustomerController@edit');
$router->post('/admin/customers/{id}', 'CustomerController@update');
$router->post('/admin/customers/{id}/delete', 'CustomerController@delete');

// Documents (typed shortcuts + generic)
foreach (['invoices' => 'invoice', 'quotations' => 'quotation', 'contracts' => 'contract', 'receipts' => 'receipt', 'reports' => 'report', 'warranty' => 'warranty'] as $path => $type) {
    $router->get('/admin/' . $path, function () use ($type) {
        (new \App\Controllers\DocumentController())->index($type);
    });
}

$router->get('/admin/documents', 'DocumentController@index');
$router->get('/admin/documents/create', 'DocumentController@create');
$router->post('/admin/documents', 'DocumentController@store');
$router->get('/admin/documents/search', 'DocumentController@search');
$router->get('/admin/documents/{id}', 'DocumentController@show');
$router->get('/admin/documents/{id}/edit', 'DocumentController@edit');
$router->post('/admin/documents/{id}', 'DocumentController@update');
$router->post('/admin/documents/{id}/delete', 'DocumentController@delete');
$router->get('/admin/documents/{id}/preview', 'DocumentController@preview');

// Templates
$router->get('/admin/templates', 'TemplateController@index');
$router->get('/admin/templates/create', 'TemplateController@create');
$router->post('/admin/templates', 'TemplateController@store');
$router->post('/admin/templates/{id}/toggle', 'TemplateController@toggle');
$router->post('/admin/templates/{id}/default', 'TemplateController@setDefault');

// Settings / Users / Logs / Backups
$router->get('/admin/settings', 'SettingsController@index');
$router->post('/admin/settings', 'SettingsController@save');
$router->get('/admin/users', 'UserController@index');
$router->get('/admin/users/create', 'UserController@create');
$router->post('/admin/users', 'UserController@store');
$router->get('/admin/users/{id}/edit', 'UserController@edit');
$router->post('/admin/users/{id}', 'UserController@update');
$router->get('/admin/logs', 'LogController@index');
$router->get('/admin/backups', 'BackupController@index');
$router->post('/admin/backups', 'BackupController@create');

// Upload serving (secure) — supports nested paths
$router->get('/uploads/{path*}', function (string $path) {
    $rel = str_replace(['..', '\\'], '', $path);
    $full = storage_path('uploads/' . $rel);
    if (!is_file($full)) {
        http_response_code(404);
        exit('Not found');
    }
    $mime = mime_content_type($full) ?: 'application/octet-stream';
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($full));
    header('Cache-Control: public, max-age=86400');
    readfile($full);
    exit;
});

// Static assets fallback
$router->get('/assets/{path*}', function (string $path) {
    $rel = str_replace(['..', '\\'], '', $path);
    $full = base_path('public/assets/' . $rel);
    if (!is_file($full)) {
        http_response_code(404);
        exit('Not found');
    }
    $ext = strtolower(pathinfo($full, PATHINFO_EXTENSION));
    $map = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'woff2' => 'font/woff2',
    ];
    header('Content-Type: ' . ($map[$ext] ?? 'application/octet-stream'));
    readfile($full);
    exit;
});

// Public document pretty URLs: /INV-2026-000001
$router->get('/{slug}', 'PublicDocumentController@show');

$router->dispatch(request_method(), $_SERVER['REQUEST_URI'] ?? '/');
