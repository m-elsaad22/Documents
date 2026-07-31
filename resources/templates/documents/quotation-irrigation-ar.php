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
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
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
    --success: #2E7D32;
    --text-body: #2D3748;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Cairo', 'Tajawal', sans-serif;
    background: #F0F4F8;
    color: var(--text-body);
    overflow-x: hidden;
  }

  /* HERO WITH ANIMATIONS */
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

  .logo-area {
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .logo-icon {
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: var(--blue-dark);
    box-shadow: 0 4px 20px rgba(212,160,23,0.4);
  }

  .logo-text h1 {
    font-size: 18px;
    font-weight: 900;
    color: #fff;
    line-height: 1.1;
    letter-spacing: 0.5px;
  }

  .logo-text span {
    font-size: 11px;
    color: var(--gold-light);
    font-weight: 400;
    letter-spacing: 1px;
  }

  .hero-badge {
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    padding: 8px 18px;
    border-radius: 50px;
    font-size: 12px;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 7px;
  }

  .hero-badge i { color: var(--gold-light); }

  .hero-content {
    padding: 60px 40px 100px;
    position: relative;
    z-index: 10;
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
  }

  .quote-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(212,160,23,0.2);
    border: 1px solid rgba(212,160,23,0.4);
    color: var(--gold-light);
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 28px;
    animation: fadeInDown 0.6s ease both;
  }

  .hero-title {
    font-size: clamp(26px, 5vw, 46px);
    font-weight: 900;
    color: #fff;
    line-height: 1.3;
    margin-bottom: 18px;
    animation: fadeInUp 0.7s ease 0.1s both;
  }

  .hero-title .highlight {
    background: linear-gradient(90deg, var(--gold), var(--gold-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .hero-subtitle {
    font-size: 16px;
    color: rgba(255,255,255,0.75);
    line-height: 1.8;
    max-width: 640px;
    margin: 0 auto 40px;
    animation: fadeInUp 0.7s ease 0.2s both;
  }

  .hero-stats {
    display: flex;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap;
    animation: fadeInUp 0.7s ease 0.3s both;
  }

  .stat-item {
    text-align: center;
  }

  .stat-num {
    font-size: 32px;
    font-weight: 900;
    color: var(--gold-light);
    line-height: 1;
  }

  .stat-label {
    font-size: 12px;
    color: rgba(255,255,255,0.6);
    margin-top: 4px;
  }

  @keyframes fadeInDown {
    from { opacity:0; transform:translateY(-20px); }
    to { opacity:1; transform:translateY(0); }
  }

  @keyframes fadeInUp {
    from { opacity:0; transform:translateY(20px); }
    to { opacity:1; transform:translateY(0); }
  }

  /* SECTION */
  .section { padding: 60px 20px; }
  .container { max-width: 900px; margin: 0 auto; }

  /* CLIENT CARD */
  .client-card {
    background: #fff;
    border-radius: 20px;
    padding: 32px;
    box-shadow: 0 4px 30px rgba(11,37,69,0.08);
    border: 1px solid rgba(11,37,69,0.06);
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.5s ease both;
  }

  .client-card::before {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 5px;
    height: 100%;
    background: linear-gradient(180deg, var(--blue-accent), var(--blue-light));
    border-radius: 0 20px 20px 0;
  }

  .card-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 24px;
  }

  .card-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--blue-dark), var(--blue-accent));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
  }

  .card-header h2 {
    font-size: 20px;
    font-weight: 800;
    color: var(--blue-dark);
  }

  .card-header p { font-size: 13px; color: var(--gray-mid); margin-top: 2px; }

  .info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
  }

  .info-item {
    background: var(--gray-light);
    border-radius: 12px;
    padding: 14px 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
  }

  .info-item i {
    color: var(--blue-accent);
    margin-top: 2px;
    font-size: 15px;
    flex-shrink: 0;
  }

  .info-item .label {
    font-size: 11px;
    color: var(--gray-mid);
    font-weight: 500;
    margin-bottom: 3px;
  }

  .info-item .value {
    font-size: 14px;
    font-weight: 700;
    color: var(--blue-dark);
  }

  /* PRODUCT CARD (EXECUTION STYLE) */
  .product-card {
    background: linear-gradient(135deg, var(--blue-dark) 0%, #0F3060 100%);
    border-radius: 20px;
    padding: 32px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
    color: white;
  }

  .product-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 28px;
  }

  .product-logo {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--blue-dark);
    flex-shrink: 0;
  }

  .product-header h2 {
    font-size: 22px;
    font-weight: 900;
    color: #fff;
  }
  .product-header p {
    font-size: 13px;
    color: rgba(255,255,255,0.65);
    margin-top: 3px;
  }
  .certified-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(212,160,23,0.25);
    border: 1px solid rgba(212,160,23,0.5);
    color: var(--gold-light);
    padding: 5px 14px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 6px;
  }

  .method-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--gold-light);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .method-steps {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }

  .method-step {
    background: rgba(255,255,255,0.06);
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    border-right: 3px solid var(--gold);
  }

  .step-num {
    width: 26px;
    height: 26px;
    background: var(--gold);
    color: var(--blue-dark);
    border-radius: 50%;
    font-size: 12px;
    font-weight: 900;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .step-text { font-size: 13px; color: rgba(255,255,255,0.85); line-height: 1.5; }
  .step-text strong { color: #fff; display: block; font-size: 12px; margin-bottom: 2px; }

  /* BENEFITS GRID */
  .benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
  }

  .benefit-card {
    background: #fff;
    border-radius: 16px;
    padding: 22px 16px;
    text-align: center;
    box-shadow: 0 2px 20px rgba(11,37,69,0.07);
    transition: all 0.3s ease;
  }
  .benefit-card i {
    font-size: 28px;
    color: var(--blue-accent);
    margin-bottom: 12px;
  }
  .benefit-card h3 { font-size: 14px; font-weight: 800; color: var(--blue-dark); margin-bottom: 6px; }
  .benefit-card p { font-size: 12px; color: var(--gray-mid); }

  /* PRICE CARDS */
  .price-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 40px rgba(11,37,69,0.1);
    margin-bottom: 28px;
  }

  .price-header {
    background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid));
    padding: 24px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
  }
  .price-header h2 {
    font-size: 20px;
    font-weight: 900;
    color: #fff;
    display: flex;
    gap: 10px;
    align-items: center;
  }
  .validity-badge {
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.2);
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 12px;
  }

  .price-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Cairo', sans-serif;
  }
  .price-table thead tr {
    background: var(--gray-light);
  }
  .price-table thead th {
    padding: 14px 20px;
    text-align: right;
    font-size: 13px;
    font-weight: 700;
    color: var(--blue-dark);
    border-bottom: 2px solid rgba(11,37,69,0.1);
  }
  .price-table tbody tr {
    border-bottom: 1px solid rgba(11,37,69,0.05);
  }
  .price-table tbody tr:nth-child(even) {
    background-color: #f8fafc;
  }
  .price-table tbody td {
    padding: 16px 20px;
    font-size: 14px;
    vertical-align: middle;
  }
  .service-name {
    font-weight: 700;
    color: var(--blue-dark);
  }
  .service-desc {
    font-size: 12px;
    color: var(--gray-mid);
    margin-top: 3px;
  }
  .price-note {
    padding: 16px 20px;
    background: rgba(212,160,23,0.07);
    border-top: 1px solid rgba(212,160,23,0.2);
    font-size: 12px;
    color: var(--gray-mid);
    display: flex;
    gap: 8px;
  }
  .price-note i { color: var(--gold); flex-shrink: 0; margin-top: 2px; }

  /* WARRANTY CARD */
  .warranty-card {
    background: linear-gradient(135deg, #1A6DAF, #0B4F8A);
    border-radius: 20px;
    padding: 32px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
  }
  .warranty-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
  }
  .warranty-years {
    width: 72px;
    height: 72px;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  .years-num {
    font-size: 26px;
    font-weight: 900;
    color: var(--blue-dark);
    line-height: 1;
  }
  .years-label { font-size: 10px; font-weight: 700; color: var(--blue-dark); }
  .warranty-header h2 { font-size: 22px; font-weight: 900; color: #fff; }
  .warranty-header p { font-size: 13px; color: rgba(255,255,255,0.7); margin-top: 4px; }

  .warranty-items {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }
  .warranty-item {
    background: rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 14px 16px;
    display: flex;
    gap: 10px;
  }
  .warranty-item i {
    width: 28px;
    height: 28px;
    background: rgba(56,161,105,0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    color: #68D391;
  }
  .wi-title { font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 3px; }
  .wi-desc { font-size: 11px; color: rgba(255,255,255,0.65); }

  /* CTA */
  .cta-section {
    background: #fff;
    border-radius: 20px;
    padding: 40px 32px;
    text-align: center;
    box-shadow: 0 4px 30px rgba(11,37,69,0.08);
    margin-bottom: 28px;
  }
  .cta-section h2 { font-size: 26px; font-weight: 900; color: var(--blue-dark); margin-bottom: 10px; }
  .cta-section p { font-size: 15px; color: var(--gray-mid); margin-bottom: 32px; max-width: 500px; margin-inline: auto; }
  .cta-buttons {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
  }
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 30px;
    border-radius: 14px;
    font-weight: 800;
    cursor: pointer;
    text-decoration: none;
    transition: 0.3s;
    border: 2px solid transparent;
  }
  .btn-primary {
    background: linear-gradient(135deg, #25D366, #1DAA54);
    color: white;
    box-shadow: 0 8px 30px rgba(37,211,102,0.35);
  }
  .btn-primary:hover { transform: translateY(-3px); }
  .btn-secondary {
    background: transparent;
    color: var(--blue-accent);
    border-color: var(--blue-accent);
  }
  .btn-secondary:hover { background: var(--blue-accent); color: white; }
  .trust-row {
    display: flex;
    justify-content: center;
    gap: 28px;
    margin-top: 28px;
    flex-wrap: wrap;
  }
  .trust-item { display: flex; align-items: center; gap: 7px; font-size: 13px; color: var(--gray-mid); }
  .trust-item i { color: var(--success); }

  .footer {
    background: var(--blue-dark);
    color: rgba(255,255,255,0.6);
    text-align: center;
    padding: 28px 20px;
    font-size: 13px;
  }
  .footer strong { color: var(--gold-light); }

  .fade-in {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }
  .fade-in.visible {
    opacity: 1;
    transform: translateY(0);
  }

  @media (max-width: 640px) {
    .hero-top-bar { padding: 14px 20px; }
    .hero-content { padding: 40px 20px 80px; }
    .hero-stats { gap: 24px; }
    .stat-num { font-size: 26px; }
    .method-steps { grid-template-columns: 1fr; }
    .warranty-items { grid-template-columns: 1fr; }
    .price-table thead th, .price-table tbody td { padding: 12px; }
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

<section class="hero">
  <div class="hero-sun"></div>
  <div class="hero-top-bar">
    <div class="logo-area">
      <div class="logo-icon"><i class="fas fa-tractor"></i></div>
      <div class="logo-text">
        <h1>ركن التطور</h1>
        <span>RUKN ELTATAWER · العين</span>
      </div>
    </div>
    <div class="hero-badge">
      <i class="fas fa-seedling"></i>
      <span>متخصصون في شبكات الري الحديثة</span>
    </div>
  </div>
  <div class="hero-content">
    <div class="quote-label">
      <i class="fas fa-file-invoice-dollar"></i>
      عرض سعر تنفيذي · صالح لمدة 30 يوماً
    </div>
    <h1 class="hero-title">
      احمِ مزرعتك وضاعف إنتاجك<br>
      بنظام <span class="highlight">ري متكامل ومؤتمت</span>
    </h1>
    <p class="hero-subtitle">
      حل هندسي دقيق يضمن توزيعاً متساوياً للمياه، يوفر استهلاك الهدر، ويعتمد على أفضل خراطيم التنقيط والمحابس — جاهز للعمل فوراً.
    </p>
    <div class="hero-stats">
      <div class="stat-item"><div class="stat-num">13,600</div><div class="stat-label">خراطيم تنقيط GR</div></div>
      <div class="stat-item"><div class="stat-num">31</div><div class="stat-label">محبس تحكم رئيسي وفرعي</div></div>
      <div class="stat-item"><div class="stat-num">4-6</div><div class="stat-label">أيام عمل للتنفيذ السريع</div></div>
      <div class="stat-item"><div class="stat-num">33,489</div><div class="stat-label">م² المساحة الإجمالية</div></div>
    </div>
  </div>
  <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
    <path fill="#F0F4F8" d="M0,40 C360,80 1080,0 1440,40 L1440,70 L0,70 Z"/>
  </svg>
</section>

<section class="section">
  <div class="container">

    <!-- CLIENT INFO CARD -->
    <div class="client-card fade-in">
      <div class="card-header">
        <div class="card-icon"><i class="fas fa-user-tie"></i></div>
        <div><h2>بيانات العميل والمشروع</h2><p>العرض مُعد خصيصاً للمزرعة في العين</p></div>
      </div>
      <div class="info-grid">
        <div class="info-item"><i class="fas fa-user"></i><div><div class="label">صاحب المزرعة</div><div class="value">الأستاذ / راشد الظهوري</div></div></div>
        <div class="info-item"><i class="fas fa-location-dot"></i><div><div class="label">موقع المشروع</div><div class="value">مدينة العين - الإمارات</div></div></div>
        <div class="info-item"><i class="fas fa-diagram-project"></i><div><div class="label">رقم المخطط</div><div class="value">RAAK083-397</div></div></div>
        <div class="info-item"><i class="fas fa-tree"></i><div><div class="label">نوع المنشأة</div><div class="value">مزرعة متكاملة (نخيل، حمضيات، خضراوات وموسميات)</div></div></div>
        <div class="info-item"><i class="fas fa-clock"></i><div><div class="label">مدة التنفيذ المتوقعة</div><div class="value">من 4 إلى 6 أيام (حسب الخيار المعتمد)</div></div></div>
      </div>
    </div>

    <!-- EXECUTION METHOD (PRODUCT CARD) -->
    <div class="product-card fade-in">
      <div class="product-header">
        <div class="product-logo"><i class="fas fa-rocket"></i></div>
        <div>
          <h2>طريقة التنفيذ السريع (Fast-Track Execution)</h2>
          <span class="certified-badge"><i class="fas fa-check-circle"></i> منهجية احترافية</span>
        </div>
      </div>
      <div class="method-steps">
        <div class="method-step"><div class="step-num">1</div><div class="step-text"><strong>تمديد الخطوط</strong> وضع الأنابيب الرئيسية (90مم) والفرعية (63/50مم) داخل الحفر الجاهزة.</div></div>
        <div class="method-step"><div class="step-num">2</div><div class="step-text"><strong>تركيب المحابس</strong> توزيع وربط المحابس ولوحة التحكم المركزية.</div></div>
        <div class="method-step"><div class="step-num">3</div><div class="step-text"><strong>شبكة التنقيط</strong> فرد وتوصيل خراطيم الـ GR بمسافات دقيقة لكل شتلة وحوض.</div></div>
        <div class="method-step"><div class="step-num">4</div><div class="step-text"><strong>الاختبار والتشغيل</strong> ضغط الشبكة بالكامل لضمان عدم وجود تسريب وتسليمها للزراعة.</div></div>
      </div>
    </div>

    <!-- TABLE 1: FULL FARM (31 VALVES) -->
    <div class="price-card fade-in">
      <div class="price-header">
        <h2><i class="fas fa-tachometer-alt"></i> المزرعة بالكامل (31 محبساً)</h2>
        <div class="validity-badge"><i class="fas fa-ruler-combined"></i> المساحة: 33,489 م²</div>
      </div>
      <table class="price-table">
        <thead><tr><th>الخيار</th><th>المواصفات التقنية</th><th>الإجمالي (درهم)</th></tr></thead>
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
      <div class="price-note"><i class="fas fa-circle-info"></i><span>السعر شامل التوريد والتركيب والتمديد، بناءً على توفير العميل للحفار والمضخة والخزان.</span></div>
    </div>

    <!-- TABLE 2: VEGETABLES AREA (9 VALVES) -->
    <div class="price-card fade-in">
      <div class="price-header" style="background:linear-gradient(135deg,#1B4F72,#2E86C1);">
        <h2><i class="fas fa-leaf"></i> منطقة الخضراوات فقط - كمرحلة أولى (9 محابس)</h2>
        <div class="validity-badge"><i class="fas fa-seedling"></i> مساحة 12,000 م² تقريباً</div>
      </div>
      <table class="price-table">
        <thead><tr><th>الخيار</th><th>المواصفات التقنية</th><th>الإجمالي (درهم)</th></tr></thead>
        <tbody>
          <tr>
            <td><div class="service-name">نظام منتج أوروبي وصيني - مرحلة أولى</div><div class="service-desc">مثالي للبدء السريع في الزراعات الموسمية بتكلفة منخفضة</div></td>
            <td><span style="background:#e3f2fd; padding:4px 12px; border-radius:50px;">أنابيب HDPE رئيسية/فرعية، محابس، فلاتر، 7000 م خراطيم GR</span></td>
            <td style="font-weight:800; color:var(--blue-accent);"> 13,789 درهم</td>
          </tr>
          <tr>
            <td><div class="service-name" style="color:var(--gold);">نظام منتج أمريكي الأعلى جودة ⭐ الأكثر طلباً</div><div class="service-desc">تحكم كامل يوفر العمالة والمياه</div></td>
            <td><span style="background:#FFF3E0; padding:4px 12px; border-radius:50px; font-size:12px;">أنابيب HDPE، محابس كهربائية، لوحة تحكم وكابلات، فلاتر + 7000 م GR</span></td>
            <td style="font-weight:800; color:var(--gold);">18,553.5 درهم</td>
          </tr>
        </tbody>
      </table>
      <div class="price-note"><i class="fas fa-lightbulb"></i><span>جميع الأسعار بالدرهم الإماراتي، تشمل المواد وأجور التركيب والاختبار.</span></div>
    </div>

    <!-- TRANSPARENCY CARD (WARRANTY STYLE) -->
    <div class="warranty-card fade-in">
      <div class="warranty-header">
        <div class="warranty-years"><span class="years-num">100%</span><span class="years-label">شفافية</span></div>
        <div><h2>شفافية تامة وبدون تكاليف خفية</h2><p>التزامنا الأخلاقي يضمن لك أفضل تجربة</p></div>
      </div>
      <div class="warranty-items">
        <div class="warranty-item"><i class="fas fa-check-circle"></i><div><div class="wi-title">خراطيم التنقيط مشمولة</div><div class="wi-desc">جميع عروضنا تتضمن خراطيم الـ GR للزراعة (والتي تتجاهلها العروض التنافسية لخفض السعر ظاهرياً).</div></div></div>
        <div class="warranty-item"><i class="fas fa-check-circle"></i><div><div class="wi-title">لا رسوم حفر</div><div class="wi-desc">العرض لا يشمل رسوم حفر بفضل توفيركم للـ Mini Excavator.</div></div></div>
        <div class="warranty-item"><i class="fas fa-check-circle"></i><div><div class="wi-title">تسليم مفتاح</div><div class="wi-desc">الشبكة تسلم مضغوطة ومختبرة وجاهزة لفتح المياه فوراً.</div></div></div>
        <div class="warranty-item"><i class="fas fa-check-circle"></i><div><div class="wi-title">ضمان التشغيل</div><div class="wi-desc">نضمن كفاءة الشبكة لمدة سنتين ضد عيوب التصنيع والتركيب.</div></div></div>
      </div>
    </div>

    <!-- CTA SECTION -->
    <div class="cta-section fade-in">
      <h2>جاهزون للبدء وتنزيل العمالة فوراً!</h2>
      <p>فريقنا الفني والمهندسون في انتظار اعتمادكم لبدء التنفيذ خلال 24 ساعة.</p>
      <div class="cta-buttons">
        <a href="https://wa.me/971501234567?text=%D8%A7%D9%84%D8%B3%D9%84%D8%A7%D9%85%20%D8%B9%D9%84%D9%8A%D9%83%D9%85%D8%8C%20%D8%A7%D8%B7%D9%84%D8%B9%D8%AA%20%D8%B9%D9%84%D9%89%20%D8%B9%D8%B1%D8%B6%20%D8%B1%D9%83%D9%86%20%D8%A7%D9%84%D8%AA%D8%B7%D9%88%D8%B1%20%D9%84%D8%B4%D8%A8%D9%83%D8%A9%20%D8%A7%D9%84%D8%B1%D9%8A%20%D9%81%D9%8A%20%D8%A7%D9%84%D8%B9%D9%8A%D9%86%D8%8C%20%D9%88%D8%A3%D8%B1%D9%8A%D8%AF%20%D8%A7%D8%B9%D8%AA%D9%85%D8%A7%D8%AF%20%D8%A7%D9%84%D8%B9%D8%B1%D8%B6.%20%D8%A3%D8%B1%D8%AC%D9%88%20%D8%A7%D9%84%D8%AA%D9%88%D8%A7%D8%B5%D9%84." class="btn btn-primary" target="_blank">
          <i class="fab fa-whatsapp" style="font-size:20px;"></i> اعتمد العرض الآن عبر واتساب
        </a>
        <a href="#" class="btn btn-secondary" onclick="alert('سيتم إرسال مخطط المشروع PDF عبر الواتساب أو البريد الإلكتروني فوراً'); return false;">
          <i class="fas fa-file-pdf"></i> تحميل مخطط المشروع PDF
        </a>
      </div>
      <div class="trust-row">
        <div class="trust-item"><i class="fas fa-check-circle"></i> معاينة مجانية</div>
        <div class="trust-item"><i class="fas fa-check-circle"></i> لا تكاليف خفية</div>
        <div class="trust-item"><i class="fas fa-check-circle"></i> ضمان مكتوب</div>
        <div class="trust-item"><i class="fas fa-check-circle"></i> فريق متخصص بالري</div>
      </div>
    </div>

  </div>
</section>

<footer class="footer">
  <p>
    <strong>ركن التطور لتنسيق الحدائق وشبكات الري الحديثة</strong> · أبوظبي - العين، الإمارات العربية المتحدة<br>
    <span style="margin-top:6px;display:inline-block;">هذا العرض مخصص للعميل / راشد الظهوري · الأسعار شاملة المواد والتركيب باستثناء الحفر بفضل تجهيزات العميل</span>
  </p>
</footer>

<script>
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(el => { if(el.isIntersecting) el.target.classList.add('visible'); });
  }, { threshold: 0.1 });
  document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
</script>
</body>
</html>