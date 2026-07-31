<?php
/**
 * KDMS Dynamic Document Template
 * Design preserved 100% from official HTML.
 * Available vars: $doc, $company, $customer, $items, $media
 */
$lang = $doc['language'] ?? 'ar';
$dir = $lang === 'ar' ? 'rtl' : 'ltr';
$companyName = $lang === 'ar' ? ($company['name_ar'] ?? '') : ($company['name_en'] ?? $company['name_ar'] ?? '');
$companyNameAlt = $lang === 'ar' ? ($company['name_en'] ?? '') : ($company['name_ar'] ?? '');
$customerName = $lang === 'ar'
    ? ($customer['name_ar'] ?? $doc['customer_name_ar'] ?? '')
    : ($customer['name_en'] ?? $customer['name_ar'] ?? $doc['customer_name_en'] ?? '');
$customerAddress = $lang === 'ar'
    ? ($customer['address_ar'] ?? $doc['project_address'] ?? '')
    : ($customer['address_en'] ?? $customer['address_ar'] ?? $doc['project_address'] ?? '');
$projectAddress = $doc['project_address'] ?: $customerAddress;
$issueDate = format_date($doc['issue_date'] ?? null, $lang);
$logo = !empty($company['logo']) ? upload_url($company['logo']) : '';
$seal = !empty($company['seal']) ? upload_url($company['seal']) : '';
$signature = !empty($company['signature']) ? upload_url($company['signature']) : '';
$currency = $doc['currency'] ?? ($company['currency'] ?? 'AED');
$currencyLabel = $lang === 'ar' ? ($company['currency_label_ar'] ?? $currency) : ($company['currency_label_en'] ?? $currency);
$amountWords = $lang === 'ar' ? ($doc['amount_words_ar'] ?? '') : ($doc['amount_words_en'] ?? $doc['amount_words_ar'] ?? '');
$cityCountry = trim(($company['city'] ?? '') . ' — ' . ($company['country'] ?? ''), ' —');
$offices = [];
if (!empty($company['offices_json'])) {
    $offices = json_decode($company['offices_json'], true) ?: [];
}
$custom = [];
if (!empty($doc['custom_fields'])) {
    $custom = is_array($doc['custom_fields']) ? $doc['custom_fields'] : (json_decode($doc['custom_fields'], true) ?: []);
}
?>
<!DOCTYPE html>
<html lang="<?= e($lang) ?>" dir="<?= e($dir) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($doc['title']) ?> | <?= e($companyName) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root{--b1:#003087;--b2:#004EA8;--b3:#0070CC;--gold:#D4A017;--gold-l:#F0C040;--text:#0A1F3A;--muted:#4A6A8A;--white:#fff;--bg:#F0F7FF;}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Cairo',sans-serif;background:linear-gradient(135deg,#0A1628 0%,#0D2A5A 40%,#0A3070 100%);min-height:100vh;padding:20px 14px 36px;color:var(--text);}
.page{max-width:720px;margin:0 auto;background:var(--white);border-radius:6px;overflow:hidden;box-shadow:0 0 0 1px rgba(0,150,199,0.22),0 8px 32px rgba(0,0,0,0.2);}
.ih{background:linear-gradient(150deg,#002060 0%,var(--b1) 25%,var(--b2) 50%,var(--b3) 75%,var(--b2) 100%);position:relative;overflow:hidden;padding:18px 32px 14px;}
.ih-top{display:flex;justify-content:space-between;align-items:center;position:relative;z-index:2;gap:12px;flex-wrap:wrap;}
.co-name{font-size:12px;font-weight:900;color:#fff;line-height:1.4;}
.co-name-en{font-size:9px;color:rgba(255,255,255,0.62);margin-top:2px;}
.co-sub{font-size:8px;color:rgba(255,255,255,0.65);margin-top:2px;}
.doc-badge{background:rgba(255,255,255,0.13);border:1px solid rgba(255,255,255,0.25);border-radius:9px;padding:8px 16px;text-align:left;}
.db-type{font-size:8px;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,0.68);margin-bottom:3px;}
.db-num{font-size:16px;font-weight:900;background:linear-gradient(90deg,#FFF0A0,var(--gold-l));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.db-date{font-size:9px;color:rgba(255,255,255,0.58);margin-top:3px;}
.ih-title{position:relative;z-index:2;text-align:center;padding:10px 0 2px;}
.title-box{display:inline-block;background:rgba(255,255,255,0.11);border:1px solid rgba(255,255,255,0.24);border-radius:9px;padding:8px 34px;}
.title-box h1{font-family:'Amiri',serif;font-size:18px;font-weight:700;color:#fff;}
.stripe{height:3px;background:linear-gradient(90deg,#001560 0%,var(--b3) 20%,#48CAE4 40%,var(--gold) 60%,#48CAE4 80%,var(--b3) 100%);}
.ib{padding:18px 32px 20px;background:var(--bg);}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;}
.info-cell{background:linear-gradient(135deg,rgba(10,22,40,0.03),rgba(33,150,243,0.05));border:1px solid rgba(33,150,243,0.12);border-radius:10px;padding:11px 14px;}
.ic-label{font-size:9.5px;text-transform:uppercase;letter-spacing:.7px;color:var(--muted);margin-bottom:3px;font-weight:600;}
.ic-val{font-size:12.5px;font-weight:700;color:var(--text);}
.cert-body{background:#fff;border:1px solid rgba(0,112,204,0.12);border-radius:10px;padding:18px;margin-bottom:14px;font-size:13px;line-height:1.9;}
.sig-row{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:10px;}
.sig-box{background:#fff;border:1px solid rgba(33,150,243,0.1);border-radius:10px;padding:16px;text-align:center;}
.inf{background:linear-gradient(135deg,#001060,var(--b1),var(--b2));padding:12px 32px;display:flex;justify-content:space-between;gap:6px;flex-wrap:wrap;}
.inf span{font-size:9px;color:rgba(255,255,255,0.38);}
.inf .rn{background:linear-gradient(90deg,var(--gold-l),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-weight:700;}
@media print{body{background:#fff;padding:0}.page{box-shadow:none;max-width:100%}*{ -webkit-print-color-adjust:exact!important;print-color-adjust:exact!important } @page{size:A4 portrait;margin:10mm}}
</style>
</head>
<body>
<!-- KDMS_TOOLBAR -->
<div class="page">
  <div class="ih">
    <div class="ih-top">
      <div style="display:flex;align-items:center;gap:12px;">
        <?php if ($logo): ?><img src="<?= e($logo) ?>" alt="logo" style="width:48px;height:48px;object-fit:contain;border-radius:10px;background:#fff;padding:2px;"><?php endif; ?>
        <div>
          <div class="co-name"><?= e($companyName) ?></div>
          <div class="co-name-en"><?= e($companyNameAlt) ?></div>
          <div class="co-sub"><?= e($cityCountry) ?></div>
        </div>
      </div>
      <div class="doc-badge">
        <div class="db-type"><?= $lang==='ar'?'رقم الشهادة':'Certificate No.' ?></div>
        <div class="db-num"><?= e($doc['document_number']) ?></div>
        <div class="db-date"><?= e($issueDate) ?></div>
      </div>
    </div>
    <div class="ih-title"><div class="title-box"><h1><?= e($doc['title']) ?></h1></div></div>
  </div>
  <div class="stripe"></div>
  <div class="ib">
    <div class="info-grid">
      <div class="info-cell"><div class="ic-label"><?= $lang==='ar'?'العميل':'Customer' ?></div><div class="ic-val"><?= e($customerName) ?></div></div>
      <div class="info-cell"><div class="ic-label"><?= $lang==='ar'?'تاريخ الإصدار':'Issue Date' ?></div><div class="ic-val"><?= e($issueDate) ?></div></div>
      <div class="info-cell" style="grid-column:1/-1;"><div class="ic-label"><?= $lang==='ar'?'عنوان المشروع':'Project Address' ?></div><div class="ic-val"><?= e($projectAddress) ?></div></div>
    </div>
    <div class="cert-body">
      <?= nl2br(e($doc['notes'] ?? ($lang==='ar'?'تشهد الشركة بأن الأعمال المذكورة قد تم تنفيذها وفقاً للمواصفات المتفق عليها وتحت ضمان الشركة للمدة المحددة.':'The company certifies that the mentioned works were executed per agreed specifications under company warranty for the specified period.'))) ?>
      <?php if (!empty($doc['terms'])): ?><div style="margin-top:12px;"><?= nl2br(e($doc['terms'])) ?></div><?php endif; ?>
    </div>
    <?php if (!empty($media)): ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:14px;">
      <?php foreach ($media as $m): if (!empty($m['file_path'])): ?>
      <img src="<?= e(upload_url($m['file_path'])) ?>" alt="" style="width:100%;max-height:220px;object-fit:cover;border-radius:8px;break-inside:avoid;">
      <?php endif; endforeach; ?>
    </div>
    <?php endif; ?>
    <div class="sig-row">
      <div class="sig-box">
        <div style="font-size:11px;font-weight:800;margin-bottom:8px;"><?= e($companyName) ?></div>
        <?php if ($seal): ?><img src="<?= e($seal) ?>" style="width:90px;height:90px;object-fit:contain;"><?php endif; ?>
        <?php if ($signature): ?><img src="<?= e($signature) ?>" style="width:120px;height:60px;object-fit:contain;display:block;margin:6px auto;"><?php endif; ?>
      </div>
      <div class="sig-box">
        <div style="font-size:11px;font-weight:800;margin-bottom:8px;"><?= e($customerName) ?></div>
        <div style="height:75px;border-bottom:2px solid #1A3A6E;margin:20px auto 0;width:160px;"></div>
      </div>
    </div>
  </div>
  <div class="inf">
    <span><?= e($companyName) ?></span>
    <span class="rn"><?= e($doc['document_number']) ?> | <?= e($issueDate) ?></span>
  </div>
</div>
</body>
</html>
