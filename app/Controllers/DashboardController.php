<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;

class DashboardController
{
    public function index(): void
    {
        Auth::requireLogin();
        $db = Database::getInstance();
        $companyFilter = '';
        $params = [];
        if (!Auth::isAdmin() && Auth::companyId()) {
            $companyFilter = ' AND company_id = :cid';
            $params['cid'] = Auth::companyId();
        }

        $stats = [
            'companies' => (int) $db->fetch('SELECT COUNT(*) AS c FROM companies WHERE is_active = 1' . (Auth::isAdmin() ? '' : ' AND id = ' . (int) Auth::companyId()))['c'],
            'customers' => (int) $db->fetch('SELECT COUNT(*) AS c FROM customers WHERE is_active = 1' . ($companyFilter ? str_replace('company_id', 'company_id', $companyFilter) : ''), $params)['c'],
            'documents' => (int) $db->fetch('SELECT COUNT(*) AS c FROM documents WHERE deleted_at IS NULL' . $companyFilter, $params)['c'],
            'invoices' => (int) $db->fetch("SELECT COUNT(*) AS c FROM documents WHERE deleted_at IS NULL AND document_type='invoice'" . $companyFilter, $params)['c'],
            'quotations' => (int) $db->fetch("SELECT COUNT(*) AS c FROM documents WHERE deleted_at IS NULL AND document_type='quotation'" . $companyFilter, $params)['c'],
            'contracts' => (int) $db->fetch("SELECT COUNT(*) AS c FROM documents WHERE deleted_at IS NULL AND document_type='contract'" . $companyFilter, $params)['c'],
        ];

        $recent = $db->fetchAll(
            'SELECT d.*, c.name_ar AS customer_name FROM documents d
             LEFT JOIN customers c ON c.id = d.customer_id
             WHERE d.deleted_at IS NULL' . ($companyFilter ? ' AND d.company_id = :cid' : '') . '
             ORDER BY d.id DESC LIMIT 10',
            $params
        );

        $byStatus = $db->fetchAll(
            'SELECT status, COUNT(*) AS cnt FROM documents WHERE deleted_at IS NULL' . $companyFilter . ' GROUP BY status',
            $params
        );

        View::render('admin/dashboard', [
            'title' => 'لوحة التحكم',
            'stats' => $stats,
            'recent' => $recent,
            'byStatus' => $byStatus,
        ]);
    }
}
