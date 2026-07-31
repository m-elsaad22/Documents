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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@300;400;600;700;900&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  :root {
    --blue-dark: #0B2545;
    --blue-mid: #134074;
    --blue-accent: #1A6DAF;
    --blue-light: #2196D3;
    --gold: #D4A017;
    --gold-light: #F0C040;
    --gray-dark: #2D3748;
    --gray-mid: #718096;
    --gray-light: #EDF2F7;
    --white: #FFFFFF;
    --success: #38A169;
    --text-body: #2D3748;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Cairo', 'Tajawal', sans-serif;
    background: #F0F4F8;
    color: var(--text-body);
    overflow-x: hidden;
  }

  /* ===== PRINT / PDF BUTTON ===== */
  .no-print {
    max-width: 960px;
    margin: 0 auto 18px;
    padding: 14px 20px 0;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }
  .btn-pdf {
    display: inline-flex; align-items: center; gap: 9px;
    padding: 11px 26px; border-radius: 9px; cursor: pointer; border: none;
    font-family: 'Cairo', sans-serif; font-size: 14px; font-weight: 700;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: var(--blue-dark); box-shadow: 0 4px 18px rgba(212,160,23,.35);
    transition: all .25s;
  }
  .btn-pdf:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(212,160,23,.45); }

  /* ===== HERO ===== */
  .hero {
    background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 50%, #0D3260 100%);
    position: relative;
    overflow: hidden;
    padding: 0;
  }

  .hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M0 0h40v40H0V0zm40 40h40v40H40V40zm0-40h2l-2 2V0zm0 4l4-4h2l-6 6V4zm0 4l8-8h2L40 10V8zm0 4L52 0h2L40 14v-2zm0 4L56 0h2L40 18v-2zm0 4L60 0h2L40 22v-2zm0 4L64 0h2L40 26v-2zm0 4L68 0h2L40 30v-2zm0 4L72 0h2L40 34v-2zm0 4L76 0h2L40 38v-2zm0 4L80 0v2L42 40h-2zm4 0L80 4v2L46 40h-2zm4 0L80 8v2L50 40h-2zm4 0l28-28v2L54 40h-2zm4 0l24-24v2L58 40h-2zm4 0l20-20v2L62 40h-2zm4 0l16-16v2L66 40h-2zm4 0l12-12v2L70 40h-2zm4 0l8-8v2l-6 6h-2zm4 0l4-4v2l-2 2h-2z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }

  .hero-sun {
    position: absolute;
    top: -60px;
    left: -60px;
    width: 320px;
    height: 320px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,200,0,0.18) 0%, rgba(255,150,0,0.08) 50%, transparent 70%);
    animation: sunPulse 4s ease-in-out infinite;
  }

  @keyframes sunPulse {
    0%, 100% { transform: scale(1); opacity: 0.7; }
    50% { transform: scale(1.1); opacity: 1; }
  }

  .hero-waves {
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100%;
  }

  .hero-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 40px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    position: relative;
    z-index: 10;
  }

  .logo-area { display: flex; align-items: center; gap: 14px; }

  .logo-icon {
    width: 52px; height: 52px;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: var(--blue-dark);
    box-shadow: 0 4px 20px rgba(212,160,23,0.4);
  }

  .logo-text h1 { font-size: 18px; font-weight: 900; color: #fff; line-height: 1.1; letter-spacing: 0.5px; }
  .logo-text span { font-size: 11px; color: var(--gold-light); font-weight: 400; letter-spacing: 1px; }

  .hero-badge {
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    padding: 8px 18px; border-radius: 50px; font-size: 12px; color: #fff;
    display: flex; align-items: center; gap: 7px;
  }
  .hero-badge i { color: var(--gold-light); }

  .hero-content {
    padding: 50px 40px 90px;
    position: relative; z-index: 10;
    max-width: 900px; margin: 0 auto; text-align: center;
  }

  .bismillah-line {
    font-family: 'Amiri', serif;
    font-size: 17px;
    color: var(--gold-light);
    opacity: 0.9;
    margin-bottom: 18px;
    animation: fadeInDown 0.6s ease both;
  }

  .quote-label {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(212,160,23,0.2);
    border: 1px solid rgba(212,160,23,0.4);
    color: var(--gold-light);
    padding: 8px 20px; border-radius: 50px; font-size: 13px; font-weight: 600;
    margin-bottom: 28px;
    animation: fadeInDown 0.6s ease both;
  }

  .hero-title {
    font-family: 'Amiri', serif;
    font-size: clamp(24px, 4.5vw, 38px);
    font-weight: 700;
    color: #fff;
    line-height: 1.4;
    margin-bottom: 18px;
    animation: fadeInUp 0.7s ease 0.1s both;
  }

  .hero-title .highlight {
    background: linear-gradient(90deg, var(--gold), var(--gold-light));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
  }

  .quote-meta {
    display: flex; justify-content: center; gap: 22px; flex-wrap: wrap;
    margin-bottom: 34px;
    animation: fadeInUp 0.7s ease 0.2s both;
  }
  .quote-meta-item {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.85);
    padding: 7px 16px; border-radius: 50px; font-size: 12.5px;
    display: flex; align-items: center; gap: 7px;
  }
  .quote-meta-item i { color: var(--gold-light); }

  .hero-stats { display: flex; justify-content: center; gap: 40px; flex-wrap: wrap; animation: fadeInUp 0.7s ease 0.3s both; }
  .stat-item { text-align: center; }
  .stat-num { font-size: 30px; font-weight: 900; color: var(--gold-light); line-height: 1; }
  .stat-label { font-size: 12px; color: rgba(255,255,255,0.6); margin-top: 4px; }

  @keyframes fadeInDown { from { opacity:0; transform:translateY(-20px); } to { opacity:1; transform:translateY(0); } }
  @keyframes fadeInUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }

  /* ===== LAYOUT ===== */
  .section { padding: 60px 20px; }
  .container { max-width: 900px; margin: 0 auto; }

  .preamble {
    background: rgba(26,109,175,0.05);
    border-right: 4px solid var(--gold);
    border-radius: 0 14px 14px 0;
    padding: 20px 24px;
    margin-bottom: 28px;
    font-size: 14px; line-height: 2; color: var(--text-body);
    animation: fadeInUp 0.5s ease both;
  }

  .c-section { margin-bottom: 28px; }
  .c-section-title {
    display: flex; align-items: center; gap: 12px;
    background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid));
    color: #fff; padding: 14px 24px; border-radius: 14px;
    font-size: 16px; font-weight: 800; margin-bottom: 20px;
    box-shadow: 0 4px 20px rgba(11,37,69,0.15);
  }
  .c-s-num {
    width: 30px; height: 30px;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: var(--blue-dark); border-radius: 50%;
    font-size: 14px; font-weight: 900;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }

  /* ===== CARD (shared visual language) ===== */
  .card {
    background: #fff; border-radius: 20px; padding: 32px;
    box-shadow: 0 4px 30px rgba(11,37,69,0.08);
    border: 1px solid rgba(11,37,69,0.06);
    margin-bottom: 28px; position: relative; overflow: hidden;
    animation: fadeInUp 0.5s ease both;
  }
  .card::before {
    content: ''; position: absolute; top: 0; right: 0; width: 5px; height: 100%;
    background: linear-gradient(180deg, var(--blue-accent), var(--blue-light));
    border-radius: 0 20px 20px 0;
  }

  /* ===== PARTIES ===== */
  .parties-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .party-box { background: var(--gray-light); border-radius: 14px; overflow: hidden; border: 1px solid rgba(11,37,69,0.06); }
  .party-head {
    background: linear-gradient(135deg, var(--blue-dark), var(--blue-accent));
    color: #fff; padding: 12px 18px; font-size: 13px; font-weight: 800;
    display: flex; align-items: center; gap: 8px;
  }
  .party-head i { color: var(--gold-light); }
  .party-body { padding: 16px 18px; background: #fff; }
  .party-row { display: flex; gap: 8px; margin-bottom: 9px; font-size: 13px; }
  .party-row:last-child { margin-bottom: 0; }
  .pr-label { color: var(--gray-mid); min-width: 78px; flex-shrink: 0; }
  .pr-val { font-weight: 700; color: var(--blue-dark); }

  /* ===== SCOPE / PAYMENT TABLE (matches quote pricing table) ===== */
  .price-table { width: 100%; border-collapse: collapse; font-family: 'Cairo', sans-serif; }
  .price-table thead tr { background: var(--gray-light); }
  .price-table thead th { padding: 14px 18px; text-align: right; font-size: 13px; font-weight: 700; color: var(--blue-dark); border-bottom: 2px solid rgba(11,37,69,0.1); }
  .price-table tbody tr { border-bottom: 1px solid rgba(11,37,69,0.05); }
  .price-table tbody tr:nth-child(even) { background: rgba(26,109,175,0.02); }
  .price-table tbody td { padding: 14px 18px; font-size: 13.5px; color: var(--text-body); vertical-align: top; line-height: 1.6; }
  .price-table tbody td:first-child { width: 30px; text-align: center; font-weight: 900; color: var(--gold); }
  .price-table .service-name { font-weight: 700; color: var(--blue-dark); }
  .price-table .service-desc { font-size: 12px; color: var(--gray-mid); margin-top: 3px; }

  .price-table-wrap { border-radius: 16px; overflow: hidden; box-shadow: 0 2px 20px rgba(11,37,69,0.08); margin-bottom: 16px; }

  .info-badges { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 4px; }
  .info-badge {
    background: rgba(26,109,175,0.06); border: 1px solid rgba(26,109,175,0.15);
    border-radius: 10px; padding: 9px 16px; font-size: 12.5px;
    display: flex; align-items: center; gap: 8px; color: var(--blue-dark); font-weight: 600;
  }
  .info-badge i { color: var(--gold); }

  /* ===== TIMELINE ===== */
  .timeline-wrap { position: relative; padding-right: 30px; }
  .timeline-wrap::before {
    content: ''; position: absolute; right: 12px; top: 6px; bottom: 6px; width: 2px;
    background: linear-gradient(180deg, var(--blue-accent), var(--gold));
  }
  .tl-item { position: relative; margin-bottom: 22px; padding-right: 22px; }
  .tl-item:last-child { margin-bottom: 0; }
  .tl-item::before {
    content: ''; position: absolute; right: -19px; top: 5px; width: 14px; height: 14px; border-radius: 50%;
    background: var(--blue-accent); border: 3px solid #fff; box-shadow: 0 0 0 2px var(--blue-accent);
  }
  .tl-item:last-child::before { background: var(--gold); box-shadow: 0 0 0 2px var(--gold); }
  .tl-date { font-size: 11.5px; font-weight: 700; color: var(--blue-accent); margin-bottom: 4px; }
  .tl-item:last-child .tl-date { color: #B8860B; }
  .tl-title { font-size: 15px; font-weight: 800; color: var(--blue-dark); margin-bottom: 4px; }
  .tl-desc { font-size: 13px; color: var(--gray-mid); line-height: 1.7; }

  /* ===== PAYMENT TOTALS (reused from quote design) ===== */
  .total-row { background: linear-gradient(135deg, rgba(11,37,69,0.04), rgba(26,109,175,0.06)) !important; font-weight: 800 !important; }
  .total-row td { padding: 16px 18px !important; font-weight: 800; }
  .total-amount { font-size: 16px !important; font-weight: 900 !important; color: var(--blue-dark) !important; }
  .vat-row td { font-size: 12.5px; color: var(--gray-mid); padding: 12px 18px !important; }
  .grand-total-row { background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid)) !important; }
  .grand-total-row td { color: #fff !important; padding: 18px !important; font-size: 15px; font-weight: 900; }
  .grand-amount { font-size: 20px !important; color: var(--gold-light) !important; }

  .receipt-note {
    background: rgba(56,161,105,0.06); border: 1px solid rgba(56,161,105,0.2); border-radius: 0 0 16px 16px;
    padding: 14px 20px; font-size: 12.5px; color: #276749; display: flex; gap: 8px; align-items: flex-start;
  }
  .receipt-note i { margin-top: 3px; flex-shrink: 0; }

  /* ===== BENEFITS-STYLE CLAUSE COLUMNS ===== */
  .clauses-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
  .clauses-col {
    background: #fff; border-radius: 16px; padding: 22px;
    box-shadow: 0 2px 20px rgba(11,37,69,0.07); border: 1px solid rgba(11,37,69,0.05);
  }
  .clauses-col-title {
    font-size: 14px; font-weight: 800; color: var(--blue-dark); margin-bottom: 14px;
    display: flex; align-items: center; gap: 8px; padding-bottom: 10px; border-bottom: 2px solid var(--gold);
  }
  .clause-item { display: flex; gap: 10px; margin-bottom: 11px; padding-bottom: 11px; border-bottom: 1px dashed rgba(11,37,69,0.1); font-size: 13px; line-height: 1.7; }
  .clause-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
  .clause-num {
    width: 22px; height: 22px; flex-shrink: 0; margin-top: 2px;
    background: var(--blue-accent); color: #fff; border-radius: 50%;
    font-size: 10px; font-weight: 900; display: flex; align-items: center; justify-content: center;
  }
  .clause-text { flex: 1; color: var(--text-body); }

  /* ===== WARRANTY / GENERAL TERMS (reused from quote warranty-card) ===== */
  .warranty-card {
    background: linear-gradient(135deg, #1A6DAF, #0B4F8A);
    border-radius: 20px; padding: 32px; margin-bottom: 28px;
    position: relative; overflow: hidden;
  }
  .warranty-card::before {
    content: '\f3ed'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
    position: absolute; top: -20px; left: -10px; font-size: 160px; color: rgba(255,255,255,0.04); line-height: 1;
  }
  .warranty-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
  .warranty-years {
    width: 72px; height: 72px;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center;
    flex-shrink: 0; box-shadow: 0 4px 20px rgba(212,160,23,0.5);
  }
  .warranty-years .years-num { font-size: 26px; font-weight: 900; color: var(--blue-dark); line-height: 1; }
  .warranty-years .years-label { font-size: 10px; color: var(--blue-dark); font-weight: 700; }
  .warranty-header h2 { color: #fff; font-size: 19px; font-weight: 900; margin-bottom: 4px; position: relative; z-index: 2; }
  .warranty-header p { color: rgba(255,255,255,0.7); font-size: 13px; position: relative; z-index: 2; }

  .gen-clauses { position: relative; z-index: 2; }
  .gen-clause { display: flex; gap: 12px; padding: 13px 0; border-bottom: 1px solid rgba(255,255,255,0.12); }
  .gen-clause:last-child { border-bottom: none; }
  .gen-num {
    width: 26px; height: 26px; flex-shrink: 0; margin-top: 1px;
    background: rgba(255,255,255,0.15); color: #fff; border-radius: 50%;
    font-size: 12px; font-weight: 900; display: flex; align-items: center; justify-content: center;
  }
  .gen-text { font-size: 13px; line-height: 1.85; color: rgba(255,255,255,0.9); }
  .gen-text strong { color: #fff; }

  /* ===== SIGNATURES ===== */
  .sig-title {
    font-family: 'Amiri', serif; font-size: 18px; font-weight: 700; text-align: center;
    color: var(--blue-dark); margin-bottom: 26px;
  }
  .sig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  .sig-box {
    background: #fff; border-radius: 16px; padding: 26px 20px; text-align: center;
    box-shadow: 0 2px 20px rgba(11,37,69,0.07); border: 1px solid rgba(11,37,69,0.05);
  }
  .sb-label { font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--blue-accent); margin-bottom: 6px; }
  .sb-name { font-size: 15px; font-weight: 800; color: var(--blue-dark); margin-bottom: 18px; }
  .sig-images { width: 150px; height: 150px; margin: 0 auto 8px; }
  .sig-images img.combined { width: 100%; height: 100%; object-fit: contain; }
  .client-sig-space { width: 180px; height: 90px; margin: 0 auto 8px; border-bottom: 2px solid var(--blue-accent); }

  /* ===== FOOTER ===== */
  .footer { background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid)); padding: 0 0 20px; color: rgba(255,255,255,0.8); }
  .footer-map-wrap { width: 100%; }
  .footer-office-bar { text-align: center; padding: 16px 20px 0; font-size: 12px; line-height: 1.9; }
  .contract-ref-bar {
    max-width: 900px; margin: 20px auto 0; padding: 14px 24px;
    background: rgba(255,255,255,0.06); border-radius: 12px;
    display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;
  }
  .contract-ref-bar span { font-size: 12px; color: rgba(255,255,255,0.6); }
  .contract-ref-bar .cf-num { color: var(--gold-light); font-weight: 700; font-size: 12.5px; }

  @media print {
    body { background: #fff; }
    .no-print { display: none; }
  }

  @media (max-width: 620px) {
    .hero-top-bar { padding: 14px 20px; }
    .hero-content { padding: 34px 20px 70px; }
    .hero-title { font-size: 22px !important; }
    .parties-grid, .clauses-cols, .sig-grid { grid-template-columns: 1fr; }
    .section { padding: 40px 14px; }
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
  <button class="btn-pdf" onclick="window.print()">
    <i class="fas fa-file-pdf"></i> طباعة / تحميل PDF
  </button>
</div>

<!-- ===== HERO ===== -->
<section class="hero">
  <div class="hero-sun"></div>

  <div class="hero-top-bar">
    <div class="logo-area">
      <div class="logo-icon"><i class="fas fa-building-shield"></i></div>
      <div class="logo-text">
        <h1>ركن التطور</h1>
        <span>RUKN ELTATAWER ·  </span>
      </div>
    </div>
    <div class="hero-badge">
      <i class="fas fa-award"></i>
      <span>معتمدون رسمياً في الإمارات</span>
    </div>
  </div>

  <div class="hero-content">
    <div class="bismillah-line">بسم الله الرحمن الرحيم</div>

    <div class="quote-label">
      <i class="fas fa-file-signature"></i>
      عقد أعمال رسمي · موقّع ومعتمد
    </div>

    <h1 class="hero-title">
      عقد أعمال عزل سطح  <br>
      بنظام <span class="highlight">Cool Roof المعتمد</span>
    </h1>

    <div class="quote-meta">
      <div class="quote-meta-item"><i class="fas fa-hashtag"></i> <?= e($doc['document_number']) ?></div>
      <div class="quote-meta-item"><i class="fas fa-calendar"></i> تاريخ التحرير: 19 يوليو 2026</div>
    </div>

    <div class="hero-stats">
      <div class="stat-item">
        <div class="stat-num">55</div>
        <div class="stat-label">متر مربع — مساحة العقد</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">1</div>
        <div class="stat-label">يوم عمل — مدة التنفيذ</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">10</div>
        <div class="stat-label">سنوات ضمان شامل</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">4</div>
        <div class="stat-label">طبقات Cool Roof</div>
      </div>
    </div>
  </div>

  <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
    <path fill="#F0F4F8" d="M0,40 C360,80 1080,0 1440,40 L1440,70 L0,70 Z"/>
  </svg>
</section>

<!-- ===== MAIN CONTENT ===== -->
<section class="section">
  <div class="container">

    <div class="preamble">
      تم الاتفاق وتحرير هذا العقد في يوم <strong>الأحد الموافق 19 يوليو 2026</strong> بين الطرفين الآتي ذكرهما، وذلك على تنفيذ أعمال عزل سطح جراج السيارات بنظام Cool Roof وفق المواصفات الفنية والشروط المنصوص عليها أدناه، والتزام كلا الطرفين بجميع بنود هذا العقد.
    </div>

    <!-- 1: أطراف العقد -->
    <div class="c-section">
      <div class="c-section-title"><div class="c-s-num">1</div> أطراف العقد</div>
      <div class="card" style="padding:20px;">
        <div class="parties-grid">
          <div class="party-box">
            <div class="party-head"><i class="fas fa-building"></i> الطرف الأول — المقاول</div>
            <div class="party-body">
              <div class="party-row"><span class="pr-label">الاسم:</span><span class="pr-val">ركن التطور لأنظمة العزل الحديث ذ.م.م</span></div>
            </div>
          </div>
          <div class="party-box">
            <div class="party-head"><i class="fas fa-user-tie"></i> الطرف الثاني — صاحب العمل</div>
            <div class="party-body">
              <div class="party-row"><span class="pr-label">الاسم:</span><span class="pr-val">جمال النعيمي</span></div>
              <div class="party-row"><span class="pr-label">العنوان:</span><span class="pr-val">بيت 19 - شارع بلال بن رباح - الراشدية 2 - عجمان</span></div>
              <div class="party-row"><span class="pr-label">نوع العقار:</span><span class="pr-val">سطح جراج سيارات</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 2: نطاق الأعمال -->
    <div class="c-section">
      <div class="c-section-title"><div class="c-s-num">2</div> نطاق الأعمال المتعاقد عليها</div>
      <div class="card" style="padding:20px;">
        <div class="price-table-wrap">
          <table class="price-table scope-table">
            <thead>
              <tr><th style="width:36px;">#</th><th>البند</th><th style="width:120px;">المواصفة</th></tr>
            </thead>
            <tbody>
<?php $n=1; foreach ($items as $item): ?>
        <tr>
          <td class="num"><?= $n++ ?></td>
          <td>
            <strong style="color:#004EA8;font-size:12.5px;"><?= e($item['title']) ?></strong>
            <?php if (!empty($item['description'])): ?><br>
            <span style="font-size:10.5px;color:var(--muted);line-height:1.65;"><?= nl2br(e($item['description'])) ?></span>
            <?php endif; ?>
          </td>
          <td class="center"><?= e(rtrim(rtrim(number_format((float)$item['quantity'], 2), '0'), '.')) ?><?= !empty($item['unit']) ? ' '.e($item['unit']) : '' ?></td>
          <td class="center"><?= e(number_format((float)$item['unit_price'], 2)) ?> <?= e($currency) ?></td>
          <td class="center"><strong style="color:#004EA8;font-size:13.5px;"><?= e(number_format((float)$item['total'], 2)) ?> <?= e($currency) ?></strong></td>
        </tr>
<?php endforeach; ?>
</tbody>
          </table>
        </div>
        <div class="info-badges">
          <div class="info-badge"><i class="fas fa-ruler-combined"></i> المساحة الإجمالية: <strong>55 متر مربع</strong></div>
          <div class="info-badge"><i class="fas fa-calendar-days"></i> مدة التنفيذ: <strong>يوم عمل واحد</strong></div>
        </div>
      </div>
    </div>

    <!-- 3: الجدول الزمني -->
    <div class="c-section">
      <div class="c-section-title"><div class="c-s-num">3</div> جدول التنفيذ الزمني</div>
      <div class="card" style="padding:24px;">
        <div class="timeline-wrap">
          <div class="tl-item">
            <div class="tl-date">الأحد — 19 يوليو 2026</div>
            <div class="tl-title">توقيع العقد والاتفاق على موعد التنفيذ</div>
            <div class="tl-desc">اعتماد العقد من الطرفين وسداد الدفعة الأولى</div>
          </div>
          <div class="tl-item">
            <div class="tl-date">الاثنين — 20 يوليو 2026</div>
            <div class="tl-title">يوم التنفيذ والتسليم</div>
            <div class="tl-desc">إزالة العزل القديم التالف وتنظيف السطح، تسوية السطح بطبقة أسمنتيشن (Teraco) ومعالجة التشققات، تطبيق Primer وشبك التسليح Tietex T325، ثم تطبيق طبقات Cool Roof الأربع بالكامل، والتحقق من جودة التطبيق والتسليم الرسمي لصاحب العمل — كل ذلك ضمن يوم عمل واحد</div>
          </div>
        </div>
      </div>
    </div>

    <!-- 4: شروط الدفع -->
    <div class="c-section">
      <div class="c-section-title"><div class="c-s-num">4</div> شروط الدفع والجدول المالي</div>
      <div class="card" style="padding:0;">
        <div class="price-table-wrap" style="margin-bottom:0;box-shadow:none;border-radius:20px 20px 0 0;">
          <table class="price-table">
            <thead>
              <tr><th>النسبة</th><th>الدفعة</th><th>الاستحقاق</th><th>المبلغ / التاريخ</th></tr>
            </thead>
            <tbody>
              <tr>
                <td style="text-align:center;color:var(--blue-accent);font-weight:900;">50%</td>
                <td><div class="service-name">الدفعة الأولى — المقدمة</div><div class="service-desc">تُسدَّد عند توقيع العقد قبل بدء العمل</div></td>
                <td>عند توقيع العقد</td>
                <td><strong style="color:var(--blue-dark);font-size:15px;">2,227.5 درهم</strong><br><span style="font-size:11.5px;color:var(--gray-mid);">19 يوليو 2026</span></td>
              </tr>
              <tr>
                <td style="text-align:center;color:var(--blue-accent);font-weight:900;">40%</td>
                <td><div class="service-name">الدفعة الثانية — أثناء التنفيذ</div><div class="service-desc">تُسدَّد عند بدأ التنفيذ</div></td>
                <td>عند التنفيذ</td>
                <td><strong style="color:var(--blue-dark);font-size:15px;">1,782 درهم</strong><br><span style="font-size:11.5px;color:var(--gray-mid);">20 يوليو 2026</span></td>
              </tr>
              <tr>
                <td style="text-align:center;color:var(--gold);font-weight:900;">10%</td>
                <td><div class="service-name">الدفعة الثالثة — عند التسليم</div><div class="service-desc">عند إتمام التنفيذ والتسليم الرسمي</div></td>
                <td>عند الانتهاء والتسليم</td>
                <td><strong style="color:var(--gold);font-size:15px;">445.5 درهم</strong><br><span style="font-size:11.5px;color:var(--gray-mid);">20 يوليو 2026</span></td>
              </tr>
              <tr class="total-row">
                <td colspan="3">الإجمالي قبل الخصم</td>
                <td class="total-amount">4,950 درهم</td>
              </tr>
              <tr class="vat-row">
                <td colspan="3"><i class="fas fa-tag" style="color:var(--gold);margin-left:6px;"></i> خصم العرض الخاص (10٪ — ساري من 20 يونيو حتى 20 يوليو 2026)</td>
                <td style="color:#C0392B;font-weight:700;">− 495 درهم</td>
              </tr>
              <tr class="grand-total-row">
                <td colspan="3">إجمالي قيمة العقد بعد الخصم</td>
                <td class="grand-amount">4,455 درهم</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="receipt-note">
          <i class="fas fa-receipt"></i>
          <span>يلتزم الطرف الأول بتسليم <strong>سند قبض رسمي موقع</strong> لصاحب العمل فور استلام كل دفعة من الدفعات الثلاث، ويُعدّ سند القبض وثيقة إثبات قانونية معتمدة.</span>
        </div>
      </div>
    </div>

    <!-- 5: التزامات الطرفين -->
    <div class="c-section">
      <div class="c-section-title"><div class="c-s-num">5</div> التزامات الطرفين</div>
      <div class="clauses-cols">
        <div class="clauses-col">
          <div class="clauses-col-title"><i class="fas fa-hard-hat" style="color:var(--gold);"></i> الطرف الأول (المقاول) يلتزم بـ:</div>
          <div class="clause-item"><div class="clause-num">1</div><div class="clause-text">تنفيذ الأعمال وفق المواصفات الفنية المتفق عليها في هذا العقد بالكامل</div></div>
          <div class="clause-item"><div class="clause-num">2</div><div class="clause-text">توفير جميع المواد والمعدات والعمالة المتخصصة اللازمة للتنفيذ</div></div>
          <div class="clause-item"><div class="clause-num">3</div><div class="clause-text">الالتزام بالجدول الزمني المحدد والانتهاء من التنفيذ خلال يوم عمل واحد</div></div>
          <div class="clause-item"><div class="clause-num">4</div><div class="clause-text">تنظيف الموقع بعد الانتهاء من الأعمال وإعادته لحالته النظيفة</div></div>
          <div class="clause-item"><div class="clause-num">5</div><div class="clause-text">إصدار سند قبض رسمي عند استلام كل دفعة مالية</div></div>
        </div>
        <div class="clauses-col">
          <div class="clauses-col-title"><i class="fas fa-user-tie" style="color:var(--gold);"></i> الطرف الثاني (صاحب العمل) يلتزم بـ:</div>
          <div class="clause-item"><div class="clause-num">1</div><div class="clause-text">سداد الدفعات المالية في مواعيدها المحددة كما هو مبيّن في جدول الدفع</div></div>
          <div class="clause-item"><div class="clause-num">2</div><div class="clause-text">توفير إمكانية الوصول الكامل والآمن إلى سطح الجراج طوال مدة التنفيذ</div></div>
          <div class="clause-item"><div class="clause-num">3</div><div class="clause-text">إبلاغ الطرف الأول فوراً بأي ملاحظات أو اعتراضات خلال مرحلة التنفيذ</div></div>
          <div class="clause-item"><div class="clause-num">4</div><div class="clause-text">عدم التدخل في آلية العمل أو تغيير المواصفات دون اتفاق مسبق مكتوب</div></div>
          <div class="clause-item"><div class="clause-num">5</div><div class="clause-text">الحضور أو تفويض من ينوب عنه عند جلسة التسليم الرسمي</div></div>
        </div>
      </div>
    </div>

    <!-- 6: الضمان والأحكام العامة -->
    <div class="warranty-card fade-in">
      <div class="warranty-header">
        <div class="warranty-years">
          <span class="years-num">10</span>
          <span class="years-label">سنوات</span>
        </div>
        <div>
          <h2>الضمان والأحكام العامة</h2>
          <p>التزامنا القانوني والأخلاقي تجاه صاحب العمل</p>
        </div>
      </div>
      <div class="gen-clauses">
        <div class="gen-clause"><div class="gen-num">1</div><div class="gen-text"><strong>الضمان:</strong> يضمن الطرف الأول سلامة التطبيق وجودة المواد المستخدمة لمدة <strong>10 سنوات</strong> من تاريخ التسليم الرسمي، تشمل الحماية من التسريب وفقدان الكفاءة الحرارية.</div></div>
        <div class="gen-clause"><div class="gen-num">2</div><div class="gen-text"><strong>إلغاء العقد:</strong> في حال رغبة أي طرف في الإلغاء قبل بدء التنفيذ، يُشترط إخطار الطرف الآخر كتابياً مع تسوية أي مستحقات مالية متبادلة.</div></div>
        <div class="gen-clause"><div class="gen-num">3</div><div class="gen-text"><strong>التأخير:</strong> في حال تأخر الطرف الثاني في سداد أي دفعة عن موعدها المحدد، يحق للطرف الأول إيقاف الأعمال مؤقتاً حتى سداد المستحق.</div></div>
        <div class="gen-clause"><div class="gen-num">4</div><div class="gen-text"><strong>فض النزاعات:</strong> تُحل أي خلافات تنشأ عن هذا العقد بالتراضي أولاً، وعند تعذّر ذلك يُرجع إلى الجهات القانونية المختصة في دولة الإمارات العربية المتحدة.</div></div>
        <div class="gen-clause"><div class="gen-num">5</div><div class="gen-text"><strong>نسخ العقد:</strong> حُرّر هذا العقد من نسختين أصليتين بيد كل طرف نسخة تحمل نفس القوة القانونية.</div></div>
      </div>
    </div>

    <!-- التوقيعات -->
    <div class="c-section">
      <div class="sig-title">توقيعات الطرفين — إقرار واعتماد</div>
      <div class="sig-grid">
        <div class="sig-box">
          <div class="sb-label">الطرف الأول — المقاول</div>
          <div class="sb-name">ركن التطور لأنظمة العزل الحديث ذ.م.م</div>
          <div class="sig-images">
            <img class="combined" src="<?= e($seal) ?>" alt="ختم وتوقيع ركن التطور">
          </div>
        </div>
        <div class="sig-box">
          <div class="sb-label">الطرف الثاني — صاحب العمل</div>
          <div class="sb-name">جمال النعيمي</div>
          <div class="client-sig-space"></div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- FOOTER مع الخريطة مدمجة -->
<footer class="footer">

  <div class="footer-office-bar">
    <p>
      <strong>ركن التطور لأنظمة العزل الحديث ذ.م.م</strong> ·  <br>
      <span style="margin-top:6px;display:inline-block;">هذا العقد وثيقة رسمية معتمدة من الطرفين · جميع الأسعار بالدرهم الإماراتي</span>
    </p>
  </div>

  <div class="contract-ref-bar">
    <span class="cf-num">RET-CON-AJM-2026-0719</span>
    <span>19 يوليو 2026</span>
    <span>جمال النعيمي — الراشدية 2، عجمان</span>
  </div>
</footer>


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
