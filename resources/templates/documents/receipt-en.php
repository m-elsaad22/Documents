<?php
/**
 * KDMS Dynamic Template — design preserved 100% from official HTML.
 * Vars: $doc, $company, $customer, $items, $media
 */
$lang = $doc['language'] ?? 'ar';
$dir = ($lang === 'ar') ? 'rtl' : 'ltr';
$companyName = ($lang === 'ar')
    ? ($company['name_ar'] ?? '')
    : ($company['name_en'] ?? $company['name_ar'] ?? '');
$companyNameAlt = ($lang === 'ar')
    ? ($company['name_en'] ?? '')
    : ($company['name_ar'] ?? '');
$customerName = ($lang === 'ar')
    ? ($customer['name_ar'] ?? '')
    : ($customer['name_en'] ?? $customer['name_ar'] ?? '');
$customerAddress = ($lang === 'ar')
    ? ($customer['address_ar'] ?? '')
    : ($customer['address_en'] ?? $customer['address_ar'] ?? '');
$projectAddress = $doc['project_address'] ?: $customerAddress;
$issueDate = format_date($doc['issue_date'] ?? null, $lang);
$logo = !empty($company['logo']) ? upload_url($company['logo']) : '';
$seal = !empty($company['seal']) ? upload_url($company['seal']) : '';
$signature = !empty($company['signature']) ? upload_url($company['signature']) : '';
$currency = $doc['currency'] ?? ($company['currency'] ?? 'AED');
$currencyLabel = ($lang === 'ar')
    ? ($company['currency_label_ar'] ?? $currency)
    : ($company['currency_label_en'] ?? $currency);
$amountWords = ($lang === 'ar')
    ? ($doc['amount_words_ar'] ?? '')
    : ($doc['amount_words_en'] ?? $doc['amount_words_ar'] ?? '');
$cityCountry = trim(($company['city'] ?? '') . ' — ' . ($company['country'] ?? ''), ' —');
$offices = [];
if (!empty($company['offices_json'])) {
    $decoded = json_decode($company['offices_json'], true);
    if (is_array($decoded)) { $offices = $decoded; }
}
$custom = [];
if (!empty($doc['custom_fields'])) {
    $custom = is_array($doc['custom_fields'])
        ? $doc['custom_fields']
        : (json_decode($doc['custom_fields'], true) ?: []);
}
$mediaUrls = [];
foreach (($media ?? []) as $m) {
    if (!empty($m['file_path'])) {
        $mediaUrls[] = upload_url($m['file_path']);
    }
}
$totalFmt = number_format((float)($doc['total'] ?? 0), 2);
$totalFmtInt = number_format((float)($doc['total'] ?? 0), 0);
?>
<!DOCTYPE html>
<html lang="<?= e($lang) ?>" dir="<?= e($dir) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($doc['title'] ?? '') ?> | <?= e($companyName) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@300;400;600;700;900&family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --royal-1:#0A1628; --royal-2:#0D2246; --royal-3:#1A3A6E;
  --pepsi-1:#003087; --pepsi-2:#004EA8; --pepsi-3:#0070CC;
  --sky-2:#0096C7; --sky-3:#48CAE4;
  --gold-2:#B8860B; --gold-3:#D4A017; --gold-4:#F0C040; --gold-5:#FFF0A0;
  --silver-3:#CFD8DC; --white:#FFFFFF; --white-m:#F8FAFF;
  --text:#0D1B2A; --muted:#4A6080;
}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Cairo',sans-serif;background:linear-gradient(135deg,#0A1628 0%,#0D2A5A 40%,#0A3070 100%);min-height:100vh;padding:30px 16px 60px;color:var(--text);}

.print-bar{max-width:720px;margin:0 auto 18px;}
.btn-print{display:inline-flex;align-items:center;gap:9px;padding:11px 26px;border-radius:10px;font-family:'Cairo',sans-serif;font-size:14px;font-weight:700;cursor:pointer;border:none;background:linear-gradient(135deg,var(--gold-2),var(--gold-3),var(--gold-4));color:var(--royal-1);box-shadow:0 4px 20px rgba(212,160,23,0.4),inset 0 1px 0 rgba(255,255,255,0.3);transition:all 0.25s;}
.btn-print:hover{transform:translateY(-2px);}

.page{
  max-width:720px; margin:0 auto;
  background:var(--white); border-radius:4px; overflow:hidden;
  box-shadow:0 0 0 1px rgba(33,150,243,0.3),0 8px 32px rgba(0,0,0,0.25),0 32px 80px rgba(0,0,0,0.2);
}

