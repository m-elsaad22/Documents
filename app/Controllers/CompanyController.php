<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Uploader;
use App\Core\View;
use App\Core\ActivityLog;

class CompanyController
{
    public function index(): void
    {
        Auth::requireLogin();
        $db = Database::getInstance();
        if (Auth::isAdmin()) {
            $rows = $db->fetchAll('SELECT * FROM companies ORDER BY id DESC');
        } else {
            $rows = $db->fetchAll('SELECT * FROM companies WHERE id = :id', ['id' => Auth::companyId()]);
        }
        View::render('admin/companies/index', ['title' => 'الشركات', 'companies' => $rows]);
    }

    public function create(): void
    {
        Auth::requireRole(['admin']);
        View::render('admin/companies/form', ['title' => 'إضافة شركة', 'company' => null]);
    }

    public function store(): void
    {
        Auth::requireRole(['admin']);
        Csrf::validate();
        $data = $this->validated();
        $id = Database::getInstance()->insert('companies', $data);
        ActivityLog::write('company.create', 'company', $id, 'Created company');
        flash('success', 'تم إضافة الشركة بنجاح');
        redirect('/admin/companies');
    }

    public function edit(string $id): void
    {
        Auth::requireLogin();
        $company = Database::getInstance()->fetch('SELECT * FROM companies WHERE id = :id', ['id' => (int) $id]);
        if (!$company) {
            flash('error', 'الشركة غير موجودة');
            redirect('/admin/companies');
        }
        if (!Auth::isAdmin() && Auth::companyId() !== (int) $id) {
            http_response_code(403); exit('Forbidden');
        }
        $assets = Database::getInstance()->fetchAll('SELECT * FROM company_assets WHERE company_id = :id ORDER BY id DESC', ['id' => (int) $id]);
        View::render('admin/companies/form', ['title' => 'تعديل الشركة', 'company' => $company, 'assets' => $assets]);
    }

    public function update(string $id): void
    {
        Auth::requireLogin();
        Csrf::validate();
        $id = (int) $id;
        if (!Auth::isAdmin() && Auth::companyId() !== $id) {
            http_response_code(403); exit('Forbidden');
        }
        $data = $this->validated($id);
        Database::getInstance()->update('companies', $data, 'id = :id', ['id' => $id]);
        ActivityLog::write('company.update', 'company', $id, 'Updated company');
        flash('success', 'تم حفظ إعدادات الشركة');
        redirect('/admin/companies/' . $id . '/edit');
    }

    private function validated(?int $id = null): array
    {
        $nameAr = trim((string) input('name_ar'));
        if ($nameAr === '') {
            flash('error', 'اسم الشركة مطلوب');
            redirect($_SERVER['HTTP_REFERER'] ?? '/admin/companies');
        }
        $slug = trim((string) input('slug')) ?: slugify($nameAr);
        $data = [
            'name_ar' => $nameAr,
            'name_en' => trim((string) input('name_en')),
            'slug' => $slug,
            'address_ar' => trim((string) input('address_ar')),
            'address_en' => trim((string) input('address_en')),
            'city' => trim((string) input('city')),
            'country' => trim((string) input('country')) ?: 'UAE',
            'email' => trim((string) input('email')),
            'website' => trim((string) input('website')),
            'phone' => trim((string) input('phone')),
            'whatsapp' => trim((string) input('whatsapp')),
            'tax_number' => trim((string) input('tax_number')),
            'cr_number' => trim((string) input('cr_number')),
            'currency' => trim((string) input('currency')) ?: 'AED',
            'currency_label_ar' => trim((string) input('currency_label_ar')) ?: 'درهم إماراتي',
            'currency_label_en' => trim((string) input('currency_label_en')) ?: 'UAE Dirham',
            'default_lang' => in_array(input('default_lang'), ['ar','en'], true) ? input('default_lang') : 'ar',
            'primary_color' => trim((string) input('primary_color')) ?: '#003087',
            'secondary_color' => trim((string) input('secondary_color')) ?: '#D4A017',
            'accent_color' => trim((string) input('accent_color')) ?: '#0070CC',
            'social_facebook' => trim((string) input('social_facebook')),
            'social_instagram' => trim((string) input('social_instagram')),
            'social_twitter' => trim((string) input('social_twitter')),
            'social_linkedin' => trim((string) input('social_linkedin')),
            'social_youtube' => trim((string) input('social_youtube')),
            'is_active' => input('is_active') ? 1 : 0,
            'updated_at' => now(),
        ];
        if (!$id) {
            $data['created_at'] = now();
            $data['is_active'] = 1;
        }

        foreach (['logo'=>'logos','seal'=>'seals','signature'=>'signatures','header_image'=>'headers','footer_image'=>'footers','qr_code'=>'misc'] as $field => $dir) {
            if (!empty($_FILES[$field]['name'])) {
                $path = Uploader::image($_FILES[$field], $dir);
                if ($path) {
                    $data[$field] = $path;
                    if ($id) {
                        Database::getInstance()->insert('company_assets', [
                            'company_id' => $id,
                            'type' => str_replace('_image','',$field) === 'qr_code' ? 'other' : (str_contains($field,'header')?'header':(str_contains($field,'footer')?'footer':$field)),
                            'title' => $field,
                            'file_path' => $path,
                            'is_default' => 1,
                            'created_at' => now(),
                        ]);
                    }
                }
            }
        }

        // Offices JSON from form arrays
        $cities = $_POST['office_city'] ?? [];
        $addrs = $_POST['office_address'] ?? [];
        $offices = [];
        foreach ($cities as $i => $city) {
            if (trim($city) === '' && trim($addrs[$i] ?? '') === '') continue;
            $offices[] = ['city_ar' => $city, 'address_ar' => $addrs[$i] ?? ''];
        }
        $data['offices_json'] = json_encode($offices, JSON_UNESCAPED_UNICODE);
        return $data;
    }
}
