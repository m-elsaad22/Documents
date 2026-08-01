<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\View;
use App\Core\ActivityLog;

class UserController
{
    public function index(): void
    {
        Auth::requireRole(['admin', 'manager']);
        $db = Database::getInstance();
        $sql = 'SELECT u.*, co.name_ar AS company_name FROM users u LEFT JOIN companies co ON co.id = u.company_id WHERE 1=1';
        $params = [];
        if (!Auth::isAdmin()) {
            $sql .= ' AND u.company_id = :cid';
            $params['cid'] = Auth::companyId();
        }
        $sql .= ' ORDER BY u.id DESC';
        View::render('admin/users/index', [
            'title' => 'المستخدمون',
            'users' => $db->fetchAll($sql, $params),
            'roles' => config('roles'),
        ]);
    }

    public function create(): void
    {
        Auth::requireRole(['admin', 'manager']);
        View::render('admin/users/form', [
            'title' => 'إضافة مستخدم',
            'user' => null,
            'companies' => $this->companies(),
            'roles' => config('roles'),
        ]);
    }

    public function store(): void
    {
        Auth::requireRole(['admin', 'manager']);
        Csrf::validate();
        $data = $this->validated();
        $id = Database::getInstance()->insert('users', $data);
        ActivityLog::write('user.create', 'user', $id, 'Created user');
        flash('success', 'تم إنشاء المستخدم');
        redirect('/admin/users');
    }

    public function edit(string $id): void
    {
        Auth::requireRole(['admin', 'manager']);
        $user = Database::getInstance()->fetch('SELECT * FROM users WHERE id=:id', ['id'=>(int)$id]);
        if (!$user) { flash('error','غير موجود'); redirect('/admin/users'); }
        View::render('admin/users/form', [
            'title' => 'تعديل مستخدم',
            'user' => $user,
            'companies' => $this->companies(),
            'roles' => config('roles'),
        ]);
    }

    public function update(string $id): void
    {
        Auth::requireRole(['admin', 'manager']);
        Csrf::validate();
        $data = $this->validated((int)$id);
        Database::getInstance()->update('users', $data, 'id=:id', ['id'=>(int)$id]);
        ActivityLog::write('user.update', 'user', (int)$id, 'Updated user');
        flash('success', 'تم تحديث المستخدم');
        redirect('/admin/users');
    }

    private function companies(): array
    {
        $db = Database::getInstance();
        if (Auth::isAdmin()) {
            return $db->fetchAll('SELECT id, name_ar FROM companies ORDER BY name_ar');
        }
        return $db->fetchAll('SELECT id, name_ar FROM companies WHERE id=:id', ['id'=>Auth::companyId()]);
    }

    private function validated(?int $id = null): array
    {
        $name = trim((string) input('name'));
        $email = strtolower(trim((string) input('email')));
        $role = (string) input('role', 'employee');
        if (!Auth::isAdmin() && $role === 'admin') $role = 'manager';
        if ($name === '' || $email === '') {
            flash('error', 'الاسم والبريد مطلوبان');
            redirect($_SERVER['HTTP_REFERER'] ?? '/admin/users');
        }
        $data = [
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'phone' => trim((string) input('phone')),
            'company_id' => Auth::isAdmin() ? (input('company_id') ?: null) : Auth::companyId(),
            'is_active' => input('is_active') ? 1 : 0,
            'updated_at' => now(),
        ];
        $pass = (string) input('password');
        if ($pass !== '') {
            $data['password'] = password_hash($pass, PASSWORD_DEFAULT);
        } elseif (!$id) {
            flash('error', 'كلمة المرور مطلوبة');
            redirect('/admin/users/create');
        }
        if (!$id) {
            $data['created_at'] = now();
            $data['is_active'] = 1;
        }
        return $data;
    }
}