/* HEADER */
.rh{
  background:linear-gradient(160deg,var(--royal-1) 0%,var(--royal-2) 25%,var(--pepsi-1) 55%,var(--royal-3) 80%,var(--royal-1) 100%);
  position:relative; overflow:hidden; padding:24px 36px 20px;
}
.rh::before{content:'';position:absolute;inset:0;background:repeating-linear-gradient(-45deg,transparent,transparent 24px,rgba(255,255,255,0.018) 24px,rgba(255,255,255,0.018) 25px);}
.orb{position:absolute;border-radius:50%;pointer-events:none;}
.orb1{width:260px;height:260px;top:-90px;right:-70px;background:radial-gradient(circle,rgba(33,150,243,0.2) 0%,transparent 65%);}
.orb2{width:160px;height:160px;bottom:-50px;left:60px;background:radial-gradient(circle,rgba(212,160,23,0.18) 0%,transparent 65%);}

.rh-top{display:flex;justify-content:space-between;align-items:center;position:relative;z-index:2;gap:16px;flex-wrap:wrap;}

.logo-a{display:flex;align-items:center;gap:14px;}
.lmark{width:52px;height:52px;border-radius:11px;background:linear-gradient(145deg,var(--gold-4),var(--gold-3),var(--gold-2));box-shadow:0 4px 16px rgba(212,160,23,0.5),inset 0 1px 0 rgba(255,255,255,0.4);display:flex;align-items:center;justify-content:center;font-size:22px;color:var(--royal-1);}
.co-name{font-size:11.5px;font-weight:900;color:#fff;text-shadow:0 1px 4px rgba(0,0,0,0.4);line-height:1.45;}
.co-name-en{font-size:10px;font-weight:500;color:rgba(255,255,255,0.6);margin-top:2px;}
.co-sub{font-size:9px;background:linear-gradient(90deg,var(--sky-3),var(--silver-3));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-top:3px;}

.doc-badge{
  background:linear-gradient(135deg,rgba(33,150,243,0.15),rgba(0,112,204,0.1));
  border:1px solid rgba(173,232,244,0.25); border-radius:10px;
  padding:10px 20px; text-align:left;
  box-shadow:0 4px 16px rgba(0,0,0,0.2),inset 0 1px 0 rgba(255,255,255,0.1);
}
.doc-badge .db-type{font-size:9px;letter-spacing:2px;text-transform:uppercase;color:var(--sky-3);margin-bottom:4px;}
.doc-badge .db-num{font-size:18px;font-weight:900;background:linear-gradient(90deg,var(--gold-4),var(--gold-3));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1;}
.doc-badge .db-date{font-size:10px;color:rgba(255,255,255,0.4);margin-top:3px;}

.rh-title{position:relative;z-index:2;text-align:center;padding:16px 0 4px;}
.title-box{display:inline-block;background:linear-gradient(135deg,rgba(33,150,243,0.15),rgba(0,112,204,0.1));border:1px solid rgba(173,232,244,0.25);border-radius:10px;padding:11px 40px;backdrop-filter:blur(10px);box-shadow:0 4px 16px rgba(0,0,0,0.2),inset 0 1px 0 rgba(255,255,255,0.1);}
.title-box h1{font-size:18px;font-weight:800;color:#fff;text-shadow:0 2px 8px rgba(0,0,0,0.4);letter-spacing:0.5px;}

.stripe{height:4px;background:linear-gradient(90deg,var(--royal-1) 0%,var(--pepsi-3) 20%,var(--sky-3) 40%,var(--gold-3) 60%,var(--sky-3) 80%,var(--pepsi-3) 100%);box-shadow:0 2px 10px rgba(33,150,243,0.4);}

/* BODY */
.rb{padding:28px 36px 32px;background:var(--white-m);}

.amount-hero{
  background:linear-gradient(135deg,var(--royal-1),var(--pepsi-1),var(--royal-2));
  border-radius:14px; padding:20px 28px;
  display:flex; justify-content:space-between; align-items:center;
  margin-bottom:24px;
  box-shadow:0 6px 24px rgba(10,22,40,0.3),inset 0 1px 0 rgba(33,150,243,0.2),inset 0 -1px 0 rgba(0,0,0,0.2);
}
.ah-label{font-size:13px;font-weight:600;color:rgba(255,255,255,0.7);}
.ah-label span{display:block;font-size:11px;color:rgba(255,255,255,0.4);margin-top:3px;}
.ah-amount{text-align:left;}
.ah-amount .big{font-size:38px;font-weight:900;background:linear-gradient(90deg,var(--gold-5),var(--gold-4),var(--gold-3));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;filter:drop-shadow(0 2px 4px rgba(212,160,23,0.4));line-height:1;}
.ah-amount .cur{font-size:13px;color:rgba(255,255,255,0.5);margin-top:4px;text-align:left;}
.ah-badge{background:rgba(56,161,105,0.2);border:1px solid rgba(56,161,105,0.4);color:#68D391;padding:5px 14px;border-radius:50px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:6px;margin-top:12px;}
.ah-badge::before{content:'';width:7px;height:7px;border-radius:50%;background:#68D391;flex-shrink:0;}

.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;}
.info-cell{background:linear-gradient(135deg,rgba(10,22,40,0.03),rgba(33,150,243,0.05));border:1px solid rgba(33,150,243,0.12);border-radius:10px;padding:12px 16px;display:flex;gap:11px;align-items:flex-start;}
.ic-icon{width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,var(--royal-2),var(--pepsi-2));display:flex;align-items:center;justify-content:center;font-size:13px;color:var(--gold-4);flex-shrink:0;box-shadow:0 2px 8px rgba(10,22,40,0.2);}
.ic-label{font-size:10px;text-transform:uppercase;letter-spacing:0.7px;color:var(--muted);margin-bottom:3px;font-weight:600;}
.ic-val{font-size:13px;font-weight:700;color:var(--text);line-height:1.3;}

.desc-box{
  background:linear-gradient(135deg,rgba(10,22,40,0.03),rgba(33,150,243,0.04));
  border:1px solid rgba(33,150,243,0.12);
  border-right:4px solid var(--gold-3);
  border-radius:10px 0 0 10px;
  padding:14px 18px; margin-bottom:16px;
  font-size:13.5px; line-height:1.9; color:var(--text);
}

.note-box{
  background:linear-gradient(135deg,rgba(240,192,64,0.08),rgba(212,160,23,0.05));
  border:1px solid rgba(212,160,23,0.3);
  border-right:4px solid var(--gold-3);
  border-radius:10px 0 0 10px;
  padding:12px 18px; margin-bottom:20px;
  font-size:12.5px; line-height:1.75; color:var(--text);
  display:flex; gap:10px; align-items:flex-start;
}
.note-icon{color:var(--gold-3);font-size:14px;margin-top:2px;flex-shrink:0;}

.pay-method{display:flex;align-items:center;gap:12px;background:linear-gradient(135deg,rgba(33,150,243,0.05),rgba(10,22,40,0.03));border:1px solid rgba(33,150,243,0.12);border-radius:10px;padding:13px 18px;margin-bottom:20px;}
.pm-icon{width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,var(--royal-3),var(--pepsi-2));display:flex;align-items:center;justify-content:center;color:var(--gold-4);font-size:16px;flex-shrink:0;box-shadow:0 2px 8px rgba(10,22,40,0.2);}
.pm-label{font-size:11px;color:var(--muted);margin-bottom:2px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;}
.pm-val{font-size:14px;font-weight:800;color:var(--royal-2);}

.written{text-align:center;font-size:14px;font-weight:700;color:var(--royal-2);background:rgba(33,150,243,0.04);border:1px dashed rgba(33,150,243,0.2);border-radius:8px;padding:11px 16px;margin-bottom:22px;letter-spacing:0.2px;}
.written em{font-style:normal;color:var(--pepsi-2);}

.sig-row{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:4px;}
.sig-box{background:linear-gradient(135deg,rgba(33,150,243,0.03),rgba(10,22,40,0.02));border:1px solid rgba(33,150,243,0.1);border-radius:10px;padding:16px 18px;text-align:center;}
.sb-lbl{font-size:9.5px;font-weight:700;letter-spacing:1px;text-transform:uppercase;background:linear-gradient(90deg,var(--pepsi-3),var(--sky-2));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:3px;}
.sb-nm{font-size:12.5px;font-weight:800;color:var(--royal-2);margin-bottom:12px;line-height:1.35;}
.sig-imgs{position:relative;width:140px;height:95px;margin:0 auto 6px;}
.sig-imgs img.seal{position:absolute;width:84px;height:84px;object-fit:contain;top:5px;right:0;opacity:0.88;}
.sig-imgs img.sign{position:absolute;width:110px;height:60px;object-fit:contain;bottom:0;left:0;z-index:2;}
.client-space{width:160px;height:75px;margin:0 auto 6px;border-bottom:2px solid var(--royal-3);}
.sb-role{font-size:10px;color:var(--muted);margin-top:4px;}

.rf{background:linear-gradient(135deg,var(--royal-1),var(--royal-2),var(--pepsi-1));padding:12px 36px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:6px;box-shadow:inset 0 1px 0 rgba(33,150,243,0.15);}
.rf span{font-size:10px;color:rgba(255,255,255,0.35);}
.rf .rn{background:linear-gradient(90deg,var(--gold-4),var(--gold-3));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-weight:700;}

@media print{
  body{background:#fff;padding:0;}
  .print-bar{display:none;}
  .page{max-width:100%;box-shadow:none;}
  *{-webkit-print-color-adjust:exact !important;print-color-adjust:exact !important;}
}
</style>

<?php if (empty($doc['show_seal'])): ?><style>.seal, img.seal, .stamp-wrap img, .stamp-box img{display:none!important;}</style><?php endif; ?>
<?php if (empty($doc['show_signature'])): ?><style>img.sign, .sign{display:none!important;}</style><?php endif; ?>
<style>
@media print{
  .kdms-toolbar,.no-print,.print-bar{display:none!important;}
  @page{size:A4 portrait;margin:10mm;}
  thead{display:table-header-group;}
  tr,img,.sig-row,.sig-box,.stamp-box,.info-grid,.amount-hero{break-inside:avoid;page-break-inside:avoid;}
  *{-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;}
}
</style>
</head>
<body>

<!-- KDMS_TOOLBAR -->

<div class="page">

  <!-- HEADER -->
  <div class="rh">
    <div class="orb orb1"></div>
    <div class="orb orb2"></div>

    <div class="rh-top">
      <div class="logo-a">
        <?php if ($logo): ?>
        <img class="kdms-logo" src="<?= e($logo) ?>" alt="logo" style="width:52px;height:52px;object-fit:contain;border-radius:11px;background:#fff;padding:2px;">
        <?php else: ?>
        <div class="lmark"><i class="fas fa-building"></i></div>
        <?php endif; ?>
        <div>
          <div class="co-name"><?= e($companyName) ?></div>
          <div class="co-name-en"><?= e($companyNameAlt) ?></div>
          <div class="co-sub"><?= e($cityCountry) ?></div>
        </div>
      </div>
      <div class="doc-badge">
        <div class="db-type">Receipt No.</div>
        <div class="db-num"><?= e($doc['document_number']) ?></div>
        <div class="db-date"><?= e($issueDate) ?></div>
      </div>
    </div>

    <div class="rh-title">
      <div class="title-box"><h1><?= e($doc['title']) ?></h1></div>
    </div>
  </div>

  <div class="stripe"></div>

  <!-- BODY -->
  <div class="rb">

    <!-- Amount Hero -->
    <div class="amount-hero">
      <div>
        <div class="ah-label">المبلغ المستلم<span>الدفعة الأولى 75% من قيمة العقد</span></div>
        <div class="ah-badge">تم الاستلام</div>
      </div>
      <div class="ah-amount">
        <div class="big"><?= e($totalFmtInt) ?></div>
        <div class="cur">درهم إماراتي (AED)</div>
      </div>
    </div>

    <!-- Info Grid -->
    <div class="info-grid">
      <div class="info-cell">
        <div class="ic-icon"><i class="fas fa-user-tie"></i></div>
        <div>
          <div class="ic-label">العميل / الدافع</div>
          <div class="ic-val"><?= e($customerName) ?></div>
        </div>
      </div>
      <div class="info-cell">
        <div class="ic-icon"><i class="fas fa-calendar-day"></i></div>
        <div>
          <div class="ic-label">Payment Date</div>
          <div class="ic-val"><?= e($issueDate) ?></div>
        </div>
      </div>
      <div class="info-cell" style="grid-column:1/-1;">
        <div class="ic-icon"><i class="fas fa-location-dot"></i></div>
        <div>
          <div class="ic-label">Project Address</div>
          <div class="ic-val"><?= e($projectAddress) ?></div>
        </div>
      </div>
    </div>

    <!-- Description -->
    <div class="desc-box">
      تم استلام المبلغ أعلاه من <strong>السيد / عادل عمار على سالم</strong> وذلك كـ <strong>دفعة أولى (75%)</strong> من القيمة Totalة للعقد رقم <strong>RET-CON-2026-0609</strong>، والمتعلق بـ:
      <ul style="margin-top:6px;margin-right:20px;">
        <li>تركيب زجاج سيكوريت 10 مم شفاف (29 متر مربع) — 7,250 درهم</li>
        <li>توريد وتركيب مكيف ستار تراك 1 طن — 700 درهم</li>
        <li>توريد وتركيب حامل مكيف — 50 درهم</li>
      </ul>
    </div>

    <!-- Note -->
    <div class="note-box">
      <i class="fas fa-circle-info note-icon"></i>
      <div><strong>ملاحظة مهمة:</strong> <?= nl2br(e($doc['notes'] ?? '')) ?></div>
    </div>

    <!-- Payment Method -->
    <div class="pay-method">
      <div class="pm-icon"><i class="fas fa-money-bill-wave"></i></div>
      <div>
        <div class="pm-label">Payment Method</div>
        <div class="pm-val"><?= e($doc['payment_method'] ?? '') ?></div>
      </div>
    </div>

    <!-- Written Amount -->
    <div class="written">
      Amount in words: &nbsp;<span><?= e($amountWords) ?></span>
    </div>

    <!-- Signatures -->
    
<?php if (!empty($doc['terms'])): ?>
<div class="kdms-terms" style="margin:14px 0;padding:12px 14px;border:1px solid rgba(0,112,204,.12);border-radius:10px;font-size:12.5px;line-height:1.8;break-inside:avoid;">
  <?= nl2br(e($doc['terms'])) ?>
</div>
<?php endif; ?>
<?php if (!empty($doc['conditions'])): ?>
<div class="kdms-conditions" style="margin:14px 0;padding:12px 14px;border:1px solid rgba(0,112,204,.12);border-radius:10px;font-size:12.5px;line-height:1.8;break-inside:avoid;">
  <?= $doc['conditions'] /* intentionally allows controlled HTML tables from admin */ ?>
</div>
<?php endif; ?>
<div class="sig-row">
      <div class="sig-box">
        <div class="sb-lbl">المقاول — الطرف الأول</div>
        <div class="sb-nm"><?= e($customerName) ?></div>
        <div class="sig-imgs">
          <img class="seal" src="<?= e($seal ?: asset('brand/khtm.webp')) ?>" alt="ختم الشركة">
          <img class="sign" src="<?= e($signature ?: asset('brand/sign.webp')) ?>" alt="توقيع المدير">
        </div>
        <div class="sb-role">المدير العام</div>
      </div>

      <div class="sig-box">
        <div class="sb-lbl">صاحب العمل — الطرف الثاني</div>
        <div class="sb-nm">عادل عمار على سالم</div>
        <div class="client-space"></div>
        <div class="sb-role">توقيع العميل</div>
      </div>
    </div>

  </div><!-- /rb -->

  <!-- FOOTER -->
  <div class="rf">
    <span>ركن التطور لأنظمة العزل الحديث ذ.م.م — أبوظبي، الإمارات</span>
    <span class="rn"><?= e($doc['document_number']) ?> &nbsp;|&nbsp; <?= e($issueDate) ?></span>
    <span>هذا السند مستند قانوني معتمد</span>
  </div>

</div><!-- /page -->

<!-- Addresses block below page -->
<div style="max-width:720px;margin:18px auto 0;display:flex;gap:12px;flex-wrap:wrap;">
  <div style="flex:1;min-width:220px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:10px;padding:12px 16px;display:flex;gap:10px;align-items:flex-start;">
    <i class="fas fa-building" style="color:var(--gold-4);margin-top:3px;font-size:13px;flex-shrink:0;"></i>
    <div>
      <div style="font-size:9px;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,0.4);margin-bottom:4px;font-weight:700;">مكتب دبي</div>
      <div style="font-size:11.5px;color:rgba(255,255,255,0.75);line-height:1.55;">05G-004, الطابق 17, برج Iris Bay,<br>الخليج التجاري (Business Bay)، دبي</div>
    </div>
  </div>
  <div style="flex:1;min-width:220px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:10px;padding:12px 16px;display:flex;gap:10px;align-items:flex-start;">
    <i class="fas fa-building" style="color:var(--gold-4);margin-top:3px;font-size:13px;flex-shrink:0;"></i>
    <div>
      <div style="font-size:9px;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,0.4);margin-bottom:4px;font-weight:700;">مكتب أبوظبي</div>
      <div style="font-size:11.5px;color:rgba(255,255,255,0.75);line-height:1.55;">مكتب 306, مجمع مزيد,<br>مدينة محمد بن زايد، أبوظبي</div>
    </div>
  </div>
</div>

</body>
</html>