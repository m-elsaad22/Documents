<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;

class LogController
{
    public function index(): void
    {
        Auth::requireRole(['admin', 'manager']);
        $db = Database::getInstance();
        $sql = 'SELECT l.*, u.name AS user_name FROM activity_logs l LEFT JOIN users u ON u.id = l.user_id WHERE 1=1';
        $params = [];
        if (!Auth::isAdmin() && Auth::companyId()) {
            $sql .= ' AND l.company_id = :cid';
            $params['cid'] = Auth::companyId();
        }
        $sql .= ' ORDER BY l.id DESC LIMIT 200';
        View::render('admin/logs/index', [
            'title' => 'سجل النشاط',
            'logs' => $db->fetchAll($sql, $params),
        ]);
    }
}
