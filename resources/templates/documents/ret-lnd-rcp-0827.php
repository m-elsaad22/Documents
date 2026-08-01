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
<html lang="<?= e($lang) ?>" dir="<?= e($dir) ?>"><head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($doc['title'] ?? '') ?> | <?= e($companyName) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&amp;family=Cairo:wght@300;400;600;700;900&amp;display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root{
  --b1:#003087;--b2:#004EA8;--b3:#0070CC;
  --sky:#0096C7;--sky-l:#48CAE4;
  --gold:#D4A017;--gold-l:#F0C040;--gold-ll:#FFF0A0;
  --green:#38A169;--green-l:#68D391;
  --text:#0A1F3A;--muted:#4A6A8A;--white:#fff;--bg:#F0F7FF;
}
*{margin:0;padding:0;box-sizing:border-box;}
body{
  font-family:'Cairo',sans-serif;
  background:linear-gradient(135deg,#0A1628 0%,#0D2A5A 40%,#0A3070 100%);
  min-height:100vh;padding:20px 14px 36px;color:var(--text);
}

.print-bar{max-width:720px;margin:0 auto 12px;display:flex;gap:10px;}
.btn-print{
  display:inline-flex;align-items:center;gap:8px;padding:9px 22px;
  border-radius:9px;font-family:'Cairo',sans-serif;font-size:13px;font-weight:700;
  cursor:pointer;border:none;
  background:linear-gradient(135deg,#B8860B,var(--gold),var(--gold-l));
  color:#0A1628;
  box-shadow:0 4px 18px rgba(212,160,23,0.4),inset 0 1px 0 rgba(255,255,255,0.3);
  transition:all .22s;
}
.btn-dl{
  display:inline-flex;align-items:center;gap:8px;padding:9px 22px;
  border-radius:9px;font-family:'Cairo',sans-serif;font-size:13px;font-weight:700;
  cursor:pointer;border:none;
  background:linear-gradient(135deg,var(--b1),var(--b3));
  color:#fff;
  box-shadow:0 4px 18px rgba(0,80,170,0.35),inset 0 1px 0 rgba(255,255,255,0.15);
  transition:all .22s;
}
.btn-print:hover,.btn-dl:hover{transform:translateY(-2px);}

/* PAGE */
.page{
  max-width:720px;margin:0 auto;background:var(--white);
  border-radius:6px;overflow:hidden;
  box-shadow:0 0 0 1px rgba(0,150,199,0.22),0 8px 32px rgba(0,0,0,0.2),0 24px 60px rgba(0,0,0,0.14);
}

/* HEADER */
.ih{
  background:linear-gradient(150deg,#002060 0%,var(--b1) 25%,var(--b2) 50%,var(--b3) 75%,var(--b2) 100%);
  position:relative;overflow:hidden;padding:18px 32px 14px;
}
.ih::before{content:'';position:absolute;inset:0;
  background:repeating-linear-gradient(-45deg,transparent,transparent 22px,rgba(255,255,255,0.03) 22px,rgba(255,255,255,0.03) 23px);pointer-events:none;}
.ih::after{content:'';position:absolute;inset:0;
  background:linear-gradient(105deg,transparent 0%,rgba(173,232,244,0.12) 40%,rgba(255,255,255,0.06) 55%,transparent 100%);pointer-events:none;}
.orb{position:absolute;border-radius:50%;pointer-events:none;}
.orb1{width:220px;height:220px;top:-85px;left:-65px;background:radial-gradient(circle,rgba(255,255,255,0.1) 0%,transparent 65%);}
.orb2{width:140px;height:140px;bottom:-50px;right:45px;background:radial-gradient(circle,rgba(212,160,23,0.18) 0%,transparent 65%);}

.ih-top{display:flex;justify-content:space-between;align-items:center;position:relative;z-index:2;gap:12px;flex-wrap:wrap;}
.logo-a{display:flex;align-items:center;gap:12px;}
.lmark{
  width:44px;height:44px;border-radius:10px;
  background:linear-gradient(145deg,var(--gold-l),var(--gold),#B8860B);
  box-shadow:0 4px 14px rgba(212,160,23,0.45),inset 0 1px 0 rgba(255,255,255,0.45);
  display:flex;align-items:center;justify-content:center;font-size:19px;color:#0A1628;
}
.co-name{font-size:12px;font-weight:900;color:#fff;text-shadow:0 1px 5px rgba(0,0,0,0.35);line-height:1.4;}
.co-name-en{font-size:9px;font-weight:500;color:rgba(255,255,255,0.62);margin-top:2px;}
.co-sub{font-size:8px;color:rgba(255,255,255,0.65);letter-spacing:.4px;margin-top:2px;}

.doc-badge{
  background:rgba(255,255,255,0.13);border:1px solid rgba(255,255,255,0.25);
  border-radius:9px;padding:8px 16px;text-align:left;
  box-shadow:0 3px 14px rgba(0,0,0,0.1),inset 0 1px 0 rgba(255,255,255,0.18);backdrop-filter:blur(8px);
}
.db-type{font-size:8px;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,0.68);margin-bottom:3px;}
.db-num{font-size:16px;font-weight:900;
  background:linear-gradient(90deg,var(--gold-ll),var(--gold-l));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1;}
.db-date{font-size:9px;color:rgba(255,255,255,0.58);margin-top:3px;}

.ih-title{position:relative;z-index:2;text-align:center;padding:10px 0 2px;}
.bism{font-family:'Amiri',serif;font-size:13px;color:rgba(255,255,255,0.88);margin-bottom:6px;}
.title-box{
  display:inline-block;
  background:rgba(255,255,255,0.11);border:1px solid rgba(255,255,255,0.24);
  border-radius:9px;padding:8px 34px;backdrop-filter:blur(8px);
  box-shadow:0 3px 14px rgba(0,0,0,0.1),inset 0 1px 0 rgba(255,255,255,0.18);
}
.title-box h1{font-family:'Amiri',serif;font-size:18px;font-weight:700;color:#fff;text-shadow:0 1px 5px rgba(0,0,0,0.25);}

.stripe{height:3px;
  background:linear-gradient(90deg,#001560 0%,var(--b3) 20%,var(--sky-l) 40%,var(--gold) 60%,var(--sky-l) 80%,var(--b3) 100%);
  box-shadow:0 2px 8px rgba(33,150,243,0.3);}

/* BODY */
.rb{padding:18px 32px 22px;background:var(--bg);}

/* Amount hero */
.amount-hero{
  background:linear-gradient(135deg,var(--b1),var(--b2),#001560);
  border-radius:13px;padding:18px 24px;
  display:flex;justify-content:space-between;align-items:center;
  margin-bottom:14px;
  box-shadow:0 6px 24px rgba(0,30,100,0.3),inset 0 1px 0 rgba(255,255,255,0.1),inset 0 -1px 0 rgba(0,0,0,0.15);
}
.ah-left .ah-label{font-size:12.5px;font-weight:600;color:rgba(255,255,255,0.72);}
.ah-left .ah-sub{font-size:10px;color:rgba(255,255,255,0.42);margin-top:3px;}
.ah-badge{
  display:inline-flex;align-items:center;gap:6px;margin-top:10px;
  background:rgba(56,161,105,0.22);border:1px solid rgba(56,161,105,0.45);
  color:var(--green-l);padding:4px 13px;border-radius:50px;font-size:11px;font-weight:700;
}
.ah-badge::before{content:'';width:6px;height:6px;border-radius:50%;background:var(--green-l);flex-shrink:0;}
.ah-right{text-align:left;}
.ah-big{
  font-size:40px;font-weight:900;line-height:1;
  background:linear-gradient(90deg,var(--gold-ll),var(--gold-l),var(--gold));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
  filter:drop-shadow(0 2px 4px rgba(212,160,23,0.35));
}
.ah-cur{font-size:12px;color:rgba(255,255,255,0.48);margin-top:4px;}

/* info grid */
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;}
.info-cell{
  background:linear-gradient(135deg,rgba(10,22,40,0.03),rgba(33,150,243,0.05));
  border:1px solid rgba(33,150,243,0.12);border-radius:10px;
  padding:11px 14px;display:flex;gap:11px;align-items:flex-start;
}
.ic-icon{
  width:32px;height:32px;border-radius:8px;
  background:linear-gradient(135deg,var(--b1),var(--b2));
  display:flex;align-items:center;justify-content:center;
  font-size:13px;color:var(--gold-l);flex-shrink:0;
  box-shadow:0 2px 8px rgba(10,22,40,0.18);
}
.ic-label{font-size:9.5px;text-transform:uppercase;letter-spacing:.7px;color:var(--muted);margin-bottom:3px;font-weight:600;}
.ic-val{font-size:12.5px;font-weight:700;color:var(--text);line-height:1.35;}

/* desc box */
.desc-box{
  background:linear-gradient(135deg,rgba(10,22,40,0.03),rgba(33,150,243,0.04));
  border:1px solid rgba(33,150,243,0.12);
  border-right:4px solid var(--gold);
  border-radius:10px 0 0 10px;
  padding:12px 16px;margin-bottom:13px;
  font-size:12.5px;line-height:1.85;color:var(--text);
}
.desc-box strong{color:var(--b2);}

/* payment method */
.pay-method{
  display:flex;align-items:center;gap:12px;
  background:linear-gradient(135deg,rgba(33,150,243,0.05),rgba(10,22,40,0.03));
  border:1px solid rgba(33,150,243,0.12);border-radius:10px;
  padding:11px 16px;margin-bottom:13px;
}
.pm-icon{
  width:34px;height:34px;border-radius:8px;
  background:linear-gradient(135deg,var(--b2),var(--b3));
  display:flex;align-items:center;justify-content:center;
  color:var(--gold-l);font-size:15px;flex-shrink:0;
  box-shadow:0 2px 8px rgba(10,22,40,0.2);
}
.pm-label{font-size:10px;color:var(--muted);margin-bottom:2px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;}
.pm-val{font-size:13.5px;font-weight:800;color:var(--b1);}

/* written */
.written{
  text-align:center;font-family:'Amiri',serif;font-size:14px;
  color:var(--b1);background:rgba(0,112,204,0.05);
  border:1px dashed rgba(0,112,204,0.22);border-radius:7px;
  padding:9px 14px;margin-bottom:14px;
}
.written span{font-weight:700;color:var(--b2);}

/* note */
.info-note{
  background:rgba(212,160,23,0.06);border:1px solid rgba(212,160,23,0.22);
  border-right:4px solid var(--gold);border-radius:0 9px 9px 0;
  padding:9px 13px;margin-bottom:14px;
  font-size:11.5px;line-height:1.75;color:var(--text);
  display:flex;gap:9px;align-items:flex-start;
}
.note-icon{color:var(--gold);font-size:13px;margin-top:2px;flex-shrink:0;}

/* stamp */
.stamp-box{text-align:center;min-width:155px;}
.stamp-lbl{
  font-size:8.5px;font-weight:700;letter-spacing:1px;text-transform:uppercase;
  background:linear-gradient(90deg,var(--b3),var(--sky));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:4px;
}
.stamp-nm{font-size:11px;font-weight:800;color:var(--b1);margin-bottom:6px;line-height:1.3;}
.stamp-role{font-size:9px;color:var(--muted);margin-top:4px;}

/* footer */
.inf{
  background:linear-gradient(135deg,#001060,var(--b1),var(--b2));
  padding:12px 32px;
  box-shadow:inset 0 1px 0 rgba(255,255,255,0.07);
}
.inf-top{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:5px;margin-bottom:10px;}
.inf-top span{font-size:9px;color:rgba(255,255,255,0.38);}
.inf-top .rn{background:linear-gradient(90deg,var(--gold-l),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-weight:700;}
.inf-offices{display:flex;gap:10px;flex-wrap:wrap;}
.inf-office{
  flex:1;min-width:180px;
  background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.14);
  border-radius:8px;padding:8px 12px;
  display:flex;align-items:flex-start;gap:8px;
}
.inf-office-icon{
  width:24px;height:24px;border-radius:5px;flex-shrink:0;
  background:linear-gradient(135deg,rgba(212,160,23,0.35),rgba(240,192,64,0.25));
  border:1px solid rgba(212,160,23,0.3);
  display:flex;align-items:center;justify-content:center;font-size:10px;color:var(--gold-l);
}
.inf-office-text{font-size:9.5px;color:rgba(255,255,255,0.5);line-height:1.55;}
.inf-office-text strong{display:block;color:rgba(255,255,255,0.85);font-weight:800;font-size:10px;margin-bottom:1px;}

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
  <div class="ih">
    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div class="ih-top">
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
        <div class="db-type">رقم السند</div>
        <div class="db-num"><?= e($doc['document_number']) ?></div>
        <div class="db-date"><?= e($issueDate) ?></div>
      </div>
    </div>
    <div class="ih-title">
      <div class="bism">بسم الله الرحمن الرحيم</div>
      <div class="title-box"><h1><?= e($doc['title']) ?></h1></div>
    </div>
  </div>

  <div class="stripe"></div>

  <!-- BODY -->
  <div class="rb">

    <!-- Amount Hero -->
    <div class="amount-hero">
      <div class="ah-left">
        <div class="ah-label">المبلغ المستلم</div>
        <div class="ah-sub">أعمال إزالة ونقل شجرة الغاف — أبوظبي إلى العين</div>
        <div class="ah-badge">تم الاستلام</div>
      </div>
      <div class="ah-right">
        <div class="ah-big">2,000</div>
        <div class="ah-cur">درهم إماراتي (AED)</div>
      </div>
    </div>

    <!-- Client Info Grid -->
    <div class="info-grid">
      <div class="info-cell">
        <div class="ic-icon"><i class="fas fa-user-tie"></i></div>
        <div>
          <div class="ic-label">اسم العميل / الدافع</div>
          <div class="ic-val"><?= e($customerName) ?></div>
        </div>
      </div>
      <div class="info-cell">
        <div class="ic-icon"><i class="fas fa-calendar-day"></i></div>
        <div>
          <div class="ic-label">تاريخ الاستلام</div>
          <div class="ic-val"><?= e($issueDate) ?></div>
        </div>
      </div>
      <div class="info-cell" style="grid-column:1/-1;">
        <div class="ic-icon"><i class="fas fa-location-dot"></i></div>
        <div>
          <div class="ic-label">عنوان العميل</div>
          <div class="ic-val"><?= e($customerAddress) ?></div>
        </div>
      </div>
    </div>

    <!-- Description -->
    <div class="desc-box">
      <strong>وصف العمل المنجز:</strong><br>
      أعمال إزالة شجرة الغاف من موقعها في أبوظبي ونقلها إلى العين — تشمل: القلع الكامل مع الجذور، التحميل، النقل، وإعادة الزراعة في الموقع الجديد.
    </div>

    <!-- Payment Method -->
    <div class="pay-method">
      <div class="pm-icon"><i class="fas fa-money-bill-wave"></i></div>
      <div>
        <div class="pm-label">طريقة الدفع</div>
        <div class="pm-val"><?= e($doc['payment_method'] ?? '') ?></div>
      </div>
    </div>

    <!-- Written -->
    <div class="written">
      المبلغ كتابةً: <span><?= e($amountWords) ?></span>
    </div>

    <!-- Note -->
    <div class="info-note">
      <i class="fas fa-circle-info note-icon"></i>
      <div>
        <strong>ملاحظة:</strong> <?= nl2br(e($doc['notes'] ?? '')) ?></div>
    </div>

    <!-- Stamp -->
    
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
<div style="display:flex;justify-content:flex-end;">
      <div class="stamp-box">
        <div class="stamp-lbl">ختم وتوقيع الشركة</div>
        <div class="stamp-nm"><?= e($companyName) ?></div>
        <img src="<?= e($signature ?: asset('brand/sign-landscaping.webp')) ?>" alt="ختم وتوقيع" style="width:155px;height:155px;object-fit:contain;opacity:0.95;display:block;margin:4px auto 0;">
        <div class="stamp-role">Rukn El-Tatawer for Landscaping LLC</div>
      </div>
    </div>

  </div>

  <!-- FOOTER -->
  <div class="inf">
    <div class="inf-top">
      <span>ركن التطور لتنسيق الحدائق ذ.م.م — الإمارات العربية المتحدة</span>
      <span class="rn"><?= e($doc['document_number']) ?> &nbsp;|&nbsp; <?= e($issueDate) ?></span>
    </div>
    <div class="inf-offices">
      <div class="inf-office">
        <div class="inf-office-icon"><i class="fas fa-building"></i></div>
        <div class="inf-office-text">
          <strong>مكتب أبوظبي</strong>
          Office 306، برج A3، مدينة محمد بن زايد، أبوظبي
        </div>
      </div>
      <div class="inf-office">
        <div class="inf-office-icon"><i class="fas fa-city"></i></div>
        <div class="inf-office-text">
          <strong>مكتب دبي</strong>
          Office 214، برج الأعمال، شارع الشيخ زايد، دبي
        </div>
      </div>
    </div>
  </div>

</div>

<script>
function downloadHTML() {
  const filename = 'RET-LND-RCP-0619.html';
  const html = document.documentElement.outerHTML;
  const blob = new Blob([html], { type: 'text/html;charset=utf-8' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = filename;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
}
</script>
</body></html>