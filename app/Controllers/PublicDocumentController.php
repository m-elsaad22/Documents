<?php
namespace App\Controllers;

use App\Core\Database;
use App\Services\DocumentService;

class PublicDocumentController
{
    public function show(string $slug): void
    {
        $doc = DocumentService::findBySlug($slug);
        if (!$doc) {
            http_response_code(404);
            echo '<!DOCTYPE html><html lang="ar" dir="rtl"><body style="font-family:Cairo,sans-serif;text-align:center;padding:80px;"><h1>المستند غير موجود</h1></body></html>';
            return;
        }

        Database::getInstance()->query(
            'UPDATE documents SET views_count = views_count + 1 WHERE id = :id',
            ['id' => $doc['id']]
        );

        echo DocumentService::renderHtml((int) $doc['id'], true);
    }
}
