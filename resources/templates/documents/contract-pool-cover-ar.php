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
    --blue-dark:   #0B2545;
    --blue-mid:    #134074;
    --blue-accent: #1A6DAF;
    --blue-light:  #2196D3;
    --teal:        #0E7490;
    --teal-light:  #22D3EE;
    --gold:        #D4A017;
    --gold-light:  #F0C040;
    --gray-dark:   #2D3748;
    --gray-mid:    #718096;
    --gray-light:  #EDF2F7;
    --white:       #FFFFFF;
    --success:     #38A169;
    --text-body:   #2D3748;
    --water-bg:    #E0F2FE;
  }

  * { margin:0; padding:0; box-sizing:border-box; }

  body {
    font-family: 'Cairo', 'Tajawal', sans-serif;
    background: #EFF6FF;
    color: var(--text-body);
    overflow-x: hidden;
  }

  /* ====== PRINT BUTTON ====== */
  .no-print {
    max-width: 960px; margin: 0 auto 16px;
    padding: 14px 20px 0;
    display: flex; gap: 10px; flex-wrap: wrap;
  }
  .btn-pdf {
    display: inline-flex; align-items: center; gap: 9px;
    padding: 11px 28px; border-radius: 9px; cursor: pointer; border: none;
    font-family: 'Cairo', sans-serif; font-size: 14px; font-weight: 700;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: var(--blue-dark); box-shadow: 0 4px 18px rgba(212,160,23,.35);
    transition: all .25s;
  }
  .btn-pdf:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(212,160,23,.45); }

  /* ====== HERO ====== */
  .hero {
    background: linear-gradient(145deg, #051B38 0%, var(--blue-dark) 35%, var(--blue-mid) 70%, #0A4E7A 100%);
    position: relative; overflow: hidden; padding: 0;
  }
  .hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
      url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.025'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }

  /* Animated water circles */
  .water-circle {
    position: absolute;
    border-radius: 50%;
    animation: waterPulse 5s ease-in-out infinite;
    pointer-events: none;
  }
  .wc1 {
    width: 380px; height: 380px;
    top: -120px; left: -80px;
    background: radial-gradient(circle, rgba(14,116,144,0.22) 0%, rgba(33,150,211,0.1) 50%, transparent 70%);
  }
  .wc2 {
    width: 260px; height: 260px;
    bottom: -60px; right: -40px;
    background: radial-gradient(circle, rgba(212,160,23,0.18) 0%, rgba(240,192,64,0.07) 50%, transparent 70%);
    animation-delay: 2.5s;
  }
  @keyframes waterPulse {
    0%,100% { transform: scale(1); opacity: .7; }
    50%      { transform: scale(1.08); opacity: 1; }
  }

  /* Product image strip in hero */
  .hero-img-strip {
    position: absolute;
    right: 0; top: 0; bottom: 0; width: 38%;
    overflow: hidden; opacity: 0.18;
  }
  .hero-img-strip img {
    width: 100%; height: 100%; object-fit: cover;
    filter: saturate(1.5) brightness(1.1);
  }

  .hero-top-bar {
    display: flex; justify-content: space-between; align-items: center;
    padding: 16px 40px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    position: relative; z-index: 10;
  }
  .logo-area { display: flex; align-items: center; gap: 14px; }
  .logo-icon {
    width: 52px; height: 52px;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: var(--blue-dark);
    box-shadow: 0 4px 20px rgba(212,160,23,0.45);
  }
  .logo-text h1 { font-size: 17px; font-weight: 900; color: #fff; line-height: 1.15; }
  .logo-text span { font-size: 11px; color: var(--teal-light); font-weight: 400; letter-spacing: 1px; }
  .hero-badge {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(8px);
    padding: 8px 18px; border-radius: 50px; font-size: 12px; color: #fff;
    display: flex; align-items: center; gap: 7px;
  }
  .hero-badge i { color: var(--gold-light); }

  .hero-content {
    padding: 46px 40px 90px;
    position: relative; z-index: 10;
    max-width: 870px; margin: 0 auto; text-align: center;
  }
  .bismillah-line {
    font-family: 'Amiri', serif; font-size: 17px;
    color: var(--gold-light); opacity: .9; margin-bottom: 16px;
    animation: fadeInDown .6s ease both;
  }
  .quote-label {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(14,116,144,0.25);
    border: 1px solid rgba(34,211,238,0.35);
    color: var(--teal-light);
    padding: 7px 20px; border-radius: 50px; font-size: 13px; font-weight: 600;
    margin-bottom: 24px; animation: fadeInDown .6s ease both;
  }
  .hero-title {
    font-family: 'Amiri', serif;
    font-size: clamp(22px, 4vw, 36px); font-weight: 700;
    color: #fff; line-height: 1.5; margin-bottom: 20px;
    animation: fadeInUp .7s ease .1s both;
  }
  .hero-title .highlight {
    background: linear-gradient(90deg, var(--gold), var(--gold-light));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
  }
  .hero-title .teal-hl {
    background: linear-gradient(90deg, var(--teal-light), #67E8F9);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
  }
  .quote-meta {
    display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;
    margin-bottom: 30px; animation: fadeInUp .7s ease .2s both;
  }
  .quote-meta-item {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.85);
    padding: 6px 15px; border-radius: 50px; font-size: 12.5px;
    display: flex; align-items: center; gap: 7px;
  }
  .quote-meta-item i { color: var(--gold-light); }
  .hero-stats {
    display: flex; justify-content: center; gap: 38px; flex-wrap: wrap;
    animation: fadeInUp .7s ease .3s both;
  }
  .stat-item { text-align: center; }
  .stat-num { font-size: 28px; font-weight: 900; color: var(--gold-light); line-height: 1; }
  .stat-unit { font-size: 13px; font-weight: 700; color: var(--teal-light); }
  .stat-label { font-size: 11.5px; color: rgba(255,255,255,0.55); margin-top: 4px; }

  @keyframes fadeInDown { from { opacity:0; transform:translateY(-20px); } to { opacity:1; transform:translateY(0); } }
  @keyframes fadeInUp   { from { opacity:0; transform:translateY(20px);  } to { opacity:1; transform:translateY(0); } }

  /* WAVE divider */
  .hero-waves { position: absolute; bottom: -2px; left: 0; width: 100%; }

  /* ====== LAYOUT ====== */
  .section { padding: 56px 20px; }
  .container { max-width: 900px; margin: 0 auto; }

  .preamble {
    background: rgba(14,116,144,0.06);
    border-right: 4px solid var(--gold);
    border-radius: 0 14px 14px 0;
    padding: 20px 24px; margin-bottom: 28px;
    font-size: 13.5px; line-height: 2.1; color: var(--text-body);
    animation: fadeInUp .5s ease both;
  }

  .c-section { margin-bottom: 28px; }
  .c-section-title {
    display: flex; align-items: center; gap: 12px;
    background: linear-gradient(135deg, var(--blue-dark), var(--teal));
    color: #fff; padding: 13px 22px; border-radius: 14px;
    font-size: 15.5px; font-weight: 800; margin-bottom: 20px;
    box-shadow: 0 4px 20px rgba(11,37,69,0.18);
  }
  .c-s-num {
    width: 30px; height: 30px;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: var(--blue-dark); border-radius: 50%;
    font-size: 14px; font-weight: 900;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }

  /* ====== CARD ====== */
  .card {
    background: #fff; border-radius: 20px; padding: 28px;
    box-shadow: 0 4px 30px rgba(11,37,69,0.08);
    border: 1px solid rgba(11,37,69,0.06);
    margin-bottom: 28px; position: relative; overflow: hidden;
    animation: fadeInUp .5s ease both;
  }
  .card::before {
    content: ''; position: absolute; top: 0; right: 0; width: 5px; height: 100%;
    background: linear-gradient(180deg, var(--teal), var(--blue-light));
    border-radius: 0 20px 20px 0;
  }

  /* ====== PARTIES ====== */
  .parties-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .party-box { background: var(--gray-light); border-radius: 14px; overflow: hidden; border: 1px solid rgba(11,37,69,0.06); }
  .party-head {
    background: linear-gradient(135deg, var(--blue-dark), var(--teal));
    color: #fff; padding: 12px 18px; font-size: 13px; font-weight: 800;
    display: flex; align-items: center; gap: 8px;
  }
  .party-head i { color: var(--gold-light); }
  .party-body { padding: 16px 18px; background: #fff; }
  .party-row { display: flex; gap: 8px; margin-bottom: 9px; font-size: 13px; }
  .party-row:last-child { margin-bottom: 0; }
  .pr-label { color: var(--gray-mid); min-width: 82px; flex-shrink: 0; }
  .pr-val { font-weight: 700; color: var(--blue-dark); line-height: 1.6; }

  /* ====== PRODUCT SPECS ====== */
  .product-hero {
    border-radius: 18px; overflow: hidden; margin-bottom: 20px;
    box-shadow: 0 6px 30px rgba(11,37,69,0.14);
    position: relative;
  }
  .product-hero-img {
    width: 100%; height: 280px; object-fit: cover; display: block;
    filter: brightness(0.75) saturate(1.2);
  }
  .product-hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(11,37,69,0.75) 0%, rgba(14,116,144,0.45) 60%, transparent 100%);
    display: flex; align-items: flex-end; padding: 26px 28px;
  }
  .product-hero-title {
    font-family: 'Amiri', serif; font-size: 22px; font-weight: 700;
    color: #fff; line-height: 1.5;
  }
  .product-hero-title span {
    display: block; font-family: 'Cairo', sans-serif;
    font-size: 13px; color: var(--teal-light); font-weight: 400; margin-top: 4px;
  }

  .specs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 18px; }
  .spec-card {
    background: var(--water-bg);
    border: 1px solid rgba(14,116,144,0.18);
    border-radius: 14px; padding: 16px 18px;
    display: flex; align-items: flex-start; gap: 12px;
  }
  .spec-icon {
    width: 40px; height: 40px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--teal), var(--blue-accent));
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; color: #fff;
    box-shadow: 0 3px 12px rgba(14,116,144,0.35);
  }
  .spec-info {}
  .spec-label { font-size: 11px; color: var(--gray-mid); font-weight: 600; margin-bottom: 3px; }
  .spec-val { font-size: 14px; font-weight: 800; color: var(--blue-dark); line-height: 1.4; }
  .spec-sub { font-size: 11.5px; color: var(--teal); font-weight: 600; margin-top: 2px; }

  /* Pool dimensions box */
  .pool-dim-box {
    background: linear-gradient(135deg, var(--blue-dark), var(--teal));
    border-radius: 16px; padding: 22px 26px;
    display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;
    margin-bottom: 18px;
  }
  .dim-group { text-align: center; }
  .dim-num { font-size: 34px; font-weight: 900; color: var(--gold-light); line-height: 1; }
  .dim-unit { font-size: 14px; font-weight: 700; color: rgba(255,255,255,0.7); }
  .dim-label { font-size: 11px; color: rgba(255,255,255,0.55); margin-top: 4px; }
  .dim-sep { font-size: 28px; color: rgba(255,255,255,0.3); font-weight: 300; }
  .dim-total {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 12px; padding: 14px 22px; text-align: center;
  }
  .dim-total .dim-num { font-size: 38px; color: var(--gold-light); }
  .dim-total .dim-label { font-size: 12px; color: rgba(255,255,255,0.7); }

  /* Product gallery strip */
  .gallery-strip {
    display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; margin-top: 16px;
  }
  .gallery-strip img {
    width: 100%; height: 100px; object-fit: cover;
    border-radius: 10px;
    border: 2px solid transparent;
    transition: all .25s;
    filter: brightness(0.9) saturate(1.1);
  }
  .gallery-strip img:hover { border-color: var(--gold); transform: scale(1.03); filter: brightness(1); }

  /* ====== DELIVERY / PAYMENT GRID ====== */
  .delivery-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
  .dlv-card {
    background: #fff; border-radius: 16px; overflow: hidden;
    box-shadow: 0 3px 20px rgba(11,37,69,0.08); border: 1px solid rgba(11,37,69,0.06);
  }
  .dlv-head {
    background: linear-gradient(135deg, var(--teal), var(--blue-accent));
    color: #fff; padding: 12px 18px; font-size: 13.5px; font-weight: 800;
    display: flex; align-items: center; gap: 8px;
  }
  .dlv-head i { color: var(--gold-light); font-size: 14px; }
  .dlv-body { padding: 16px 18px; }
  .dlv-row { display: flex; gap: 8px; margin-bottom: 10px; font-size: 13px; align-items: flex-start; }
  .dlv-row:last-child { margin-bottom: 0; }
  .dlv-icon { color: var(--teal); width: 18px; flex-shrink: 0; margin-top: 2px; }
  .dlv-text { color: var(--text-body); line-height: 1.6; }
  .dlv-text strong { color: var(--blue-dark); }

  /* Big price display */
  .price-display {
    background: linear-gradient(145deg, var(--blue-dark) 0%, #0E4272 60%, var(--teal) 100%);
    border-radius: 20px; padding: 32px 28px;
    text-align: center; position: relative; overflow: hidden;
    margin-top: 18px;
    box-shadow: 0 8px 40px rgba(11,37,69,0.25);
  }
  .price-display::before {
    content: '';
    position: absolute; top: -60px; left: 50%; transform: translateX(-50%);
    width: 300px; height: 300px; border-radius: 50%;
    background: radial-gradient(circle, rgba(212,160,23,0.15) 0%, transparent 70%);
  }
  .price-label { font-size: 13px; color: rgba(255,255,255,0.6); margin-bottom: 8px; position: relative; z-index: 2; }
  .price-big {
    font-size: 58px; font-weight: 900;
    background: linear-gradient(90deg, var(--gold), var(--gold-light));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    line-height: 1; margin-bottom: 6px; position: relative; z-index: 2;
  }
  .price-currency { font-size: 20px; font-weight: 700; color: rgba(255,255,255,0.75); position: relative; z-index: 2; }
  .price-note { font-size: 12.5px; color: rgba(255,255,255,0.55); margin-top: 12px; position: relative; z-index: 2; line-height: 1.7; }
  .price-badges {
    display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; margin-top: 16px; position: relative; z-index: 2;
  }
  .price-badge {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.18);
    color: rgba(255,255,255,0.85); border-radius: 50px;
    padding: 6px 14px; font-size: 12px;
    display: flex; align-items: center; gap: 6px;
  }
  .price-badge i { color: var(--gold-light); }

  /* ====== OBLIGATIONS ====== */
  .clauses-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
  .clauses-col {
    background: #fff; border-radius: 16px; padding: 22px;
    box-shadow: 0 2px 20px rgba(11,37,69,0.07); border: 1px solid rgba(11,37,69,0.05);
  }
  .clauses-col-title {
    font-size: 14px; font-weight: 800; color: var(--blue-dark); margin-bottom: 14px;
    display: flex; align-items: center; gap: 8px;
    padding-bottom: 10px; border-bottom: 2px solid var(--gold);
  }
  .clause-item {
    display: flex; gap: 10px; margin-bottom: 11px; padding-bottom: 11px;
    border-bottom: 1px dashed rgba(11,37,69,0.1); font-size: 13px; line-height: 1.75;
  }
  .clause-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
  .clause-num {
    width: 22px; height: 22px; flex-shrink: 0; margin-top: 2px;
    background: var(--teal); color: #fff; border-radius: 50%;
    font-size: 10px; font-weight: 900; display: flex; align-items: center; justify-content: center;
  }
  .clause-text { flex: 1; color: var(--text-body); }

  /* No-installation callout */
  .callout-box {
    background: linear-gradient(135deg, rgba(212,160,23,0.08), rgba(240,192,64,0.05));
    border: 1.5px solid rgba(212,160,23,0.35);
    border-radius: 14px; padding: 16px 20px; margin-top: 16px;
    display: flex; gap: 12px; align-items: flex-start;
    font-size: 13px; line-height: 1.8; color: var(--gray-dark);
  }
  .callout-box i { color: var(--gold); font-size: 18px; margin-top: 2px; flex-shrink: 0; }

  /* ====== GENERAL TERMS (blue card) ====== */
  .terms-card {
    background: linear-gradient(135deg, #0A3B6B, var(--teal));
    border-radius: 20px; padding: 30px; margin-bottom: 28px;
    position: relative; overflow: hidden;
  }
  .terms-card::before {
    content: '\f519'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
    position: absolute; top: -20px; left: -10px;
    font-size: 140px; color: rgba(255,255,255,0.04); line-height: 1;
  }
  .terms-header { display: flex; align-items: center; gap: 16px; margin-bottom: 22px; }
  .terms-icon-wrap {
    width: 64px; height: 64px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 24px; color: var(--blue-dark);
    box-shadow: 0 4px 20px rgba(212,160,23,0.5);
  }
  .terms-header h2 { color: #fff; font-size: 18px; font-weight: 900; margin-bottom: 4px; position: relative; z-index: 2; }
  .terms-header p { color: rgba(255,255,255,0.65); font-size: 13px; position: relative; z-index: 2; }
  .gen-clauses { position: relative; z-index: 2; }
  .gen-clause { display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1); }
  .gen-clause:last-child { border-bottom: none; }
  .gen-num {
    width: 26px; height: 26px; flex-shrink: 0; margin-top: 1px;
    background: rgba(255,255,255,0.15); color: #fff; border-radius: 50%;
    font-size: 12px; font-weight: 900; display: flex; align-items: center; justify-content: center;
  }
  .gen-text { font-size: 13px; line-height: 1.85; color: rgba(255,255,255,0.88); }
  .gen-text strong { color: #fff; }

  /* ====== DELIVERY TIMELINE ====== */
  .timeline-wrap { position: relative; padding-right: 34px; }
  .timeline-wrap::before {
    content: ''; position: absolute; right: 13px; top: 8px; bottom: 8px; width: 2px;
    background: linear-gradient(180deg, var(--teal), var(--gold));
  }
  .tl-item { position: relative; margin-bottom: 22px; padding-right: 24px; }
  .tl-item:last-child { margin-bottom: 0; }
  .tl-item::before {
    content: ''; position: absolute; right: -21px; top: 6px;
    width: 14px; height: 14px; border-radius: 50%;
    background: var(--teal); border: 3px solid #fff; box-shadow: 0 0 0 2px var(--teal);
  }
  .tl-item:last-child::before { background: var(--gold); box-shadow: 0 0 0 2px var(--gold); }
  .tl-date { font-size: 11.5px; font-weight: 700; color: var(--teal); margin-bottom: 4px; }
  .tl-item:last-child .tl-date { color: #B8860B; }
  .tl-title { font-size: 15px; font-weight: 800; color: var(--blue-dark); margin-bottom: 4px; }
  .tl-desc { font-size: 13px; color: var(--gray-mid); line-height: 1.75; }

  /* ====== SHIPPING INFO ====== */
  .shipping-row { display: flex; gap: 14px; flex-wrap: wrap; }
  .ship-badge {
    background: var(--water-bg);
    border: 1px solid rgba(14,116,144,0.22);
    border-radius: 12px; padding: 12px 18px;
    display: flex; align-items: center; gap: 10px;
    font-size: 13px; color: var(--blue-dark); font-weight: 600; flex: 1; min-width: 180px;
  }
  .ship-badge i { color: var(--teal); font-size: 16px; flex-shrink: 0; }

  /* ====== SIGNATURES ====== */
  .sig-title {
    font-family: 'Amiri', serif; font-size: 18px; font-weight: 700; text-align: center;
    color: var(--blue-dark); margin-bottom: 24px;
  }
  .sig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  .sig-box {
    background: #fff; border-radius: 16px; padding: 26px 20px; text-align: center;
    box-shadow: 0 2px 20px rgba(11,37,69,0.07); border: 1px solid rgba(11,37,69,0.05);
  }
  .sb-label { font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--teal); margin-bottom: 6px; }
  .sb-name { font-size: 15px; font-weight: 800; color: var(--blue-dark); margin-bottom: 18px; }
  .sig-stamp { width: 150px; height: 150px; margin: 0 auto 8px; }
  .sig-stamp img { width: 100%; height: 100%; object-fit: contain; }
  .client-sig-space { width: 200px; height: 90px; margin: 0 auto 8px; border-bottom: 2px solid var(--teal); }
  .sig-date { font-size: 12px; color: var(--gray-mid); margin-top: 10px; }

  /* Image divider */
  .img-divider {
    width: 100%; height: 200px; position: relative; overflow: hidden; margin: 0;
  }
  .img-divider img {
    width: 100%; height: 100%; object-fit: cover;
    filter: brightness(0.45) saturate(1.3);
  }
  .img-divider-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(90deg, rgba(11,37,69,0.85) 0%, rgba(14,116,144,0.55) 50%, rgba(11,37,69,0.85) 100%);
    display: flex; align-items: center; justify-content: center;
  }
  .img-divider-text {
    font-family: 'Amiri', serif; font-size: 20px; font-weight: 700;
    color: #fff; text-align: center; line-height: 1.6;
  }
  .img-divider-text span {
    display: block; font-family: 'Cairo', sans-serif;
    font-size: 13px; color: var(--gold-light); font-weight: 400; margin-top: 6px;
  }

  /* ====== FOOTER ====== */
  .footer {
    background: linear-gradient(135deg, #051630, var(--blue-dark), #0A3B5A);
    padding: 0 0 20px; color: rgba(255,255,255,0.8);
  }
  .footer-office-bar { text-align: center; padding: 22px 20px 0; font-size: 12.5px; line-height: 2; }
  .footer-branches {
    display: flex; justify-content: center; gap: 32px; flex-wrap: wrap;
    margin-top: 14px; padding: 0 20px;
  }
  .branch-item {
    display: flex; align-items: flex-start; gap: 8px; font-size: 12px;
    color: rgba(255,255,255,0.65); text-align: right;
  }
  .branch-item i { color: var(--teal-light); margin-top: 3px; flex-shrink: 0; }
  .branch-item strong { color: rgba(255,255,255,0.85); display: block; }
  .contract-ref-bar {
    max-width: 900px; margin: 18px auto 0; padding: 13px 24px;
    background: rgba(255,255,255,0.06); border-radius: 12px;
    display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;
  }
  .contract-ref-bar span { font-size: 12px; color: rgba(255,255,255,0.55); }
  .contract-ref-bar .cf-num { color: var(--gold-light); font-weight: 700; font-size: 12.5px; }

  /* ====== PRINT STYLES ====== */
  @media print {
    body { background: #fff; }
    .no-print { display: none !important; }
    .card, .dlv-card, .clauses-col, .sig-box { box-shadow: none !important; }
    .hero { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .price-display, .terms-card, .pool-dim-box { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .footer { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    @page { margin: 1cm; size: A4; }
  }

  /* ====== RESPONSIVE ====== */
  @media (max-width: 660px) {
    .hero-top-bar { padding: 12px 18px; }
    .hero-content { padding: 30px 18px 70px; }
    .parties-grid, .specs-grid, .delivery-grid, .clauses-cols, .sig-grid { grid-template-columns: 1fr; }
    .gallery-strip { grid-template-columns: 1fr 1fr; }
    .section { padding: 36px 14px; }
    .pool-dim-box { flex-direction: column; text-align: center; gap: 12px; }
    .dim-sep { display: none; }
    .hero-stats { gap: 22px; }
    .shipping-row { flex-direction: column; }
    .footer-branches { flex-direction: column; gap: 14px; align-items: center; }
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

<!-- ===== PRINT BUTTON ===== -->
<div class="no-print">
  <button class="btn-pdf" onclick="window.print()">
    <i class="fas fa-file-pdf"></i> طباعة / تحميل PDF
  </button>
</div>

<!-- ===== HERO ===== -->
<section class="hero">
  <div class="water-circle wc1"></div>
  <div class="water-circle wc2"></div>
  <div class="hero-img-strip">
    <img src="https://www.rukn-eltatawer.com/s2.jpeg" alt="">
  </div>

  <div class="hero-top-bar">
    <div class="logo-area">
      <div class="logo-icon"><i class="fas fa-water"></i></div>
      <div class="logo-text">
        <h1>ركن التطور</h1>
        <span>RUKN ELTATAWER · أحواض السباحة</span>
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
      عقد توريد وتوصيل رسمي · موقّع ومعتمد
    </div>

    <h1 class="hero-title">
      عقد توريد وتوصيل<br>
      <span class="highlight">غطاء مسبح فقاعات شمسي</span>
      <br>+ <span class="teal-hl">بكرة ستانلس ستيل</span>
    </h1>

    <div class="quote-meta">
      <div class="quote-meta-item"><i class="fas fa-hashtag"></i> <?= e($doc['document_number']) ?></div>
      <div class="quote-meta-item"><i class="fas fa-calendar"></i> تاريخ التحرير: 21 يوليو 2026</div>
      <div class="quote-meta-item"><i class="fas fa-map-marker-alt"></i> التوصيل إلى: مدينة العين</div>
    </div>

    <div class="hero-stats">
      <div class="stat-item">
        <div class="stat-num">56.4<span class="stat-unit" style="font-size:16px;"> م²</span></div>
        <div class="stat-label">مساحة الغطاء الإجمالية</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">400<span class="stat-unit" style="font-size:16px;"> μm</span></div>
        <div class="stat-label">سماكة الغطاء الفقاعي</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">95<span class="stat-unit" style="font-size:18px;">%</span></div>
        <div class="stat-label">تقليل التبخر</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">48<span class="stat-unit" style="font-size:14px;"> ساعة</span></div>
        <div class="stat-label">أقصى وقت للتوصيل</div>
      </div>
    </div>
  </div>

  <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
    <path fill="#EFF6FF" d="M0,35 C240,70 480,0 720,35 C960,70 1200,0 1440,35 L1440,70 L0,70 Z"/>
  </svg>
</section>

<!-- ===== MAIN CONTENT ===== -->
<section class="section">
  <div class="container">

    <!-- مقدمة العقد -->
    <div class="preamble">
      تم إبرام هذا العقد في يوم <strong>الاثنين الموافق 21 يوليو 2026</strong> بين الطرفين الموضح أدناه، وذلك على توريد وتوصيل <strong>غطاء فقاعات شمسي للمسبح مع بكرة (رولر) ستانلس ستيل</strong> وفق المواصفات التقنية والشروط المنصوص عليها في هذا العقد، والتزم كلا الطرفين بجميع بنوده كاملةً.
    </div>

    <!-- 1: أطراف العقد -->
    <div class="c-section">
      <div class="c-section-title"><div class="c-s-num">1</div> أطراف العقد</div>
      <div class="card" style="padding:20px;">
        <div class="parties-grid">
          <div class="party-box">
            <div class="party-head"><i class="fas fa-building"></i> الطرف الأول — المورّد</div>
            <div class="party-body">
              <div class="party-row"><span class="pr-label">الاسم:</span><span class="pr-val">شركة ركن التطور لأحواض السباحة</span></div>
              <div class="party-row"><span class="pr-label">الفرع الرئيسي:</span><span class="pr-val">مكتب 306، أبراج مزيد مول، أبوظبي</span></div>
              <div class="party-row"><span class="pr-label">الفرع الثاني:</span><span class="pr-val">25 شارع الغلال، مقابل الجيمي مول، العين</span></div>
            </div>
          </div>
          <div class="party-box">
            <div class="party-head"><i class="fas fa-user-tie"></i> الطرف الثاني — العميل</div>
            <div class="party-body">
              <div class="party-row"><span class="pr-label">الاسم:</span><span class="pr-val">أفنان حسن</span></div>
              <div class="party-row"><span class="pr-label">عنوان التوصيل:</span><span class="pr-val">العين، المويجعي، مجلود، شارع العازم، بناية 146</span></div>
              <div class="party-row"><span class="pr-label">نوع الطلب:</span><span class="pr-val">توصيل فقط (بدون تركيب)</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 2: مواصفات المنتج -->
    <div class="c-section">
      <div class="c-section-title"><div class="c-s-num">2</div> مواصفات المنتج والمقاسات التفصيلية</div>
      <div class="card" style="padding:20px;">

        <!-- Product hero image -->
        <div class="product-hero">
          <img class="product-hero-img" src="https://www.rukn-eltatawer.com/s1.jpeg" alt="غطاء مسبح فقاعات مع بكرة ستانلس">
          <div class="product-hero-overlay">
            <div class="product-hero-title">
              غطاء فقاعات شمسي + بكرة ستانلس ستيل
              <span>Solar Bubble Pool Cover — 400 Micron · Stainless Steel Roller with Wheels & Handle</span>
            </div>
          </div>
        </div>

        <!-- Pool dimensions -->
        <div class="pool-dim-box">
          <div class="dim-group">
            <div class="dim-num">4.70</div>
            <div class="dim-unit">متر</div>
            <div class="dim-label">العرض</div>
          </div>
          <div class="dim-sep">×</div>
          <div class="dim-group">
            <div class="dim-num">12</div>
            <div class="dim-unit">متر</div>
            <div class="dim-label">الطول</div>
          </div>
          <div class="dim-sep">=</div>
          <div class="dim-total">
            <div class="dim-num">56.4</div>
            <div class="dim-unit">م²</div>
            <div class="dim-label">المساحة الإجمالية للغطاء</div>
          </div>
        </div>

        <!-- Specs grid -->
        <div class="specs-grid">
          <div class="spec-card">
            <div class="spec-icon"><i class="fas fa-layer-group"></i></div>
            <div class="spec-info">
              <div class="spec-label">نوع الغطاء</div>
              <div class="spec-val">غطاء فقاعات شمسي</div>
              <div class="spec-sub">Solar Bubble Cover</div>
            </div>
          </div>
          <div class="spec-card">
            <div class="spec-icon"><i class="fas fa-ruler"></i></div>
            <div class="spec-info">
              <div class="spec-label">السماكة</div>
              <div class="spec-val">400 ميكرون</div>
              <div class="spec-sub">400 Micron Thickness</div>
            </div>
          </div>
          <div class="spec-card">
            <div class="spec-icon"><i class="fas fa-fire-flame-curved"></i></div>
            <div class="spec-info">
              <div class="spec-label">الخاصية الحرارية</div>
              <div class="spec-val">الحفاظ على الحرارة</div>
              <div class="spec-sub">تقليل التبخر بنسبة 95%</div>
            </div>
          </div>
          <div class="spec-card">
            <div class="spec-icon"><i class="fas fa-cut"></i></div>
            <div class="spec-info">
              <div class="spec-label">تقنية القص</div>
              <div class="spec-val">قص دقيق بالليزر</div>
              <div class="spec-sub">أطراف محكمة ومضبوطة</div>
            </div>
          </div>
          <div class="spec-card">
            <div class="spec-icon"><i class="fas fa-circle-notch"></i></div>
            <div class="spec-info">
              <div class="spec-label">البكرة (الرولر)</div>
              <div class="spec-val">ستانلس ستيل مقاوم للصدأ</div>
              <div class="spec-sub">Stainless Steel Roller</div>
            </div>
          </div>
          <div class="spec-card">
            <div class="spec-icon"><i class="fas fa-gears"></i></div>
            <div class="spec-info">
              <div class="spec-label">مكونات البكرة</div>
              <div class="spec-val">عجلات + مقبض تدوير</div>
              <div class="spec-sub">Wheels & Turning Handle</div>
            </div>
          </div>
        </div>

        <!-- Product gallery -->
        <div class="gallery-strip">
          <img src="https://www.rukn-eltatawer.com/s2.jpeg" alt="بكرة المسبح">
          <img src="https://www.rukn-eltatawer.com/s5.jpeg" alt="مقبض البكرة">
          <img src="https://www.rukn-eltatawer.com/s7.webp" alt="ربط الغطاء">
          <img src="https://www.rukn-eltatawer.com/s3.webp" alt="الغطاء على المسبح">
        </div>
      </div>
    </div>

    <!-- 3: شروط التوصيل والدفع -->
    <div class="c-section">
      <div class="c-section-title"><div class="c-s-num">3</div> شروط التوصيل والدفع</div>

      <div class="delivery-grid">
        <!-- Delivery details -->
        <div class="dlv-card">
          <div class="dlv-head"><i class="fas fa-truck"></i> تفاصيل التوصيل والشحن</div>
          <div class="dlv-body">
            <div class="dlv-row"><span class="dlv-icon"><i class="fas fa-clock"></i></span><div class="dlv-text"><strong>موعد التوصيل:</strong> خلال 24 إلى 48 ساعة من تاريخ توقيع العقد</div></div>
            <div class="dlv-row"><span class="dlv-icon"><i class="fas fa-map-pin"></i></span><div class="dlv-text"><strong>وجهة التسليم:</strong> العين، المويجعي، مجلود، شارع العازم، بناية 146</div></div>
            <div class="dlv-row"><span class="dlv-icon"><i class="fas fa-box"></i></span><div class="dlv-text"><strong>طريقة التغليف:</strong> كرتون واحد — البكرة مفككة والغطاء مضغوط (الأطراف المقصوصة بالليزر مؤمّنة)</div></div>
            <div class="dlv-row"><span class="dlv-icon"><i class="fas fa-weight-hanging"></i></span><div class="dlv-text"><strong>الوزن الإجمالي للطرد:</strong> من 35 إلى 37 كيلوجرام</div></div>
            <div class="dlv-row"><span class="dlv-icon"><i class="fas fa-truck-fast"></i></span><div class="dlv-text"><strong>رسوم التوصيل:</strong> شاملة ومدرجة في السعر النهائي</div></div>
          </div>
        </div>

        <!-- Shipping info -->
        <div class="dlv-card">
          <div class="dlv-head"><i class="fas fa-calendar-check"></i> الجدول الزمني للتسليم</div>
          <div class="dlv-body">
            <div class="timeline-wrap">
              <div class="tl-item">
                <div class="tl-date">الاثنين — 21 يوليو 2026</div>
                <div class="tl-title">توقيع العقد وسداد قيمته</div>
                <div class="tl-desc">اعتماد العقد من الطرفين وبدء تحضير الطلب وتجهيزه للشحن</div>
              </div>
              <div class="tl-item">
                <div class="tl-date">الثلاثاء - الأربعاء (22-23 يوليو 2026)</div>
                <div class="tl-title">التوصيل والتسليم للعميل</div>
                <div class="tl-desc">توصيل الطرد (البكرة مفككة + الغطاء مضغوط) إلى عنوان العميل في العين خلال 24 إلى 48 ساعة من توقيع العقد</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Price display -->
      <div class="price-display">
        <div class="price-label">القيمة الإجمالية للعقد (السعر النهائي)</div>
        <div class="price-big">2,450</div>
        <div class="price-currency">درهم إماراتي</div>
        <div class="price-note">
          السعر النهائي بعد الخصم · شامل رسوم التوصيل إلى مدينة العين<br>
          يشمل: غطاء فقاعات شمسي 400 ميكرون (56.4 م²) + بكرة ستانلس ستيل كاملة
        </div>
        <div class="price-badges">
          <div class="price-badge"><i class="fas fa-check-circle"></i> سعر نهائي شامل التوصيل</div>
          <div class="price-badge"><i class="fas fa-tag"></i> سعر بعد الخصم</div>
          <div class="price-badge"><i class="fas fa-shield-halved"></i> لا رسوم إضافية</div>
        </div>
      </div>

      <!-- Shipping badges row -->
      <div class="shipping-row" style="margin-top:16px;">
        <div class="ship-badge"><i class="fas fa-weight-hanging"></i><span>الوزن: <strong>35 – 37 كجم</strong></span></div>
        <div class="ship-badge"><i class="fas fa-box-open"></i><span>التغليف: <strong>كرتون واحد</strong></span></div>
        <div class="ship-badge"><i class="fas fa-ruler-combined"></i><span>المساحة: <strong>4.70 × 12 متر</strong></span></div>
        <div class="ship-badge"><i class="fas fa-truck-fast"></i><span>التوصيل: <strong>24 – 48 ساعة</strong></span></div>
      </div>
    </div>

  </div>
</section>

<!-- Image divider strip -->
<div class="img-divider">
  <img src="https://www.rukn-eltatawer.com/s4.jpg" alt="بكرة مسبح">
  <div class="img-divider-overlay">
    <div class="img-divider-text">
      أنظمة أغطية المسابح الاحترافية
      <span>غطاء فقاعات شمسي · بكرة ستانلس ستيل · قص بالليزر · توصيل معتمد داخل الإمارات</span>
    </div>
  </div>
</div>

<section class="section" style="padding-top:50px;">
  <div class="container">

    <!-- 4: التزامات الطرفين -->
    <div class="c-section">
      <div class="c-section-title"><div class="c-s-num">4</div> التزامات الطرفين</div>

      <div class="clauses-cols">
        <div class="clauses-col">
          <div class="clauses-col-title"><i class="fas fa-building" style="color:var(--gold);"></i> الطرف الأول (المورّد) يلتزم بـ:</div>
          <div class="clause-item"><div class="clause-num">1</div><div class="clause-text">توريد المنتجات وفق المواصفات التقنية المحددة في هذا العقد بالكامل</div></div>
          <div class="clause-item"><div class="clause-num">2</div><div class="clause-text">قص الغطاء بدقة على مقاسات المسبح (4.70م × 12م) بتقنية الليزر وتأمين الأطراف</div></div>
          <div class="clause-item"><div class="clause-num">3</div><div class="clause-text">التوصيل إلى عنوان العميل خلال المدة المتفق عليها (24 – 48 ساعة)</div></div>
          <div class="clause-item"><div class="clause-num">4</div><div class="clause-text">ضمان وصول المنتج سليماً ومعبأً بطريقة تحافظ على جودته خلال الشحن</div></div>
          <div class="clause-item"><div class="clause-num">5</div><div class="clause-text">إصدار إيصال رسمي معتمد فور استلام قيمة العقد</div></div>
        </div>
        <div class="clauses-col">
          <div class="clauses-col-title"><i class="fas fa-user-tie" style="color:var(--gold);"></i> الطرف الثاني (العميل) يلتزم بـ:</div>
          <div class="clause-item"><div class="clause-num">1</div><div class="clause-text">سداد القيمة الإجمالية للعقد (2,450 درهم) عند التوقيع أو قبل التوصيل</div></div>
          <div class="clause-item"><div class="clause-num">2</div><div class="clause-text">التحقق من صحة عنوان التوصيل وضمان وجود مستلم في العنوان المحدد</div></div>
          <div class="clause-item"><div class="clause-num">3</div><div class="clause-text">فحص المنتج فور الاستلام والإبلاغ فوراً عن أي ملاحظة قبل مغادرة المندوب</div></div>
          <div class="clause-item"><div class="clause-num">4</div><div class="clause-text">التوقيع على وصل الاستلام لإتمام عملية التسليم رسمياً</div></div>
          <div class="clause-item"><div class="clause-num">5</div><div class="clause-text">تثبيت الغطاء والبكرة بنفسه أو بمساعدة فني خاص به (التوصيل فقط بدون تركيب)</div></div>
        </div>
      </div>

      <!-- No installation callout -->
      <div class="callout-box">
        <i class="fas fa-triangle-exclamation"></i>
        <div>
          <strong>تنبيه هام — التوصيل فقط بدون تركيب:</strong><br>
          طلب العميل صراحةً خدمة التوصيل فقط دون تركيب. يلتزم الطرف الأول بتوصيل المنتج إلى العنوان المحدد وتسليمه للعميل أو من ينوب عنه، وتقع مسؤولية التركيب والتثبيت على عاتق الطرف الثاني بالكامل. في حال رغب العميل لاحقاً في خدمة التركيب، تُحدَّد تكلفتها باتفاق منفصل.
        </div>
      </div>
    </div>

    <!-- 5: الأحكام العامة -->
    <div class="c-section">
      <div class="c-section-title"><div class="c-s-num">5</div> الأحكام العامة وشروط العقد</div>
    </div>
    <div class="terms-card">
      <div class="terms-header">
        <div class="terms-icon-wrap"><i class="fas fa-scale-balanced"></i></div>
        <div>
          <h2>الأحكام والشروط العامة للعقد</h2>
          <p>التزامنا القانوني والمهني تجاه عملائنا</p>
        </div>
      </div>
      <div class="gen-clauses">
        <div class="gen-clause"><div class="gen-num">1</div><div class="gen-text"><strong>ضمان المنتج:</strong> يضمن الطرف الأول جودة المنتجات المورّدة وتطابقها مع المواصفات المذكورة في هذا العقد. أي خلل تصنيعي يُكتشف عند الاستلام يعالجه الطرف الأول على نفقته الخاصة.</div></div>
        <div class="gen-clause"><div class="gen-num">2</div><div class="gen-text"><strong>حالات التأخير:</strong> في حال تأخر التوصيل لأسباب خارجة عن سيطرة الطرف الأول (ظروف جوية، قوة قاهرة، إغلاق طرق)، يُخطر العميل فوراً ويُحدد موعد بديل دون أي غرامات.</div></div>
        <div class="gen-clause"><div class="gen-num">3</div><div class="gen-text"><strong>إلغاء العقد:</strong> يحق للعميل إلغاء الطلب قبل تجهيز المنتج أو قصّه، ويُردّ له المبلغ كاملاً. أما بعد القص والتجهيز، فلا يحق استرداد المبلغ لأن الغطاء يُقصّ على مقاسات خاصة بالمسبح.</div></div>
        <div class="gen-clause"><div class="gen-num">4</div><div class="gen-text"><strong>فضّ النزاعات:</strong> تُحل أي خلافات تنشأ عن هذا العقد بالتراضي والتفاهم المباشر أولاً، وعند تعذّر ذلك يُرجع إلى الجهات القانونية المختصة في إمارة أبوظبي — دولة الإمارات العربية المتحدة.</div></div>
        <div class="gen-clause"><div class="gen-num">5</div><div class="gen-text"><strong>نسخ العقد:</strong> حُرّر هذا العقد من نسختين أصليتين، بيد كل طرف نسخة تحمل نفس الحجية القانونية، ويُعتبر كلاهما وثيقة رسمية ملزمة للطرفين.</div></div>
        <div class="gen-clause"><div class="gen-num">6</div><div class="gen-text"><strong>المنتجات المورّدة:</strong> جميع المنتجات أصلية وتلتزم بمعايير الجودة المعتمدة. الغطاء الفقاعي الشمسي 400 ميكرون مقاوم للأشعة فوق البنفسجية (UV-Resistant) ومخصص للاستخدام الخارجي في المناخ الخليجي.</div></div>
      </div>
    </div>

    <!-- 6: التوقيعات -->
    <div class="c-section">
      <div class="sig-title">توقيعات الطرفين — إقرار رسمي واعتماد</div>
      <div class="sig-grid">
        <div class="sig-box">
          <div class="sb-label">الطرف الأول — المورّد</div>
          <div class="sb-name">شركة ركن التطور لأحواض السباحة</div>
          <div class="sig-stamp">
            <img src="<?= e($seal) ?>" alt="ختم وتوقيع ركن التطور">
          </div>
          <div class="sig-date">21 يوليو 2026</div>
        </div>
        <div class="sig-box">
          <div class="sb-label">الطرف الثاني — العميل</div>
          <div class="sb-name">أفنان حسن</div>
          <div class="client-sig-space"></div>
          <div class="sig-date">التوقيع وتاريخه: _______________</div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer>
  <!-- Google Maps embed -->
  <div style="width:100%;line-height:0;overflow:hidden;">
    <iframe
      width="100%"
      height="220"
      style="border:0;filter:brightness(0.80) contrast(1.05) saturate(0.85);display:block;"
      loading="lazy"
      allowfullscreen
      src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=24.372480,54.538188&zoom=16&maptype=roadmap&language=ar">
    </iframe>
  </div>

  <!-- Office info bar -->
  <div style="background:linear-gradient(135deg,#051630,var(--blue-dark),#0A3B5A);padding:18px 36px;display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;border-top:3px solid var(--gold);">

    <!-- مكتب أبوظبي -->
    <div style="display:flex;align-items:flex-start;gap:10px;flex:1;min-width:180px;">
      <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--gold),var(--gold-light));display:flex;align-items:center;justify-content:center;font-size:15px;color:var(--blue-dark);flex-shrink:0;margin-top:2px;">
        <i class="fas fa-building"></i>
      </div>
      <div>
        <div style="font-size:9px;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.4);margin-bottom:4px;font-weight:700;">مكتب أبوظبي</div>
        <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,.8);line-height:1.7;">مكتب 306، أبراج مزيد مول<br>مدينة محمد بن زايد، أبوظبي</div>
      </div>
    </div>

    <!-- وسط: الشركة والمرجع -->
    <div style="flex:1;min-width:220px;text-align:center;">
      <div style="font-size:12px;font-weight:800;color:#fff;margin-bottom:4px;">شركة ركن التطور لأحواض السباحة</div>
      <div style="font-size:9px;color:rgba(255,255,255,.35);margin-bottom:6px;">Rukn El-Tatawer for Swimming Pools · أبوظبي، الإمارات</div>
      <div style="display:inline-block;background:linear-gradient(90deg,var(--gold-light),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-weight:700;font-size:11px;">RET-POOL-AIN-2026-0721 · 21 يوليو 2026</div>
      <div style="margin-top:6px;">
        <a href="https://maps.google.com/?q=24.372480,54.538188" target="_blank"
           style="display:inline-flex;align-items:center;gap:5px;color:rgba(255,255,255,.4);font-size:10px;text-decoration:none;">
          <i class="fas fa-external-link-alt" style="font-size:9px;"></i> فتح الخريطة
        </a>
      </div>
    </div>


  </div>

  <!-- ref bar -->
  <div style="background:#040E1A;padding:10px 36px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:6px;">
    <span style="font-size:11px;color:var(--gold-light);font-weight:700;">RET-POOL-AIN-2026-0721</span>
    <span style="font-size:11px;color:rgba(255,255,255,.35);">أفنان حسن — العين، المويجعي، بناية 146</span>
    <span style="font-size:11px;color:rgba(255,255,255,.35);">21 يوليو 2026</span>
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
