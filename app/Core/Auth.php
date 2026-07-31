<?php
namespace App\Core;

/**
 * Authentication & session helpers
 */
class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $db = Database::getInstance();
        $user = $db->fetch(
            'SELECT * FROM users WHERE email = :email AND is_active = 1 LIMIT 1',
            ['email' => strtolower(trim($email))]
        );

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['company_id'] = $user['company_id'] ? (int) $user['company_id'] : null;
        $_SESSION['user_name'] = $user['name'];

        $db->update('users', [
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ], 'id = :id', ['id' => $user['id']]);

        ActivityLog::write('login', 'user', (int) $user['id'], 'User logged in');
        return true;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }
        $cached = Database::getInstance()->fetch(
            'SELECT * FROM users WHERE id = :id',
            ['id' => $_SESSION['user_id']]
        );
        return $cached;
    }

    public static function id(): ?int
    {
        return self::check() ? (int) $_SESSION['user_id'] : null;
    }

    public static function role(): ?string
    {
        return $_SESSION['user_role'] ?? null;
    }

    public static function companyId(): ?int
    {
        return isset($_SESSION['company_id']) ? (int) $_SESSION['company_id'] : null;
    }

    public static function isAdmin(): bool
    {
        return self::role() === 'admin';
    }

    public static function isManager(): bool
    {
        return in_array(self::role(), ['admin', 'manager'], true);
    }

    public static function can(string $permission): bool
    {
        $role = self::role();
        if ($role === 'admin') {
            return true;
        }

        $map = [
            'manager' => [
                'companies.view', 'customers.*', 'documents.*', 'templates.view',
                'settings.view', 'settings.edit', 'users.view', 'logs.view', 'dashboard',
            ],
            'employee' => [
                'customers.view', 'customers.create', 'documents.*', 'dashboard', 'templates.view',
            ],
        ];

        $allowed = $map[$role] ?? [];
        foreach ($allowed as $rule) {
            if ($rule === $permission) {
                return true;
            }
            if (str_ends_with($rule, '.*')) {
                $prefix = substr($rule, 0, -1);
                if (str_starts_with($permission, $prefix)) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            redirect('/login');
        }
    }

    public static function requireRole(array $roles): void
    {
        self::requireLogin();
        if (!in_array(self::role(), $roles, true)) {
            http_response_code(403);
            View::render('errors/403', ['title' => '403'], 'layouts/admin');
            exit;
        }
    }

    public static function logout(): void
    {
        if (self::check()) {
            ActivityLog::write('logout', 'user', self::id(), 'User logged out');
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }
}
