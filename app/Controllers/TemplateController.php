<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Uploader;
use App\Core\View;
use App\Core\ActivityLog;

class TemplateController
{
    public function index(): void
    {
        Auth::requireLogin();
        $rows = Database::getInstance()->fetchAll(
            'SELECT t.*, co.name_ar AS company_name FROM templates t
             LEFT JOIN companies co ON co.id = t.company_id
             ORDER BY t.document_type, t.language, t.name'
        );
        View::render('admin/templates/index', ['title' => 'القوالب', 'templates' => $rows]);
    }

    public function create(): void
    {
        Auth::requireRole(['admin', 'manager']);
        View::render('admin/templates/form', ['title' => 'رفع قالب', 'template' => null]);
    }

    public function store(): void
    {
        Auth::requireRole(['admin', 'manager']);
        Csrf::validate();
        $name = trim((string) input('name'));
        $type = (string) input('document_type');
        $lang = in_array(input('language'), ['ar','en'], true) ? input('language') : 'ar';
        $slug = trim((string) input('slug')) ?: slugify($name);

        if (empty($_FILES['template_file']['name'])) {
            flash('error', 'يرجى رفع ملف القالب');
            redirect('/admin/templates/create');
        }

        $ext = strtolower(pathinfo($_FILES['template_file']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['php','html','htm'], true)) {
            flash('error', 'نوع الملف غير مدعوم');
            redirect('/admin/templates/create');
        }

        $dir = base_path('resources/templates/documents');
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $filename = $slug . '-' . $lang . '-' . time() . '.php';
        $dest = $dir . '/' . $filename;
        $raw = file_get_contents($_FILES['template_file']['tmp_name']);
        // If HTML uploaded, wrap minimally
        if ($ext !== 'php') {
            $raw = "<?php\n// Uploaded template\n?>\n" . $raw;
        }
        file_put_contents($dest, $raw);

        $id = Database::getInstance()->insert('templates', [
            'company_id' => Auth::isAdmin() ? (input('company_id') ?: null) : Auth::companyId(),
            'name' => $name,
            'slug' => $slug,
            'document_type' => $type,
            'language' => $lang,
            'file_path' => 'resources/templates/documents/' . $filename,
            'is_active' => input('is_active') ? 1 : 0,
            'is_default' => input('is_default') ? 1 : 0,
            'description' => trim((string) input('description')),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (!empty($_POST['is_default'])) {
            $this->clearDefault($type, $lang, $id);
        }

        ActivityLog::write('template.create', 'template', $id, 'Uploaded template');
        flash('success', 'تم رفع القالب');
        redirect('/admin/templates');
    }

    public function toggle(string $id): void
    {
        Auth::requireRole(['admin', 'manager']);
        Csrf::validate();
        $db = Database::getInstance();
        $t = $db->fetch('SELECT * FROM templates WHERE id=:id', ['id'=>(int)$id]);
        if ($t) {
            $db->update('templates', ['is_active' => $t['is_active'] ? 0 : 1, 'updated_at'=>now()], 'id=:id', ['id'=>(int)$id]);
        }
        flash('success', 'تم تحديث حالة القالب');
        redirect('/admin/templates');
    }

    public function setDefault(string $id): void
    {
        Auth::requireRole(['admin', 'manager']);
        Csrf::validate();
        $db = Database::getInstance();
        $t = $db->fetch('SELECT * FROM templates WHERE id=:id', ['id'=>(int)$id]);
        if ($t) {
            $this->clearDefault($t['document_type'], $t['language'], (int)$id);
            $db->update('templates', ['is_default'=>1,'is_active'=>1,'updated_at'=>now()], 'id=:id', ['id'=>(int)$id]);
        }
        flash('success', 'تم تعيين القالب الافتراضي');
        redirect('/admin/templates');
    }

    private function clearDefault(string $type, string $lang, int $exceptId): void
    {
        Database::getInstance()->query(
            'UPDATE templates SET is_default = 0 WHERE document_type = :t AND language = :l AND id != :id',
            ['t'=>$type,'l'=>$lang,'id'=>$exceptId]
        );
    }
}
