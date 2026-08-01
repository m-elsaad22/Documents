<?php
namespace App\Services;

/**
 * Renders document HTML templates with dynamic data.
 * Templates are PHP files that receive $doc, $company, $customer, $items, $media.
 * Design CSS/structure must stay identical to original HTML designs.
 *
 * Mobile viewing scales the original design to fit the phone width
 * without changing layout/colors/fonts. Print/PDF keeps full A4 design.
 */
class TemplateEngine
{
    public static function render(string $templatePath, array $data): string
    {
        $full = base_path($templatePath);
        if (!is_file($full)) {
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
     * Inject action toolbar + mobile fit + print rules into rendered HTML
     */
    public static function injectToolbar(string $html, array $doc, string $publicUrl, ?string $whatsapp = null): string
    {
        $isAr = ($doc['language'] ?? 'ar') === 'ar';
        $waText = urlencode(($doc['title'] ?? '') . ' — ' . $publicUrl);
        $waPhone = preg_replace('/\D+/', '', (string) $whatsapp);

        $toolbar = '
<!-- KDMS Viewer Controls (does not alter document design) -->
<div class="kdms-toolbar no-print">
  <button type="button" onclick="window.print()" class="kdms-btn kdms-btn-gold">' . ($isAr ? 'طباعة / PDF' : 'Print / PDF') . '</button>
  <button type="button" onclick="kdmsCopyLink()" class="kdms-btn kdms-btn-blue">' . ($isAr ? 'نسخ الرابط' : 'Copy Link') . '</button>
  <a href="https://wa.me/' . e($waPhone) . '?text=' . $waText . '" target="_blank" rel="noopener" class="kdms-btn kdms-btn-wa">' . ($isAr ? 'واتساب' : 'WhatsApp') . '</a>
  <a href="mailto:?subject=' . rawurlencode($doc['title'] ?? '') . '&body=' . rawurlencode($publicUrl) . '" class="kdms-btn kdms-btn-mail">' . ($isAr ? 'بريد' : 'Email') . '</a>
</div>
<div class="kdms-stage no-print-reset" id="kdms-stage"></div>
<script>
(function(){
  const copyMsg = ' . json_encode($isAr ? 'تم نسخ الرابط' : 'Link copied') . ';
  const copyPrompt = ' . json_encode($isAr ? 'انسخ الرابط:' : 'Copy link:') . ';
  const publicUrl = ' . json_encode($publicUrl) . ';

  window.kdmsCopyLink = function(){
    if (navigator.clipboard) navigator.clipboard.writeText(publicUrl).then(function(){ alert(copyMsg); });
    else prompt(copyPrompt, publicUrl);
  };

  function pickRoot(){
    return document.querySelector(".page")
      || document.querySelector(".container")
      || document.querySelector("main.document")
      || null;
  }

  function fitDocument(){
    var root = pickRoot();
    if (!root) return;

    // Reset before measuring
    root.style.transform = "";
    root.style.transformOrigin = "top center";
    root.style.marginLeft = "";
    root.style.marginRight = "";
    root.style.width = root.dataset.kdmsDesignWidth ? (root.dataset.kdmsDesignWidth + "px") : "";

    // Remember original design width once (keeps design 100%)
    if (!root.dataset.kdmsDesignWidth) {
      var natural = Math.max(root.scrollWidth, root.offsetWidth, 720);
      // Prefer CSS max-width when present
      var cs = window.getComputedStyle(root);
      var maxW = parseFloat(cs.maxWidth);
      if (!isNaN(maxW) && maxW > 0 && maxW < 2000) natural = maxW;
      root.dataset.kdmsDesignWidth = String(Math.round(natural));
    }

    var designW = parseFloat(root.dataset.kdmsDesignWidth) || 720;
    root.style.width = designW + "px";
    root.style.maxWidth = designW + "px";
    root.style.boxSizing = "border-box";

    var pad = window.innerWidth < 480 ? 12 : 20;
    var available = Math.max(280, window.innerWidth - pad);
    var scale = available / designW;
    if (scale > 1) scale = 1;

    root.style.transformOrigin = "top center";
    root.style.transform = "scale(" + scale + ")";

    // Compensate layout height after scale so page is not cut off
    var fullH = root.scrollHeight;
    var scaledH = fullH * scale;
    root.style.marginBottom = Math.max(0, (scaledH - fullH)) + "px";

    // Center horizontally when scaled
    if (scale < 1) {
      var offset = (available - designW) / 2;
      // With transform-origin top center + parent padding, auto margins are enough
      root.style.marginLeft = "auto";
      root.style.marginRight = "auto";
    }

    document.documentElement.style.setProperty("--kdms-scale", String(scale));
  }

  function boot(){
    // Soften body padding on phones without touching .page internals
    document.documentElement.classList.add("kdms-mobile-ready");
    fitDocument();
    window.addEventListener("resize", function(){
      clearTimeout(window.__kdmsFitT);
      window.__kdmsFitT = setTimeout(fitDocument, 120);
    });
    window.addEventListener("orientationchange", function(){ setTimeout(fitDocument, 220); });
    // Re-fit after fonts/images load
    window.addEventListener("load", fitDocument);
    setTimeout(fitDocument, 350);
    setTimeout(fitDocument, 1200);
  }

  if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", boot);
  else boot();
})();
</script>
<style id="kdms-viewer-css">
/* Toolbar */
.kdms-toolbar{
  position: sticky; top: 0; z-index: 9999;
  display:flex; flex-wrap:wrap; gap:8px; justify-content:center; align-items:center;
  max-width: 920px; margin: 0 auto 12px; padding: 10px 12px;
  font-family: Cairo, Tajawal, sans-serif;
  background: rgba(7,26,48,.92);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,.12);
  border-radius: 14px;
  box-shadow: 0 10px 28px rgba(0,0,0,.25);
}
.kdms-btn{
  display:inline-flex; align-items:center; justify-content:center;
  min-height: 40px; padding: 8px 14px; border:0; border-radius:10px;
  font: inherit; font-weight:800; font-size:13px; cursor:pointer; text-decoration:none !important;
  color:#fff; white-space:nowrap;
}
.kdms-btn-gold{background:linear-gradient(135deg,#B8860B,#D4A017,#F0C040); color:#0A1628 !important;}
.kdms-btn-blue{background:#003087;}
.kdms-btn-wa{background:#25D366;}
.kdms-btn-mail{background:#0070CC;}

/* Screen: keep original design, only scale to viewport */
@media screen {
  html.kdms-mobile-ready body{
    overflow-x: hidden !important;
  }
  @media (max-width: 900px) {
    html.kdms-mobile-ready body{
      padding-left: 6px !important;
      padding-right: 6px !important;
      padding-top: 10px !important;
    }
    .kdms-toolbar{
      position: sticky;
      margin: 0 4px 10px;
      border-radius: 12px;
      gap: 6px;
      padding: 8px;
    }
    .kdms-btn{
      flex: 1 1 calc(50% - 6px);
      min-width: calc(50% - 6px);
      font-size: 12.5px;
      padding: 10px 8px;
    }
    /* Prevent accidental horizontal overflow from side ornaments */
    img, svg, video, canvas { max-width: 100%; }
  }
}

/* Print / PDF: original design at A4, no scaling */
@media print{
  .kdms-toolbar, .no-print, .print-bar, .kdms-stage{ display:none !important; }
  @page{ size: A4 portrait; margin: 10mm; }
  html, body{
    background: #fff !important;
    padding: 0 !important;
    margin: 0 !important;
    overflow: visible !important;
  }
  .page, .container{
    transform: none !important;
    width: auto !important;
    max-width: 100% !important;
    margin: 0 auto !important;
    margin-bottom: 0 !important;
    box-shadow: none !important;
  }
  thead{ display: table-header-group; }
  tr, .sig-row, .sig-box, .stamp-box, .info-grid, .amount-hero, img, figure, .kdms-media{
    break-inside: avoid;
    page-break-inside: avoid;
  }
  *{
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
}
</style>
';

        // Ensure viewport exists / refresh it
        if (preg_match('/<meta[^>]+name=["\']viewport["\'][^>]*>/i', $html)) {
            $html = preg_replace(
                '/<meta[^>]+name=["\']viewport["\'][^>]*>/i',
                '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">',
                $html,
                1
            );
        } elseif (preg_match('/<head[^>]*>/i', $html)) {
            $html = preg_replace(
                '/<head[^>]*>/i',
                '$0<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">',
                $html,
                1
            );
        }

        // Prefer replacing existing print-bar; else inject after <body>
        if (preg_match('/<!--\s*KDMS_TOOLBAR\s*-->/i', $html)) {
            $html = preg_replace('/<!--\s*KDMS_TOOLBAR\s*-->/i', $toolbar, $html, 1);
        } elseif (preg_match('/<div class="print-bar"[\s\S]*?<\/div>/i', $html)) {
            $html = preg_replace('/<div class="print-bar"[\s\S]*?<\/div>/i', $toolbar, $html, 1);
        } elseif (preg_match('/<div class="no-print"[\s\S]*?<\/div>/i', $html)) {
            $html = preg_replace('/<div class="no-print"[\s\S]*?<\/div>/i', $toolbar, $html, 1);
        } elseif (preg_match('/<body[^>]*>/i', $html)) {
            $html = preg_replace('/<body[^>]*>/i', '$0' . $toolbar, $html, 1);
        } else {
            $html = $toolbar . $html;
        }

        return $html;
    }
}
