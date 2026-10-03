<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\View;
use App\Core\ActivityLog;

class CustomerController
{
    public function index(): void
    {
        Auth::requireLogin();
        $db = Database::getInstance();
        $q = trim((string) input('q', ''));
        $sql = 'SELECT cu.*, co.name_ar AS company_name FROM customers cu LEFT JOIN companies co ON co.id = cu.company_id WHERE 1=1';
        $params = [];
        if (!Auth::isAdmin() && Auth::companyId()) {
            $sql .= ' AND cu.company_id = :cid';
            $params['cid'] = Auth::companyId();
        }
        if ($q !== '') {
            $sql .= ' AND (cu.name_ar LIKE :q OR cu.name_en LIKE :q OR cu.phone LIKE :q OR cu.email LIKE :q)';
            $params['q'] = "%$q%";
        }
        $sql .= ' ORDER BY cu.id DESC';
        View::render('admin/customers/index', [
            'title' => 'العملاء',
            'customers' => $db->fetchAll($sql, $params),
            'q' => $q,
        ]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        $companies = $this->companies();
        View::render('admin/customers/form', ['title' => 'إضافة عميل', 'customer' => null, 'companies' => $companies]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        Csrf::validate();
        $data = $this->validated();
        $id = Database::getInstance()->insert('customers', $data);
        ActivityLog::write('customer.create', 'customer', $id, 'Created customer');
        flash('success', 'تم إضافة العميل');
        redirect('/admin/customers');
    }

    public function edit(string $id): void
    {
        Auth::requireLogin();
        $customer = Database::getInstance()->fetch('SELECT * FROM customers WHERE id = :id', ['id' => (int) $id]);
        if (!$customer) { flash('error','غير موجود'); redirect('/admin/customers'); }
        View::render('admin/customers/form', [
            'title' => 'تعديل عميل',
            'customer' => $customer,
            'companies' => $this->companies(),
        ]);
    }

    public function update(string $id): void
    {
        Auth::requireLogin();
        Csrf::validate();
        $data = $this->validated((int)$id);
        Database::getInstance()->update('customers', $data, 'id = :id', ['id' => (int)$id]);
        ActivityLog::write('customer.update', 'customer', (int)$id, 'Updated customer');
        flash('success', 'تم تحديث العميل');
        redirect('/admin/customers');
    }

    public function delete(string $id): void
    {
        Auth::requireLogin();
        Csrf::validate();
        Database::getInstance()->update('customers', ['is_active' => 0], 'id = :id', ['id' => (int)$id]);
        flash('success', 'تم تعطيل العميل');
        redirect('/admin/customers');
    }

    private function companies(): array
    {
        $db = Database::getInstance();
        if (Auth::isAdmin()) {
            return $db->fetchAll('SELECT id, name_ar FROM companies WHERE is_active = 1 ORDER BY name_ar');
        }
        return $db->fetchAll('SELECT id, name_ar FROM companies WHERE id = :id', ['id' => Auth::companyId()]);
    }

    private function validated(?int $id = null): array
    {
        $name = trim((string) input('name_ar'));
        if ($name === '') {
            flash('error', 'اسم العميل مطلوب');
            redirect($_SERVER['HTTP_REFERER'] ?? '/admin/customers');
        }
        $companyId = Auth::isAdmin() ? (int) input('company_id') : (int) Auth::companyId();
        $data = [
            'company_id' => $companyId,
            'name_ar' => $name,
            'name_en' => trim((string) input('name_en')),
            'email' => trim((string) input('email')),
            'phone' => trim((string) input('phone')),
            'whatsapp' => trim((string) input('whatsapp')),
            'address_ar' => trim((string) input('address_ar')),
            'address_en' => trim((string) input('address_en')),
            'city' => trim((string) input('city')),
            'country' => trim((string) input('country')),
            'tax_number' => trim((string) input('tax_number')),
            'notes' => trim((string) input('notes')),
            'is_active' => 1,
            'updated_at' => now(),
        ];
        if (!$id) $data['created_at'] = now();
        return $data;
    }
}
