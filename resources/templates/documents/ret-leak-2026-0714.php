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
  .orb1 { width: 320px; height: 320px; top: -100px; left: -80px; background: radial-gradient(circle, rgba(255,200,0,0.12) 0%, transparent 65%); }
  .orb2 { width: 220px; height: 220px; bottom: -60px; right: 60px; background: radial-gradient(circle, rgba(26,109,175,0.18) 0%, transparent 65%); }

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
    flex-shrink: 0;
  }

  .logo-text h1 {
    font-size: 16px;
    font-weight: 900;
    color: #fff;
    line-height: 1.2;
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
    top: 0; right: 0;
    width: 5px;
    height: 100%;
    background: linear-gradient(180deg, var(--blue-accent), var(--blue-light));
    border-radius: 0 20px 20px 0;
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
    left: -40px;
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

  /* ===== PHOTOS SECTION ===== */
  .photos-note {
    background: linear-gradient(135deg, rgba(11,37,69,0.04), rgba(26,109,175,0.03));
    border: 1px solid rgba(11,37,69,0.1);
    border-right: 4px solid var(--blue-accent);
    border-radius: 12px 0 0 12px;
    padding: 18px 20px;
    font-size: 13.5px;
    line-height: 1.9;
    color: var(--text-body);
  }

  .photos-placeholder {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-top: 18px;
  }

  .photo-box {
    background: var(--gray-light);
    border-radius: 12px;
    aspect-ratio: 4/3;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 2px dashed rgba(11,37,69,0.15);
    gap: 8px;
    transition: all 0.2s;
  }

  .photo-box:hover { border-color: var(--blue-accent); background: rgba(26,109,175,0.04); }
  .photo-box i { font-size: 28px; color: rgba(11,37,69,0.25); }
  .photo-box span { font-size: 11px; color: var(--gray-mid); font-weight: 600; text-align: center; }

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
  .rc-body li { display: flex; gap: 8px; align-items: flex-start; padding: 4px 0; border-bottom: 1px dashed rgba(11,37,69,0.06); }
  .rc-body li:last-child { border-bottom: none; }
  .rc-body li i { font-size: 10px; margin-top: 6px; flex-shrink: 0; }
  .rc-present .rc-body li i { color: var(--amber); }
  .rc-future  .rc-body li i { color: var(--danger-light); }

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

  .rec-list { list-style: none; }
  .rec-item {
    display: flex;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    font-size: 13.5px;
    color: rgba(255,255,255,0.9);
    align-items: flex-start;
  }

  .rec-item:last-child { border-bottom: none; }
  .rec-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--gold-light); flex-shrink: 0; margin-top: 7px; }

  /* ===== VERDICT CARD ===== */
  .verdict-card {
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 28px;
    box-shadow: 0 8px 40px rgba(11,37,69,0.15);
  }

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

  .sig-lbl { font-size: 10px; font-weight: 700; letter-spacing: 1px; color: var(--blue-accent); margin-bottom: 4px; }
  .sig-name { font-size: 14px; font-weight: 800; color: var(--blue-dark); margin-bottom: 20px; }
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
    .sig-row { grid-template-columns: 1fr; }
    .photos-placeholder { grid-template-columns: 1fr 1fr; }
    .base-card { padding: 22px 18px; }
    .hero-badge span { display: none; }
  }

  @media (max-width: 400px) {
    .inspect-grid { grid-template-columns: 1fr; }
    .photos-placeholder { grid-template-columns: 1fr; }
    .rating-grid { grid-template-columns: 1fr 1fr; }
  }

  /* ===== PRINT ===== */
  @media print {
    body { background: #fff; }
    .no-print { display: none; }
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
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
    <i class="fas fa-file-pdf"></i> طباعة / تحميل PDF
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
        <h1>ركن التطور للعوازل وكشف التسربات ذ.م.م</h1>
        <span>RUKN ELTATAWER · Insulation &amp; Leak Detection L.L.C</span>
      </div>
    </div>
    <div class="hero-badge">
      <i class="fas fa-award"></i>
      <span>معتمدون رسمياً · أبوظبي، الإمارات العربية المتحدة</span>
    </div>
  </div>

  <div class="hero-content">
    <div class="report-label">
      <i class="fas fa-magnifying-glass-location"></i>
      تقرير كشف تسربات مياه رسمي
    </div>

    <h1 class="hero-title">
      تقرير <span class="highlight">كشف تسربات المياه</span><br>
      فيلا العميل محمد سالم الخميسي — مدينة محمد بن زايد
    </h1>

    <p class="hero-subtitle">
      فحص ميداني دقيق لمصادر تسرب المياه في العقار، يشمل عزل السطح، بايبات وحدات التكييف،
      وجراوتن الحمامات — مع تحديد الأسباب والمخاطر والتوصيات الفنية اللازمة للمعالجة.
    </p>

    <div class="report-meta">
      <div class="meta-item"><i class="fas fa-hashtag"></i> RET-LEAK-2026-0714</div>
      <div class="meta-item"><i class="fas fa-calendar-alt"></i> تاريخ الفحص: الأربعاء 1 يوليو 2026</div>
      <div class="meta-item"><i class="fas fa-location-dot"></i> مدينة محمد بن زايد، أبوظبي</div>
    </div>

    <div class="hero-stats">
      <div class="stat-item">
        <div class="stat-num">3</div>
        <div class="stat-label">محاور فحص رئيسية</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">1</div>
        <div class="stat-label">مصدر خطر عاجل</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">100%</div>
        <div class="stat-label">فحص ميداني مباشر</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">24h</div>
        <div class="stat-label">تسليم التقرير خلال</div>
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
        <h2>بيانات العميل والعقار</h2>
        <p>معلومات العميل والعقار محل الفحص</p>
      </div>
      <div class="sec-num-badge" style="margin-right: auto;">1</div>
    </div>
    <div class="info-grid">
      <div class="info-item">
        <i class="fas fa-user"></i>
        <div>
          <div class="label">اسم العميل</div>
          <div class="value">محمد سالم الخميسي</div>
        </div>
      </div>
      <div class="info-item">
        <i class="fas fa-location-dot"></i>
        <div>
          <div class="label">موقع العقار</div>
          <div class="value">فيلا 313، شارع حسن بن هميلة المزروعي — حوض 17 — مدينة محمد بن زايد، أبوظبي</div>
        </div>
      </div>
      <div class="info-item">
        <i class="fas fa-home"></i>
        <div>
          <div class="label">نوع العقار</div>
          <div class="value">فيلا سكنية</div>
        </div>
      </div>
      <div class="info-item">
        <i class="fas fa-calendar-check"></i>
        <div>
          <div class="label">تاريخ الفحص</div>
          <div class="value">الأربعاء 1 يوليو 2026</div>
        </div>
      </div>
      <div class="info-item">
        <i class="fas fa-magnifying-glass"></i>
        <div>
          <div class="label">نوع الفحص</div>
          <div class="value">كشف تسربات مياه — فحص ميداني</div>
        </div>
      </div>
      <div class="info-item">
        <i class="fas fa-hard-hat"></i>
        <div>
          <div class="label">الفريق المنفذ</div>
          <div class="value">فريق ركن التطور الفني</div>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. SCOPE OF INSPECTION -->
  <div class="dark-card fade-in">
    <div class="dark-header">
      <div class="dark-icon"><i class="fas fa-clipboard-list"></i></div>
      <div>
        <div class="dark-title">نطاق الفحص ومحاوره الرئيسية</div>
        <div class="dark-sub">المحاور التي تم فحصها ميدانياً لتحديد مصادر تسرب المياه</div>
      </div>
    </div>

    <div class="inspect-grid">
      <div class="inspect-box">
        <i class="fas fa-layer-group"></i>
        <div class="ib-title">عزل السطح وبايبات التكييف</div>
        <div class="ib-sub">حالة العزل حول البايبات ومنافذ الشاش</div>
      </div>
      <div class="inspect-box">
        <i class="fas fa-snowflake"></i>
        <div class="ib-title">بايبات الضغط الرئيسية</div>
        <div class="ib-sub">عزل خطوط التكييف الرئيسية على السطح</div>
      </div>
      <div class="inspect-box">
        <i class="fas fa-bath"></i>
        <div class="ib-title">جراوتن الحمامات</div>
        <div class="ib-sub">فحص مادة الحشو بين السيراميك بالشوكة</div>
      </div>
    </div>
  </div>

  <!-- 3. ROOF INSULATION — MAIN ISSUE -->
  <div class="base-card accent-danger fade-in">
    <div class="card-header">
      <div class="card-icon danger"><i class="fas fa-house-flood-water"></i></div>
      <div>
        <h2>أولاً: فحص عزل السطح — المشكلة الرئيسية</h2>
        <p>تسرب مياه من السطح باتجاه لوحة الكهرباء أسفله</p>
      </div>
      <div class="sec-num-badge" style="margin-right: auto;">2</div>
    </div>

    <div class="section-label">الملاحظات التفصيلية</div>
    <ul class="findings-list">
      <li class="fi">
        <div class="fi-num">1</div>
        <div>
          <div class="fi-title">فتحات البايبات على السطح غير مغلقة</div>
          <div class="fi-desc">منافذ خروج بايبات التكييف على السطح غير مغلقة بالكامل حول محيطها، ما يسمح بتسرب مياه الأمطار ومياه التكييف إلى الداخل عبر هذه الفتحات.</div>
          <span class="fi-tag tag-high"><i class="fas fa-triangle-exclamation"></i> عالية الخطورة</span>
        </div>
      </li>
      <li class="fi">
        <div class="fi-num">2</div>
        <div>
          <div class="fi-title">الشاش المستخدم غير  مُحكم التغطية</div>
          <div class="fi-desc">الشاش المُركب حول فتحات البايبات   لا يغطي الفتحة بشكل محكم، ما يترك مساحات مكشوفة تسمح بدخول المياه.</div>
          <span class="fi-tag tag-high"><i class="fas fa-triangle-exclamation"></i> عالية الخطورة</span>
        </div>
      </li>
      <li class="fi">
        <div class="fi-num">3</div>
        <div>
          <div class="fi-title">أجزاء من السطح مكشوفة وغير معزولة</div>
          <div class="fi-desc">بالإضافه إلى الغرفتين اللتين تحويان فتحات البايبات، توجد أماكن أخرى مفتوحة على السطح وغير معزولة مائياً، ما يزيد من احتمالية تسرب المياه إلى الأسقف الداخلية.</div>
          <span class="fi-tag tag-high"><i class="fas fa-triangle-exclamation"></i> عالية الخطورة</span>
        </div>
      </li>
      <li class="fi">
        <div class="fi-num">4</div>
        <div>
          <div class="fi-title">تسرب مياه مباشر إلى لوحة الكهرباء أسفل السطح</div>
          <div class="fi-desc">النتيجة المباشرة لهذه المشكلات هي تسرب المياه عبر السطح إلى لوحة الكهرباء الموجودة أسفله مباشرة — وهو أخطر ملاحظة في هذا التقرير، حيث يشكل خطراً كهربائياً حقيقياً يستوجب تدخلاً فورياً قبل أي معالجة أخرى.</div>
          <span class="fi-tag tag-high"><i class="fas fa-bolt"></i> خطر عاجل — أولوية قصوى</span>
        </div>
      </li>
    </ul>
  </div>

  <!-- 4. AC MAIN PRESSURE PIPES -->
  <div class="base-card accent-amber fade-in">
    <div class="card-header">
      <div class="card-icon amber"><i class="fas fa-temperature-high"></i></div>
      <div>
        <h2>ثانياً: عزل بايبات الضغط الرئيسية للتكييف</h2>
        <p>خطوط التكييف الرئيسية على السطح — وحدة التكييف الرئيسية</p>
      </div>
      <div class="sec-num-badge" style="margin-right: auto;">3</div>
    </div>

    <div class="section-label">الملاحظات التفصيلية</div>
    <ul class="findings-list">
      <li class="fi">
        <div class="fi-num">1</div>
        <div>
          <div class="fi-title">استخدام ممبرين أسود لاصق بدلاً من مادة العزل المخصصة</div>
          <div class="fi-desc">بايبات الضغط الرئيسية الخاصة بوحدة التكييف الرئيسية على السطح معزولة حالياً بممبرين أسود لاصق (bituminous membrane)، وهي مادة غير مخصصة لعزل خطوط التكييف. هذه المادة تضعف كفاءة التبريد تدريجياً، وقد تؤدي في النهاية إلى تلف الوحدة بالكامل إن لم تُستبدل بمادة عازلة حرارياً مخصصة لبايبات التكييف (مثل عزل الأرمافلكس أو ما يعادله).</div>
          <span class="fi-tag tag-med"><i class="fas fa-circle-exclamation"></i> متوسطة الخطورة — يُوصى بالمعالجة قريباً</span>
        </div>
      </li>
    </ul>
  </div>

  <!-- 5. BATHROOM GROUT -->
  <div class="base-card accent-gold fade-in">
    <div class="card-header">
      <div class="card-icon gold"><i class="fas fa-bath"></i></div>
      <div>
        <h2>ثالثاً: فحص جراوتن الحمامات</h2>
        <p>مادة الحشو بين بلاط الحمامات — فحص مباشر بالشوكة</p>
      </div>
      <div class="sec-num-badge" style="margin-right: auto;">4</div>
    </div>

    <div class="section-label">الملاحظات التفصيلية</div>
    <ul class="findings-list">
      <li class="fi">
        <div class="fi-num">1</div>
        <div>
          <div class="fi-title">جراوتن الحمامات ضعيف جداً ويحتاج استبدالاً كاملاً</div>
          <div class="fi-desc">عند فحص الجراوتن (مادة حشو الفواصل بين السيراميك) في الحمامات باستخدام الشوكة، تبيّن أنه ضعيف جداً لدرجة أنه كان يُنزع بسهولة باليد. نسبة الملوحة العالية في المياه تعمل على تفتيت هذه المادة تدريجياً، ما يسمح بتسرب المياه إلى طبقة الدفان أو الرمل أسفل السيراميك، ويؤدي مع الوقت إلى إتلاف البنية أسفل الأرضية.</div>
          <span class="fi-tag tag-high"><i class="fas fa-triangle-exclamation"></i> عالية الخطورة — يستوجب الاستبدال</span>
        </div>
      </li>
    </ul>
  </div>

  <!-- 7. OVERALL RATING -->
  <div class="base-card fade-in">
    <div class="card-header">
      <div class="card-icon gold"><i class="fas fa-star-half-stroke"></i></div>
      <div>
        <h2>التقييم العام للمحاور المفحوصة</h2>
        <p>نظرة شاملة على حالة كل محور من محاور الكشف</p>
      </div>
      <div class="sec-num-badge" style="margin-right: auto;">5</div>
    </div>

    <div class="rating-grid">
      <div class="rating-item bad">
        <div class="rating-label">عزل السطح حول البايبات</div>
        <div class="rating-stars">⭐☆☆☆☆</div>
        <div class="rating-text">يستوجب تدخلاً عاجلاً</div>
      </div>
      <div class="rating-item bad">
        <div class="rating-label">السلامة الكهربائية (لوحة الكهرباء)</div>
        <div class="rating-stars">⭐☆☆☆☆</div>
        <div class="rating-text">خطر مباشر</div>
      </div>
      <div class="rating-item medium">
        <div class="rating-label">عزل بايبات الضغط الرئيسية</div>
        <div class="rating-stars">⭐⭐☆☆☆</div>
        <div class="rating-text">يحتاج معالجة</div>
      </div>
      <div class="rating-item bad">
        <div class="rating-label">جراوتن الحمامات</div>
        <div class="rating-stars">⭐☆☆☆☆</div>
        <div class="rating-text">يحتاج استبدالاً كاملاً</div>
      </div>
    </div>
  </div>

  <!-- 8. RISKS -->
  <div class="base-card accent-danger fade-in">
    <div class="card-header">
      <div class="card-icon danger"><i class="fas fa-shield-halved"></i></div>
      <div>
        <h2>المخاطر الحالية والمستقبلية</h2>
        <p>تقييم الأضرار القائمة وما قد ينتج عنها إذا تُركت دون معالجة</p>
      </div>
      <div class="sec-num-badge" style="margin-right: auto;">6</div>
    </div>

    <div class="risk-grid">
      <div class="risk-card rc-present">
        <div class="rc-head"><i class="fas fa-eye"></i> الأضرار والمشكلات الحالية</div>
        <ul class="rc-body">
          <li><i class="fas fa-diamond"></i> تسرب مياه فعلي من السطح إلى لوحة الكهرباء</li>
          <li><i class="fas fa-diamond"></i> فتحات بايبات مكشوفة وغير مغلقة على السطح</li>
          <li><i class="fas fa-diamond"></i> شاش غير مناسب لا يغطي منافذ التسرب</li>
          <li><i class="fas fa-diamond"></i> عزل غير مناسب لبايبات الضغط الرئيسية للتكييف</li>
          <li><i class="fas fa-diamond"></i> جراوتن ضعيف جداً في الحمامات يسرب المياه للدفان</li>
        </ul>
      </div>
      <div class="risk-card rc-future">
        <div class="rc-head"><i class="fas fa-triangle-exclamation"></i> المخاطر المستقبلية إن تُركت</div>
        <ul class="rc-body">
          <li><i class="fas fa-diamond"></i> خطر كهربائي جسيم نتيجة وصول المياه إلى اللوحة الكهربائية</li>
          <li><i class="fas fa-diamond"></i> ضعف تدريجي في كفاءة التكييف وصولاً إلى تلف الوحدة الرئيسية</li>
          <li><i class="fas fa-diamond"></i> تلف الرمل والدفان أسفل سيراميك الحمامات وتدهور الأرضية</li>
          <li><i class="fas fa-diamond"></i> تسرب المياه إلى الأسقف الداخلية وظهور رطوبة وعفن</li>
          <li><i class="fas fa-diamond"></i> ارتفاع تكلفة الإصلاح كلما تأخرت المعالجة</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- 9. TECHNICAL RECOMMENDATIONS -->
  <div class="base-card fade-in">
    <div class="card-header">
      <div class="card-icon green"><i class="fas fa-lightbulb"></i></div>
      <div>
        <h2>التوصيات الفنية</h2>
        <p>إجراءات العلاج المقترحة مرتبة حسب الأولوية</p>
      </div>
      <div class="sec-num-badge" style="margin-right: auto;">7</div>
    </div>

    <div class="rec-box">
      <div class="rec-title">
        <i class="fas fa-list-check"></i>
        الإجراءات الموصى بها
      </div>
      <ul class="rec-list">
        <li class="rec-item">
          <div class="rec-dot"></div>
          <span><strong>أولوية قصوى:</strong> إغلاق فتحات البايبات على السطح بمادة عازلة مائية محكمة، ومعالجة موقع التسرب فوق لوحة الكهرباء فوراً لإزالة الخطر الكهربائي.</span>
        </li>
        <li class="rec-item">
          <div class="rec-dot"></div>
          <span><strong>أولوية قصوى:</strong> معالجة أخطاء الشاش وتغطيته بالعازل وجعله محكم التغطية حول جميع فتحات البايبات.</span>
        </li>
        <li class="rec-item">
          <div class="rec-dot"></div>
          <span><strong>مهم:</strong> إعادة عزل كامل مساحة السطح مائياً، بما يشمل الأجزاء المكشوفة خارج الغرفتين الحاليتين.</span>
        </li>
        <li class="rec-item">
          <div class="rec-dot"></div>
          <span><strong>مهم:</strong> استبدال الممبرين الأسود على بايبات الضغط الرئيسية بمادة عزل حراري مخصصة لخطوط التكييف.</span>
        </li>
        <li class="rec-item">
          <div class="rec-dot"></div>
          <span><strong>مهم:</strong> إزالة جراوتن الحمامات القديم بالكامل وإعادة الحشو بمادة جراوتن عالية الجودة مقاومة للملوحة.</span>
        </li>
        <li class="rec-item">
          <div class="rec-dot"></div>
          <span><strong>توصية:</strong> فحص لوحة الكهرباء من قِبل كهربائي معتمد للتأكد من عدم وجود ضرر ناتج عن التسرب السابق.</span>
        </li>
      </ul>
    </div>
  </div>

  <!-- 10. FINAL VERDICT -->
  <div class="verdict-card fade-in">
    <div class="verdict-caution">
      <div class="verdict-header">
        <div class="verdict-icon"><i class="fas fa-circle-exclamation"></i></div>
        <div>
          <div class="verdict-label">الرأي الفني النهائي</div>
          <div class="verdict-text">يستوجب تدخلاً عاجلاً قبل موسم الأمطار</div>
        </div>
      </div>
      <div class="verdict-body">
        <p>
          بناءً على الفحص الميداني الذي أجراه فريق <strong>ركن التطور للعوازل وكشف التسربات</strong>،
          تم تحديد مصدر تسرب المياه بشكل واضح في عزل السطح حول فتحات بايبات التكييف، مع ملاحظات إضافية
          في عزل بايبات الضغط الرئيسية وجراوتن الحمامات.
        </p>
        <br>
        <p>
          الملاحظة الأخطر هي وصول المياه المتسربة من السطح إلى <strong>لوحة الكهرباء</strong> الموجودة
          أسفله مباشرة، وهو ما يستوجب معالجة فورية قبل أي إجراء آخر نظراً لما يمثله من خطر مباشر على
          سلامة السكان. باقي الملاحظات (بايبات الضغط وجراوتن الحمامات) قابلة للمعالجة ضمن خطة عزل شاملة
          للسطح والحمامات.
        </p>
        <br>
        <p style="color: var(--gray-mid); font-size: 13px;">
          يُنصح بالتواصل مع فريقنا للحصول على عرض أسعار لمعالجة جميع المشكلات المرصودة في هذا التقرير.
        </p>
      </div>
    </div>
  </div>

  <!-- 11. CTA -->
  <div class="cta-section fade-in">
    <h2>تواصل معنا لمعالجة المشكلات المرصودة</h2>
    <p>فريقنا الفني جاهز لتقديم عرض أسعار لحل جميع الملاحظات الواردة في التقرير</p>

    <div class="cta-buttons">
      <a href="https://wa.me/971XXXXXXXX" class="btn btn-wa">
        <i class="fab fa-whatsapp"></i>
        تواصل عبر واتساب
      </a>
      <a href="tel:+971XXXXXXXX" class="btn btn-call">
        <i class="fas fa-phone"></i>
        اتصل بنا مباشرة
      </a>
    </div>

    <div class="trust-row">
      <div class="trust-item"><i class="fas fa-circle-check"></i> فريق فني معتمد</div>
      <div class="trust-item"><i class="fas fa-circle-check"></i> أجهزة كشف تسربات متخصصة</div>
      <div class="trust-item"><i class="fas fa-circle-check"></i> تقرير خلال 24 ساعة</div>
      <div class="trust-item"><i class="fas fa-circle-check"></i> ضمان على أعمال العزل</div>
    </div>
  </div>

</div>
</section>

<!-- FOOTER مع الخريطة مدمجة -->
<footer class="footer">
  <!-- الخريطة داخل الفوتر -->
  <div style="margin-bottom: 24px; border-radius: 24px; overflow: hidden;">
    <iframe width="100%" height="200" style="border:0; filter: brightness(0.85) contrast(1.05); display: block;" loading="lazy" allowfullscreen src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=24.372480,54.538188&zoom=16&maptype=roadmap&language=ar"></iframe>
  </div>

  <!-- معلومات المكتب -->
  <div style="margin-bottom: 20px; padding: 16px; background: rgba(255,255,255,0.05); border-radius: 20px;">
    <div style="display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap;">
      <i class="fas fa-location-dot" style="color: #E9C77B;"></i>
      <span style="font-size: 13px;">Office 306, Tower A3, MBZ City, AbuDhabi</span>
      <a href="https://maps.google.com/?q=24.37248,54.538188" target="_blank" style="color: #E9C77B; text-decoration: none; font-size: 12px;">
        <i class="fas fa-external-link-alt"></i> فتح الخريطة
      </a>
    </div>
  </div>

  <!-- النص الأصلي للفوتر -->
  <p>
    <strong>ركن التطور لأنظمة العزل الحديث ذ.م.م</strong><br>
    <span style="margin-top:6px;display:inline-block;">هذا التقرير سري ومُعد حصرياً لصاحبه · رقم التقرير: RET-LEAK-2026-0714 · تاريخ الإصدار: الأربعاء 1 يوليو 2026</span>
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
