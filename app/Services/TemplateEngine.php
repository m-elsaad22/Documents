<?php
namespace App\Services;

/**
 * Renders document HTML templates with dynamic data.
 * Templates are PHP files that receive $doc, $company, $customer, $items, $media.
 * Design CSS/structure must stay identical to original HTML designs.
 */
class TemplateEngine
{
    public static function render(string $templatePath, array $data): string
    {
        $full = base_path($templatePath);
        if (!is_file($full)) {
            // Fall back to bundled templates
            $full = base_path('resources/templates/documents/' . basename($templatePath));
        }
        if (!is_file($full)) {
            throw new \RuntimeException('Template not found: ' . $templatePath);
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $full;
        return (string) ob_get_clean();
    }

    /**
     * Inject action toolbar (PDF / Print / Share / Copy) into rendered HTML
     */
    public static function injectToolbar(string $html, array $doc, string $publicUrl, ?string $whatsapp = null): string
    {
        $isAr = ($doc['language'] ?? 'ar') === 'ar';
        $waText = urlencode(($doc['title'] ?? '') . ' — ' . $publicUrl);
        $waPhone = preg_replace('/\D+/', '', (string) $whatsapp);

        $toolbar = '
<div class="kdms-toolbar no-print" style="max-width:820px;margin:0 auto 14px;display:flex;flex-wrap:wrap;gap:8px;justify-content:center;font-family:Cairo,sans-serif;">
  <button type="button" onclick="window.print()" class="kdms-btn" style="padding:10px 18px;border:none;border-radius:10px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#B8860B,#D4A017,#F0C040);color:#0A1628;">
    ' . ($isAr ? 'طباعة / PDF' : 'Print / PDF') . '
  </button>
  <button type="button" onclick="kdmsCopyLink()" class="kdms-btn" style="padding:10px 18px;border:none;border-radius:10px;font-weight:700;cursor:pointer;background:#003087;color:#fff;">
    ' . ($isAr ? 'نسخ الرابط' : 'Copy Link') . '
  </button>
  <a href="https://wa.me/' . e($waPhone) . '?text=' . $waText . '" target="_blank" rel="noopener" class="kdms-btn" style="padding:10px 18px;border-radius:10px;font-weight:700;text-decoration:none;background:#25D366;color:#fff;display:inline-flex;align-items:center;">
    ' . ($isAr ? 'واتساب' : 'WhatsApp') . '
  </a>
  <a href="mailto:?subject=' . rawurlencode($doc['title'] ?? '') . '&body=' . rawurlencode($publicUrl) . '" class="kdms-btn" style="padding:10px 18px;border-radius:10px;font-weight:700;text-decoration:none;background:#0070CC;color:#fff;display:inline-flex;align-items:center;">
    ' . ($isAr ? 'بريد إلكتروني' : 'Email') . '
  </a>
</div>
<script>
function kdmsCopyLink(){
  const url = ' . json_encode($publicUrl) . ';
  if (navigator.clipboard) { navigator.clipboard.writeText(url).then(()=>alert(' . json_encode($isAr ? 'تم نسخ الرابط' : 'Link copied') . ')); }
  else { prompt(' . json_encode($isAr ? 'انسخ الرابط:' : 'Copy link:') . ', url); }
}
</script>
<style>
@media print{
  .kdms-toolbar, .no-print, .print-bar{display:none !important;}
  @page{size:A4 portrait;margin:10mm;}
  body{background:#fff !important;padding:0 !important;}
  .page{box-shadow:none !important;max-width:100% !important;margin:0 !important;}
  thead{display:table-header-group;}
  tr, .sig-row, .sig-box, .stamp-box, .info-grid, .amount-hero, img{
    break-inside:avoid; page-break-inside:avoid;
  }
  *{-webkit-print-color-adjust:exact !important; print-color-adjust:exact !important;}
}
</style>';

        // Prefer replacing existing print-bar; else inject after <body>
        if (preg_match('/<div class="print-bar"[\s\S]*?<\/div>/i', $html)) {
            $html = preg_replace('/<div class="print-bar"[\s\S]*?<\/div>/i', $toolbar, $html, 1);
        } elseif (preg_match('/<body[^>]*>/i', $html)) {
            $html = preg_replace('/<body[^>]*>/i', '$0' . $toolbar, $html, 1);
        } else {
            $html = $toolbar . $html;
        }

        return $html;
    }
}
