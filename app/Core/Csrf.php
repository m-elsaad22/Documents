<?php
namespace App\Core;

/**
 * CSRF Protection
 */
class Csrf
{
    private const KEY = '_csrf_token';

    public static function token(): string
    {
        if (empty($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::KEY];
    }

    public static function field(): string
    {
        $token = self::token();
        return '<input type="hidden" name="_token" value="' . e($token) . '">';
    }

    public static function meta(): string
    {
        return '<meta name="csrf-token" content="' . e(self::token()) . '">';
    }

    public static function verify(?string $token = null): bool
    {
        $token = $token ?? ($_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
        if (!$token || empty($_SESSION[self::KEY])) {
            return false;
        }
        return hash_equals($_SESSION[self::KEY], $token);
    }

    public static function validate(): void
    {
        if (!self::verify()) {
            http_response_code(419);
            if (is_ajax()) {
                View::json(['success' => false, 'message' => 'CSRF token mismatch'], 419);
            }
            flash('error', 'انتهت صلاحية الجلسة. يرجى إعادة المحاولة.');
            redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }
}
