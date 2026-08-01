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

  /* ===== CLIENT CARD ===== */
  .section { padding: 60px 20px; }
  .container { max-width: 900px; margin: 0 auto; }

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

  /* ===== PRODUCT SPECS ===== */
  .product-card {
    background: linear-gradient(135deg, var(--blue-dark) 0%, #0F3060 100%);
    border-radius: 20px;
    padding: 32px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
    color: white;
  }

  .product-card::after {
    content: '';
    position: absolute;
    bottom: -40px;
    left: -40px;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.03);
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

  .specs-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 28px;
  }

  .spec-box {
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 14px;
    padding: 18px 14px;
    text-align: center;
    transition: all 0.3s ease;
  }

  .spec-box:hover {
    background: rgba(255,255,255,0.12);
    transform: translateY(-3px);
  }

  .spec-icon {
    font-size: 24px;
    margin-bottom: 10px;
    display: block;
  }

  .spec-value {
    font-size: 26px;
    font-weight: 900;
    color: var(--gold-light);
    line-height: 1;
  }

  .spec-unit { font-size: 14px; font-weight: 400; color: rgba(255,255,255,0.5); }

  .spec-label {
    font-size: 12px;
    color: rgba(255,255,255,0.6);
    margin-top: 6px;
    line-height: 1.4;
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
    grid-template-columns: 1fr;
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

  /* ===== BENEFITS ===== */
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
    border: 1px solid rgba(11,37,69,0.05);
    transition: all 0.3s ease;
  }

  .benefit-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 35px rgba(11,37,69,0.12);
    border-color: var(--blue-light);
  }

  .benefit-card i {
    font-size: 28px;
    color: var(--blue-accent);
    margin-bottom: 12px;
    display: block;
  }

  .benefit-card h3 {
    font-size: 14px;
    font-weight: 800;
    color: var(--blue-dark);
    margin-bottom: 6px;
  }

  .benefit-card p { font-size: 12px; color: var(--gray-mid); line-height: 1.5; }

  /* ===== PRICE TABLE ===== */
  .price-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 40px rgba(11,37,69,0.1);
    border: 1px solid rgba(11,37,69,0.06);
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
    align-items: center;
    gap: 10px;
  }

  .validity-badge {
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.9);
    padding: 6px 16px;
    border-radius: 50px;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
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
    transition: background 0.2s;
  }

  .price-table tbody tr:hover { background: rgba(26,109,175,0.04); }

  .price-table tbody td {
    padding: 16px 20px;
    font-size: 14px;
    color: var(--text-body);
    vertical-align: middle;
  }

  .price-table .service-name {
    font-weight: 700;
    color: var(--blue-dark);
  }

  .price-table .service-desc {
    font-size: 12px;
    color: var(--gray-mid);
    margin-top: 3px;
  }

  .price-range {
    font-weight: 700;
    color: var(--blue-accent);
  }

  .total-row {
    background: linear-gradient(135deg, rgba(11,37,69,0.04), rgba(26,109,175,0.06)) !important;
    font-weight: 800 !important;
  }

  .total-row td {
    padding: 18px 20px !important;
    font-weight: 800;
  }

  .total-amount {
    font-size: 18px !important;
    font-weight: 900 !important;
    color: var(--blue-dark) !important;
  }

  .vat-row td { font-size: 13px; color: var(--gray-mid); }

  .grand-total-row { background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid)) !important; }

  .grand-total-row td {
    color: #fff !important;
    padding: 20px !important;
    font-size: 16px;
    font-weight: 900;
  }

  .grand-amount {
    font-size: 22px !important;
    color: var(--gold-light) !important;
  }

  .price-note {
    padding: 16px 20px;
    background: rgba(212,160,23,0.07);
    border-top: 1px solid rgba(212,160,23,0.2);
    font-size: 12px;
    color: var(--gray-mid);
    display: flex;
    align-items: flex-start;
    gap: 8px;
  }

  .price-note i { color: var(--gold); flex-shrink: 0; margin-top: 2px; }

  /* ===== WARRANTY ===== */
  .warranty-card {
    background: linear-gradient(135deg, #1A6DAF, #0B4F8A);
    border-radius: 20px;
    padding: 32px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
  }

  .warranty-card::before {
    content: '\f3ed';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    position: absolute;
    top: -20px;
    left: -10px;
    font-size: 160px;
    color: rgba(255,255,255,0.04);
    line-height: 1;
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
    flex-shrink: 0;
    box-shadow: 0 4px 20px rgba(212,160,23,0.5);
  }

  .warranty-years .years-num {
    font-size: 26px;
    font-weight: 900;
    color: var(--blue-dark);
    line-height: 1;
  }

  .warranty-years .years-label {
    font-size: 10px;
    font-weight: 700;
    color: var(--blue-dark);
  }

  .warranty-header h2 {
    font-size: 22px;
    font-weight: 900;
    color: #fff;
  }

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
    align-items: flex-start;
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
    flex-shrink: 0;
  }

  .warranty-item .wi-title {
    font-size: 13px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 3px;
  }

  .warranty-item .wi-desc { font-size: 11px; color: rgba(255,255,255,0.65); line-height: 1.4; }

  /* ===== CTA ===== */
  .cta-section {
    background: #fff;
    border-radius: 20px;
    padding: 40px 32px;
    text-align: center;
    box-shadow: 0 4px 30px rgba(11,37,69,0.08);
    margin-bottom: 28px;
    border: 1px solid rgba(11,37,69,0.06);
  }

  .cta-section h2 {
    font-size: 26px;
    font-weight: 900;
    color: var(--blue-dark);
    margin-bottom: 10px;
  }

  .cta-section p {
    font-size: 15px;
    color: var(--gray-mid);
    margin-bottom: 32px;
    max-width: 500px;
    margin-inline: auto;
    margin-bottom: 32px;
  }

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
    font-family: 'Cairo', sans-serif;
    font-size: 15px;
    font-weight: 800;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid transparent;
  }

  .btn-primary {
    background: linear-gradient(135deg, #25D366, #1DAA54);
    color: white;
    box-shadow: 0 8px 30px rgba(37,211,102,0.35);
  }

  .btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 40px rgba(37,211,102,0.45);
  }

  .btn-secondary {
    background: transparent;
    color: var(--blue-accent);
    border-color: var(--blue-accent);
  }

  .btn-secondary:hover {
    background: var(--blue-accent);
    color: white;
    transform: translateY(-3px);
  }

  .trust-row {
    display: flex;
    justify-content: center;
    gap: 28px;
    margin-top: 28px;
    flex-wrap: wrap;
  }

  .trust-item {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    color: var(--gray-mid);
  }

  .trust-item i { color: var(--success); font-size: 14px; }

  /* ===== FOOTER ===== */
  .footer {
    background: var(--blue-dark);
    color: rgba(255,255,255,0.6);
    text-align: center;
    padding: 28px 20px;
    font-size: 13px;
  }

  .footer strong { color: var(--gold-light); }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 640px) {
    .hero-top-bar { padding: 14px 20px; }
    .hero-content { padding: 40px 20px 80px; }
    .hero-stats { gap: 24px; }
    .stat-num { font-size: 26px; }
    .specs-grid { grid-template-columns: 1fr 1fr; }
    .method-steps { grid-template-columns: 1fr; }
    .warranty-items { grid-template-columns: 1fr; }
    .hero-badge span { display: none; }
    .client-card, .product-card, .price-card, .cta-section, .warranty-card { padding: 22px 18px; }
    .price-header { padding: 18px; }
    .price-table thead th, .price-table tbody td { padding: 12px 12px; font-size: 13px; }
    .grand-amount { font-size: 18px !important; }
    .cta-section h2 { font-size: 20px; }
    .btn { padding: 14px 22px; font-size: 14px; }
    .section { padding: 40px 14px; }
  }

  @media (max-width: 400px) {
    .specs-grid { grid-template-columns: 1fr; }
    .hero-title { font-size: 22px; }
  }

  /* ===== SCROLL ANIMATION ===== */
  .fade-in {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }
  .fade-in.visible {
    opacity: 1;
    transform: translateY(0);
  }

  /* ===== QUOTE NUMBER ===== */
  .quote-meta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 20px;
    animation: fadeInUp 0.7s ease 0.35s both;
  }

  .quote-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: rgba(255,255,255,0.65);
    background: rgba(255,255,255,0.07);
    padding: 6px 14px;
    border-radius: 50px;
    border: 1px solid rgba(255,255,255,0.1);
  }

  .quote-meta-item i { color: var(--gold-light); font-size: 12px; }
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

