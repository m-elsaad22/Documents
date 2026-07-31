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
<title><?= e($doc['title'] ?? '') ?> | <?= e($companyName) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Cairo:wght@300;400;500;600;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  /* Water-deep blues */
  --deep:    #040E1A;
  --ocean:   #062340;
  --marine:  #0A3D6B;
  --lagoon:  #0E6B9A;
  --aqua:    #0FA3C8;
  --crystal: #4EC9E0;
  --foam:    #AEE8F4;
  /* Gold */
  --gold-dk: #8B6200;
  --gold:    #C89000;
  --gold-m:  #E8B020;
  --gold-lt: #F5D060;
  --gold-xl: #FFF0A8;
  /* Neutrals */
  --ink:     #080F1A;
  --slate:   #1E3050;
  --steel:   #3A5A7A;
  --mist:    #6A8AA8;
  --silver:  #B8CCE0;
  --pearl:   #EDF4FA;
  --white:   #FFFFFF;
  --border:  rgba(14,107,154,0.15);
}

*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

body {
  font-family:'Cairo',sans-serif;
  background: linear-gradient(160deg, var(--deep) 0%, var(--ocean) 40%, #071828 100%);
  min-height:100vh;
  padding:32px 16px 60px;
  color:var(--ink);
}

/* ── PRINT BUTTON ── */
.no-print { max-width:900px; margin:0 auto 20px; display:flex; gap:10px; }
.btn-dl {
  display:inline-flex; align-items:center; gap:8px;
  padding:11px 24px; border-radius:9px; cursor:pointer; border:none;
  font-family:'Cairo',sans-serif; font-size:13.5px; font-weight:700;
  background:linear-gradient(135deg,var(--gold-dk),var(--gold),var(--gold-lt));
  color:var(--deep); box-shadow:0 4px 18px rgba(200,144,0,.4);
  transition:all .25s;
}
.btn-dl:hover { transform:translateY(-2px); }

/* ── PAGE ── */
.page {
  max-width:900px; margin:0 auto;
  background:var(--white); overflow:hidden;
  border-radius:3px;
  box-shadow:
    0 0 0 1px rgba(14,107,154,.3),
    0 4px 8px rgba(0,0,0,.1),
    0 16px 48px rgba(0,0,0,.25),
    0 48px 100px rgba(0,0,0,.18);
}

/* ── HEADER ── */
.hdr {
  background: linear-gradient(165deg,
    var(--deep) 0%,
    var(--ocean) 30%,
    var(--marine) 60%,
    var(--lagoon) 100%);
  position:relative; overflow:hidden; padding:0;
}

/* Water shimmer overlay */
.hdr::before {
  content:'';
  position:absolute; inset:0;
  background:
    repeating-linear-gradient(
      -55deg,
      transparent 0px, transparent 18px,
      rgba(255,255,255,.022) 18px, rgba(255,255,255,.022) 19px
    );
}

/* Light caustics */
.hdr::after {
  content:'';
  position:absolute; inset:0;
  background:
    radial-gradient(ellipse at 70% 30%, rgba(14,163,200,.25) 0%, transparent 55%),
    radial-gradient(ellipse at 20% 80%, rgba(200,144,0,.12) 0%, transparent 45%);
}

/* Animated pool ripple */
.ripple-wrap {
  position:absolute; bottom:0; left:0; right:0; height:120px;
  overflow:hidden; z-index:1;
}
.ripple-svg { width:100%; height:100%; }

.hdr-inner { position:relative; z-index:2; }

/* Top bar */
.hdr-top {
  display:flex; justify-content:space-between; align-items:center;
  padding:26px 42px 20px; gap:16px; flex-wrap:wrap;
  border-bottom:1px solid rgba(255,255,255,.07);
}

.brand { display:flex; align-items:center; gap:14px; }
.brand-icon {
  width:56px; height:56px; border-radius:13px; flex-shrink:0;
  background:linear-gradient(145deg,var(--gold-lt),var(--gold-m),var(--gold));
  box-shadow:0 4px 18px rgba(200,144,0,.5),inset 0 1px 0 rgba(255,255,255,.5);
  display:flex; align-items:center; justify-content:center;
  font-size:24px; color:var(--deep);
}
.brand-name { font-size:14px; font-weight:900; color:#fff; line-height:1.25; text-shadow:0 1px 4px rgba(0,0,0,.3); }
.brand-sub  { font-size:8.5px; color:rgba(255,255,255,.5); margin-top:3px; letter-spacing:.5px; }

.doc-badge {
  background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2);
  border-radius:10px; padding:10px 20px; text-align:left;
  backdrop-filter:blur(8px); box-shadow:inset 0 1px 0 rgba(255,255,255,.15);
}
.db-lbl { font-size:8.5px; letter-spacing:2px; text-transform:uppercase; color:rgba(255,255,255,.5); margin-bottom:4px; }
.db-num {
  font-size:17px; font-weight:900; line-height:1;
  background:linear-gradient(90deg,var(--gold-xl),var(--gold-lt));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
}
.db-date { font-size:10px; color:rgba(255,255,255,.4); margin-top:4px; }

/* Hero area */
.hdr-hero {
  padding:32px 42px 110px;
  text-align:center;
}
.contract-eyebrow {
  display:inline-block;
  font-size:10px; letter-spacing:4px; text-transform:uppercase;
  color:var(--crystal); border:1px solid rgba(78,201,224,.3);
  border-radius:4px; padding:5px 16px; margin-bottom:16px;
}
.contract-title {
  font-family:'Playfair Display',serif;
  font-size:clamp(22px,4vw,34px); font-weight:800;
  color:#fff; text-shadow:0 2px 10px rgba(0,0,0,.4);
  line-height:1.25; margin-bottom:10px;
}
.contract-title em { font-style:italic; color:var(--crystal); }
.contract-sub { font-size:13px; color:rgba(255,255,255,.55); line-height:1.7; max-width:540px; margin:0 auto; }

/* Client strip inside hero */
.client-strip {
  display:flex; gap:10px; justify-content:center; flex-wrap:wrap; margin-top:22px;
}
.cs-pill {
  background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2);
  border-radius:8px; padding:7px 16px; font-size:12px; color:#fff;
  display:flex; align-items:center; gap:7px;
}
.cs-pill i { color:var(--gold-lt); font-size:11px; }

