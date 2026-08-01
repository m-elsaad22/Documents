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
    --danger: #C0392B;
    --danger-light: #E74C3C;
    --amber: #D4700A;
    --amber-light: #F39C12;
    --green: #0A5C2A;
    --green-light: #27AE60;
    --text-body: #2D3748;
    --border: rgba(11,37,69,0.08);
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Cairo', 'Tajawal', sans-serif;
    background: #F0F4F8;
    color: var(--text-body);
    overflow-x: hidden;
  }

  /* ===== PRINT BUTTON ===== */
  .no-print {
    max-width: 960px;
    margin: 0 auto 18px;
    padding: 18px 20px 0;
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
    background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M0 0h40v40H0V0zm40 40h40v40H40V40z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }

  .hero-orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
  }
  .orb1 { width: 320px; height: 320px; top: -100px; right: -80px; background: radial-gradient(circle, rgba(255,200,0,0.12) 0%, transparent 65%); }
  .orb2 { width: 220px; height: 220px; bottom: -60px; left: 60px; background: radial-gradient(circle, rgba(26,109,175,0.18) 0%, transparent 65%); }

  .hero-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 40px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    position: relative;
    z-index: 10;
    gap: 16px;
    flex-wrap: wrap;
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
    flex-shrink: 0;
  }

  .logo-text h1 {
    font-size: 15px;
    font-weight: 900;
    color: #fff;
    line-height: 1.25;
  }

  .logo-text span {
    font-size: 10px;
    color: var(--gold-light);
    font-weight: 400;
    letter-spacing: 0.5px;
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
    padding: 50px 40px 90px;
    position: relative;
    z-index: 10;
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
  }

  .report-label {
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
    margin-bottom: 24px;
    animation: fadeInDown 0.6s ease both;
  }

  .hero-title {
    font-size: clamp(24px, 4.5vw, 42px);
    font-weight: 900;
    color: #fff;
    line-height: 1.4;
    margin-bottom: 16px;
    animation: fadeInUp 0.7s ease 0.1s both;
  }

  .hero-title .highlight {
    background: linear-gradient(90deg, var(--gold), var(--gold-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .hero-subtitle {
    font-size: 15px;
    color: rgba(255,255,255,0.75);
    line-height: 1.8;
    max-width: 620px;
    margin: 0 auto 30px;
    animation: fadeInUp 0.7s ease 0.2s both;
  }

  .report-meta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 30px;
    animation: fadeInUp 0.7s ease 0.3s both;
  }

  .meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: rgba(255,255,255,0.7);
    background: rgba(255,255,255,0.08);
    padding: 7px 16px;
    border-radius: 50px;
    border: 1px solid rgba(255,255,255,0.12);
  }

  .meta-item i { color: var(--gold-light); font-size: 12px; }

  .hero-stats {
    display: flex;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap;
    animation: fadeInUp 0.7s ease 0.4s both;
  }

  .stat-item { text-align: center; }

  .stat-num {
    font-size: 30px;
    font-weight: 900;
    color: var(--gold-light);
    line-height: 1;
  }

  .stat-label {
    font-size: 12px;
    color: rgba(255,255,255,0.6);
    margin-top: 4px;
  }

  .hero-waves {
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100%;
  }

  @keyframes fadeInDown {
    from { opacity:0; transform:translateY(-20px); }
    to { opacity:1; transform:translateY(0); }
  }
  @keyframes fadeInUp {
    from { opacity:0; transform:translateY(20px); }
    to { opacity:1; transform:translateY(0); }
  }

  /* ===== URGENCY BAR ===== */
  .urgency-bar {
    display: inline-flex; align-items: center; justify-content: center; gap: 12px;
    background: linear-gradient(135deg, rgba(192,57,43,.28), rgba(139,26,26,.22));
    border: 1px solid rgba(192,57,43,.45); border-radius: 50px; padding: 9px 24px;
    font-size: 13px; font-weight: 700; color: #F1948A; letter-spacing: .3px;
    margin-bottom: 26px;
    animation: fadeInUp 0.7s ease 0.25s both;
  }
  .urgency-bar::before, .urgency-bar::after {
    content: ''; width: 8px; height: 8px; border-radius: 50%; background: #E74C3C;
    animation: pls 1.4s ease infinite; flex-shrink: 0;
  }
  @keyframes pls { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:.3;transform:scale(1.5);} }

  /* ===== STRIPE ===== */
  .stripe {
    height: 4px;
    background: linear-gradient(90deg, var(--blue-dark) 0%, var(--blue-accent) 30%, var(--blue-light) 55%, var(--gold) 75%, var(--blue-mid) 100%);
  }

  /* ===== SECTIONS ===== */
  .main-section { padding: 60px 20px; }
  .container { max-width: 960px; margin: 0 auto; }

  /* ===== CARDS (shared) ===== */
  .base-card {
    background: #fff;
    border-radius: 20px;
    padding: 32px;
    box-shadow: 0 4px 30px rgba(11,37,69,0.08);
    border: 1px solid rgba(11,37,69,0.06);
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
  }

  .base-card.fade-in {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }
  .base-card.fade-in.visible {
    opacity: 1;
    transform: translateY(0);
  }

  .base-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 5px;
    height: 100%;
    background: linear-gradient(180deg, var(--blue-accent), var(--blue-light));
    border-radius: 20px 0 0 20px;
  }

  .base-card.accent-gold::before { background: linear-gradient(180deg, var(--gold), var(--gold-light)); }
  .base-card.accent-danger::before { background: linear-gradient(180deg, var(--danger), var(--danger-light)); }
  .base-card.accent-green::before { background: linear-gradient(180deg, var(--green), var(--green-light)); }
  .base-card.accent-amber::before { background: linear-gradient(180deg, var(--amber), var(--amber-light)); }

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

  .card-icon.gold { background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: var(--blue-dark); }
  .card-icon.danger { background: linear-gradient(135deg, var(--danger), var(--danger-light)); }
  .card-icon.green { background: linear-gradient(135deg, var(--green), var(--green-light)); }
  .card-icon.amber { background: linear-gradient(135deg, var(--amber), var(--amber-light)); }

  .card-header h2 {
    font-size: 20px;
    font-weight: 800;
    color: var(--blue-dark);
  }

  .card-header p { font-size: 13px; color: var(--gray-mid); margin-top: 2px; }

  .sec-num-badge {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--blue-accent), var(--blue-light));
    color: #fff;
    font-size: 12px;
    font-weight: 900;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-left: auto;
  }

  /* ===== INFO GRID ===== */
  .info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 14px;
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

  /* ===== DARK PRODUCT CARD ===== */
  .dark-card {
    background: linear-gradient(135deg, var(--blue-dark) 0%, #0F3060 100%);
    border-radius: 20px;
    padding: 32px;
    margin-bottom: 28px;
    position: relative;
    overflow: hidden;
    color: white;
  }

  .dark-card::after {
    content: '';
    position: absolute;
    bottom: -40px;
    right: -40px;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,0.03);
    pointer-events: none;
  }

  .dark-card .dark-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 26px;
    position: relative;
    z-index: 2;
  }

  .dark-card .dark-icon {
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
    box-shadow: 0 4px 16px rgba(212,160,23,0.4);
  }

  .dark-card .dark-title {
    font-size: 21px;
    font-weight: 900;
    color: #fff;
    line-height: 1.2;
  }

  .dark-card .dark-sub {
    font-size: 13px;
    color: rgba(255,255,255,0.65);
    margin-top: 4px;
  }

  /* ===== INSPECTION CATEGORIES GRID ===== */
  .inspect-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 26px;
    position: relative;
    z-index: 2;
  }

  .inspect-box {
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 14px;
    padding: 18px 14px;
    text-align: center;
    transition: all 0.3s ease;
  }

  .inspect-box:hover {
    background: rgba(255,255,255,0.13);
    transform: translateY(-3px);
  }

  .inspect-box i {
    font-size: 26px;
    margin-bottom: 10px;
    display: block;
    color: var(--gold-light);
  }

  .inspect-box .ib-title {
    font-size: 13px;
    font-weight: 800;
    color: #fff;
    margin-bottom: 5px;
    line-height: 1.3;
  }

  .inspect-box .ib-sub {
    font-size: 11px;
    color: rgba(255,255,255,0.55);
    line-height: 1.4;
  }

  /* ===== PREAMBLE ===== */
  .preamble {
    background: linear-gradient(135deg, rgba(11,37,69,0.04), rgba(26,109,175,0.03));
    border: 1px solid rgba(11,37,69,0.08);
    border-left: 4px solid var(--blue-accent);
    border-radius: 0 12px 12px 0;
    padding: 20px 24px;
    font-size: 13.5px;
    line-height: 1.95;
    color: var(--text-body);
  }

  /* ===== FINDINGS LIST ===== */
  .section-label {
    font-size: 13px;
    font-weight: 700;
    color: var(--gray-mid);
    letter-spacing: 0.4px;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--gray-light);
  }

  .findings-list { list-style: none; }

  .fi {
    display: flex;
    gap: 14px;
    padding: 15px 18px;
    margin-bottom: 10px;
    border-radius: 12px;
    border: 1px solid rgba(11,37,69,0.07);
    background: linear-gradient(135deg, rgba(11,37,69,0.03), rgba(26,109,175,0.02));
    transition: all 0.2s;
  }

  .fi:hover { border-color: rgba(26,109,175,0.2); box-shadow: 0 3px 12px rgba(11,37,69,0.07); }
  .fi:last-child { margin-bottom: 0; }

  .fi-num {
    width: 30px;
    height: 30px;
    flex-shrink: 0;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--blue-dark), var(--blue-accent));
    color: #fff;
    font-size: 13px;
    font-weight: 900;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 2px;
  }

  .fi-title { font-size: 14px; font-weight: 800; color: var(--blue-dark); margin-bottom: 5px; }
  .fi-desc { font-size: 13px; color: var(--gray-dark); line-height: 1.7; }

  .fi-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-top: 8px;
    padding: 3px 12px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 700;
  }

  .tag-high { background: rgba(192,57,43,0.1); color: var(--danger-light); border: 1px solid rgba(192,57,43,0.2); }
  .tag-med  { background: rgba(212,112,10,0.1); color: var(--amber); border: 1px solid rgba(212,112,10,0.2); }
  .tag-ok   { background: rgba(39,174,96,0.1); color: var(--green-light); border: 1px solid rgba(39,174,96,0.2); }

  /* ===== CONDITION CARDS ===== */
  .cond-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
  }

  .cond-card { border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.07); border: 1px solid var(--border); }

  .cc-head {
    padding: 11px 16px;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 7px;
  }

  .cc-blue .cc-head   { background: linear-gradient(135deg, var(--blue-dark), var(--blue-accent)); color: #fff; }
  .cc-green .cc-head  { background: linear-gradient(135deg, var(--green), var(--green-light)); color: #fff; }
  .cc-amber .cc-head  { background: linear-gradient(135deg, var(--amber), var(--amber-light)); color: #fff; }
  .cc-danger .cc-head { background: linear-gradient(135deg, var(--danger), var(--danger-light)); color: #fff; }

  .cc-body { padding: 14px 16px; background: #fff; font-size: 13px; color: var(--text-body); line-height: 1.7; }

  /* ===== ROOT CAUSE ANALYSIS ===== */
  .rca-list { list-style: none; }
  .rca-item {
    display: flex;
    gap: 14px;
    padding: 16px 18px;
    margin-bottom: 10px;
    border-radius: 12px;
    background: var(--gray-light);
    border: 1px solid rgba(11,37,69,0.06);
    border-left: 4px solid var(--gold);
    transition: all 0.2s;
  }
  .rca-item:hover { box-shadow: 0 3px 14px rgba(11,37,69,0.08); transform: translateX(3px); }
  .rca-item:last-child { margin-bottom: 0; }
  .rca-num {
    width: 30px; height: 30px; flex-shrink: 0; border-radius: 8px;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: var(--blue-dark); font-size: 13px; font-weight: 900;
    display: flex; align-items: center; justify-content: center; margin-top: 2px;
  }
  .rca-title { font-size: 14px; font-weight: 800; color: var(--blue-dark); margin-bottom: 5px; }
  .rca-desc { font-size: 13px; color: var(--gray-dark); line-height: 1.7; }

  /* ===== HUMIDITY / SUB-CARD ===== */
  .hum-card {
    background: linear-gradient(135deg, rgba(212,112,10,0.05), rgba(243,156,18,0.03));
    border: 1px solid rgba(212,112,10,0.18);
    border-radius: 14px;
    padding: 20px 22px;
  }
  .hum-title {
    font-size: 15px; font-weight: 800; color: var(--amber);
    display: flex; align-items: center; gap: 9px; margin-bottom: 14px;
  }
  .hum-list { list-style: none; margin-bottom: 4px; }
  .hum-item {
    display: flex; gap: 10px; align-items: flex-start;
    font-size: 13px; line-height: 1.75; color: var(--text-body);
    padding: 6px 0; border-bottom: 1px dashed rgba(11,37,69,0.07);
  }
  .hum-item:last-child { border-bottom: none; }
  .hum-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--amber-light); flex-shrink: 0; margin-top: 8px; }

  .hum-steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
  .hs-box {
    background: #fff; border: 1px solid var(--border); border-radius: 12px;
    padding: 16px 14px; text-align: center;
    box-shadow: 0 2px 10px rgba(11,37,69,0.05);
  }
  .hs-num {
    width: 28px; height: 28px; border-radius: 50%; margin: 0 auto 8px;
    background: linear-gradient(135deg, var(--blue-dark), var(--blue-accent));
    color: #fff; font-size: 12px; font-weight: 900;
    display: flex; align-items: center; justify-content: center;
  }
  .hs-title { font-size: 12.5px; font-weight: 800; color: var(--blue-dark); margin-bottom: 5px; }
  .hs-desc { font-size: 11.5px; color: var(--gray-mid); line-height: 1.55; }

  /* ===== MANHOLE GRID ===== */
  .manhole-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin-bottom: 14px;
  }
  .mh-box { border-radius: 12px; overflow: hidden; border: 1px solid var(--border); box-shadow: 0 2px 10px rgba(11,37,69,0.05); }
  .mh-head {
    background: linear-gradient(135deg, var(--blue-dark), var(--blue-accent));
    color: #fff; font-size: 12px; font-weight: 800; text-align: center; padding: 9px 6px;
  }
  .mh-body { background: #fff; padding: 12px 10px; }
  .mh-status { display: flex; flex-direction: column; gap: 7px; }
  .ms-row { display: flex; align-items: center; gap: 7px; font-size: 11.5px; color: var(--gray-dark); }
  .ms-row i { font-size: 11px; width: 12px; flex-shrink: 0; }
  .ms-row i.bad { color: var(--danger-light); }
  .ms-row i.need { color: var(--amber-light); }

  /* ===== OVERALL RATING ===== */
  .rating-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 14px;
    margin-top: 20px;
  }

  .rating-item {
    background: var(--gray-light);
    border-radius: 14px;
    padding: 20px 14px;
    text-align: center;
    border: 2px solid transparent;
    transition: all 0.3s;
  }

  .rating-item.good { border-color: rgba(39,174,96,0.3); background: rgba(39,174,96,0.06); }
  .rating-item.medium { border-color: rgba(212,160,23,0.3); background: rgba(212,160,23,0.06); }
  .rating-item.bad { border-color: rgba(192,57,43,0.3); background: rgba(192,57,43,0.06); }

  .rating-label { font-size: 12px; color: var(--gray-mid); margin-bottom: 8px; font-weight: 600; }
  .rating-stars { font-size: 18px; margin-bottom: 6px; line-height: 1; }
  .rating-text { font-size: 13px; font-weight: 800; }
  .rating-item.good .rating-text { color: var(--green-light); }
  .rating-item.medium .rating-text { color: var(--amber); }
  .rating-item.bad .rating-text { color: var(--danger-light); }

  /* ===== RISK GRID ===== */
  .risk-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }

  .risk-card { border-radius: 12px; overflow: hidden; border: 1px solid var(--border); }
  .rc-head { padding: 10px 16px; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 7px; }
  .rc-present .rc-head { background: linear-gradient(135deg, var(--amber), var(--amber-light)); color: #fff; }
  .rc-future  .rc-head { background: linear-gradient(135deg, var(--danger), var(--danger-light)); color: #fff; }
  .rc-body { padding: 14px 16px; background: #fff; font-size: 13px; color: var(--text-body); line-height: 1.7; list-style: none; }
  .rc-body ul { list-style: none; }
  .rc-body li { display: flex; gap: 8px; align-items: flex-start; padding: 4px 0; border-bottom: 1px dashed rgba(11,37,69,0.06); }
  .rc-body li:last-child { border-bottom: none; }
  .rc-body li i { font-size: 10px; margin-top: 6px; flex-shrink: 0; }
  .rc-present .rc-body li i { color: var(--amber); }
  .rc-future  .rc-body li i { color: var(--danger-light); }

  /* ===== SOLUTIONS ===== */
  .sol-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .sol-card {
    border-radius: 16px; overflow: hidden; border: 1px solid var(--border);
    box-shadow: 0 4px 18px rgba(11,37,69,0.08); background: #fff;
    display: flex; flex-direction: column;
  }
  .sol-card.sol-2 { border: 2px solid rgba(39,174,96,0.4); box-shadow: 0 6px 26px rgba(39,174,96,0.16); }

  .sol-head { display: flex; align-items: center; gap: 13px; padding: 16px 20px; }
  .sol-1 .sol-head { background: linear-gradient(135deg, var(--gray-mid), #8FA0B4); }
  .sol-2 .sol-head { background: linear-gradient(135deg, var(--green), var(--green-light)); }
  .sol-head-icon {
    width: 42px; height: 42px; border-radius: 11px; flex-shrink: 0;
    background: rgba(255,255,255,0.2); display: flex; align-items: center;
    justify-content: center; font-size: 18px; color: #fff;
  }
  .sol-head-text h3 { font-size: 15px; font-weight: 900; color: #fff; line-height: 1.3; }
  .sol-head-text .sh-sub { font-size: 11px; color: rgba(255,255,255,0.75); margin-top: 3px; }

  .sol-body { padding: 18px 20px 20px; flex: 1; display: flex; flex-direction: column; }
  .sol-steps { list-style: none; margin-bottom: 16px; flex: 1; }
  .sol-step {
    display: flex; gap: 10px; align-items: flex-start;
    font-size: 12.5px; line-height: 1.7; color: var(--text-body);
    padding: 7px 0; border-bottom: 1px dashed rgba(11,37,69,0.07);
  }
  .sol-step:last-child { border-bottom: none; }
  .ss-n {
    width: 21px; height: 21px; border-radius: 50%; flex-shrink: 0; margin-top: 2px;
    background: var(--gray-light); color: var(--blue-dark);
    font-size: 10.5px; font-weight: 900;
    display: flex; align-items: center; justify-content: center;
  }
  .sol-2 .ss-n { background: rgba(39,174,96,0.15); color: var(--green); }

  .sol-meta { display: flex; flex-wrap: wrap; gap: 8px; }
  .sm-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 700;
  }
  .sm-dur { background: rgba(26,109,175,0.1); color: var(--blue-accent); border: 1px solid rgba(26,109,175,0.2); }
  .sm-war { background: rgba(212,160,23,0.12); color: var(--amber); border: 1px solid rgba(212,160,23,0.25); }
  .sm-rec { background: rgba(39,174,96,0.12); color: var(--green-light); border: 1px solid rgba(39,174,96,0.25); }

  /* ===== COMPARISON TABLE ===== */
  .cmp-table {
    width: 100%; border-collapse: separate; border-spacing: 0;
    border-radius: 14px; overflow: hidden;
    box-shadow: 0 4px 18px rgba(11,37,69,0.08);
    border: 1px solid var(--border);
    font-size: 12.5px;
  }
  .cmp-table thead th {
    background: linear-gradient(135deg, var(--blue-dark), var(--blue-accent));
    color: #fff; font-size: 12.5px; font-weight: 800;
    padding: 13px 14px; text-align: left;
  }
  .cmp-table thead th:last-child { background: linear-gradient(135deg, var(--green), var(--green-light)); }
  .cmp-table td {
    padding: 12px 14px; border-bottom: 1px solid rgba(11,37,69,0.07);
    background: #fff; color: var(--text-body); line-height: 1.6; vertical-align: top;
  }
  .cmp-table tbody tr:nth-child(even) td { background: rgba(11,37,69,0.02); }
  .cmp-table tbody tr:last-child td { border-bottom: none; }
  .cmp-table td:first-child { font-weight: 800; color: var(--blue-dark); }
  .cmp-table td.winner { background: rgba(39,174,96,0.06) !important; }
  .winner-badge {
    display: inline-flex; align-items: center; gap: 4px;
    background: rgba(39,174,96,0.14); color: var(--green);
    border: 1px solid rgba(39,174,96,0.3);
    padding: 2px 9px; border-radius: 50px;
    font-size: 10.5px; font-weight: 800; margin-right: 6px;
  }

  /* ===== RECOMMENDATIONS ===== */
  .rec-box {
    background: linear-gradient(135deg, var(--blue-accent), var(--blue-mid));
    border-radius: 16px;
    padding: 26px 30px;
    color: #fff;
    box-shadow: 0 8px 28px rgba(26,109,175,0.3), inset 0 1px 0 rgba(255,255,255,0.1);
    margin-bottom: 20px;
  }

  .rec-title {
    font-size: 18px;
    font-weight: 900;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .rec-body { font-size: 13.5px; color: rgba(255,255,255,0.9); line-height: 2; margin-bottom: 6px; }

  .rec-list, .rec-points { list-style: none; }
  .rec-item, .rp {
    display: flex;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    font-size: 13.5px;
    color: rgba(255,255,255,0.9);
    align-items: flex-start;
    line-height: 1.8;
  }

  .rec-item:last-child, .rp:last-child { border-bottom: none; }
  .rec-dot, .rp-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--gold-light); flex-shrink: 0; margin-top: 9px; }

  /* ===== CLOSING NOTE ===== */
  .closing {
    background: linear-gradient(135deg, rgba(11,37,69,0.04), rgba(26,109,175,0.03));
    border: 1px solid rgba(11,37,69,0.09);
    border-left: 4px solid var(--gold);
    border-radius: 0 12px 12px 0;
    padding: 18px 22px;
    font-size: 13.5px;
    line-height: 1.95;
    color: var(--text-body);
  }

  /* ===== VERDICT CARD ===== */
  .verdict-card {
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 28px;
    box-shadow: 0 8px 40px rgba(11,37,69,0.15);
  }

  .verdict-card.fade-in {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }
  .verdict-card.fade-in.visible { opacity: 1; transform: translateY(0); }

  .verdict-header {
    padding: 22px 30px;
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .verdict-suitable .verdict-header   { background: linear-gradient(135deg, var(--green), var(--green-light)); }
  .verdict-caution .verdict-header    { background: linear-gradient(135deg, var(--amber), var(--amber-light)); }
  .verdict-unsuitable .verdict-header { background: linear-gradient(135deg, var(--danger), var(--danger-light)); }

  .verdict-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: #fff;
    flex-shrink: 0;
  }

  .verdict-label { font-size: 12px; color: rgba(255,255,255,0.75); font-weight: 600; margin-bottom: 4px; }
  .verdict-text { font-size: 22px; font-weight: 900; color: #fff; }

  .verdict-body {
    padding: 24px 30px;
    background: #fff;
    font-size: 14px;
    line-height: 1.9;
    color: var(--text-body);
  }

  /* ===== SIGNATURE SECTION ===== */
  .sig-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 28px;
  }

  .sig-box {
    background: var(--gray-light);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
  }

  .sig-lbl { font-size: 10px; font-weight: 700; letter-spacing: 1px; color: var(--blue-accent); margin-bottom: 4px; text-transform: uppercase; }
  .sig-name { font-size: 14px; font-weight: 800; color: var(--blue-dark); margin-bottom: 12px; }
  .sig-line { border-top: 1.5px solid var(--blue-mid); padding-top: 8px; font-size: 11px; color: var(--gray-mid); }

  /* ===== CONTACT CTA ===== */
  .cta-section {
    background: #fff;
    border-radius: 20px;
    padding: 40px 32px;
    text-align: center;
    box-shadow: 0 4px 30px rgba(11,37,69,0.08);
    margin-bottom: 28px;
    border: 1px solid rgba(11,37,69,0.06);
  }

  .cta-section.fade-in {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }
  .cta-section.fade-in.visible { opacity: 1; transform: translateY(0); }

  .cta-section h2 { font-size: 24px; font-weight: 900; color: var(--blue-dark); margin-bottom: 10px; }
  .cta-section p { font-size: 14px; color: var(--gray-mid); margin-bottom: 28px; max-width: 500px; margin-inline: auto; }

  .cta-buttons { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    border-radius: 12px;
    font-family: 'Cairo', sans-serif;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid transparent;
  }

  .btn-wa {
    background: linear-gradient(135deg, #25D366, #1DAA54);
    color: white;
    box-shadow: 0 6px 24px rgba(37,211,102,0.35);
  }

  .btn-wa:hover { transform: translateY(-3px); box-shadow: 0 10px 32px rgba(37,211,102,0.45); }

  .btn-call {
    background: transparent;
    color: var(--blue-accent);
    border-color: var(--blue-accent);
  }

  .btn-call:hover { background: var(--blue-accent); color: white; transform: translateY(-3px); }

  .trust-row {
    display: flex;
    justify-content: center;
    gap: 24px;
    margin-top: 24px;
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
    .hero-top-bar { padding: 14px 18px; }
    .hero-content { padding: 36px 18px 80px; }
    .hero-stats { gap: 20px; }
    .stat-num { font-size: 24px; }
    .inspect-grid { grid-template-columns: 1fr 1fr; }
    .cond-grid { grid-template-columns: 1fr; }
    .risk-grid { grid-template-columns: 1fr; }
    .sol-grid { grid-template-columns: 1fr; }
    .hum-steps { grid-template-columns: 1fr; }
    .manhole-grid { grid-template-columns: 1fr 1fr; }
    .sig-row { grid-template-columns: 1fr; }
    .base-card { padding: 22px 18px; }
    .hero-badge span { display: none; }
    .cmp-table { font-size: 11.5px; }
    .cmp-table td, .cmp-table thead th { padding: 9px 8px; }
  }

  @media (max-width: 400px) {
    .inspect-grid { grid-template-columns: 1fr; }
    .rating-grid { grid-template-columns: 1fr 1fr; }
    .manhole-grid { grid-template-columns: 1fr; }
  }

  /* ===== PRINT ===== */
  @media print {
    body { background: #fff; }
    .no-print { display: none; }
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .base-card, .dark-card, .verdict-card, .cta-section { break-inside: avoid; }
    .base-card.fade-in, .verdict-card.fade-in, .cta-section.fade-in { opacity: 1 !important; transform: none !important; }
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

<!-- PRINT BUTTON -->
<div class="no-print">
  <button class="btn-pdf" onclick="window.print()">
    <i class="fas fa-file-pdf"></i> Print / Download PDF
  </button>
</div>

<!-- ===== HERO ===== -->
<section class="hero">
  <div class="hero-orb orb1"></div>
  <div class="hero-orb orb2"></div>

  <div class="hero-top-bar">
    <div class="logo-area">
      <div class="logo-icon"><i class="fas fa-droplet-slash"></i></div>
      <div class="logo-text">
        <h1>Rukn Eltatawer Insulation &amp; Leak Detection L.L.C</h1>
        <span>RUKN ELTATAWER &middot; Certified Engineering Expertise</span>
      </div>
    </div>
    <div class="hero-badge">
      <i class="fas fa-award"></i>
      <span>Officially Licensed &middot; Abu Dhabi, United Arab Emirates</span>
    </div>
  </div>

  <div class="hero-content">
    <div class="report-label">
      <i class="fas fa-magnifying-glass-location"></i>
      Official Water Leak Detection Report
    </div>

    <h1 class="hero-title">
      <span class="highlight">Water Leak Detection</span> Report<br>
      Villa of Mr. Mohammed Salem Al Khumaisi &mdash; Mohammed Bin Zayed City
    </h1>

    <p class="hero-subtitle">
      A precise field inspection of the water leakage sources within the property, covering roof waterproofing,
      AC unit pipe penetrations, and bathroom grout &mdash; identifying root causes, risks, and the technical
      recommendations required for treatment.
    </p>

    <div class="report-meta">
      <div class="meta-item"><i class="fas fa-hashtag"></i> RET-LEAK-2026-0714</div>
      <div class="meta-item"><i class="fas fa-calendar-alt"></i> Inspection Date: Wednesday, July 01, 2026</div>
      <div class="meta-item"><i class="fas fa-location-dot"></i> Mohammed Bin Zayed City, Abu Dhabi</div>
    </div>

    <div class="hero-stats">
      <div class="stat-item">
        <div class="stat-num">3</div>
        <div class="stat-label">Main Inspection Areas</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">1</div>
        <div class="stat-label">Urgent Hazard Source</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">100%</div>
        <div class="stat-label">Direct Field Inspection</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">24h</div>
        <div class="stat-label">Report Delivery Within</div>
      </div>
    </div>
  </div>

  <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 70" preserveAspectRatio="none">
    <path fill="#F0F4F8" d="M0,40 C360,80 1080,0 1440,40 L1440,70 L0,70 Z"/>
  </svg>
</section>

<div class="stripe"></div>

<!-- ===== MAIN CONTENT ===== -->
<section class="main-section">
<div class="container">

  <!-- 1. PROPERTY & CLIENT INFO -->
  <div class="base-card fade-in">
    <div class="card-header">
      <div class="card-icon"><i class="fas fa-user-tie"></i></div>
      <div>
        <h2>Client &amp; Property Details</h2>
        <p>Information on the client and the property under inspection</p>
      </div>
      <div class="sec-num-badge">1</div>
    </div>
    <div class="info-grid">
      <div class="info-item">
        <i class="fas fa-user"></i>
        <div>
          <div class="label">Client Name</div>
          <div class="value">Mohammed Salem Al Khumaisi</div>
        </div>
      </div>
      <div class="info-item">
        <i class="fas fa-location-dot"></i>
        <div>
          <div class="label">Property Location</div>
          <div class="value">Villa 313, Hassan Bin Humaila Al Mazrouei Street &mdash; Sector 17 &mdash; Mohammed Bin Zayed City, Abu Dhabi</div>
        </div>
      </div>
      <div class="info-item">
        <i class="fas fa-home"></i>
        <div>
          <div class="label">Property Type</div>
          <div class="value">Residential Villa</div>
        </div>
      </div>
      <div class="info-item">
        <i class="fas fa-calendar-check"></i>
        <div>
          <div class="label">Inspection Date</div>
          <div class="value">Wednesday, July 01, 2026</div>
        </div>
      </div>
      <div class="info-item">
        <i class="fas fa-magnifying-glass"></i>
        <div>
          <div class="label">Inspection Type</div>
          <div class="value">Water Leak Detection &mdash; Field Inspection</div>
        </div>
      </div>
      <div class="info-item">
        <i class="fas fa-hard-hat"></i>
        <div>
          <div class="label">Executing Team</div>
          <div class="value">Rukn Eltatawer Technical Team</div>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. SCOPE OF INSPECTION -->
  <div class="dark-card fade-in">
    <div class="dark-header">
      <div class="dark-icon"><i class="fas fa-clipboard-list"></i></div>
      <div>
        <div class="dark-title">Scope of Inspection &amp; Main Areas</div>
        <div class="dark-sub">The areas inspected on site to identify the water leakage sources</div>
      </div>
    </div>

    <div class="inspect-grid">
      <div class="inspect-box">
        <i class="fas fa-layer-group"></i>
        <div class="ib-title">Roof Waterproofing &amp; AC Pipes</div>
        <div class="ib-sub">Condition of the waterproofing around pipe penetrations and mesh outlets</div>
      </div>
      <div class="inspect-box">
        <i class="fas fa-snowflake"></i>
        <div class="ib-title">Main Pressure Pipes</div>
        <div class="ib-sub">Insulation of the main AC lines on the roof</div>
      </div>
      <div class="inspect-box">
        <i class="fas fa-bath"></i>
        <div class="ib-title">Bathroom Grout</div>
        <div class="ib-sub">Physical probe testing of the joint filler between tiles</div>
      </div>
    </div>
  </div>

  <!-- 3. ROOF INSULATION — MAIN ISSUE -->
  <div class="base-card accent-danger fade-in">
    <div class="card-header">
      <div class="card-icon danger"><i class="fas fa-house-flood-water"></i></div>
      <div>
        <h2>First: Roof Waterproofing Inspection &mdash; The Primary Issue</h2>
        <p>Water leaking from the roof towards the electrical distribution board below</p>
      </div>
      <div class="sec-num-badge">2</div>
    </div>

    <div class="section-label">Detailed Findings</div>
    <ul class="findings-list">
      <li class="fi">
        <div class="fi-num">1</div>
        <div>
          <div class="fi-title">Pipe Penetrations on the Roof Are Not Sealed</div>
          <div class="fi-desc">The AC pipe outlets on the roof are not fully sealed around their perimeter, allowing rainwater and AC condensate water to penetrate inward through these openings.</div>
          <span class="fi-tag tag-high"><i class="fas fa-triangle-exclamation"></i> High Severity</span>
        </div>
      </li>
      <li class="fi">
        <div class="fi-num">2</div>
        <div>
          <div class="fi-title">The Installed Mesh Does Not Provide a Tight Seal</div>
          <div class="fi-desc">The mesh fitted around the pipe penetrations does not cover the openings tightly, leaving exposed gaps that allow water ingress.</div>
          <span class="fi-tag tag-high"><i class="fas fa-triangle-exclamation"></i> High Severity</span>
        </div>
      </li>
      <li class="fi">
        <div class="fi-num">3</div>
        <div>
          <div class="fi-title">Sections of the Roof Are Exposed and Unwaterproofed</div>
          <div class="fi-desc">In addition to the two rooms containing the pipe penetrations, other open areas of the roof are left without any waterproofing layer, increasing the likelihood of water reaching the internal ceilings.</div>
          <span class="fi-tag tag-high"><i class="fas fa-triangle-exclamation"></i> High Severity</span>
        </div>
      </li>
      <li class="fi">
        <div class="fi-num">4</div>
        <div>
          <div class="fi-title">Direct Water Leakage into the Electrical Board Below the Roof</div>
          <div class="fi-desc">The direct consequence of these defects is water penetrating through the roof into the electrical distribution board located immediately beneath it &mdash; the most serious finding in this report, as it presents a genuine electrical hazard requiring immediate intervention ahead of any other treatment.</div>
          <span class="fi-tag tag-high"><i class="fas fa-bolt"></i> Urgent Hazard &mdash; Top Priority</span>
        </div>
      </li>
    </ul>
  </div>

  <!-- 4. AC MAIN PRESSURE PIPES -->
  <div class="base-card accent-amber fade-in">
    <div class="card-header">
      <div class="card-icon amber"><i class="fas fa-temperature-high"></i></div>
      <div>
        <h2>Second: Insulation of the Main AC Pressure Pipes</h2>
        <p>Main AC lines on the roof &mdash; serving the primary AC unit</p>
      </div>
      <div class="sec-num-badge">3</div>
    </div>

    <div class="section-label">Detailed Findings</div>
    <ul class="findings-list">
      <li class="fi">
        <div class="fi-num">1</div>
        <div>
          <div class="fi-title">Black Adhesive Membrane Used Instead of Dedicated Insulation Material</div>
          <div class="fi-desc">The main pressure pipes serving the primary AC unit on the roof are currently insulated with a black adhesive bituminous membrane &mdash; a material not intended for insulating AC lines. This material progressively reduces cooling efficiency and may ultimately lead to complete failure of the unit if it is not replaced with a thermal insulation material designed for AC pipework (such as Armaflex insulation or equivalent).</div>
          <span class="fi-tag tag-med"><i class="fas fa-circle-exclamation"></i> Medium Severity &mdash; Treatment Recommended Soon</span>
        </div>
      </li>
    </ul>
  </div>

  <!-- 5. BATHROOM GROUT -->
  <div class="base-card accent-gold fade-in">
    <div class="card-header">
      <div class="card-icon gold"><i class="fas fa-bath"></i></div>
      <div>
        <h2>Third: Bathroom Grout Inspection</h2>
        <p>The joint filler between bathroom tiles &mdash; direct probe testing</p>
      </div>
      <div class="sec-num-badge">4</div>
    </div>

    <div class="section-label">Detailed Findings</div>
    <ul class="findings-list">
      <li class="fi">
        <div class="fi-num">1</div>
        <div>
          <div class="fi-title">Bathroom Grout Is Severely Degraded and Requires Full Replacement</div>
          <div class="fi-desc">When the grout (the filler material between ceramic joints) in the bathrooms was probe-tested, it proved so weak that it could be removed by hand with minimal effort. The high salinity of the water progressively breaks this material down, allowing water to seep into the sand sub-base beneath the ceramic tiles and, over time, damaging the structure below the floor.</div>
          <span class="fi-tag tag-high"><i class="fas fa-triangle-exclamation"></i> High Severity &mdash; Replacement Required</span>
        </div>
      </li>
    </ul>
  </div>

  <!-- 6. OVERALL RATING -->
  <div class="base-card fade-in">
    <div class="card-header">
      <div class="card-icon gold"><i class="fas fa-star-half-stroke"></i></div>
      <div>
        <h2>Overall Rating of the Inspected Areas</h2>
        <p>A comprehensive view of the condition of each inspection area</p>
      </div>
      <div class="sec-num-badge">5</div>
    </div>

    <div class="rating-grid">
      <div class="rating-item bad">
        <div class="rating-label">Roof Waterproofing Around Pipes</div>
        <div class="rating-stars">&#11088;&#9734;&#9734;&#9734;&#9734;</div>
        <div class="rating-text">Urgent Intervention Required</div>
      </div>
      <div class="rating-item bad">
        <div class="rating-label">Electrical Safety (Distribution Board)</div>
        <div class="rating-stars">&#11088;&#9734;&#9734;&#9734;&#9734;</div>
        <div class="rating-text">Direct Hazard</div>
      </div>
      <div class="rating-item medium">
        <div class="rating-label">Main AC Pressure Pipe Insulation</div>
        <div class="rating-stars">&#11088;&#11088;&#9734;&#9734;&#9734;</div>
        <div class="rating-text">Treatment Needed</div>
      </div>
      <div class="rating-item bad">
        <div class="rating-label">Bathroom Grout</div>
        <div class="rating-stars">&#11088;&#9734;&#9734;&#9734;&#9734;</div>
        <div class="rating-text">Full Replacement Needed</div>
      </div>
    </div>
  </div>

  <!-- 7. RISKS -->
  <div class="base-card accent-danger fade-in">
    <div class="card-header">
      <div class="card-icon danger"><i class="fas fa-shield-halved"></i></div>
      <div>
        <h2>Current &amp; Future Risks</h2>
        <p>Assessment of existing damage and what may result if left untreated</p>
      </div>
      <div class="sec-num-badge">6</div>
    </div>

    <div class="risk-grid">
      <div class="risk-card rc-present">
        <div class="rc-head"><i class="fas fa-eye"></i> Current Damage &amp; Issues</div>
        <ul class="rc-body">
          <li><i class="fas fa-diamond"></i> Active water leakage from the roof into the electrical distribution board</li>
          <li><i class="fas fa-diamond"></i> Exposed, unsealed pipe penetrations on the roof</li>
          <li><i class="fas fa-diamond"></i> Unsuitable mesh that fails to cover the leakage points</li>
          <li><i class="fas fa-diamond"></i> Improper insulation on the main AC pressure pipes</li>
          <li><i class="fas fa-diamond"></i> Severely degraded bathroom grout leaking water into the sub-base</li>
        </ul>
      </div>
      <div class="risk-card rc-future">
        <div class="rc-head"><i class="fas fa-triangle-exclamation"></i> Future Risks if Left Untreated</div>
        <ul class="rc-body">
          <li><i class="fas fa-diamond"></i> Severe electrical hazard as water continues to reach the distribution board</li>
          <li><i class="fas fa-diamond"></i> Progressive loss of AC efficiency, potentially leading to failure of the main unit</li>
          <li><i class="fas fa-diamond"></i> Deterioration of the sand and sub-base beneath the bathroom tiles and floor degradation</li>
          <li><i class="fas fa-diamond"></i> Water penetration into internal ceilings with damp patches and mould growth</li>
          <li><i class="fas fa-diamond"></i> Escalating remediation costs the longer treatment is delayed</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- 8. TECHNICAL RECOMMENDATIONS -->
  <div class="base-card fade-in">
    <div class="card-header">
      <div class="card-icon green"><i class="fas fa-lightbulb"></i></div>
      <div>
        <h2>Technical Recommendations</h2>
        <p>Proposed remedial actions ranked by priority</p>
      </div>
      <div class="sec-num-badge">7</div>
    </div>

    <div class="rec-box">
      <div class="rec-title">
        <i class="fas fa-list-check"></i>
        Recommended Actions
      </div>
      <ul class="rec-list">
        <li class="rec-item">
          <div class="rec-dot"></div>
          <span><strong>Top Priority:</strong> Seal the pipe penetrations on the roof with a fully watertight waterproofing compound, and treat the leakage point above the electrical board immediately to eliminate the electrical hazard.</span>
        </li>
        <li class="rec-item">
          <div class="rec-dot"></div>
          <span><strong>Top Priority:</strong> Rectify the defective mesh, cover it with the waterproofing layer, and ensure a tight, complete seal around all pipe penetrations.</span>
        </li>
        <li class="rec-item">
          <div class="rec-dot"></div>
          <span><strong>Important:</strong> Re-waterproof the full roof area, including the exposed sections outside the two existing rooms.</span>
        </li>
        <li class="rec-item">
          <div class="rec-dot"></div>
          <span><strong>Important:</strong> Replace the black membrane on the main pressure pipes with a thermal insulation material dedicated to AC lines.</span>
        </li>
        <li class="rec-item">
          <div class="rec-dot"></div>
          <span><strong>Important:</strong> Fully remove the existing bathroom grout and re-fill the joints with a high-quality, salinity-resistant grout.</span>
        </li>
        <li class="rec-item">
          <div class="rec-dot"></div>
          <span><strong>Advisory:</strong> Have the electrical distribution board inspected by a certified electrician to confirm no damage has resulted from the earlier leakage.</span>
        </li>
      </ul>
    </div>
  </div>

  <!-- 9. FINAL VERDICT -->
  <div class="verdict-card fade-in">
    <div class="verdict-caution">
      <div class="verdict-header">
        <div class="verdict-icon"><i class="fas fa-circle-exclamation"></i></div>
        <div>
          <div class="verdict-label">Final Technical Opinion</div>
          <div class="verdict-text">Urgent Intervention Required Before the Rainy Season</div>
        </div>
      </div>
      <div class="verdict-body">
        <p>
          Based on the field inspection carried out by the <strong>Rukn Eltatawer Insulation &amp; Leak Detection</strong> team,
          the source of the water leakage has been clearly identified in the roof waterproofing around the AC pipe penetrations,
          with additional findings concerning the insulation of the main pressure pipes and the bathroom grout.
        </p>
        <br>
        <p>
          The most serious finding is the migration of leaking water from the roof into the <strong>electrical distribution board</strong>
          located directly below it, which requires immediate treatment ahead of any other action given the direct risk it poses to
          occupant safety. The remaining findings (pressure pipes and bathroom grout) can be addressed within a comprehensive
          waterproofing plan for the roof and bathrooms.
        </p>
        <br>
        <p style="color: var(--gray-mid); font-size: 13px;">
          We recommend contacting our team to obtain a quotation for treating all the issues documented in this report.
        </p>
      </div>
    </div>
  </div>

  <!-- 10. CTA -->
  <div class="cta-section fade-in">
    <h2>Contact Us to Treat the Documented Issues</h2>
    <p>Our technical team is ready to provide a quotation covering every finding raised in this report</p>

    <div class="cta-buttons">
      <a href="https://wa.me/971XXXXXXXX" class="btn btn-wa">
        <i class="fab fa-whatsapp"></i>
        Contact via WhatsApp
      </a>
      <a href="tel:+971XXXXXXXX" class="btn btn-call">
        <i class="fas fa-phone"></i>
        Call Us Directly
      </a>
    </div>

    <div class="trust-row">
      <div class="trust-item"><i class="fas fa-circle-check"></i> Certified Technical Team</div>
      <div class="trust-item"><i class="fas fa-circle-check"></i> Specialist Leak Detection Equipment</div>
      <div class="trust-item"><i class="fas fa-circle-check"></i> Report Within 24 Hours</div>
      <div class="trust-item"><i class="fas fa-circle-check"></i> Warranty on Waterproofing Works</div>
    </div>
  </div>

</div>
</section>

<!-- FOOTER with embedded map -->
<footer class="footer">
  <div style="margin-bottom: 24px; border-radius: 24px; overflow: hidden;">
    <iframe width="100%" height="200" style="border:0; filter: brightness(0.85) contrast(1.05); display: block;" loading="lazy" allowfullscreen src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=24.372480,54.538188&zoom=16&maptype=roadmap&language=en"></iframe>
  </div>

  <div style="margin-bottom: 20px; padding: 16px; background: rgba(255,255,255,0.05); border-radius: 20px;">
    <div style="display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap;">
      <i class="fas fa-location-dot" style="color: #E9C77B;"></i>
      <span style="font-size: 13px;">Office 306, Tower A3, MBZ City, Abu Dhabi</span>
      <a href="https://maps.google.com/?q=24.37248,54.538188" target="_blank" style="color: #E9C77B; text-decoration: none; font-size: 12px;">
        <i class="fas fa-external-link-alt"></i> Open Map
      </a>
    </div>
  </div>

  <p>
    <strong>Rukn Eltatawer Modern Insulation Systems L.L.C</strong><br>
    <span style="margin-top:6px;display:inline-block;">This report is confidential and prepared exclusively for its recipient &middot; Report No.: RET-LEAK-2026-0714 &middot; Issue Date: Wednesday, July 01, 2026</span>
  </p>
</footer>

<script>
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, { threshold: 0.08 });

  document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
</script>

</body>
</html>
