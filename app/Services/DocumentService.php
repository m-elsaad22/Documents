<?php
namespace App\Services;

use App\Core\Database;
use App\Core\ActivityLog;
use App\Core\Auth;

/**
 * Document CRUD & rendering service
 */
class DocumentService
{
    public static function find(int $id): ?array
    {
        return Database::getInstance()->fetch(
            'SELECT d.*, c.name_ar AS customer_name_ar, c.name_en AS customer_name_en,
                    c.email AS customer_email, c.phone AS customer_phone,
                    c.address_ar AS customer_address_ar, c.address_en AS customer_address_en,
                    co.name_ar AS company_name_ar
             FROM documents d
             LEFT JOIN customers c ON c.id = d.customer_id
             LEFT JOIN companies co ON co.id = d.company_id
             WHERE d.id = :id AND d.deleted_at IS NULL',
            ['id' => $id]
        );
    }

    public static function findBySlug(string $slug): ?array
    {
        return Database::getInstance()->fetch(
            'SELECT * FROM documents WHERE public_slug = :s AND deleted_at IS NULL LIMIT 1',
            ['s' => $slug]
        );
    }

    public static function items(int $documentId): array
    {
        return Database::getInstance()->fetchAll(
            'SELECT * FROM document_items WHERE document_id = :id ORDER BY sort_order ASC, id ASC',
            ['id' => $documentId]
        );
    }

    public static function media(int $documentId): array
    {
        return Database::getInstance()->fetchAll(
            'SELECT * FROM document_media WHERE document_id = :id ORDER BY sort_order ASC, id ASC',
            ['id' => $documentId]
        );
    }

    public static function company(int $companyId): ?array
    {
        return Database::getInstance()->fetch('SELECT * FROM companies WHERE id = :id', ['id' => $companyId]);
    }

    public static function customer(?int $customerId): ?array
    {
        if (!$customerId) {
            return null;
        }
        return Database::getInstance()->fetch('SELECT * FROM customers WHERE id = :id', ['id' => $customerId]);
    }

    public static function template(?int $templateId): ?array
    {
        if (!$templateId) {
            return null;
        }
        return Database::getInstance()->fetch('SELECT * FROM templates WHERE id = :id', ['id' => $templateId]);
    }

    public static function defaultTemplate(string $type, string $lang, ?int $companyId = null): ?array
    {
        $db = Database::getInstance();
        $row = $db->fetch(
            'SELECT * FROM templates
             WHERE document_type = :t AND language = :l AND is_active = 1
               AND (company_id IS NULL OR company_id = :c)
             ORDER BY is_default DESC, company_id DESC, id ASC LIMIT 1',
            ['t' => $type, 'l' => $lang, 'c' => $companyId]
        );
        return $row;
    }

