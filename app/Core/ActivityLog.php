<?php
namespace App\Core;

/**
 * Activity logging
 */
class ActivityLog
{
    public static function write(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        ?string $description = null,
        ?array $meta = null
    ): void {
        try {
            Database::getInstance()->insert('activity_logs', [
                'company_id'  => Auth::companyId(),
                'user_id'     => Auth::id(),
                'action'      => $action,
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
                'description' => $description,
                'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent'  => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
                'meta_json'   => $meta ? json_encode($meta, JSON_UNESCAPED_UNICODE) : null,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            // Never break the app because of logging
            error_log('ActivityLog error: ' . $e->getMessage());
        }
    }
}