<!-- ===== HERO ===== -->
<section class="hero">
  <div class="hero-sun"></div>

  <div class="hero-top-bar">
    <div class="logo-area">
      <div class="logo-icon"><i class="fas fa-building-shield"></i></div>
      <div class="logo-text">
        <h1>ركن التطور</h1>
        <span>RUKN ELTATAWER · أبوظبي</span>
      </div>
    </div>
    <div class="hero-badge">
      <i class="fas fa-award"></i>
      <span>معتمدون رسمياً في الإمارات</span>
    </div>
  </div>

  <div class="hero-content">
    <div class="quote-label">
      <i class="fas fa-file-invoice-dollar"></i>
      عرض سعر رسمي · قابل للتنفيذ الفوري
    </div>

    <h1 class="hero-title">
      احمِ فيلتك من حرارة أبوظبي<br>
      بنظام <span class="highlight">Roof Protection المعتمد</span>
    </h1>

    <p class="hero-subtitle">
      حل علمي متكامل يعكس 86.7٪ من أشعة الشمس، يخفض درجة حرارة السطح بأكثر من 30 درجة،
      ويوفر لك حتى 35٪ من فاتورة التبريد — مع ضمان 10 سنوات شامل.
    </p>

    <div class="quote-meta">
      <div class="quote-meta-item"><i class="fas fa-hashtag"></i> RET-AD-2024-0487</div>
      <div class="quote-meta-item"><i class="fas fa-calendar"></i> تاريخ الإصدار: 21 أبريل 2026</div>
      <div class="quote-meta-item"><i class="fas fa-clock"></i> المعاينة: 21 أبريل 2026</div>
    </div>

    <div class="hero-stats">
      <div class="stat-item">
        <div class="stat-num">86.7%</div>
        <div class="stat-label">انعكاس الأشعة الشمسية</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">10</div>
        <div class="stat-label">سنوات ضمان شامل</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">120</div>
        <div class="stat-label">متر مربع — مساحة المشروع</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">500+</div>
        <div class="stat-label">مشروع منفذ في الإمارات</div>
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

    <!-- Client Info -->
    <div class="client-card fade-in">
      <div class="card-header">
        <div class="card-icon"><i class="fas fa-user-tie"></i></div>
        <div>
          <h2>بيانات العميل والمشروع</h2>
          <p>العرض المُعد خصيصاً لهذا المشروع</p>
        </div>
      </div>
      <div class="info-grid">
        <div class="info-item">
          <i class="fas fa-user"></i>
          <div>
            <div class="label">صاحب الفيلا</div>
            <div class="value">عبدالله السويدي</div>
          </div>
        </div>
        <div class="info-item">
          <i class="fas fa-location-dot"></i>
          <div>
            <div class="label">موقع المشروع</div>
            <div class="value">فيلا 16 - شارع السَّماح - حوض 32 - مدينة خليفة - أبوظبي</div>
          </div>
        </div>
        <div class="info-item">
          <i class="fas fa-ruler-combined"></i>
          <div>
            <div class="label">مساحة السطح</div>
            <div class="value">120 متر مربع</div>
          </div>
        </div>
        <div class="info-item">
          <i class="fas fa-home"></i>
          <div>
            <div class="label">نوع المنشأة</div>
            <div class="value">فيلا سكنية — سطح مستوٍ</div>
          </div>
        </div>
        <div class="info-item">
          <i class="fas fa-screwdriver-wrench"></i>
          <div>
            <div class="label">نوع الخدمة</div>
            <div class="value">Roof Protection — 3 طبقات</div>
          </div>
        </div>
        <div class="info-item">
          <i class="fas fa-calendar-check"></i>
          <div>
            <div class="label">مدة التنفيذ المتوقعة</div>
            <div class="value">2 - 3 أيام عمل</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Product Specs -->
    <div class="product-card fade-in">
      <div class="product-header">
        <div class="product-logo"><i class="fas fa-sun"></i></div>
        <div>
          <h2>Roof Protection System</h2>
          <p>نظام العزل الأكريليكي المطاطي العاكس للحرارة بنسبة 100%</p>
          <span class="certified-badge"><i class="fas fa-certificate"></i> منتج معتمد ومختبر دولياً</span>
        </div>
      </div>

      <div class="specs-grid">
        <div class="spec-box">
          <span class="spec-icon">☀️</span>
          <div class="spec-value">0.867 <span class="spec-unit">SRI</span></div>
          <div class="spec-label">معدل انعكاس الأشعة الشمسية<br><em style="color:rgba(255,255,255,0.4);font-size:11px">Solar Reflectance Index</em></div>
        </div>
        <div class="spec-box">
          <span class="spec-icon">🌡️</span>
          <div class="spec-value">0.91 <span class="spec-unit">EI</span></div>
          <div class="spec-label">معدل الانبعاث الحراري<br><em style="color:rgba(255,255,255,0.4);font-size:11px">Thermal Emittance</em></div>
        </div>
        <div class="spec-box">
          <span class="spec-icon">🔧</span>
          <div class="spec-value">40<span class="spec-unit">%</span></div>
          <div class="spec-label">قدرة تمدد عالية<br><em style="color:rgba(255,255,255,0.4);font-size:11px">Elongation at Break</em></div>
        </div>
      </div>

      <div class="method-title">
        <i class="fas fa-list-check"></i>
        بيان طريقة التنفيذ (Method Statement)
      </div>

      <div class="method-steps">
        <div class="method-step">
          <div class="step-num">1</div>
          <div class="step-text">
            <strong>تحضير السطح</strong>
            تنظيف شامل للسطح من جميع الأغراض والغبار والشوائب تمهيداً للتطبيق
          </div>
        </div>
        <div class="method-step">
          <div class="step-num">2</div>
          <div class="step-text">
            <strong>معالجة الأركان الحادة</strong>
            تطبيق "ريبوند 100" مع خليط من الإسمنت والرمل بزاوية 45 درجة لإحكام جميع الأركان
          </div>
        </div>
        <div class="method-step">
          <div class="step-num">3</div>
          <div class="step-text">
            <strong>تعبئة التشققات</strong>
            استخدام "Epoxy Crack Filler" لردم وتعبئة جميع التشققات في الأسطح الخرسانية
          </div>
        </div>
        <div class="method-step">
          <div class="step-num">4</div>
          <div class="step-text">
            <strong>طبقة Primer أساسية</strong>
            طلاء طبقة Primer بسماكة 75 مايكرون بنسبة تخفيف 10-15٪ ماء عذب، تطبيق بالرول
          </div>
        </div>
        <div class="method-step">
          <div class="step-num">5</div>
          <div class="step-text">
            <strong>تركيب شبك النسيج Tietex T325</strong>
            تثبيت الشبك على الأركان وفتحات التصريف والمواسير مع تداخل 5-10 سم على الزوايا
          </div>
        </div>
        <div class="method-step">
          <div class="step-num">6</div>
          <div class="step-text">
            <strong>تطبيق طبقات العزل (500 مايكرون/طبقة)</strong>
            طلاء طبقة عزل 500 مايكرون بالرول، تثبيت شبك T325، إعادة التطبيق 500 مايكرون مع تداخل 5-10 سم على الزوايا لكامل المساحة
          </div>
        </div>
      </div>
    </div>

    <!-- Benefits -->
    <div class="benefits-grid fade-in">
      <div class="benefit-card">
        <i class="fas fa-bolt"></i>
        <h3>توفير الطاقة</h3>
        <p>خفض استهلاك مكيفات الهواء حتى 35٪ شهرياً</p>
      </div>
      <div class="benefit-card">
        <i class="fas fa-temperature-arrow-down"></i>
        <h3>برودة فعلية</h3>
        <p>خفض حرارة السطح 30-40 درجة مقارنة بالأسطح العادية</p>
      </div>
      <div class="benefit-card">
        <i class="fas fa-droplet-slash"></i>
        <h3>عزل مائي تام</h3>
        <p>حماية كاملة من تسرب المياه وفرق الضغط</p>
      </div>
      <div class="benefit-card">
        <i class="fas fa-leaf"></i>
        <h3>صديق للبيئة</h3>
        <p>يخفض البصمة الكربونية ومعتمد من جهات دولية</p>
      </div>
      <div class="benefit-card">
        <i class="fas fa-expand"></i>
        <h3>مرونة عالية</h3>
        <p>تمدد 40٪ يمتص حركة الهيكل دون تشقق</p>
      </div>
    </div>

    <!-- Pricing Table -->
    <div class="price-card fade-in">
      <div class="price-header" style="background:linear-gradient(135deg,#1B4F72,#2E86C1);">
        <h2><i class="fas fa-shield-halved"></i> نظام Roof Protection — 3 طبقات</h2>
        <div class="validity-badge"><i class="fas fa-ruler-combined"></i> المساحة: 120 م²</div>
      </div>

      <table class="price-table">
        <thead>
          <tr>
            <th>الخدمة</th>
            <th>نسبة العزل الحراري</th>
            <th>سعر المتر</th>
            <th>الإجمالي (120 م²)</th>
          </tr>
        </thead>
        <tbody>