    public static function create(array $data, array $items = [], array $media = []): int
    {
        $db = Database::getInstance();
        $companyId = (int) $data['company_id'];
        $type = $data['document_type'];
        $numbers = DocumentNumberService::next($companyId, $type);

        $db->beginTransaction();
        try {
            $totals = self::calcTotals($items, (float) ($data['discount'] ?? 0), (float) ($data['tax_rate'] ?? 0));

            $id = $db->insert('documents', [
                'company_id'      => $companyId,
                'customer_id'     => $data['customer_id'] ?: null,
                'template_id'     => $data['template_id'] ?: null,
                'created_by'      => Auth::id(),
                'document_type'   => $type,
                'document_number' => $numbers['document_number'],
                'public_slug'     => $numbers['public_slug'],
                'language'        => $data['language'] ?? 'ar',
                'title'           => $data['title'],
                'status'          => $data['status'] ?? 'draft',
                'issue_date'      => $data['issue_date'] ?? today(),
                'due_date'        => !empty($data['due_date']) ? $data['due_date'] : null,
                'valid_until'     => !empty($data['valid_until']) ? $data['valid_until'] : null,
                'currency'        => $data['currency'] ?? 'AED',
                'subtotal'        => $totals['subtotal'],
                'discount'        => $totals['discount'],
                'tax_rate'        => $totals['tax_rate'],
                'tax_amount'      => $totals['tax_amount'],
                'total'           => $totals['total'],
                'amount_paid'     => $data['amount_paid'] ?? 0,
                'amount_words_ar' => $data['amount_words_ar'] ?? null,
                'amount_words_en' => $data['amount_words_en'] ?? null,
                'payment_method'  => $data['payment_method'] ?? null,
                'project_address' => $data['project_address'] ?? null,
                'notes'           => $data['notes'] ?? null,
                'terms'           => $data['terms'] ?? null,
                'conditions'      => $data['conditions'] ?? null,
                'custom_fields'   => isset($data['custom_fields']) ? json_encode($data['custom_fields'], JSON_UNESCAPED_UNICODE) : null,
                'show_signature'  => isset($data['show_signature']) ? 1 : 0,
                'show_seal'       => isset($data['show_seal']) ? 1 : 0,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            self::syncItems($id, $items);
            self::syncMedia($id, $media);

            $db->commit();
            ActivityLog::write('document.create', 'document', $id, 'Created ' . $numbers['document_number']);
            return $id;
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function update(int $id, array $data, array $items = [], array $media = []): void
    {
        $db = Database::getInstance();
        $db->beginTransaction();
        try {
            $totals = self::calcTotals($items, (float) ($data['discount'] ?? 0), (float) ($data['tax_rate'] ?? 0));

            $db->update('documents', [
                'customer_id'     => $data['customer_id'] ?: null,
                'template_id'     => $data['template_id'] ?: null,
                'language'        => $data['language'] ?? 'ar',
                'title'           => $data['title'],
                'status'          => $data['status'] ?? 'draft',
                'issue_date'      => $data['issue_date'] ?? today(),
                'due_date'        => !empty($data['due_date']) ? $data['due_date'] : null,
                'valid_until'     => !empty($data['valid_until']) ? $data['valid_until'] : null,
                'currency'        => $data['currency'] ?? 'AED',
                'subtotal'        => $totals['subtotal'],
                'discount'        => $totals['discount'],
                'tax_rate'        => $totals['tax_rate'],
                'tax_amount'      => $totals['tax_amount'],
                'total'           => $totals['total'],
                'amount_paid'     => $data['amount_paid'] ?? 0,
                'amount_words_ar' => $data['amount_words_ar'] ?? null,
                'amount_words_en' => $data['amount_words_en'] ?? null,
                'payment_method'  => $data['payment_method'] ?? null,
                'project_address' => $data['project_address'] ?? null,
                'notes'           => $data['notes'] ?? null,
                'terms'           => $data['terms'] ?? null,
                'conditions'      => $data['conditions'] ?? null,
                'custom_fields'   => isset($data['custom_fields']) ? json_encode($data['custom_fields'], JSON_UNESCAPED_UNICODE) : null,
                'show_signature'  => !empty($data['show_signature']) ? 1 : 0,
                'show_seal'       => !empty($data['show_seal']) ? 1 : 0,
                'updated_at'      => now(),
            ], 'id = :id', ['id' => $id]);

            $db->delete('document_items', 'document_id = :id', ['id' => $id]);
            self::syncItems($id, $items);

            // Keep existing media unless replaced — sync new uploads
            if (!empty($media)) {
                self::syncMedia($id, $media, false);
            }

            $db->commit();
            ActivityLog::write('document.update', 'document', $id, 'Updated document');
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function softDelete(int $id): void
    {
        Database::getInstance()->update('documents', [
            'deleted_at' => now(),
            'updated_at' => now(),
        ], 'id = :id', ['id' => $id]);
        ActivityLog::write('document.delete', 'document', $id, 'Deleted document');
    }

    public static function renderHtml(int $id, bool $withToolbar = true): string
    {
        $doc = self::find($id);
        if (!$doc) {
            throw new \RuntimeException('Document not found');
        }

        $company = self::company((int) $doc['company_id']);
        $customer = self::customer($doc['customer_id'] ? (int) $doc['customer_id'] : null);
        $items = self::items($id);
        $media = self::media($id);
        $template = self::template($doc['template_id'] ? (int) $doc['template_id'] : null);

        if (!$template) {
            $template = self::defaultTemplate($doc['document_type'], $doc['language'], (int) $doc['company_id']);
        }
        if (!$template) {
            throw new \RuntimeException('No template available for this document');
        }

        $html = TemplateEngine::render($template['file_path'], [
            'doc'      => $doc,
            'company'  => $company,
            'customer' => $customer,
            'items'    => $items,
            'media'    => $media,
            'template' => $template,
        ]);

        if ($withToolbar) {
            $publicUrl = url($doc['public_slug']);
            $html = TemplateEngine::injectToolbar($html, $doc, $publicUrl);
        }

        return $html;
    }

    public static function list(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $db = Database::getInstance();
        $where = ['d.deleted_at IS NULL'];
        $params = [];

        if (!empty($filters['company_id'])) {
            $where[] = 'd.company_id = :company_id';
            $params['company_id'] = $filters['company_id'];
        }
        if (!empty($filters['document_type'])) {
            $where[] = 'd.document_type = :document_type';
            $params['document_type'] = $filters['document_type'];
        }
        if (!empty($filters['status'])) {
            $where[] = 'd.status = :status';
            $params['status'] = $filters['status'];
        }
        if (!empty($filters['customer_id'])) {
            $where[] = 'd.customer_id = :customer_id';
            $params['customer_id'] = $filters['customer_id'];
        }
        if (!empty($filters['q'])) {
            $where[] = '(d.document_number LIKE :q OR d.title LIKE :q OR c.name_ar LIKE :q OR c.name_en LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }
        if (!empty($filters['date_from'])) {
            $where[] = 'd.issue_date >= :date_from';
            $params['date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $where[] = 'd.issue_date <= :date_to';
            $params['date_to'] = $filters['date_to'];
        }

        $sqlWhere = implode(' AND ', $where);
        $total = (int) $db->fetch(
            "SELECT COUNT(*) AS cnt FROM documents d LEFT JOIN customers c ON c.id = d.customer_id WHERE $sqlWhere",
            $params
        )['cnt'];

        $offset = max(0, ($page - 1) * $perPage);
        $rows = $db->fetchAll(
            "SELECT d.*, c.name_ar AS customer_name, co.name_ar AS company_name
             FROM documents d
             LEFT JOIN customers c ON c.id = d.customer_id
             LEFT JOIN companies co ON co.id = d.company_id
             WHERE $sqlWhere
             ORDER BY d.id DESC
             LIMIT $perPage OFFSET $offset",
            $params
        );

        return [
            'data' => $rows,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'pages' => (int) ceil(max(1, $total) / $perPage),
        ];
    }

    private static function calcTotals(array $items, float $discount, float $taxRate): array
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $qty = (float) ($item['quantity'] ?? 1);
            $price = (float) ($item['unit_price'] ?? 0);
            $lineDisc = (float) ($item['discount'] ?? 0);
            $subtotal += max(0, ($qty * $price) - $lineDisc);
        }
        $subtotal = round($subtotal, 2);
        $discount = round($discount, 2);
        $taxable = max(0, $subtotal - $discount);
        $taxAmount = round($taxable * ($taxRate / 100), 2);
        $total = round($taxable + $taxAmount, 2);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total' => $total,
        ];
    }

    private static function syncItems(int $documentId, array $items): void
    {
        $db = Database::getInstance();
        $i = 0;
        foreach ($items as $item) {
            if (trim($item['title'] ?? '') === '') {
                continue;
            }
            $qty = (float) ($item['quantity'] ?? 1);
            $price = (float) ($item['unit_price'] ?? 0);
            $disc = (float) ($item['discount'] ?? 0);
            $lineTotal = max(0, ($qty * $price) - $disc);
            $db->insert('document_items', [
                'document_id' => $documentId,
                'sort_order'  => $i++,
                'item_number' => $item['item_number'] ?? null,
                'title'       => $item['title'],
                'description' => $item['description'] ?? null,
                'quantity'    => $qty,
                'unit'        => $item['unit'] ?? null,
                'unit_price'  => $price,
                'discount'    => $disc,
                'tax_rate'    => (float) ($item['tax_rate'] ?? 0),
                'total'       => $lineTotal,
            ]);
        }
    }

    private static function syncMedia(int $documentId, array $media, bool $replace = false): void
    {
        $db = Database::getInstance();
        if ($replace) {
            $db->delete('document_media', 'document_id = :id', ['id' => $documentId]);
        }
        $i = 0;
        foreach ($media as $m) {
            if (empty($m['file_path']) && empty($m['content_html'])) {
                continue;
            }
            $db->insert('document_media', [
                'document_id'  => $documentId,
                'type'         => $m['type'] ?? 'image',
                'title'        => $m['title'] ?? null,
                'caption'      => $m['caption'] ?? null,
                'file_path'    => $m['file_path'] ?? null,
                'content_html' => $m['content_html'] ?? null,
                'sort_order'   => $i++,
                'created_at'   => now(),
            ]);
        }
    }
}
