<?php
namespace App\Services;

use App\Core\Database;

/**
 * Auto document numbering: INV-2026-000001
 */
class DocumentNumberService
{
    public static function next(int $companyId, string $type): array
    {
        $db = Database::getInstance();
        $year = (int) date('Y');
        $prefix = config("doc_prefixes.$type", strtoupper(substr($type, 0, 3)));

        $db->beginTransaction();
        try {
            $seq = $db->fetch(
                'SELECT * FROM document_sequences WHERE company_id = :c AND document_type = :t AND year = :y',
                ['c' => $companyId, 't' => $type, 'y' => $year]
            );

            if (!$seq) {
                $db->insert('document_sequences', [
                    'company_id'    => $companyId,
                    'document_type' => $type,
                    'year'          => $year,
                    'last_number'   => 1,
                ]);
                $num = 1;
            } else {
                $num = (int) $seq['last_number'] + 1;
                $db->update(
                    'document_sequences',
                    ['last_number' => $num],
                    'id = :id',
                    ['id' => $seq['id']]
                );
            }

            $documentNumber = sprintf('%s-%d-%06d', $prefix, $year, $num);
            $publicSlug = $documentNumber;

            $db->commit();
            return [
                'document_number' => $documentNumber,
                'public_slug'     => $publicSlug,
                'sequence'        => $num,
            ];
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