<?php $n = 1; foreach ($items as $item): ?>
        <tr>
          <td style="text-align:center;"><?= $n++ ?></td>
          <td colspan="2">
            <strong><?= e($item['title']) ?></strong>
            <?php if (!empty($item['description'])): ?><div style="font-size:11px;opacity:.8;"><?= nl2br(e($item['description'])) ?></div><?php endif; ?>
          </td>
          <td style="text-align:center;"><strong><?= e(number_format((float)$item['total'], 2)) ?> <?= e($currency) ?></strong></td>
        </tr>
<?php endforeach; ?>
</tbody>
      </table>

      <!-- Payment Terms -->
      <div style="padding:24px 20px;border-top:1px solid rgba(11,37,69,0.08);">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
          <i class="fas fa-money-bill-wave" style="color:var(--blue-accent);font-size:16px;"></i>
          <span style="font-size:15px;font-weight:800;color:var(--blue-dark);">شروط الدفع</span>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
          <div style="background:var(--gray-light);border-radius:12px;padding:16px;text-align:center;border-top:3px solid var(--blue-dark);">
            <div style="font-size:26px;font-weight:900;color:var(--blue-dark);line-height:1;">50%</div>
            <div style="font-size:12px;font-weight:700;color:var(--blue-accent);margin:6px 0 4px;">دفعة مقدمة</div>
            <div style="font-size:11px;color:var(--gray-mid);">عند التعاقد وتوقيع العقد</div>
            <div style="font-size:13px;font-weight:800;color:var(--blue-dark);margin-top:8px;">3,900 درهم</div>
          </div>
          <div style="background:var(--gray-light);border-radius:12px;padding:16px;text-align:center;border-top:3px solid var(--blue-accent);">
            <div style="font-size:26px;font-weight:900;color:var(--blue-dark);line-height:1;">40%</div>
            <div style="font-size:12px;font-weight:700;color:var(--blue-accent);margin:6px 0 4px;">عند بدء العمل</div>
            <div style="font-size:11px;color:var(--gray-mid);">في اليوم الأول من التنفيذ</div>
            <div style="font-size:13px;font-weight:800;color:var(--blue-dark);margin-top:8px;">3,120 درهم</div>
          </div>
          <div style="background:var(--gray-light);border-radius:12px;padding:16px;text-align:center;border-top:3px solid var(--gold);">
            <div style="font-size:26px;font-weight:900;color:var(--blue-dark);line-height:1;">10%</div>
            <div style="font-size:12px;font-weight:700;color:var(--gold);margin:6px 0 4px;">عند الإنتهاء والتسليم</div>
            <div style="font-size:11px;color:var(--gray-mid);">بعد الاختبار وتسليم السطح</div>
            <div style="font-size:13px;font-weight:800;color:var(--blue-dark);margin-top:8px;">780 درهم</div>
          </div>
        </div>
      </div>

      <div class="price-note">
        <i class="fas fa-circle-info"></i>
        <span>جميع الأسعار بالدرهم الإماراتي · تشمل: المواد + العمالة + تجهيز السطح + تنظيف الموقع بعد الانتهاء.</span>
      </div>
    </div>

    <!-- Warranty -->
    <div class="warranty-card fade-in">
      <div class="warranty-header">
        <div class="warranty-years">
          <span class="years-num">10</span>
          <span class="years-label">سنوات</span>
        </div>
        <div>
          <h2>ضمان شامل بلا استثناء</h2>
          <p>أنت مستثمر في راحة بالك لعقد كامل — التزامنا القانوني والأخلاقي</p>
        </div>
      </div>

      <div class="warranty-items">
        <div class="warranty-item">
          <i class="fas fa-check"></i>
          <div>
            <div class="wi-title">ضمان ضد التسريب</div>
            <div class="wi-desc">أي تسريب مائي خلال 10 سنوات نصلحه على نفقتنا الكاملة فوراً</div>
          </div>
        </div>
        <div class="warranty-item">
          <i class="fas fa-check"></i>
          <div>
            <div class="wi-title">ضمان الانعكاس الحراري</div>
            <div class="wi-desc">نضمن عدم انخفاض كفاءة عزل الحرارة عن المعيار المحدد طوال فترة الضمان</div>
          </div>
        </div>
        <div class="warranty-item">
          <i class="fas fa-check"></i>
          <div>
            <div class="wi-title">ضمان تماسك الطبقات</div>
            <div class="wi-desc">لا قشور، لا تشققات، لا انفصال طبقات — أو إعادة التطبيق مجاناً</div>
          </div>
        </div>
        <div class="warranty-item">
          <i class="fas fa-check"></i>
          <div>
            <div class="wi-title">خدمة ما بعد البيع</div>
            <div class="wi-desc">فريق فني متاح على مدار الساعة + زيارات دورية للصيانة الوقائية</div>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA -->
    <div class="cta-section fade-in">
      <h2>🚀 جاهز لحماية فيلتك الآن؟</h2>
      <p>لا تدع حرارة الصيف تسرق راحتك وتضاعف فاتورتك — فريقنا جاهز للمعاينة المجانية خلال 24 ساعة</p>

      <div class="cta-buttons">
        <a href="https://wa.me/971586634710?text=السلام عليكم، أنا عبدالله السويدي، اطلعت على عرض السعر الخاص بعزل سطح فيلا 16 شارع السماح مدينة خليفة أبوظبي، وأريد اعتماد العرض والمضي قدماً. أرجو التواصل." class="btn btn-primary" target="_blank">
          <i class="fab fa-whatsapp" style="font-size:20px;"></i>
          اعتمد العرض الآن عبر واتساب
        </a>
        <a href="#" class="btn btn-secondary" onclick="downloadPDF(); return false;">
          <i class="fas fa-file-pdf"></i>
          تحميل عرض السعر PDF
        </a>
      </div>

      <div class="trust-row">
        <div class="trust-item"><i class="fas fa-check-circle"></i> معاينة مجانية خلال 24 ساعة</div>
        <div class="trust-item"><i class="fas fa-check-circle"></i> لا تكاليف خفية</div>
        <div class="trust-item"><i class="fas fa-check-circle"></i> ضمان مكتوب ومعتمد</div>
        <div class="trust-item"><i class="fas fa-check-circle"></i> فريق إماراتي متخصص</div>
      </div>
    </div>

  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <p>
    <strong>ركن التطور لأنظمة العزل الحديث ذ.م.م</strong> · أبوظبي، الإمارات العربية المتحدة<br>
    <span style="margin-top:6px;display:inline-block;">هذا العرض مُعد حصرياً لصاحبه ·</span>
  </p>
</footer>

<script>
  // Scroll animations
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(el => {
      if (el.isIntersecting) {
        el.target.classList.add('visible');
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

  function downloadPDF() {
    window.print();
  }
</script>

<style>
@media print {
  body { background: #fff !important; }
  .hero { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  .product-card { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  .warranty-card { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  .grand-total-row { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  .cta-section { display: none; }
  .btn { display: none; }
  .trust-row { display: none; }
  .footer { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>

</body>
</html>
