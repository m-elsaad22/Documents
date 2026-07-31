<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Uploader;
use App\Core\View;
use App\Services\DocumentService;

class DocumentController
{
    public function index(string $type = ''): void
    {
        Auth::requireLogin();
        $type = $type ?: (string) input('type', '');
        $filters = [
            'document_type' => $type ?: null,
            'status' => input('status') ?: null,
            'customer_id' => input('customer_id') ?: null,
            'q' => input('q') ?: null,
            'date_from' => input('date_from') ?: null,
            'date_to' => input('date_to') ?: null,
            'company_id' => Auth::isAdmin() ? (input('company_id') ?: null) : Auth::companyId(),
        ];
        $page = max(1, (int) input('page', 1));
        $result = DocumentService::list(array_filter($filters), $page, 20);

        $title = $type ? doc_type_label($type) : 'المستندات';
        View::render('admin/documents/index', [
            'title' => $title,
            'type' => $type,
            'result' => $result,
            'filters' => $filters,
            'statuses' => config('statuses'),
        ]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        $type = (string) input('type', 'invoice');
        View::render('admin/documents/form', $this->formData($type, null));
    }

    public function store(): void
    {
        Auth::requireLogin();
        Csrf::validate();
        [$data, $items, $media] = $this->payload();
        $id = DocumentService::create($data, $items, $media);
        flash('success', 'تم إنشاء المستند بنجاح');
        redirect('/admin/documents/' . $id);
    }

    public function show(string $id): void
    {
        Auth::requireLogin();
        $doc = DocumentService::find((int) $id);
        if (!$doc) { flash('error','غير موجود'); redirect('/admin/documents'); }
        View::render('admin/documents/show', [
            'title' => $doc['document_number'],
            'doc' => $doc,
            'items' => DocumentService::items((int)$id),
            'media' => DocumentService::media((int)$id),
            'publicUrl' => url($doc['public_slug']),
        ]);
    }

    public function edit(string $id): void
    {
        Auth::requireLogin();
        $doc = DocumentService::find((int) $id);
        if (!$doc) { flash('error','غير موجود'); redirect('/admin/documents'); }
        View::render('admin/documents/form', $this->formData($doc['document_type'], $doc));
    }

    public function update(string $id): void
    {
        Auth::requireLogin();
        Csrf::validate();
        [$data, $items, $media] = $this->payload();
        DocumentService::update((int)$id, $data, $items, $media);
        flash('success', 'تم تحديث المستند');
        redirect('/admin/documents/' . $id);
    }

    public function delete(string $id): void
    {
        Auth::requireLogin();
        Csrf::validate();
        DocumentService::softDelete((int)$id);
        flash('success', 'تم حذف المستند');
        redirect('/admin/documents');
    }

    public function preview(string $id): void
    {
        Auth::requireLogin();
        echo DocumentService::renderHtml((int)$id, true);
        exit;
    }

    public function search(): void
    {
        Auth::requireLogin();
        $q = trim((string) input('q', ''));
        $filters = [
            'q' => $q,
            'company_id' => Auth::isAdmin() ? null : Auth::companyId(),
        ];
        $result = DocumentService::list(array_filter($filters), 1, 30);
        if (is_ajax()) {
            View::json(['success' => true, 'data' => $result['data']]);
        }
        View::render('admin/documents/index', [
            'title' => 'نتائج البحث',
            'type' => '',
            'result' => $result,
            'filters' => $filters,
            'statuses' => config('statuses'),
        ]);
    }

    private function formData(string $type, ?array $doc): array
    {
        $db = Database::getInstance();
        $companyId = $doc['company_id'] ?? Auth::companyId();
        $companies = Auth::isAdmin()
            ? $db->fetchAll('SELECT id, name_ar, currency FROM companies WHERE is_active=1 ORDER BY name_ar')
            : $db->fetchAll('SELECT id, name_ar, currency FROM companies WHERE id=:id', ['id' => Auth::companyId()]);

        $customersSql = 'SELECT id, name_ar, company_id FROM customers WHERE is_active=1';
        $cparams = [];
        if (!Auth::isAdmin() && Auth::companyId()) {
            $customersSql .= ' AND company_id = :cid';
            $cparams['cid'] = Auth::companyId();
        }
        $customersSql .= ' ORDER BY name_ar';

        $templates = $db->fetchAll(
            'SELECT * FROM templates WHERE document_type = :t AND is_active = 1 ORDER BY is_default DESC, language, name',
            ['t' => $type]
        );

        return [
            'title' => $doc ? 'تعديل مستند' : 'إنشاء مستند',
            'type' => $type,
            'doc' => $doc,
            'items' => $doc ? DocumentService::items((int)$doc['id']) : [],
            'media' => $doc ? DocumentService::media((int)$doc['id']) : [],
            'companies' => $companies,
            'customers' => $db->fetchAll($customersSql, $cparams),
            'templates' => $templates,
            'statuses' => config('statuses'),
        ];
    }

    private function payload(): array
    {
        $type = (string) input('document_type', 'invoice');
        $companyId = Auth::isAdmin() ? (int) input('company_id') : (int) Auth::companyId();
        $data = [
            'company_id' => $companyId,
            'customer_id' => (int) input('customer_id') ?: null,
            'template_id' => (int) input('template_id') ?: null,
            'document_type' => $type,
            'language' => in_array(input('language'), ['ar','en'], true) ? input('language') : 'ar',
            'title' => trim((string) input('title')),
            'status' => (string) input('status', 'draft'),
            'issue_date' => input('issue_date') ?: today(),
            'due_date' => input('due_date') ?: null,
            'valid_until' => input('valid_until') ?: null,
            'currency' => input('currency') ?: 'AED',
            'discount' => (float) input('discount', 0),
            'tax_rate' => (float) input('tax_rate', 0),
            'amount_paid' => (float) input('amount_paid', 0),
            'amount_words_ar' => trim((string) input('amount_words_ar')),
            'amount_words_en' => trim((string) input('amount_words_en')),
            'payment_method' => trim((string) input('payment_method')),
            'project_address' => trim((string) input('project_address')),
            'notes' => trim((string) input('notes')),
            'terms' => trim((string) input('terms')),
            'conditions' => trim((string) input('conditions')),
            'show_signature' => input('show_signature') ? 1 : 0,
            'show_seal' => input('show_seal') ? 1 : 0,
        ];
        if ($data['title'] === '') {
            $data['title'] = doc_type_label($type) . ' ' . date('Y-m-d');
        }

        $titles = $_POST['item_title'] ?? [];
        $descs = $_POST['item_description'] ?? [];
        $qtys = $_POST['item_qty'] ?? [];
        $units = $_POST['item_unit'] ?? [];
        $prices = $_POST['item_price'] ?? [];
        $items = [];
        foreach ($titles as $i => $title) {
            if (trim((string)$title) === '') continue;
            $items[] = [
                'title' => $title,
                'description' => $descs[$i] ?? '',
                'quantity' => $qtys[$i] ?? 1,
                'unit' => $units[$i] ?? '',
                'unit_price' => $prices[$i] ?? 0,
            ];
        }

        $media = [];
        if (!empty($_FILES['media']['name']) && is_array($_FILES['media']['name'])) {
            foreach ($_FILES['media']['name'] as $i => $name) {
                if (!$name) continue;
                $file = [
                    'name' => $_FILES['media']['name'][$i],
                    'type' => $_FILES['media']['type'][$i],
                    'tmp_name' => $_FILES['media']['tmp_name'][$i],
                    'error' => $_FILES['media']['error'][$i],
                    'size' => $_FILES['media']['size'][$i],
                ];
                $path = Uploader::image($file, 'documents');
                if ($path) {
                    $media[] = [
                        'type' => 'image',
                        'title' => $_POST['media_title'][$i] ?? '',
                        'caption' => $_POST['media_caption'][$i] ?? '',
                        'file_path' => $path,
                    ];
                }
            }
        }

        return [$data, $items, $media];
    }
}
