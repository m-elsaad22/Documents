<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\View;

class SettingsController
{
    public function index(): void
    {
        Auth::requireLogin();
        // Redirect company users to their company settings
        if (!Auth::isAdmin() && Auth::companyId()) {
            redirect('/admin/companies/' . Auth::companyId() . '/edit');
        }
        $db = Database::getInstance();
        $settings = [];
        foreach ($db->fetchAll('SELECT setting_key, setting_value FROM settings WHERE company_id IS NULL') as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        View::render('admin/settings/index', ['title' => 'الإعدادات', 'settings' => $settings]);
    }

    public function save(): void
    {
        Auth::requireRole(['admin']);
        Csrf::validate();
        $db = Database::getInstance();
        $keys = ['system_name', 'support_email', 'default_currency'];
        foreach ($keys as $key) {
            $val = trim((string) input($key, ''));
            $exists = $db->fetch('SELECT id FROM settings WHERE company_id IS NULL AND setting_key = :k', ['k'=>$key]);
            if ($exists) {
                $db->update('settings', ['setting_value'=>$val], 'id=:id', ['id'=>$exists['id']]);
            } else {
                $db->insert('settings', ['company_id'=>null,'setting_key'=>$key,'setting_value'=>$val]);
            }
        }
        flash('success', 'تم حفظ الإعدادات');
        redirect('/admin/settings');
    }
}
