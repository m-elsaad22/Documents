<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\View;
use App\Core\ActivityLog;

class BackupController
{
    public function index(): void
    {
        Auth::requireRole(['admin']);
        $rows = Database::getInstance()->fetchAll('SELECT * FROM backups ORDER BY id DESC LIMIT 50');
        View::render('admin/backups/index', ['title' => 'النسخ الاحتياطي', 'backups' => $rows]);
    }

    public function create(): void
    {
        Auth::requireRole(['admin']);
        Csrf::validate();
        $dbCfg = require base_path('config/database.php');
        $dir = storage_path('backups');
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $file = 'backup_' . date('Ymd_His') . '.sql';
        $path = $dir . '/' . $file;

        if (($dbCfg['driver'] ?? '') === 'sqlite') {
            copy($dbCfg['database'], $dir . '/backup_' . date('Ymd_His') . '.sqlite');
            $file = basename($dir . '/backup_' . date('Ymd_His') . '.sqlite');
            // find newest
            $files = glob($dir . '/backup_*.sqlite');
            rsort($files);
            $full = $files[0];
            $file = basename($full);
            $size = filesize($full);
        } else {
            // Logical dump of key tables
            $db = Database::getInstance();
            $tables = ['companies','users','customers','templates','documents','document_items','document_media','document_sequences','settings','activity_logs'];
            $out = "-- KDMS Backup " . date('c') . "\n";
            foreach ($tables as $t) {
                try {
                    $rows = $db->fetchAll("SELECT * FROM $t");
                } catch (\Throwable $e) { continue; }
                $out .= "\n-- TABLE $t\n";
                foreach ($rows as $row) {
                    $cols = array_keys($row);
                    $vals = array_map(function ($v) {
                        if ($v === null) return 'NULL';
                        return "'" . str_replace("'", "''", (string)$v) . "'";
                    }, array_values($row));
                    $out .= 'INSERT INTO ' . $t . ' (' . implode(',', $cols) . ') VALUES (' . implode(',', $vals) . ");\n";
                }
            }
            file_put_contents($path, $out);
            $size = filesize($path);
            $full = $path;
        }

        $id = Database::getInstance()->insert('backups', [
            'company_id' => null,
            'created_by' => Auth::id(),
            'file_path' => 'storage/backups/' . $file,
            'file_size' => $size ?? 0,
            'notes' => 'Manual backup',
            'created_at' => now(),
        ]);
        ActivityLog::write('backup.create', 'backup', $id, 'Created backup');
        flash('success', 'تم إنشاء نسخة احتياطية');
        redirect('/admin/backups');
    }
}