/* ── GOLD STRIPE ── */
.gold-stripe {
  height:5px;
  background:linear-gradient(90deg,
    var(--deep) 0%, var(--lagoon) 15%, var(--aqua) 30%,
    var(--gold) 50%, var(--aqua) 70%, var(--lagoon) 85%, var(--deep) 100%);
  box-shadow:0 2px 14px rgba(14,107,154,.4);
}

/* ── BODY ── */
.body { padding:36px 42px 40px; background:var(--pearl); }

/* Preamble */
.preamble {
  background:#fff; border:1px solid var(--border);
  border-right:5px solid var(--aqua);
  border-radius:0 12px 12px 0;
  padding:18px 22px; margin-bottom:28px;
  font-size:13.5px; line-height:2.1; color:var(--slate);
  font-family:'Amiri',serif;
}
.preamble strong { color:var(--marine); }

/* Section header */
.sec {
  display:flex; align-items:center; gap:12px;
  margin-bottom:16px; border-bottom:1.5px solid rgba(14,107,154,.1);
  padding-bottom:12px;
}
.sec-icon {
  width:38px; height:38px; border-radius:10px; flex-shrink:0;
  display:flex; align-items:center; justify-content:center;
  font-size:16px;
  box-shadow:0 3px 10px rgba(0,0,0,.12);
}
.ic-aqua { background:linear-gradient(135deg,var(--lagoon),var(--aqua)); color:#fff; }
.ic-gold { background:linear-gradient(135deg,var(--gold-dk),var(--gold-m)); color:#fff; }
.ic-deep { background:linear-gradient(135deg,var(--deep),var(--marine)); color:#fff; }
.ic-green{ background:linear-gradient(135deg,#0A5C2A,#1A8A45); color:#fff; }

.sec-num {
  width:22px; height:22px; border-radius:50%; flex-shrink:0;
  background:linear-gradient(135deg,var(--lagoon),var(--aqua));
  color:#fff; font-size:11px; font-weight:900;
  display:flex; align-items:center; justify-content:center;
}
.sec h2 { font-family:'Playfair Display',serif; font-size:17px; font-weight:700; color:var(--deep); }
.sec-line { flex:1; height:1px; background:rgba(14,107,154,.1); }

.block { margin-bottom:26px; }

/* Parties */
.parties { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.party {
  background:#fff; border:1px solid var(--border); border-radius:12px;
  overflow:hidden; box-shadow:0 2px 10px rgba(14,107,154,.06);
}
.party-head {
  background:linear-gradient(135deg,var(--marine),var(--lagoon));
  padding:10px 16px; font-size:12px; font-weight:700; color:#fff;
  display:flex; align-items:center; gap:8px;
}
.party-head i { color:var(--gold-lt); }
.party-body { padding:14px 16px; }
.pr { display:flex; gap:8px; margin-bottom:6px; font-size:12.5px; }
.pr:last-child { margin-bottom:0; }
.pr-l { color:var(--mist); min-width:72px; flex-shrink:0; }
.pr-v { font-weight:700; color:var(--slate); line-height:1.35; }

/* Pool spec card */
.pool-spec {
  background:linear-gradient(135deg,var(--deep),var(--marine));
  border-radius:14px; padding:20px 24px; color:#fff;
  display:flex; align-items:center; gap:24px; flex-wrap:wrap;
  box-shadow:0 6px 24px rgba(4,14,26,.3),inset 0 1px 0 rgba(255,255,255,.07);
}
.pool-icon-big {
  width:64px; height:64px; flex-shrink:0;
  background:linear-gradient(145deg,var(--gold-lt),var(--gold-m),var(--gold));
  border-radius:16px;
  display:flex; align-items:center; justify-content:center;
  font-size:28px; color:var(--deep);
  box-shadow:0 4px 16px rgba(200,144,0,.5),inset 0 1px 0 rgba(255,255,255,.4);
}
.pool-details { flex:1; }
.pool-details h3 { font-size:16px; font-weight:800; color:#fff; margin-bottom:8px; }
.pool-tags { display:flex; gap:9px; flex-wrap:wrap; }
.ptag {
  background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2);
  border-radius:7px; padding:5px 13px; font-size:12px; color:#fff;
  display:flex; align-items:center; gap:6px;
}
.ptag i { color:var(--gold-lt); font-size:11px; }

/* Work steps */
.steps { list-style:none; display:grid; grid-template-columns:1fr 1fr; gap:11px; }
.step {
  background:#fff; border:1px solid var(--border); border-radius:10px;
  padding:13px 15px; display:flex; gap:12px; align-items:flex-start;
  box-shadow:0 1px 6px rgba(14,107,154,.05); transition:box-shadow .2s;
}
.step:hover { box-shadow:0 4px 14px rgba(14,107,154,.1); }
.step-num {
  width:28px; height:28px; border-radius:50%; flex-shrink:0;
  background:linear-gradient(135deg,var(--lagoon),var(--aqua));
  color:#fff; font-size:12px; font-weight:800;
  display:flex; align-items:center; justify-content:center;
  box-shadow:0 2px 8px rgba(14,107,154,.3);
}
.step-title { font-size:12.5px; font-weight:800; color:var(--marine); margin-bottom:3px; }
.step-desc  { font-size:11.5px; color:var(--mist); line-height:1.55; }

/* Chemicals */
.chem-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:11px; }
.chem-card {
  background:#fff; border:1px solid var(--border); border-radius:10px;
  padding:13px 15px; display:flex; gap:10px; align-items:flex-start;
  box-shadow:0 1px 6px rgba(14,107,154,.05);
}
.chem-dot {
  width:10px; height:10px; border-radius:50%; flex-shrink:0; margin-top:5px;
}
.chem-name  { font-size:12.5px; font-weight:800; color:var(--marine); margin-bottom:3px; }
.chem-desc  { font-size:11.5px; color:var(--mist); line-height:1.55; }

/* Schedule & pricing */
.sched-block {
  background:linear-gradient(135deg,var(--deep),var(--ocean),var(--marine));
  border-radius:14px; overflow:hidden;
  box-shadow:0 6px 24px rgba(4,14,26,.28),inset 0 1px 0 rgba(255,255,255,.07);
  margin-bottom:14px;
}
.sb-head {
  padding:18px 24px; border-bottom:1px solid rgba(255,255,255,.07);
  display:flex; justify-content:space-between; align-items:center;
}
.sb-head h3 { font-size:15px; font-weight:800; color:#fff; }
.sb-price {
  font-size:26px; font-weight:900;
  background:linear-gradient(90deg,var(--gold-xl),var(--gold-lt));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
  background-clip:text;
}
.sb-rows { padding:14px 24px; display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.sb-row {
  background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.08);
  border-radius:10px; padding:13px 16px;
  display:flex; align-items:center; gap:12px;
}
.sbr-icon {
  width:38px; height:38px; border-radius:9px; flex-shrink:0;
  background:linear-gradient(135deg,var(--lagoon),var(--aqua));
  display:flex; align-items:center; justify-content:center;
  font-size:16px; color:#fff;
  box-shadow:0 2px 8px rgba(14,107,154,.35);
}
.sbr-label { font-size:11px; color:rgba(255,255,255,.5); margin-bottom:2px; }
.sbr-val   { font-size:13.5px; font-weight:800; color:#fff; }

/* First month box */
.first-month {
  background:linear-gradient(135deg,rgba(200,144,0,.12),rgba(245,208,96,.06));
  border:1px solid rgba(200,144,0,.3); border-radius:12px;
  padding:15px 20px; margin-bottom:14px;
  display:flex; gap:14px; align-items:flex-start;
}
.fm-icon {
  width:40px; height:40px; flex-shrink:0; border-radius:10px;
  background:linear-gradient(135deg,var(--gold-dk),var(--gold-m));
  display:flex; align-items:center; justify-content:center;
  font-size:18px; color:#fff; box-shadow:0 3px 10px rgba(200,144,0,.35);
}
.fm-title { font-size:14px; font-weight:800; color:var(--deep); margin-bottom:5px; }
.fm-text  { font-size:13px; color:var(--steel); line-height:1.7; }

/* Obligations */
.ob-cols { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.ob-head {
  font-size:12px; font-weight:700; color:var(--deep);
  margin-bottom:10px; display:flex; align-items:center; gap:7px;
  padding-bottom:8px; border-bottom:2px solid var(--gold-lt);
}
.ob-item {
  display:flex; gap:9px; padding:7px 0;
  border-bottom:1px dashed rgba(14,107,154,.12);
  font-size:12.5px; color:var(--slate);
}
.ob-item:last-child { border-bottom:none; }
.ob-n {
  width:20px; height:20px; flex-shrink:0; margin-top:2px;
  border-radius:50%; background:linear-gradient(135deg,var(--marine),var(--lagoon));
  color:#fff; font-size:10px; font-weight:800;
  display:flex; align-items:center; justify-content:center;
}

/* Clauses */
.clause {
  display:flex; gap:9px; padding:9px 13px; margin-bottom:6px;
  border-radius:9px; background:#fff; border:1px solid var(--border);
  font-size:12.5px; color:var(--slate);
}
.cl-n {
  width:24px; height:24px; flex-shrink:0; margin-top:1px;
  border-radius:50%; background:linear-gradient(135deg,var(--marine),var(--lagoon));
  color:#fff; font-size:11px; font-weight:800;
  display:flex; align-items:center; justify-content:center;
}

/* Signature */
.sig-section {
  border-top:3px solid var(--aqua);
  padding-top:26px; margin-top:26px;
}
.sig-title {
  font-family:'Playfair Display',serif;
  font-size:17px; font-weight:700;
  text-align:center; color:var(--deep); margin-bottom:22px;
}
.sig-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; }
.sig-box {
  background:#fff; border:1px solid var(--border); border-radius:12px;
  padding:18px; text-align:center;
  box-shadow:0 2px 10px rgba(14,107,154,.06);
}
.slbl {
  font-size:9px; font-weight:700; letter-spacing:1px; text-transform:uppercase;
  background:linear-gradient(90deg,var(--lagoon),var(--aqua));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
  background-clip:text; margin-bottom:4px;
}
.snm { font-size:13px; font-weight:800; color:var(--deep); margin-bottom:14px; }

/* Seal — realistic size, overlapping */
.seal-wrap { position:relative; width:150px; height:100px; margin:0 auto 8px; }
.seal-wrap img.seal-img {
  position:absolute; width:110px; height:110px; object-fit:contain;
  top:0; right:0; opacity:.92;
}
/* No separate sign image — seal only per request */

.client-sig-line { width:160px; height:80px; margin:0 auto 8px; border-bottom:2px solid var(--marine); }

/* Footer */
.footer {
  background:linear-gradient(135deg,var(--deep),var(--ocean),var(--marine));
  padding:13px 42px;
  display:flex; justify-content:space-between; align-items:center;
  flex-wrap:wrap; gap:6px;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.05);
}
.footer span { font-size:9.5px; color:rgba(255,255,255,.35); }
.footer .fn {
  background:linear-gradient(90deg,var(--gold-lt),var(--gold-m));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
  background-clip:text; font-weight:700;
}

/* Print */
/* fade-in for scroll reveal */
.fade-in { opacity:0; transform:translateY(18px); transition:opacity .55s ease, transform .55s ease; }
.fade-in.visible { opacity:1; transform:translateY(0); }

@media print {
  body { background:#fff; padding:0; }
  .no-print { display:none; }
  .page { max-width:100%; box-shadow:none; }
  .fade-in { opacity:1; transform:none; }
  footer iframe { display:none; }
  * { -webkit-print-color-adjust:exact !important; print-color-adjust:exact !important; }
}
@media(max-width:620px){
  .body,.hdr-top,.hdr-hero { padding-right:18px; padding-left:18px; }
  .parties,.steps,.chem-grid,.ob-cols,.sb-rows,.sig-grid { grid-template-columns:1fr; }
}
</style>

<?php if (empty($doc['show_seal'])): ?>
<style>.seal, img.seal, .stamp-wrap, .stamp-box img{display:none!important;}</style>
<?php endif; ?>
<?php if (empty($doc['show_signature'])): ?>
<style>img.sign, .sign{display:none!important;}</style>
<?php endif; ?>
</head>
<body>

<div class="no-print">
  <button class="btn-dl" onclick="window.print()"><i class="fas fa-file-pdf"></i> تحميل العقد PDF</button>
</div>

<div class="page">

  <!-- ══ HEADER ══ -->
  <div class="hdr">
    <!-- Pool ripple SVG -->
    <div class="ripple-wrap">
      <svg class="ripple-svg" viewBox="0 0 900 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,60 C150,20 300,100 450,60 C600,20 750,100 900,60 L900,120 L0,120 Z"
              fill="rgba(14,107,154,0.25)"/>
        <path d="M0,80 C120,45 280,110 450,75 C620,40 780,105 900,75 L900,120 L0,120 Z"
              fill="rgba(4,14,26,0.6)"/>
        <path d="M0,95 C180,70 360,115 540,90 C720,65 810,105 900,90 L900,120 L0,120 Z"
              fill="rgba(4,14,26,0.85)"/>
      </svg>
    </div>

    <div class="hdr-inner">
      <div class="hdr-top">
        <div class="brand">
          <div class="brand-icon"><i class="fas fa-water"></i></div>
          <div>
            <div class="brand-name">ركن التطور لأحواض السباحة ذ.م.م</div>
            <div class="brand-sub">أبوظبي — الإمارات العربية المتحدة · تأسست في الإمارات</div>
          </div>
        </div>
        <div class="doc-badge">
          <div class="db-lbl">Contract No.</div>
          <div class="db-num"><?= e($doc['document_number']) ?></div>
          <div class="db-date"><?= e($issueDate) ?></div>
        </div>
      </div>

      <div class="hdr-hero">
        <div class="contract-eyebrow">عقد صيانة دورية · Periodic Maintenance Contract</div>
        <div class="contract-title">
          عقد تنظيف <em>حوض السباحة</em><br>خدمة التنظيف العميق الشامل
        </div>
        <div class="contract-sub">
          خدمة تنظيف عميق شاملة لمرة واحدة — غسيل الجدران والأرضية بالأسيد، تعقيم كامل، وضبط الكيماويات لضمان مياه آمنة وصافية
        </div>
        <div class="client-strip">
          <div class="cs-pill"><i class="fas fa-user-tie"></i> <strong>حسن الهاشمي</strong></div>
          <div class="cs-pill"><i class="fas fa-location-dot"></i> مدينة الرياض، أبوظبي</div>
          <div class="cs-pill"><i class="fas fa-swimming-pool"></i> حوض 3م × 7م</div>
        </div>
      </div>
    </div>
  </div>

  <div class="gold-stripe"></div>

  <!-- ══ BODY ══ -->
  <div class="body">

    <!-- Preamble -->
    <div class="preamble">
      تم إبرام هذا العقد في يوم <strong>الثلاثاء الموافق 7 يوليو 2026</strong> بين <strong>ركن التطور لأحواض السباحة ذ.م.م</strong> بوصفها الطرف المقدِّم للخدمة، وبين <strong>السيد / حسن الهاشمي</strong> بوصفه صاحب العمل، وذلك على تقديم خدمة التنظيف العميق الشامل لحوض السباحة في الموقع المحدد أدناه لمرة واحدة، وفق الشروط والبنود المنصوص عليها في هذا العقد.
    </div>

    <!-- 1. Parties -->
    <div class="block fade-in">
      <div class="sec">
        <div class="sec-icon ic-aqua"><i class="fas fa-handshake"></i></div>
        <div class="sec-num">1</div>
        <h2>أطراف العقد</h2>
        <div class="sec-line"></div>
      </div>
      <div class="parties">
        <div class="party">
          <div class="party-head"><i class="fas fa-building-shield"></i> الطرف الأول — مقدّم الخدمة</div>
          <div class="party-body">
            <div class="pr"><span class="pr-l">الاسم:</span><span class="pr-v">ركن التطور لأحواض السباحة ذ.م.م</span></div>
            <div class="pr"><span class="pr-l">الموقع:</span><span class="pr-v">أبوظبي — الإمارات العربية المتحدة</span></div>
          </div>
        </div>
        <div class="party">
          <div class="party-head"><i class="fas fa-user-tie"></i> الطرف الثاني — صاحب العمل</div>
          <div class="party-body">
            <div class="pr"><span class="pr-l">الاسم:</span><span class="pr-v">حسن الهاشمي</span></div>
            <div class="pr"><span class="pr-l">العنوان:</span><span class="pr-v">أبوظبي — مدينة الرياض، شارع اليمام، فيلا 25</span></div>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. Pool Spec -->
    <div class="block fade-in">
      <div class="sec">
        <div class="sec-icon ic-aqua"><i class="fas fa-water"></i></div>
        <div class="sec-num">2</div>
        <h2>تفاصيل حوض السباحة</h2>
        <div class="sec-line"></div>
      </div>
      <div class="pool-spec fade-in">
        <div class="pool-icon-big"><i class="fas fa-swimming-pool"></i></div>
        <div class="pool-details">
          <h3>حوض سباحة خاص — فيلا 25، مدينة الرياض</h3>
          <div class="pool-tags">
            <div class="ptag"><i class="fas fa-ruler-combined"></i> الأبعاد: 3م × 7م</div>
            <div class="ptag"><i class="fas fa-expand"></i> المساحة: 21 م²</div>
            <div class="ptag"><i class="fas fa-calendar-check"></i> تاريخ التنفيذ: 7 يوليو 2026</div>
            <div class="ptag"><i class="fas fa-broom"></i> تنظيف عميق شامل</div>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. Work Process -->
    <div class="block fade-in">
      <div class="sec">
        <div class="sec-icon ic-deep"><i class="fas fa-list-check"></i></div>
        <div class="sec-num">3</div>
        <h2>خطوات التنظيف العميق الشامل</h2>
        <div class="sec-line"></div>
      </div>
                        <ul class="steps">
        <li class="step">
          <div class="step-num">1</div>
          <div>
            <div class="step-title">غسيل الأسيد والتعقيم</div>
            <div class="step-desc">غسيل الجدران والأرضية بالأسيد ومواد التعقيم لإزالة التكلسات والبقع الصعبة</div>
          </div>
        </li>
        <li class="step">
          <div class="step-num">2</div>
          <div>
            <div class="step-title">التنظيف السطحي الشامل</div>
            <div class="step-desc">إزالة الشوائب وأوراق الشجر والحشرات، وفرك الجدران والأرضية بفرشاة خاصة</div>
          </div>
        </li>
        <li class="step">
          <div class="step-num">3</div>
          <div>
            <div class="step-title">كنس قاع المسبح</div>
            <div class="step-desc">شفط الغبار والرمل المترسب في القاع بالكامل بمكنسة المسابح الاحترافية</div>
          </div>
        </li>
        <li class="step">
          <div class="step-num">4</div>
          <div>
            <div class="step-title">صيانة الفلاتر والمضخات</div>
            <div class="step-desc">تنظيف سلة المضخة والسكيمر + غسيل عكسي (Backwash) لضمان فلترة قوية</div>
          </div>
        </li>
        <li class="step">
          <div class="step-num">5</div>
          <div>
            <div class="step-title">تحليل الماء</div>
            <div class="step-desc">فحص نسبة الكلور والحموضة (pH) فورياً لضمان سلامة الماء للجلد والعين</div>
          </div>
        </li>
        <li class="step">
          <div class="step-num">6</div>
          <div>
            <div class="step-title">ضبط الكيماويات والتسليم</div>
            <div class="step-desc">إضافة المواد الكيميائية المعتمدة لضبط الكلور والـ pH حتى يصبح المسبح آمناً للاستخدام فوراً</div>
          </div>
        </li>
      </ul>
    </div>

    <!-- 4. Chemicals -->
    <div class="block fade-in">
      <div class="sec">
        <div class="sec-icon ic-aqua"><i class="fas fa-flask-vial"></i></div>
        <div class="sec-num">4</div>
        <h2>المواد الكيميائية المستخدمة <span style="font-size:12px;font-weight:400;color:var(--mist);">(مشمولة في الخدمة)</span></h2>
        <div class="sec-line"></div>
      </div>
      <div class="chem-grid">
        <div class="chem-card">
          <div class="chem-dot" style="background:linear-gradient(135deg,#0EA5E9,#38BDF8);"></div>
          <div><div class="chem-name">أقراص وبودرة الكلور</div><div class="chem-desc">تعقيم الماء وقتل الجراثيم والبكتيريا بشكل فعّال ومستدام</div></div>
        </div>
        <div class="chem-card">
          <div class="chem-dot" style="background:linear-gradient(135deg,#F59E0B,#FCD34D);"></div>
          <div><div class="chem-name">موازن الحموضة (pH Plus / pH Minus)</div><div class="chem-desc">ضبط توازن الماء لحماية العين والجلد وإطالة عمر المعدات</div></div>
        </div>
        <div class="chem-card">
          <div class="chem-dot" style="background:linear-gradient(135deg,#10B981,#34D399);"></div>
          <div><div class="chem-name">مضاد الطحالب (Algaecide)</div><div class="chem-desc">يمنع خضرار الماء وتكون الطحالب على جدران وقاع المسبح</div></div>
        </div>
        <div class="chem-card">
          <div class="chem-dot" style="background:linear-gradient(135deg,#8B5CF6,#A78BFA);"></div>
          <div><div class="chem-name">مصفّي الماء (Clarifier)</div><div class="chem-desc">يجمع ذرات الغبار الدقيقة ليلتقطها الفلتر ويبقى الماء نقياً ولامعاً</div></div>
        </div>
      </div>
    </div>

    <!-- 5. Schedule & Pricing -->
    <div class="block fade-in">
      <div class="sec">
        <div class="sec-icon ic-gold"><i class="fas fa-receipt"></i></div>
        <div class="sec-num">5</div>
        <h2>قيمة الخدمة وشروط الدفع</h2>
        <div class="sec-line"></div>
      </div>
      <div class="sched-block fade-in">
        <div class="sb-head">
          <div>
            <h3>خدمة التنظيف العميق الشامل — زيارة واحدة</h3>
            <div style="font-size:11px;color:rgba(255,255,255,.45);margin-top:3px;">شاملة غسيل الأسيد، التعقيم الكامل، وضبط الكيماويات</div>
          </div>
          <div class="sb-price">500 درهم</div>
        </div>
        <div class="sb-rows">
          <div class="sb-row">
            <div class="sbr-icon"><i class="fas fa-broom"></i></div>
            <div><div class="sbr-label">نوع الخدمة</div><div class="sbr-val">تنظيف عميق شامل</div></div>
          </div>
          <div class="sb-row">
            <div class="sbr-icon"><i class="fas fa-calendar-day"></i></div>
            <div><div class="sbr-label">تاريخ التنفيذ</div><div class="sbr-val">7 يوليو 2026</div></div>
          </div>
          <div class="sb-row">
            <div class="sbr-icon"><i class="fas fa-university"></i></div>
            <div><div class="sbr-label">طريقة الدفع</div><div class="sbr-val">تحويل بنكي</div></div>
          </div>
          <div class="sb-row">
            <div class="sbr-icon"><i class="fas fa-coins"></i></div>
            <div><div class="sbr-label">إجمالي الخدمة</div><div class="sbr-val">500 درهم — مرة واحدة</div></div>
          </div>
        </div>
      </div>

      <!-- First month -->
      <div class="first-month fade-in">
        <div class="fm-icon"><i class="fas fa-star"></i></div>
        <div>
          <div class="fm-title">الدفع عند التوقيع — 500 درهم</div>
          <div class="fm-text">يُسدَّد المبلغ كاملاً البالغ <strong>500 درهم</strong> في تاريخ توقيع هذا العقد (7 يوليو 2026) عبر التحويل البنكي، ويبدأ التنفيذ فور تأكيد استلام الدفعة.</div>
        </div>
      </div>
    </div>

    <!-- 6. Obligations -->
    <div class="block fade-in">
      <div class="sec">
        <div class="sec-icon ic-deep"><i class="fas fa-scale-balanced"></i></div>
        <div class="sec-num">6</div>
        <h2>التزامات الطرفين</h2>
        <div class="sec-line"></div>
      </div>
      <div class="ob-cols">
        <div>
          <div class="ob-head"><i class="fas fa-building-shield" style="color:var(--gold-m);"></i> الطرف الأول يلتزم بـ:</div>
          <div class="ob-item"><div class="ob-n">1</div>تنفيذ خدمة التنظيف العميق الشامل في التاريخ المتفق عليه (7 يوليو 2026)</div>
          <div class="ob-item"><div class="ob-n">2</div>توفير جميع المواد الكيميائية المعتمدة ضمن الخدمة</div>
          <div class="ob-item"><div class="ob-n">3</div>استخدام أحدث المعدات والأدوات المتخصصة في كل زيارة</div>
          <div class="ob-item"><div class="ob-n">4</div>إخطار العميل فور الانتهاء من الخدمة مع توضيح حالة المسبح</div>
          <div class="ob-item"><div class="ob-n">5</div>إصدار تقرير موجز عن حالة المسبح بعد تنفيذ الخدمة</div>
        </div>
        <div>
          <div class="ob-head"><i class="fas fa-user-tie" style="color:var(--gold-m);"></i> الطرف الثاني يلتزم بـ:</div>
          <div class="ob-item"><div class="ob-n">1</div>سداد قيمة الخدمة كاملةً (500 درهم) عند توقيع هذا العقد اليوم</div>
          <div class="ob-item"><div class="ob-n">2</div>توفير إمكانية الوصول الكامل للمسبح في يوم التنفيذ المتفق عليه</div>
          <div class="ob-item"><div class="ob-n">3</div>التأكد من توفر مصدر كهرباء وماء في الموقع يوم التنفيذ</div>
          <div class="ob-item"><div class="ob-n">4</div>إخطار الشركة في حال أي تغيير في موعد التنفيذ قبل 24 ساعة على الأقل</div>
          <div class="ob-item"><div class="ob-n">5</div>عدم استخدام المسبح أثناء التنفيذ وحتى اكتمال ضبط الكيماويات</div>
        </div>
      </div>
    </div>

    <!-- 7. General -->
    <div class="block fade-in">
      <div class="sec">
        <div class="sec-icon ic-deep"><i class="fas fa-gavel"></i></div>
        <div class="sec-num">7</div>
        <h2>الأحكام العامة</h2>
        <div class="sec-line"></div>
      </div>
      <div class="clause"><div class="cl-n">1</div><div><strong>نطاق الخدمة:</strong> يسري هذا العقد لزيارة واحدة فقط بتاريخ 7 يوليو 2026، ولا يُجدَّد تلقائياً. أي خدمات لاحقة تستوجب إبرام اتفاق جديد.</div></div>
      <div class="clause"><div class="cl-n">2</div><div><strong>الدفع المسبق:</strong> لا يُبدأ بأي عمل قبل تأكيد استلام كامل قيمة الخدمة (500 درهم) عبر التحويل البنكي.</div></div>
      <div class="clause"><div class="cl-n">3</div><div><strong>الأعمال الإضافية:</strong> أي إصلاحات أو استبدال قطع غيار تتجاوز نطاق الصيانة الدورية تُحتسب بتكلفة إضافية يُتفق عليها كتابياً مسبقاً.</div></div>
      <div class="clause"><div class="cl-n">4</div><div><strong>القوة القاهرة:</strong> لا يُعدّ أي طرف مسؤولاً عن التأخر الناجم عن ظروف خارجة عن السيطرة كالطوارئ أو الأحوال المناخية القاسية.</div></div>
      <div class="clause"><div class="cl-n">5</div><div><strong>فض النزاعات:</strong> تُحل أي خلافات بالتراضي أولاً، وعند التعذّر يُرجع إلى الجهات القانونية المختصة في إمارة أبوظبي.</div></div>
      <div class="clause"><div class="cl-n">6</div><div><strong>نسخ العقد:</strong> حُرّر هذا العقد من نسختين أصليتين بيد كل طرف نسخة تحمل نفس القوة القانونية.</div></div>
    </div>

    <!-- Signatures -->
    <div class="sig-section fade-in">
      <div class="sig-title">توقيعات الطرفين — إقرار وقبول</div>
      <div class="sig-grid">

        <div class="sig-box">
          <div class="slbl">الطرف الأول — مقدّم الخدمة</div>
          <div class="snm">ركن التطور لأحواض السباحة ذ.م.م</div>
          <div class="seal-wrap">
            <img class="seal-img"
                 src="<?= e($seal) ?>"
                 alt="ختم الشركة"
                 onerror="this.style.opacity='.3'">
          </div>
        </div>

        <div class="sig-box">
          <div class="slbl">الطرف الثاني — صاحب العمل</div>
          <div class="snm">حسن الهاشمي</div>
          <div class="client-sig-line"></div>
        </div>

      </div>
    </div>

  </div><!-- /body -->

  <!-- ══ FOOTER WITH MAP ══ -->
  <footer>
    <!-- Google Maps embed -->
    <div style="width:100%;line-height:0;overflow:hidden;">
      <iframe
        width="100%"
        height="200"
        style="border:0;filter:brightness(0.82) contrast(1.05) saturate(0.9);display:block;"
        loading="lazy"
        allowfullscreen
        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=24.372480,54.538188&zoom=16&maptype=roadmap&language=ar">
      </iframe>
    </div>

    <!-- Office info bar -->
    <div style="background:linear-gradient(135deg,var(--deep),var(--ocean),var(--marine));padding:16px 42px;display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;border-top:3px solid var(--gold);">

      <!-- Office 1: Abu Dhabi -->
      <div style="display:flex;align-items:flex-start;gap:10px;flex:1;min-width:200px;">
        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,var(--gold-dk),var(--gold-lt));display:flex;align-items:center;justify-content:center;font-size:14px;color:var(--deep);flex-shrink:0;margin-top:2px;">
          <i class="fas fa-building"></i>
        </div>
        <div>
          <div style="font-size:9px;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.4);margin-bottom:4px;font-weight:700;">مكتب أبوظبي</div>
          <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,.8);line-height:1.6;">مكتب 306، مزيد مول<br>مدينة محمد بن زايد، أبوظبي</div>
        </div>
      </div>

      <!-- Center: company + ref -->
      <div style="flex:1;min-width:220px;text-align:center;">
        <div style="font-size:11px;font-weight:800;color:#fff;margin-bottom:4px;">ركن التطور لأحواض السباحة ذ.م.م</div>
        <div style="font-size:9px;color:rgba(255,255,255,.35);margin-bottom:6px;">Rukn El-Tatawer for Swimming Pools LLC · أبوظبي، الإمارات</div>
        <div style="display:inline-block;background:linear-gradient(90deg,var(--gold-lt),var(--gold-m));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-weight:700;font-size:11px;">RET-POOL-2026-0707 · 7 يوليو 2026</div>
        <div style="margin-top:5px;">
          <a href="https://maps.google.com/?q=24.372480,54.538188" target="_blank"
             style="display:inline-flex;align-items:center;gap:5px;color:rgba(255,255,255,.45);font-size:10px;text-decoration:none;">
            <i class="fas fa-external-link-alt" style="font-size:9px;"></i> فتح الخريطة
          </a>
        </div>
      </div>

      <!-- Office 2: WhatsApp / Contact -->
      <div style="display:flex;align-items:flex-start;gap:10px;flex:1;min-width:200px;justify-content:flex-end;">
        <div>
          <div style="font-size:9px;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.4);margin-bottom:4px;font-weight:700;text-align:left;">تواصل معنا</div>
          <a href="https://wa.me/971586634710" target="_blank"
             style="display:flex;align-items:center;gap:8px;text-decoration:none;margin-bottom:4px;">
            <div style="width:26px;height:26px;border-radius:6px;background:#25D366;display:flex;align-items:center;justify-content:center;font-size:13px;color:#fff;flex-shrink:0;">
              <i class="fab fa-whatsapp"></i>
            </div>
            <span style="font-size:12px;font-weight:600;color:rgba(255,255,255,.8);">+971 58 663 4710</span>
          </a>
          <div style="font-size:10px;color:rgba(255,255,255,.35);text-align:left;">هذا العقد وثيقة رسمية ملزِمة للطرفين</div>
        </div>
      </div>

    </div>
  </footer>

</div><!-- /page -->

<script>
// Scroll reveal — same as uploaded file
const observer = new IntersectionObserver(els => {
  els.forEach(el => { if (el.isIntersecting) { el.target.classList.add('visible'); observer.unobserve(el.target); } });
}, { threshold: 0.12 });
document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
</script>


<?php if (!empty($media)): ?>
<div class="kdms-media" style="margin:16px 0;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
<?php foreach ($media as $m): if (($m['type'] ?? '') === 'image' && !empty($m['file_path'])): ?>
  <figure style="margin:0;break-inside:avoid;page-break-inside:avoid;">
    <img src="<?= e(upload_url($m['file_path'])) ?>" alt="<?= e($m['title'] ?? '') ?>" style="width:100%;max-height:260px;object-fit:cover;border-radius:8px;border:1px solid rgba(0,0,0,.08);">
    <?php if (!empty($m['caption']) || !empty($m['title'])): ?>
    <figcaption style="font-size:11px;color:#4A6A8A;margin-top:6px;text-align:center;"><?= e($m['caption'] ?: $m['title']) ?></figcaption>
    <?php endif; ?>
  </figure>
<?php endif; endforeach; ?>
</div>
<?php endif; ?>
</body>
</html>
