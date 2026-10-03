<?php
/**
 * KDMS Global Helper Functions
 */

use App\Core\Csrf;
use App\Core\Auth;

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function config(string $key, $default = null)
{
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require dirname(__DIR__, 2) . '/config/app.php';
    }
    $parts = explode('.', $key);
    $val = $cfg;
    foreach ($parts as $p) {
        if (!is_array($val) || !array_key_exists($p, $val)) {
            return $default;
        }
        $val = $val[$p];
    }
    return $val;
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function url(string $path = ''): string
{
    $base = rtrim(config('url'), '/');
    if ($path === '' || $path === '/') {
        return $base . '/';
    }
    return $base . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function upload_url(string $path): string
{
    if (!$path) {
        return '';
    }
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    return url('uploads/' . ltrim($path, '/'));
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }
    $msg = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $msg;
}

function old(string $key, $default = '')
{
    return $_SESSION['_old'][$key] ?? $default;
}

function store_old(array $data): void
{
    $_SESSION['_old'] = $data;
}

function clear_old(): void
{
    unset($_SESSION['_old']);
}

function is_ajax(): bool
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function csrf_field(): string
{
    return Csrf::field();
}

function csrf_token(): string
{
    return Csrf::token();
}

function auth_user(): ?array
{
    return Auth::user();
}

function format_money($amount, string $currency = 'AED'): string
{
    return number_format((float) $amount, 2) . ' ' . $currency;
}

function format_date(?string $date, string $lang = 'ar'): string
{
    if (!$date) {
        return '';
    }
    $ts = strtotime($date);
    if (!$ts) {
        return $date;
    }
    if ($lang === 'en') {
        return date('l, d F Y', $ts);
    }
    // Arabic formatted date
    $days = ['الأحد','الإثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت'];
    $months = [
        1=>'يناير',2=>'فبراير',3=>'مارس',4=>'أبريل',5=>'مايو',6=>'يونيو',
        7=>'يوليو',8=>'أغسطس',9=>'سبتمبر',10=>'أكتوبر',11=>'نوفمبر',12=>'ديسمبر'
    ];
    return $days[(int) date('w', $ts)] . '، ' . date('j', $ts) . ' ' . $months[(int) date('n', $ts)] . ' ' . date('Y', $ts);
}

function status_label(string $status, string $lang = 'ar'): string
{
    $statuses = config('statuses', []);
    return $statuses[$status][$lang] ?? $status;
}

function doc_type_label(string $type, string $lang = 'ar'): string
{
    $map = [
        'invoice'   => ['ar' => 'فاتورة', 'en' => 'Invoice'],
        'quotation' => ['ar' => 'عرض سعر', 'en' => 'Quotation'],
        'contract'  => ['ar' => 'عقد', 'en' => 'Contract'],
        'receipt'   => ['ar' => 'سند قبض', 'en' => 'Receipt'],
        'report'    => ['ar' => 'تقرير', 'en' => 'Report'],
        'warranty'  => ['ar' => 'شهادة ضمان', 'en' => 'Warranty'],
    ];
    return $map[$type][$lang] ?? $type;
}

function slugify(string $text): string
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = trim($text, '-');
    $text = strtolower($text);
    return $text ?: 'item';
}

function request_method(): string
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
}

function input(string $key, $default = null)
{
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

function json_input(): array
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function base_path(string $path = ''): string
{
    return dirname(__DIR__, 2) . ($path ? '/' . ltrim($path, '/') : '');
}

function storage_path(string $path = ''): string
{
    return base_path('storage' . ($path ? '/' . ltrim($path, '/') : ''));
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

function today(): string
{
    return date('Y-m-d');
}
