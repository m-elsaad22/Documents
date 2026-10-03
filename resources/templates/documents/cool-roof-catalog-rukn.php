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
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&family=Tajawal:wght@300;400;500;700;800;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ══════════════════════════════════════════
   TOKEN SYSTEM
   ══════════════════════════════════════════ */
:root {
  --ink:        #040C18;
  --navy:       #071A35;
  --navy-mid:   #0C2D5C;
  --blue:       #1557A8;
  --sky:        #2AADEC;
  --ice:        #E8F6FF;
  --gold:       #C9940A;
  --gold-light: #F0C842;
  --snow:       #FFFFFF;
  --fog:        #F2F7FC;
  --mist:       #D6E7F5;
  --text:       #1A2B40;
  --sub:        #4A6080;

  --ff-display: 'Tajawal', sans-serif;
  --ff-body:    'Cairo', sans-serif;

  --r: 16px;
  --shadow-card: 0 12px 48px rgba(4,12,24,0.12);
  --shadow-deep: 0 24px 80px rgba(4,12,24,0.22);
}

*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

html { scroll-behavior:smooth; }

body {
  font-family: var(--ff-body);
  background: var(--fog);
  color: var(--text);
  overflow-x: hidden;
}

/* ══════════════════════════════════════════
   SCROLL PROGRESS BAR
   ══════════════════════════════════════════ */
#progress-bar {
  position: fixed;
  top: 0; left: 0;
  height: 3px;
  width: 0%;
  background: linear-gradient(90deg, var(--sky), var(--gold-light));
  z-index: 9999;
  transition: width 0.1s linear;
}

/* ══════════════════════════════════════════
   STICKY NAV
   ══════════════════════════════════════════ */
.site-nav {
  position: fixed;
  top: 3px; left: 0; right: 0;
  z-index: 900;
  background: rgba(7,26,53,0.92);
  backdrop-filter: blur(16px);
  border-bottom: 1px solid rgba(255,255,255,0.08);
  padding: 14px 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.nav-logo {
  font-family: var(--ff-display);
  font-weight: 900;
  font-size: 1.15rem;
  color: var(--snow);
  letter-spacing: 0.02em;
  display: flex;
  align-items: center;
  gap: 10px;
}
.nav-logo span { color: var(--sky); }
.nav-links { display:flex; gap:28px; list-style:none; }
.nav-links a {
  color: rgba(255,255,255,0.7);
  text-decoration: none;
  font-size: 0.85rem;
  font-family: var(--ff-display);
  font-weight: 500;
  transition: color 0.2s;
}
.nav-links a:hover { color: var(--sky); }
.nav-cta {
  background: linear-gradient(135deg, var(--sky), var(--blue));
  color: var(--snow) !important;
  padding: 8px 20px;
  border-radius: 40px;
  font-weight: 700 !important;
  transition: transform 0.2s, box-shadow 0.2s !important;
}
.nav-cta:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(42,173,236,0.4) !important; color:var(--snow) !important; }

/* ══════════════════════════════════════════
   HERO  — CINEMATIC
   ══════════════════════════════════════════ */
.hero {
  min-height: 100vh;
  background: var(--ink);
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  padding: 120px 40px 80px;
}

/* Animated sky-gradient backdrop */
.hero-bg {
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse 120% 80% at 50% 110%, #0C2D5C 0%, transparent 60%),
    radial-gradient(ellipse 80% 60% at 80% 20%, rgba(42,173,236,0.15) 0%, transparent 50%),
    radial-gradient(ellipse 60% 80% at 10% 60%, rgba(21,87,168,0.2) 0%, transparent 60%),
    linear-gradient(180deg, #010B1C 0%, #071A35 40%, #0C2D5C 100%);
}

/* Stars */
.hero-stars {
  position: absolute;
  inset: 0;
  overflow: hidden;
}
.star {
  position: absolute;
  width: 2px; height: 2px;
  border-radius: 50%;
  background: white;
  animation: twinkle var(--d, 3s) var(--delay, 0s) infinite;
}
@keyframes twinkle {
  0%,100% { opacity:0.2; transform:scale(1); }
  50%      { opacity:1;   transform:scale(1.5); }
}

/* Sun orb */
.hero-sun {
  position: absolute;
  top: -80px; right: 10%;
  width: 380px; height: 380px;
  border-radius: 50%;
  background: radial-gradient(circle at 40% 40%,
    rgba(255,220,80,0.25) 0%,
    rgba(255,160,20,0.1) 40%,
    transparent 70%);
  animation: sunFloat 8s ease-in-out infinite;
  pointer-events: none;
}
@keyframes sunFloat {
  0%,100% { transform:translateY(0) scale(1); }
  50%      { transform:translateY(20px) scale(1.05); }
}

/* Roof cross-section SVG illustration */
.hero-roof-layer {
  position: absolute;
  bottom: 0; left: 0; right: 0;
  height: 220px;
  overflow: hidden;
}

/* Grid lines */
.hero-grid {
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(42,173,236,0.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(42,173,236,0.04) 1px, transparent 1px);
  background-size: 60px 60px;
}

.hero-content {
  position: relative;
  z-index: 10;
  text-align: center;
  max-width: 900px;
}

.hero-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: rgba(42,173,236,0.1);
  border: 1px solid rgba(42,173,236,0.3);
  border-radius: 40px;
  padding: 8px 20px;
  color: var(--sky);
  font-size: 0.8rem;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  margin-bottom: 32px;
  animation: fadeSlideUp 0.8s 0.2s both;
}
.hero-eyebrow::before {
  content: '';
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--sky);
  animation: pulse 2s infinite;
}
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(1.5)} }

.hero-title {
  font-family: var(--ff-display);
  font-weight: 900;
  font-size: clamp(3rem, 7vw, 6rem);
  line-height: 1.05;
  color: var(--snow);
  margin-bottom: 8px;
  animation: fadeSlideUp 0.8s 0.3s both;
}
.hero-title .accent-sky { color: var(--sky); }
.hero-title .accent-gold { color: var(--gold-light); }

.hero-sub {
  font-family: var(--ff-display);
  font-weight: 300;
  font-size: clamp(1rem, 2.5vw, 1.4rem);
  color: rgba(255,255,255,0.55);
  margin-bottom: 48px;
  letter-spacing: 0.04em;
  animation: fadeSlideUp 0.8s 0.45s both;
}

.hero-stats {
  display: flex;
  justify-content: center;
  gap: 0;
  margin-bottom: 52px;
  animation: fadeSlideUp 0.8s 0.55s both;
}
.hero-stat {
  padding: 0 40px;
  border-left: 1px solid rgba(255,255,255,0.1);
  text-align: center;
}
.hero-stat:last-child { border-right: 1px solid rgba(255,255,255,0.1); }
.hero-stat-num {
  font-family: var(--ff-display);
  font-size: 2.6rem;
  font-weight: 900;
  color: var(--snow);
  line-height: 1;
  display: block;
}
.hero-stat-num span { color: var(--sky); font-size: 1.6rem; }
.hero-stat-label {
  font-size: 0.78rem;
  color: rgba(255,255,255,0.45);
  font-weight: 500;
  margin-top: 4px;
  display: block;
  letter-spacing: 0.05em;
}

.hero-actions {
  display: flex;
  gap: 16px;
  justify-content: center;
  animation: fadeSlideUp 0.8s 0.65s both;
}
.btn-primary {
  background: linear-gradient(135deg, var(--sky) 0%, var(--blue) 100%);
  color: var(--snow);
  border: none;
  border-radius: 50px;
  padding: 16px 40px;
  font-family: var(--ff-display);
  font-weight: 800;
  font-size: 1rem;
  cursor: pointer;
  box-shadow: 0 8px 32px rgba(42,173,236,0.35);
  transition: all 0.25s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
.btn-primary:hover { transform:translateY(-3px); box-shadow: 0 16px 48px rgba(42,173,236,0.5); }
.btn-ghost {
  background: transparent;
  color: var(--snow);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: 50px;
  padding: 16px 36px;
  font-family: var(--ff-display);
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.25s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  backdrop-filter: blur(8px);
}
.btn-ghost:hover { border-color: var(--sky); color: var(--sky); }

/* Scroll indicator */
.scroll-indicator {
  position: absolute;
  bottom: 36px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  color: rgba(255,255,255,0.3);
  font-size: 0.7rem;
  letter-spacing: 0.1em;
  animation: fadeIn 1s 1.5s both;
}
.scroll-line {
  width: 1px;
  height: 50px;
  background: linear-gradient(180deg, rgba(42,173,236,0.6), transparent);
  animation: scrollLine 2s infinite;
}
@keyframes scrollLine {
  0%   { transform:scaleY(0); transform-origin:top; }
  50%  { transform:scaleY(1); transform-origin:top; }
  51%  { transform:scaleY(1); transform-origin:bottom; }
  100% { transform:scaleY(0); transform-origin:bottom; }
}

@keyframes fadeSlideUp {
  from { opacity:0; transform:translateY(30px); }
  to   { opacity:1; transform:translateY(0); }
}
@keyframes fadeIn {
  from { opacity:0; } to { opacity:1; }
}

/* ══════════════════════════════════════════
   SECTION WRAPPER
   ══════════════════════════════════════════ */
.section {
  padding: 100px 0;
}
.section-dark {
  background: var(--navy);
  color: var(--snow);
}
.section-light { background: var(--fog); }
.section-white { background: var(--snow); }
.section-midnight { background: var(--ink); color: var(--snow); }

.container {
  max-width: 1160px;
  margin: 0 auto;
  padding: 0 40px;
}

.section-label {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: var(--sky);
  margin-bottom: 20px;
}
.section-label::before {
  content:'';
  display:block;
  width:24px; height:2px;
  background: var(--sky);
  flex-shrink: 0;
}
.section-title {
  font-family: var(--ff-display);
  font-weight: 900;
  font-size: clamp(2rem, 4vw, 3.2rem);
  line-height: 1.15;
  margin-bottom: 16px;
}
.section-desc {
  font-size: 1.05rem;
  line-height: 1.8;
  color: var(--sub);
  max-width: 560px;
}
.section-dark .section-desc,
.section-midnight .section-desc { color: rgba(255,255,255,0.55); }

/* ══════════════════════════════════════════
   WHAT IS COOL ROOF  — split layout
   ══════════════════════════════════════════ */
.what-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 80px;
  align-items: center;
}
.what-visual {
  position: relative;
}
.roof-diagram {
  width: 100%;
  aspect-ratio: 4/3;
  border-radius: 20px;
  overflow: hidden;
  position: relative;
  background: linear-gradient(180deg, #87CEEB 0%, #BFEFFF 60%, #E0F7FF 100%);
  box-shadow: var(--shadow-deep);
}
/* Animated sun in diagram */
.diag-sun {
  position: absolute;
  top: 16px; right: 24px;
  width: 70px; height: 70px;
  border-radius: 50%;
  background: radial-gradient(circle, #FFD700 40%, rgba(255,200,0,0.3) 70%, transparent 100%);
  animation: sunFloat 4s ease-in-out infinite;
}
.diag-rays {
  position: absolute;
  top: 0; right: 0;
  width: 200px; height: 200px;
  overflow: hidden;
}
/* Roof layers */
.diag-layers {
  position: absolute;
  bottom: 0; left: 0; right: 0;
}
.diag-layer {
  height: 24px;
  display: flex;
  align-items: center;
  padding: 0 16px;
  font-size: 0.7rem;
  font-weight: 700;
  color: white;
}
.dl-topcoat  { background: #E8F4FF; color: #0C2D5C; height:28px; }
.dl-coolroof { background: var(--sky); }
.dl-mesh     { background: #2A8FC9; }
.dl-primer   { background: var(--blue); }
.dl-concrete { background: #8B9DB0; }

/* Arrows showing heat reflection */
.diag-arrows {
  position: absolute;
  top: 30px; left: 30px;
}

.what-points { list-style:none; display:flex; flex-direction:column; gap:20px; }
.what-point {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 20px 24px;
  background: var(--snow);
  border-radius: var(--r);
  box-shadow: 0 4px 20px rgba(4,12,24,0.07);
  border-right: 4px solid var(--sky);
  transition: transform 0.2s, box-shadow 0.2s;
}
.what-point:hover { transform:translateX(-4px); box-shadow: 0 8px 32px rgba(42,173,236,0.15); }
.wp-icon {
  width: 44px; height: 44px;
  border-radius: 12px;
  background: linear-gradient(135deg, var(--sky), var(--blue));
  display: grid;
  place-items: center;
  color: white;
  font-size: 1.1rem;
  flex-shrink: 0;
}
.wp-text h4 { font-size:0.95rem; font-weight:700; color:var(--text); margin-bottom:4px; }
.wp-text p  { font-size:0.82rem; color:var(--sub); line-height:1.6; }

/* ══════════════════════════════════════════
   THERMAL INFOGRAPHIC  — HERO number
   ══════════════════════════════════════════ */
.temp-band {
  background: linear-gradient(135deg, #010B1C, #071A35, #0D3260);
  border-radius: 24px;
  padding: 60px;
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  gap: 40px;
  align-items: center;
  margin: 60px 0;
  position: relative;
  overflow: hidden;
  box-shadow: var(--shadow-deep);
}
.temp-band::before {
  content:'';
  position: absolute;
  inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.tb-side { text-align: center; }
.tb-temp-before {
  font-family: var(--ff-display);
  font-size: 5rem;
  font-weight: 900;
  color: #FF6B35;
  line-height: 1;
  display: block;
}
.tb-temp-after {
  font-family: var(--ff-display);
  font-size: 5rem;
  font-weight: 900;
  color: var(--sky);
  line-height: 1;
  display: block;
}
.tb-label {
  font-size: 0.82rem;
  color: rgba(255,255,255,0.4);
  letter-spacing: 0.1em;
  text-transform: uppercase;
  margin-top: 8px;
  display: block;
}
.tb-arrow {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  color: var(--snow);
}
.tb-arrow-icon {
  width: 60px; height: 60px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--sky), var(--blue));
  display: grid;
  place-items: center;
  font-size: 1.3rem;
}
.tb-arrow-text {
  font-family: var(--ff-display);
  font-weight: 800;
  font-size: 1.1rem;
  color: var(--gold-light);
  text-align: center;
}
.tb-sub {
  font-size: 0.78rem;
  color: rgba(255,255,255,0.4);
  text-align: center;
}

/* ══════════════════════════════════════════
   FEATURES GRID — card mosaic
   ══════════════════════════════════════════ */
.features-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}
.feature-card {
  background: var(--snow);
  border-radius: 20px;
  padding: 36px 28px;
  box-shadow: var(--shadow-card);
  position: relative;
  overflow: hidden;
  transition: transform 0.3s, box-shadow 0.3s;
  border-bottom: 3px solid transparent;
}
.feature-card::before {
  content:'';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--sky), var(--blue));
  transform: scaleX(0);
  transform-origin: right;
  transition: transform 0.3s;
}
.feature-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-deep); }
.feature-card:hover::before { transform: scaleX(1); }

.fc-icon {
  width: 60px; height: 60px;
  border-radius: 16px;
  display: grid;
  place-items: center;
  font-size: 1.4rem;
  color: white;
  margin-bottom: 24px;
  background: linear-gradient(135deg, var(--sky), var(--blue));
  box-shadow: 0 8px 24px rgba(42,173,236,0.3);
}
.feature-card.fc-gold .fc-icon {
  background: linear-gradient(135deg, var(--gold-light), var(--gold));
  box-shadow: 0 8px 24px rgba(201,148,10,0.3);
}
.feature-card.fc-green .fc-icon {
  background: linear-gradient(135deg, #3ECF8E, #1A9E6A);
  box-shadow: 0 8px 24px rgba(62,207,142,0.3);
}
.fc-num {
  position: absolute;
  bottom: 24px; left: 24px;
  font-family: var(--ff-display);
  font-size: 5rem;
  font-weight: 900;
  color: rgba(4,12,24,0.04);
  line-height: 1;
}
.fc-title { font-weight: 800; font-size: 1.05rem; margin-bottom: 10px; color: var(--text); }
.fc-desc  { font-size: 0.85rem; line-height: 1.7; color: var(--sub); }

/* LARGE FEATURE CARD */
.feature-card.fc-large {
  grid-column: span 2;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 32px;
  align-items: center;
  padding: 44px 40px;
}
.fc-large-text { }
.fc-large-visual {
  background: linear-gradient(135deg, var(--ice), #C8E8FF);
  border-radius: 12px;
  padding: 32px;
  text-align: center;
}
.fc-percent {
  font-family: var(--ff-display);
  font-size: 5.5rem;
  font-weight: 900;
  color: var(--blue);
  line-height: 1;
}
.fc-percent span { color: var(--sky); }
.fc-percent-label {
  font-size: 0.85rem;
  color: var(--sub);
  margin-top: 8px;
  line-height: 1.5;
}

/* ══════════════════════════════════════════
   LAYERS / SCOPE OF WORK
   ══════════════════════════════════════════ */
.layers-section {
  background: var(--ink);
  position: relative;
  overflow: hidden;
}
.layers-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 80px;
  align-items: start;
}
.layer-steps { display: flex; flex-direction: column; gap: 0; }
.layer-step {
  display: flex;
  gap: 20px;
  padding: 28px 0;
  border-bottom: 1px solid rgba(255,255,255,0.06);
  position: relative;
}
.layer-step:last-child { border-bottom: none; }
.ls-num {
  width: 40px; height: 40px;
  border-radius: 50%;
  background: rgba(42,173,236,0.12);
  border: 1px solid rgba(42,173,236,0.3);
  display: grid;
  place-items: center;
  font-family: var(--ff-display);
  font-weight: 900;
  font-size: 0.85rem;
  color: var(--sky);
  flex-shrink: 0;
}
.ls-body h4 { font-size:0.95rem; font-weight:700; color:var(--snow); margin-bottom:6px; }
.ls-body p  { font-size:0.82rem; color:rgba(255,255,255,0.45); line-height:1.6; }
.ls-spec {
  display: inline-block;
  background: rgba(42,173,236,0.1);
  color: var(--sky);
  border-radius: 4px;
  padding: 2px 8px;
  font-size: 0.7rem;
  font-weight: 700;
  margin-top: 6px;
}

/* Animated cross-section */
.roof-cross {
  position: sticky;
  top: 120px;
  background: linear-gradient(180deg, #87CEEB 0%, #C8EFFF 50%, transparent 100%);
  border-radius: 20px;
  overflow: hidden;
  padding: 40px 0 0;
  box-shadow: var(--shadow-deep);
}
.rcs-sky {
  padding: 20px 20px 0;
  text-align: center;
  font-size: 0.7rem;
  color: #0C2D5C;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.1em;
}
.rcs-sun {
  width: 60px; height: 60px;
  border-radius: 50%;
  background: radial-gradient(circle, #FFD700 40%, rgba(255,200,0,0.4) 70%, transparent 100%);
  margin: 16px auto 0;
}
.rcs-layer {
  display: flex;
  align-items: center;
  padding: 12px 20px;
  gap: 12px;
  font-size: 0.78rem;
  font-weight: 700;
  position: relative;
}
.rcs-layer-label { color: white; white-space:nowrap; }
.rcs-layer-spec  { font-size:0.68rem; color:rgba(255,255,255,0.65); font-weight:400; }
.rcl-1 { background: #E8F4FF; }
.rcl-1 .rcs-layer-label { color: #0C2D5C; }
.rcl-2 { background: var(--sky); }
.rcl-3 { background: #2090C5; }
.rcl-4 { background: var(--blue); }
.rcl-5 { background: #0C3D7A; }
.rcl-6 { background: #7A8FA0; }
.rcl-6 .rcs-layer-label, .rcl-6 .rcs-layer-spec { color:rgba(255,255,255,0.75); }

/* ══════════════════════════════════════════
   PROOF / THERMAL SCAN
   ══════════════════════════════════════════ */
.proof-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
  align-items: center;
}
.proof-flir {
  background: var(--navy);
  border-radius: 20px;
  overflow: hidden;
  box-shadow: var(--shadow-deep);
  padding: 32px;
}
.flir-pair {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-bottom: 20px;
}
.flir-img {
  border-radius: 10px;
  overflow: hidden;
  position: relative;
  aspect-ratio: 4/3;
  display: flex;
  align-items: flex-end;
  padding: 10px;
}
.flir-before {
  background: linear-gradient(135deg, #FF4444 0%, #FF8C00 40%, #FFD700 70%, #FF6B35 100%);
}
.flir-after {
  background: linear-gradient(135deg, #1A3AFF 0%, #0080FF 40%, #00B4FF 65%, #00FFAA 100%);
}
.flir-badge {
  background: rgba(0,0,0,0.65);
  color: white;
  font-size: 0.72rem;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 20px;
  backdrop-filter: blur(4px);
}
.flir-temp {
  position: absolute;
  top: 10px; right: 10px;
  font-family: var(--ff-display);
  font-weight: 900;
  font-size: 1.1rem;
  color: white;
  text-shadow: 0 2px 6px rgba(0,0,0,0.5);
}
.flir-label {
  font-size: 0.8rem;
  color: rgba(255,255,255,0.45);
  text-align: center;
}
.cert-badges {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}
.cert-badge {
  background: var(--snow);
  border-radius: var(--r);
  padding: 24px 20px;
  text-align: center;
  box-shadow: var(--shadow-card);
}
.cb-icon {
  font-size: 2rem;
  color: var(--blue);
  margin-bottom: 10px;
}
.cb-val {
  font-family: var(--ff-display);
  font-weight: 900;
  font-size: 1.8rem;
  color: var(--text);
  line-height: 1;
}
.cb-val span { color: var(--sky); }
.cb-name {
  font-size: 0.75rem;
  color: var(--sub);
  margin-top: 4px;
}

/* ══════════════════════════════════════════
   PRICING  — PREMIUM CARDS
   ══════════════════════════════════════════ */
.pricing-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 28px;
}
.price-card {
  background: var(--snow);
  border-radius: 24px;
  overflow: hidden;
  box-shadow: var(--shadow-card);
  transition: transform 0.3s, box-shadow 0.3s;
  position: relative;
}
.price-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-deep); }
.price-card.pc-featured {
  background: linear-gradient(160deg, var(--navy) 0%, var(--navy-mid) 100%);
  transform: scale(1.04);
  box-shadow: 0 24px 64px rgba(42,173,236,0.3);
}
.price-card.pc-featured:hover { transform: scale(1.04) translateY(-6px); }

.pc-badge {
  position: absolute;
  top: 20px; left: 20px;
  background: linear-gradient(135deg, var(--gold-light), var(--gold));
  color: var(--ink);
  font-size: 0.7rem;
  font-weight: 800;
  padding: 5px 14px;
  border-radius: 20px;
  letter-spacing: 0.05em;
}
.pc-header {
  padding: 40px 32px 28px;
  border-bottom: 1px solid rgba(4,12,24,0.07);
  text-align: center;
}
.pc-featured .pc-header { border-bottom-color: rgba(255,255,255,0.1); }
.pc-layers {
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--sky);
  margin-bottom: 12px;
}
.pc-title {
  font-family: var(--ff-display);
  font-weight: 900;
  font-size: 1.3rem;
  color: var(--text);
  margin-bottom: 4px;
}
.pc-featured .pc-title { color: var(--snow); }
.pc-isolation {
  font-size: 0.85rem;
  color: var(--sub);
}
.pc-featured .pc-isolation { color: rgba(255,255,255,0.5); }

.pc-price {
  padding: 32px;
  text-align: center;
}
.pc-price-num {
  font-family: var(--ff-display);
  font-weight: 900;
  font-size: 3.5rem;
  color: var(--text);
  line-height: 1;
}
.pc-featured .pc-price-num { color: var(--snow); }
.pc-currency {
  font-size: 1.2rem;
  color: var(--sky);
  vertical-align: super;
}
.pc-unit {
  font-size: 0.8rem;
  color: var(--sub);
  margin-top: 4px;
}
.pc-featured .pc-unit { color: rgba(255,255,255,0.4); }

.pc-features { padding: 0 32px 32px; }
.pc-feature {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 0;
  border-bottom: 1px solid rgba(4,12,24,0.05);
  font-size: 0.85rem;
  color: var(--text);
}
.pc-featured .pc-feature { border-bottom-color: rgba(255,255,255,0.06); color: rgba(255,255,255,0.7); }
.pc-feature:last-child { border-bottom: none; }
.pc-feature-icon { color: var(--sky); font-size: 0.8rem; }
.pc-featured .pc-feature-icon { color: var(--gold-light); }

.pc-cta {
  display: block;
  margin: 0 32px 32px;
  padding: 14px;
  border-radius: 12px;
  text-align: center;
  font-family: var(--ff-display);
  font-weight: 800;
  font-size: 0.95rem;
  text-decoration: none;
  transition: all 0.25s;
  cursor: pointer;
  border: none;
}
.pc-cta-outline {
  background: transparent;
  border: 1.5px solid var(--mist);
  color: var(--blue);
}
.pc-cta-outline:hover { border-color: var(--sky); background: var(--ice); }
.pc-cta-solid {
  background: linear-gradient(135deg, var(--sky), var(--blue));
  color: white;
  box-shadow: 0 8px 24px rgba(42,173,236,0.35);
}
.pc-cta-solid:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(42,173,236,0.5); }

/* Pricing note */
.pricing-note {
  margin-top: 40px;
  background: linear-gradient(135deg, rgba(42,173,236,0.08), rgba(21,87,168,0.05));
  border: 1px solid rgba(42,173,236,0.2);
  border-radius: 16px;
  padding: 24px 32px;
  display: flex;
  align-items: center;
  gap: 16px;
}
.pricing-note-icon { font-size: 1.4rem; color: var(--sky); flex-shrink:0; }
.pricing-note p { font-size: 0.88rem; color: var(--sub); line-height: 1.7; }
.pricing-note strong { color: var(--text); }

/* ══════════════════════════════════════════
   WARRANTY / GUARANTEE
   ══════════════════════════════════════════ */
.warranty-section {
  background: linear-gradient(135deg, #010B1C 0%, #071A35 50%, #0D3260 100%);
  position: relative;
  overflow: hidden;
}
.warranty-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 80px;
  align-items: center;
}
.warranty-badge {
  width: 260px; height: 260px;
  border-radius: 50%;
  background: rgba(42,173,236,0.08);
  border: 2px solid rgba(42,173,236,0.2);
  display: grid;
  place-items: center;
  text-align: center;
  margin: 0 auto;
  position: relative;
}
.warranty-badge::before, .warranty-badge::after {
  content:'';
  position: absolute;
  border-radius: 50%;
  border: 1px solid rgba(42,173,236,0.1);
}
.warranty-badge::before { inset: -20px; }
.warranty-badge::after  { inset: -40px; }
.wb-num {
  font-family: var(--ff-display);
  font-weight: 900;
  font-size: 5rem;
  color: var(--snow);
  line-height: 1;
}
.wb-unit { font-size: 1.2rem; color: var(--sky); }
.wb-label { font-size: 0.85rem; color: rgba(255,255,255,0.5); margin-top: 8px; }

.warranty-points { list-style:none; display:flex; flex-direction:column; gap:16px; }
.warranty-point {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 20px 24px;
  background: rgba(255,255,255,0.04);
  border-radius: 12px;
  border: 1px solid rgba(255,255,255,0.06);
}
.wpp-icon {
  width: 40px; height: 40px;
  border-radius: 10px;
  background: rgba(42,173,236,0.15);
  display: grid;
  place-items: center;
  color: var(--sky);
  font-size: 1rem;
  flex-shrink: 0;
}
.wpp-text h4 { font-weight:700; font-size:0.9rem; color:var(--snow); margin-bottom:4px; }
.wpp-text p  { font-size:0.8rem; color:rgba(255,255,255,0.4); line-height:1.5; }

/* ══════════════════════════════════════════
   CTA FINAL SECTION
   ══════════════════════════════════════════ */
.cta-section {
  background: linear-gradient(135deg, var(--sky) 0%, var(--blue) 100%);
  padding: 80px 40px;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.cta-section::before {
  content:'';
  position: absolute;
  inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff' fill-opacity='0.06'%3E%3Ccircle cx='20' cy='20' r='1'/%3E%3C/g%3E%3C/svg%3E");
}
.cta-title {
  font-family: var(--ff-display);
  font-weight: 900;
  font-size: clamp(2rem, 4vw, 3rem);
  color: var(--snow);
  margin-bottom: 16px;
  position: relative;
}
.cta-sub {
  font-size: 1.05rem;
  color: rgba(255,255,255,0.7);
  margin-bottom: 40px;
  position: relative;
}
.cta-actions {
  display: flex;
  gap: 16px;
  justify-content: center;
  position: relative;
}
.btn-white {
  background: white;
  color: var(--blue);
  border: none;
  border-radius: 50px;
  padding: 16px 40px;
  font-family: var(--ff-display);
  font-weight: 800;
  font-size: 1rem;
  cursor: pointer;
  box-shadow: 0 8px 32px rgba(0,0,0,0.2);
  transition: all 0.25s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
.btn-white:hover { transform:translateY(-3px); box-shadow:0 16px 48px rgba(0,0,0,0.3); }
.btn-ghost-white {
  background: transparent;
  color: white;
  border: 2px solid rgba(255,255,255,0.5);
  border-radius: 50px;
  padding: 16px 36px;
  font-family: var(--ff-display);
  font-weight: 700;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.25s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
.btn-ghost-white:hover { border-color:white; background:rgba(255,255,255,0.1); }

/* ══════════════════════════════════════════
   FOOTER
   ══════════════════════════════════════════ */
.site-footer {
  background: var(--ink);
  padding: 48px 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid rgba(255,255,255,0.05);
}
.footer-logo {
  font-family: var(--ff-display);
  font-weight: 900;
  font-size: 1.2rem;
  color: var(--snow);
}
.footer-logo span { color: var(--sky); }
.footer-copy { font-size: 0.78rem; color: rgba(255,255,255,0.3); }
.footer-socials { display:flex; gap:14px; }
.footer-social {
  width: 36px; height: 36px;
  border-radius: 50%;
  background: rgba(255,255,255,0.06);
  display: grid;
  place-items: center;
  color: rgba(255,255,255,0.4);
  text-decoration: none;
  font-size: 0.9rem;
  transition: all 0.2s;
}
.footer-social:hover { background: var(--sky); color:white; }

/* ══════════════════════════════════════════
   PDF DOWNLOAD BUTTON — FLOATING
   ══════════════════════════════════════════ */
.pdf-fab {
  position: fixed;
  bottom: 36px;
  left: 36px;
  z-index: 800;
  display: flex;
  align-items: center;
  gap: 12px;
  background: linear-gradient(135deg, var(--gold-light), var(--gold));
  color: var(--ink);
  border: none;
  border-radius: 50px;
  padding: 14px 24px 14px 18px;
  font-family: var(--ff-display);
  font-weight: 800;
  font-size: 0.88rem;
  cursor: pointer;
  box-shadow: 0 8px 32px rgba(201,148,10,0.45);
  transition: all 0.25s;
  animation: fabIn 1s 1s both;
}
.pdf-fab:hover { transform:translateY(-4px); box-shadow:0 16px 48px rgba(201,148,10,0.6); }
.pdf-fab i { font-size: 1rem; }
@keyframes fabIn {
  from { opacity:0; transform:translateY(20px); }
  to   { opacity:1; transform:translateY(0); }
}

/* ══════════════════════════════════════════
   PRINT / PDF STYLES
   ══════════════════════════════════════════ */
@media print {
  .site-nav, .pdf-fab, #progress-bar, .scroll-indicator { display:none !important; }
  .hero { min-height: auto; padding-top: 60px; }
  .section { padding: 60px 0; }
  * { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
}

/* ══════════════════════════════════════════
   RESPONSIVE
   ══════════════════════════════════════════ */
@media (max-width: 900px) {
  .what-grid, .layers-grid, .proof-grid, .warranty-grid { grid-template-columns:1fr; gap:40px; }
  .features-grid, .pricing-grid { grid-template-columns:1fr; }
  .feature-card.fc-large { grid-column:span 1; grid-template-columns:1fr; }
  .hero-stats { flex-wrap:wrap; }
  .temp-band { grid-template-columns:1fr; gap:24px; padding:40px; }
  .site-nav { padding:14px 20px; }
  .nav-links { display:none; }
  .price-card.pc-featured { transform:scale(1); }
  .site-footer { flex-direction:column; gap:24px; text-align:center; }
}

/* ══════════════════════════════════════════
   INTERSECTION OBSERVER ANIMATIONS
   ══════════════════════════════════════════ */
.reveal {
  opacity: 0;
  transform: translateY(32px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
.reveal.visible {
  opacity: 1;
  transform: none;
}
.reveal-delay-1 { transition-delay: 0.1s; }
.reveal-delay-2 { transition-delay: 0.2s; }
.reveal-delay-3 { transition-delay: 0.3s; }
.reveal-delay-4 { transition-delay: 0.4s; }
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

<!-- Progress Bar -->
<div id="progress-bar"></div>

<!-- ══ NAV ══ -->
<nav class="site-nav">
  <div class="nav-logo">
    <i class="fas fa-layer-group" style="color:var(--sky)"></i>
    <span>RUKN</span> ELTATAWER
  </div>
  <ul class="nav-links">
    <li><a href="#what">المنتج</a></li>
    <li><a href="#features">المميزات</a></li>
    <li><a href="#scope">نطاق العمل</a></li>
    <li><a href="#pricing">الأسعار</a></li>
    <li><a href="#warranty">الضمان</a></li>
    <li><a href="#contact" class="nav-cta">اتصل بنا</a></li>
  </ul>
</nav>

<!-- ══ HERO ══ -->
<section class="hero" id="top">
  <div class="hero-bg"></div>
  <div class="hero-stars" id="starsContainer"></div>
  <div class="hero-sun"></div>
  <div class="hero-grid"></div>

  <div class="hero-content">
    <div class="hero-eyebrow">كتالوج 2026 — الإصدار الاحترافي</div>
    <h1 class="hero-title">
      <span class="accent-sky">كول</span> رووف<br>
      <span style="font-size:0.55em;font-weight:300;color:rgba(255,255,255,0.5);letter-spacing:0.05em">COOL ROOF SYSTEM</span>
    </h1>
    <p class="hero-sub">أفضل نظام عزل حراري ومائي للأسطح في الإمارات<br>تقنية إيلاستومير 100% أكريليك · ضمان 10 سنوات</p>

    <div class="hero-stats">
      <div class="hero-stat">
        <span class="hero-stat-num">70<span>%</span></span>
        <span class="hero-stat-label">عزل حراري</span>
      </div>
      <div class="hero-stat">
        <span class="hero-stat-num">0.86<span></span></span>
        <span class="hero-stat-label">معامل انعكاس الشمس</span>
      </div>
      <div class="hero-stat">
        <span class="hero-stat-num">10<span>y</span></span>
        <span class="hero-stat-label">ضمان شامل</span>
      </div>
      <div class="hero-stat">
        <span class="hero-stat-num">40<span>%</span></span>
        <span class="hero-stat-label">توفير الكهرباء</span>
      </div>
    </div>

    <div class="hero-actions">
      <a href="#pricing" class="btn-primary"><i class="fas fa-tag"></i> عرض الأسعار</a>
      <a href="#scope" class="btn-ghost"><i class="fas fa-play-circle"></i> كيف يعمل؟</a>
    </div>
  </div>

  <div class="scroll-indicator">
    <div class="scroll-line"></div>
    <span>اسحب للأسفل</span>
  </div>
</section>

<!-- ══ WHAT IS COOL ROOF ══ -->
<section class="section section-white" id="what">
  <div class="container">
    <div class="what-grid">
      <div class="what-visual reveal">
        <div class="roof-diagram">
          <div class="diag-sun"></div>
          <!-- Heat rays animation -->
          <svg style="position:absolute;top:20px;right:80px;width:120px" viewBox="0 0 120 160">
            <defs>
              <marker id="arr1" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
                <path d="M0,0 L6,3 L0,6 Z" fill="#FF8C00"/>
              </marker>
              <marker id="arr2" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
                <path d="M0,0 L6,3 L0,6 Z" fill="#2AADEC"/>
              </marker>
            </defs>
            <!-- incoming -->
            <line x1="100" y1="10" x2="30" y2="100" stroke="#FF8C00" stroke-width="2" stroke-dasharray="4,3" marker-end="url(#arr1)" opacity="0.8">
              <animate attributeName="stroke-dashoffset" from="0" to="-14" dur="1s" repeatCount="indefinite"/>
            </line>
            <line x1="80" y1="5" x2="15" y2="80" stroke="#FF8C00" stroke-width="2" stroke-dasharray="4,3" marker-end="url(#arr1)" opacity="0.5">
              <animate attributeName="stroke-dashoffset" from="0" to="-14" dur="1.3s" repeatCount="indefinite"/>
            </line>
            <!-- reflected -->
            <line x1="30" y1="100" x2="90" y2="20" stroke="#2AADEC" stroke-width="2.5" stroke-dasharray="4,3" marker-end="url(#arr2)" opacity="0.9">
              <animate attributeName="stroke-dashoffset" from="-14" to="0" dur="1s" repeatCount="indefinite"/>
            </line>
          </svg>

          <div class="diag-layers">
            <div class="diag-layer dl-topcoat">Top Coat — واقية الانعكاس</div>
            <div class="diag-layer dl-coolroof">طبقتان Cool Roof — 500µ لكل طبقة</div>
            <div class="diag-layer dl-mesh">Fiber Mesh — 93 جم/م²</div>
            <div class="diag-layer dl-primer">طبقة Primer — 75µ</div>
            <div class="diag-layer dl-concrete" style="background:#9EB0C0;color:#1A2B40">السطح الخرساني</div>
          </div>
        </div>
      </div>

      <div class="reveal reveal-delay-2">
        <div class="section-label">ما هو كول رووف؟</div>
        <h2 class="section-title">نظام عزل سطح<br><span style="color:var(--sky)">متطور ومُعتمد</span></h2>
        <p class="section-desc" style="margin-bottom:32px">
          طلاء مائي مرن للأسطح، عازل للماء وعاكس لأشعة الشمس، يعتمد على 100% أكريليك بوليمر. مُصمم خصيصاً لمناخ الخليج القاسي.
        </p>
        <ul class="what-points">
          <li class="what-point">
            <div class="wp-icon"><i class="fas fa-sun"></i></div>
            <div class="wp-text">
              <h4>انعكاس استثنائي للشمس</h4>
              <p>معامل انعكاس 0.867 — يعكس ما يزيد عن 86% من إشعاع الشمس</p>
            </div>
          </li>
          <li class="what-point">
            <div class="wp-icon"><i class="fas fa-tint"></i></div>
            <div class="wp-text">
              <h4>عزل مائي تام</h4>
              <p>يوفر حاجزاً مائياً متكاملاً يمنع أي تسرب للمياه بمرونة عالية</p>
            </div>
          </li>
          <li class="what-point">
            <div class="wp-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="wp-text">
              <h4>مقاومة للأشعة فوق البنفسجية والعوامل الجوية</h4>
              <p>مناسب للظروف القاسية في الشرق الأوسط بمقاومة ممتازة للأوزون</p>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <!-- Thermal comparison band -->
    <div class="temp-band reveal">
      <div class="tb-side">
        <span class="tb-temp-before">55°C</span>
        <span class="tb-label">درجة السطح قبل التطبيق</span>
        <div style="margin-top:16px;display:flex;justify-content:center">
          <div style="background:rgba(255,107,53,0.15);border-radius:8px;padding:8px 16px;display:inline-flex;align-items:center;gap:8px">
            <i class="fas fa-fire" style="color:#FF6B35"></i>
            <span style="color:#FF6B35;font-size:0.8rem;font-weight:700">حرارة عالية — استهلاك مرتفع</span>
          </div>
        </div>
      </div>
      <div class="tb-arrow">
        <div class="tb-arrow-icon"><i class="fas fa-arrow-left"></i></div>
        <div class="tb-arrow-text">انخفاض<br>14–20°C</div>
        <div class="tb-sub">فرق مُثبت بمسح حراري FLIR</div>
      </div>
      <div class="tb-side">
        <span class="tb-temp-after">35°C</span>
        <span class="tb-label">درجة السطح بعد التطبيق</span>
        <div style="margin-top:16px;display:flex;justify-content:center">
          <div style="background:rgba(42,173,236,0.15);border-radius:8px;padding:8px 16px;display:inline-flex;align-items:center;gap:8px">
            <i class="fas fa-snowflake" style="color:var(--sky)"></i>
            <span style="color:var(--sky);font-size:0.8rem;font-weight:700">سطح بارد — توفير في الكهرباء</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══ FEATURES ══ -->
<section class="section section-light" id="features">
  <div class="container">
    <div style="margin-bottom:52px" class="reveal">
      <div class="section-label">المميزات التقنية</div>
      <h2 class="section-title">لماذا كول رووف<br><span style="color:var(--sky)">من ركن التطور؟</span></h2>
    </div>
    <div class="features-grid">

      <div class="feature-card fc-large reveal">
        <div class="fc-large-text">
          <div class="fc-icon"><i class="fas fa-bolt"></i></div>
          <h3 class="fc-title" style="font-size:1.3rem;margin-bottom:12px">توفير حتى 40% في فاتورة الكهرباء</h3>
          <p class="fc-desc">بتقليل درجة حرارة السطح بشكل كبير، يعمل نظام التكييف بكفاءة أعلى بكثير، مما ينعكس مباشرة على فاتورتك الشهرية. مُثبت بدراسات مقارنة على أسطح فلل مدينة خليفة أ.</p>
        </div>
        <div class="fc-large-visual">
          <div class="fc-percent"><span>40</span>%</div>
          <div class="fc-percent-label">توفير موثق في استهلاك<br>الطاقة للتبريد</div>
          <div style="margin-top:20px;display:flex;gap:8px;justify-content:center">
            <div style="width:8px;height:8px;border-radius:50%;background:var(--sky)"></div>
            <div style="width:8px;height:8px;border-radius:50%;background:var(--blue);opacity:0.5"></div>
            <div style="width:8px;height:8px;border-radius:50%;background:var(--blue);opacity:0.25"></div>
          </div>
        </div>
      </div>

      <div class="feature-card reveal reveal-delay-1">
        <div class="fc-icon"><i class="fas fa-water"></i></div>
        <h3 class="fc-title">عزل مائي فائق</h3>
        <p class="fc-desc">مقاومة كاملة لتسرب المياه مع مرونة تستوعب حركة المبنى وتمدده الحراري دون تشقق.</p>
        <div class="fc-num">2</div>
      </div>

      <div class="feature-card reveal reveal-delay-2">
        <div class="fc-icon"><i class="fas fa-expand-arrows-alt"></i></div>
        <h3 class="fc-title">مرونة عالية وتجسير الشقوق</h3>
        <p class="fc-desc">يغطي الشقوق الدقيقة ويمنع تمددها مع الحفاظ على سلامة طبقة العزل كاملة.</p>
        <div class="fc-num">3</div>
      </div>

      <div class="feature-card fc-gold reveal reveal-delay-1">
        <div class="fc-icon"><i class="fas fa-leaf"></i></div>
        <h3 class="fc-title">محتوى VOC منخفض جداً</h3>
        <p class="fc-desc">آمن بيئياً ومناسب للاستخدام في المناطق السكنية. لا رائحة نفاذة أثناء التطبيق.</p>
        <div class="fc-num">4</div>
      </div>

      <div class="feature-card fc-green reveal reveal-delay-2">
        <div class="fc-icon"><i class="fas fa-layer-group"></i></div>
        <h3 class="fc-title">تصاق ممتاز على جميع الأسطح</h3>
        <p class="fc-desc">خرسانة، بلاط إسمنتي، فولاذ، مجلفن، بولي يوريثان فوم، بيتومين — نظام واحد يناسب الجميع.</p>
        <div class="fc-num">5</div>
      </div>

    </div>
  </div>
</section>

<!-- ══ SCOPE OF WORK ══ -->
<section class="section layers-section section-midnight" id="scope">
  <div class="container">
    <div style="margin-bottom:60px" class="reveal">
      <div class="section-label">نطاق العمل الفني</div>
      <h2 class="section-title" style="color:var(--snow)">خطوات التطبيق<br><span style="color:var(--sky)">بدقة احترافية</span></h2>
      <p class="section-desc">كل مشروع يُنفَّذ وفق بروتوكول تقني صارم بأيدي فريق مدرب ومعتمد.</p>
    </div>
    <div class="layers-grid">
      <div class="layer-steps">

        <div class="layer-step reveal">
          <div class="ls-num">1</div>
          <div class="ls-body">
            <h4>تحضير السطح</h4>
            <p>تنظيف شامل بالضغط العالي وإزالة كل المواد المتقلبة والأتربة والمتصلبين القديمة. إصلاح الشقوق بـ Epoxy Crack Filler.</p>
            <span class="ls-spec">معالجة الزوايا بـ Jazeera Rebond 100</span>
          </div>
        </div>

        <div class="layer-step reveal reveal-delay-1">
          <div class="ls-num">2</div>
          <div class="ls-body">
            <h4>تركيب Fiber Mesh</h4>
            <p>شبك نسيجي بوزن 93 جم/م² على الزوايا والأركان والأنابيب مع تداخل 5-10 سم لضمان الاستمرارية.</p>
            <span class="ls-spec">T325 Jazeera Tietex</span>
          </div>
        </div>

        <div class="layer-step reveal reveal-delay-2">
          <div class="ls-num">3</div>
          <div class="ls-body">
            <h4>طبقة Primer</h4>
            <p>تطبيق طبقة أساس بسُمك 75µ لتعزيز التصاق المنتج النهائي وضمان تغطية متجانسة.</p>
            <span class="ls-spec">75µ — مخففة 10-15% ماء</span>
          </div>
        </div>

        <div class="layer-step reveal reveal-delay-1">
          <div class="ls-num">4</div>
          <div class="ls-body">
            <h4>طبقتان من Cool Roof</h4>
            <p>تطبيق طبقتين بسُمك 500µ لكل طبقة باستخدام مسدس الرش اللاهوائي للحصول على تغطية مثالية ومنتظمة.</p>
            <span class="ls-spec">500µ × 2 طبقة = 1000µ إجمالي</span>
          </div>
        </div>

        <div class="layer-step reveal reveal-delay-2">
          <div class="ls-num">5</div>
          <div class="ls-body">
            <h4>Top Coat — الطبقة الواقية</h4>
            <p>طلاء نهائي لتعزيز الانعكاس الحراري وإضافة حماية إضافية من الأشعة فوق البنفسجية والعوامل الجوية.</p>
            <span class="ls-spec">طبقة واقية عاكسة للأشعة</span>
          </div>
        </div>

      </div>

      <div class="roof-cross reveal reveal-delay-2">
        <div class="rcs-sky">
          <div class="rcs-sun"></div>
          <div style="margin-top:12px;color:#0C2D5C;font-size:0.7rem;font-weight:700">أشعة الشمس المنعكسة ↑</div>
        </div>
        <div class="rcs-layer rcl-1">
          <div>
            <div class="rcs-layer-label">Top Coat</div>
            <div class="rcs-layer-spec">واقية الانعكاس</div>
          </div>
        </div>
        <div class="rcs-layer rcl-2">
          <div>
            <div class="rcs-layer-label">Cool Roof — الطبقة الثانية</div>
            <div class="rcs-layer-spec">500µ</div>
          </div>
        </div>
        <div class="rcs-layer rcl-3">
          <div>
            <div class="rcs-layer-label">Cool Roof — الطبقة الأولى</div>
            <div class="rcs-layer-spec">500µ</div>
          </div>
        </div>
        <div class="rcs-layer rcl-4">
          <div>
            <div class="rcs-layer-label">Fiber Mesh T325</div>
            <div class="rcs-layer-spec">93 جم/م²</div>
          </div>
        </div>
        <div class="rcs-layer rcl-5">
          <div>
            <div class="rcs-layer-label">Primer</div>
            <div class="rcs-layer-spec">75µ</div>
          </div>
        </div>
        <div class="rcs-layer rcl-6">
          <div>
            <div class="rcs-layer-label" style="color:white">السطح الخرساني</div>
            <div class="rcs-layer-spec">Concrete Deck</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══ PROOF / CERTIFICATIONS ══ -->
<section class="section section-white" id="proof">
  <div class="container">
    <div style="margin-bottom:52px" class="reveal">
      <div class="section-label">الأداء المُثبت</div>
      <h2 class="section-title">شهادات معتمدة<br><span style="color:var(--sky)">ونتائج حقيقية</span></h2>
    </div>
    <div class="proof-grid">
      <div class="proof-flir reveal">
        <div style="font-size:0.75rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--sky);margin-bottom:16px">مسح حراري FLIR — قبل وبعد</div>
        <div class="flir-pair">
          <div class="flir-img flir-before">
            <span class="flir-temp">52.8°C</span>
            <span class="flir-badge">قبل التطبيق</span>
          </div>
          <div class="flir-img flir-after">
            <span class="flir-temp">38.8°C</span>
            <span class="flir-badge">بعد التطبيق</span>
          </div>
        </div>
        <div class="flir-label">انخفاض فعلي مُسجل: <strong style="color:var(--sky)">14°C</strong> في درجة حرارة السطح</div>

        <div style="margin-top:24px;padding-top:20px;border-top:1px solid rgba(255,255,255,0.08)">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;text-align:center">
            <div>
              <div style="font-family:var(--ff-display);font-weight:900;font-size:1.8rem;color:var(--snow)">0.867</div>
              <div style="font-size:0.72rem;color:rgba(255,255,255,0.4);margin-top:4px">Solar Reflectance</div>
            </div>
            <div>
              <div style="font-family:var(--ff-display);font-weight:900;font-size:1.8rem;color:var(--snow)">0.91</div>
              <div style="font-size:0.72rem;color:rgba(255,255,255,0.4);margin-top:4px">Thermal Emittance</div>
            </div>
          </div>
          <div style="margin-top:12px;background:rgba(42,173,236,0.08);border-radius:8px;padding:12px 16px;font-size:0.75rem;color:rgba(255,255,255,0.45);line-height:1.6">
            <i class="fas fa-certificate" style="color:var(--sky);margin-left:6px"></i>
            اختبار معتمد من CRRC (Cool Roof Rating Council) — التقرير DCC-504-02-01
          </div>
        </div>
      </div>

      <div class="reveal reveal-delay-2">
        <div style="margin-bottom:24px">
          <h3 style="font-weight:800;font-size:1.2rem;margin-bottom:8px">شهادات الجودة</h3>
          <p style="font-size:0.85rem;color:var(--sub);line-height:1.7">المنتج معتمد ومُختبر وفق معايير دولية صارمة لضمان الأداء المذكور.</p>
        </div>
        <div class="cert-badges">
          <div class="cert-badge">
            <div class="cb-icon"><i class="fas fa-award"></i></div>
            <div class="cb-val"><span>CRRC</span></div>
            <div class="cb-name">معتمد دولياً</div>
          </div>
          <div class="cert-badge">
            <div class="cb-icon"><i class="fas fa-thermometer-half"></i></div>
            <div class="cb-val">14<span>°C</span></div>
            <div class="cb-name">انخفاض مُثبت</div>
          </div>
          <div class="cert-badge">
            <div class="cb-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="cb-val">10<span>y</span></div>
            <div class="cb-name">ضمان الأداء</div>
          </div>
          <div class="cert-badge">
            <div class="cb-icon"><i class="fas fa-percentage"></i></div>
            <div class="cb-val">86<span>%</span></div>
            <div class="cb-name">انعكاس الشمس</div>
          </div>
        </div>

        <div style="margin-top:28px;background:linear-gradient(135deg,var(--navy),#0D3260);border-radius:16px;padding:28px;color:white">
          <div style="font-size:0.75rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--sky);margin-bottom:12px">أسطح متوافقة</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
            <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;color:rgba(255,255,255,0.7)"><i class="fas fa-check" style="color:var(--sky)"></i> خرسانة وإسمنتي</div>
            <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;color:rgba(255,255,255,0.7)"><i class="fas fa-check" style="color:var(--sky)"></i> فولاذ ومجلفن</div>
            <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;color:rgba(255,255,255,0.7)"><i class="fas fa-check" style="color:var(--sky)"></i> بولي يوريثان فوم</div>
            <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;color:rgba(255,255,255,0.7)"><i class="fas fa-check" style="color:var(--sky)"></i> بيتومين</div>
            <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;color:rgba(255,255,255,0.7)"><i class="fas fa-check" style="color:var(--sky)"></i> بلاط إسمنتي</div>
            <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;color:rgba(255,255,255,0.7)"><i class="fas fa-check" style="color:var(--sky)"></i> أسطح الهناجر</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══ PRICING ══ -->
<section class="section section-light" id="pricing">
  <div class="container">
    <div style="text-align:center;margin-bottom:56px" class="reveal">
      <div class="section-label" style="justify-content:center">جدول الأسعار 2026</div>
      <h2 class="section-title">اختر الباقة المناسبة</h2>
      <p style="font-size:1rem;color:var(--sub);margin-top:8px">الأسعار شاملة المواد والعمالة والضمان</p>
    </div>
    <div class="pricing-grid">

      <!-- Tier 1 -->
      <div class="price-card reveal">
        <div class="pc-header">
          <div class="pc-layers">2 طبقات</div>
          <div class="pc-title">الباقة الأساسية</div>
          <div class="pc-isolation">عزل حراري 40%</div>
        </div>
        <div class="pc-price">
          <div class="pc-price-num"><span class="pc-currency">AED</span>60</div>
          <div class="pc-unit">لكل متر مربع</div>
        </div>
        <div class="pc-features">
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> تنظيف وتحضير السطح</div>
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> طبقة Primer 75µ</div>
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> طبقتان Cool Roof</div>
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> ضمان 5 سنوات</div>
          <div class="pc-feature" style="color:var(--mist)"><i class="fas fa-times" style="color:var(--mist)"></i> Fiber Mesh</div>
          <div class="pc-feature" style="color:var(--mist)"><i class="fas fa-times" style="color:var(--mist)"></i> Top Coat</div>
        </div>
        <a href="#contact" class="pc-cta pc-cta-outline">اطلب عرض سعر</a>
      </div>

      <!-- Tier 2 — FEATURED -->
      <div class="price-card pc-featured reveal reveal-delay-1">
        <div class="pc-badge">الأكثر طلباً</div>
        <div class="pc-header">
          <div class="pc-layers">4 طبقات</div>
          <div class="pc-title">الباقة الاحترافية</div>
          <div class="pc-isolation">عزل حراري 55%</div>
        </div>
        <div class="pc-price">
          <div class="pc-price-num"><span class="pc-currency">AED</span>110</div>
          <div class="pc-unit">لكل متر مربع</div>
        </div>
        <div class="pc-features">
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> تنظيف وتحضير السطح</div>
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> إصلاح الشقوق بالإيبوكسي</div>
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> Fiber Mesh 93 جم/م²</div>
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> طبقة Primer 75µ</div>
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> 4 طبقات Cool Roof</div>
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> ضمان 10 سنوات</div>
        </div>
        <a href="#contact" class="pc-cta pc-cta-solid">اطلب عرض سعر</a>
      </div>

      <!-- Tier 3 -->
      <div class="price-card reveal reveal-delay-2">
        <div class="pc-header">
          <div class="pc-layers">6 طبقات</div>
          <div class="pc-title">الباقة المتكاملة</div>
          <div class="pc-isolation">عزل حراري 70%</div>
        </div>
        <div class="pc-price">
          <div class="pc-price-num"><span class="pc-currency">AED</span>160</div>
          <div class="pc-unit">لكل متر مربع</div>
        </div>
        <div class="pc-features">
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> التحضير الكامل</div>
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> Fiber Mesh مزدوج</div>
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> طبقة Primer مُعززة</div>
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> 6 طبقات Cool Roof</div>
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> Top Coat واقية</div>
          <div class="pc-feature"><i class="fas fa-check pc-feature-icon"></i> ضمان 10 سنوات شامل</div>
        </div>
        <a href="#contact" class="pc-cta pc-cta-outline">اطلب عرض سعر</a>
      </div>

    </div>

    <div class="pricing-note reveal">
      <i class="fas fa-info-circle pricing-note-icon"></i>
      <p>
        <strong>ملاحظة مهمة:</strong> <?= nl2br(e($doc['notes'] ?? '')) ?></div>
  </div>
</section>

<!-- ══ WARRANTY ══ -->
<section class="section warranty-section" id="warranty">
  <div class="container">
    <div class="warranty-grid">
      <div class="reveal">
        <div class="section-label">الضمان والالتزام</div>
        <h2 class="section-title" style="color:var(--snow)">نلتزم بجودة<br><span style="color:var(--sky)">لا تتنازل عنها</span></h2>
        <p class="section-desc" style="margin-bottom:40px">في ركن التطور، نؤمن بأن الثقة تُبنى بالالتزام. لذلك نقدم ضماناً شاملاً يغطي كل جانب من جوانب العمل.</p>
        <ul class="warranty-points">
          <li class="warranty-point">
            <div class="wpp-icon"><i class="fas fa-certificate"></i></div>
            <div class="wpp-text">
              <h4>ضمان شامل 10 سنوات</h4>
              <p>يشمل المواد والأداء والتطبيق — ضمان موثق رسمياً</p>
            </div>
          </li>
          <li class="warranty-point">
            <div class="wpp-icon"><i class="fas fa-search"></i></div>
            <div class="wpp-text">
              <h4>معاينة مجانية للسطح</h4>
              <p>فريقنا يزورك مجاناً لتقييم الوضع وتقديم أنسب توصية</p>
            </div>
          </li>
          <li class="warranty-point">
            <div class="wpp-icon"><i class="fas fa-percentage"></i></div>
            <div class="wpp-text">
              <h4>خصم 15% للعقود السريعة</h4>
              <p>وفر على مشروعك عند توقيع العقد خلال 7 أيام من المعاينة</p>
            </div>
          </li>
          <li class="warranty-point">
            <div class="wpp-icon"><i class="fas fa-users"></i></div>
            <div class="wpp-text">
              <h4>فريق مدرب ومعتمد</h4>
              <p>تقنيون متخصصون بخبرة تطبيق كول رووف في ظروف مناخ الإمارات</p>
            </div>
          </li>
        </ul>
      </div>
      <div class="reveal reveal-delay-2" style="display:flex;flex-direction:column;align-items:center;gap:40px">
        <div class="warranty-badge">
          <div>
            <div class="wb-num">10<span class="wb-unit">y</span></div>
            <div class="wb-label">ضمان شامل<br>على المواد والأداء</div>
          </div>
        </div>
        <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:16px;padding:28px;text-align:center;width:100%">
          <div style="font-size:0.75rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--sky);margin-bottom:16px">مناطق التغطية</div>
          <div style="display:flex;flex-direction:column;gap:10px">
            <div style="display:flex;align-items:center;gap:10px;font-size:0.85rem;color:rgba(255,255,255,0.7)">
              <i class="fas fa-map-marker-alt" style="color:var(--sky);width:16px"></i>مدينة خليفة أ — أبوظبي
            </div>
            <div style="display:flex;align-items:center;gap:10px;font-size:0.85rem;color:rgba(255,255,255,0.7)">
              <i class="fas fa-map-marker-alt" style="color:var(--sky);width:16px"></i>الشامخة — محمد بن زايد
            </div>
            <div style="display:flex;align-items:center;gap:10px;font-size:0.85rem;color:rgba(255,255,255,0.7)">
              <i class="fas fa-map-marker-alt" style="color:var(--sky);width:16px"></i>الريف — ديسكفري جاردنز
            </div>
            <div style="display:flex;align-items:center;gap:10px;font-size:0.85rem;color:rgba(255,255,255,0.7)">
              <i class="fas fa-map-marker-alt" style="color:var(--sky);width:16px"></i>جميع مناطق الإمارات
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══ CTA ══ -->
<section class="cta-section" id="contact">
  <h2 class="cta-title">احمِ سطحك اليوم<br>ووفّر في كل شهر</h2>
  <p class="cta-sub">احصل على معاينة مجانية وعرض سعر مخصص لسطحك</p>
  <div class="cta-actions">
    <a href="tel:+971123550866" class="btn-white"><i class="fas fa-phone"></i> اتصل الآن</a>
    <a href="https://wa.me/971123550866" class="btn-ghost-white"><i class="fab fa-whatsapp"></i> واتساب</a>
  </div>
  <div style="margin-top:40px;font-size:0.82rem;color:rgba(255,255,255,0.55);position:relative">
    <i class="fas fa-globe" style="margin-left:6px"></i>www.rukneltatawer.com &nbsp;|&nbsp;
    <i class="fas fa-at" style="margin-right:2px;margin-left:6px"></i>rukneltatawer
  </div>
</section>

<!-- ══ FOOTER ══ -->
<footer class="site-footer">
  <div class="footer-logo"><span>RUKN</span> ELTATAWER — COOL ROOF</div>
  <div class="footer-copy">© 2026 ركن التطور. جميع الحقوق محفوظة.</div>
  <div class="footer-socials">
    <a class="footer-social" href="#"><i class="fab fa-instagram"></i></a>
    <a class="footer-social" href="#"><i class="fab fa-facebook-f"></i></a>
    <a class="footer-social" href="#"><i class="fab fa-whatsapp"></i></a>
    <a class="footer-social" href="#"><i class="fab fa-tiktok"></i></a>
  </div>
</footer>

<!-- ══ PDF FAB ══ -->
<button class="pdf-fab" onclick="downloadPDF()">
  <i class="fas fa-file-pdf"></i>
  تحميل الكتالوج PDF
</button>

<!-- ══════════════════════════════════════════
     JAVASCRIPT
     ══════════════════════════════════════════ -->
<script>
/* --- Stars --- */
(function generateStars() {
  const c = document.getElementById('starsContainer');
  for (let i = 0; i < 120; i++) {
    const s = document.createElement('div');
    s.className = 'star';
    s.style.cssText = `
      top:${Math.random()*100}%;
      left:${Math.random()*100}%;
      --d:${2+Math.random()*4}s;
      --delay:${Math.random()*4}s;
      opacity:${0.1+Math.random()*0.6};
      width:${1+Math.random()*2}px;
      height:${1+Math.random()*2}px;
    `;
    c.appendChild(s);
  }
})();

/* --- Progress bar --- */
window.addEventListener('scroll', () => {
  const doc = document.documentElement;
  const pct = (doc.scrollTop / (doc.scrollHeight - doc.clientHeight)) * 100;
  document.getElementById('progress-bar').style.width = pct + '%';
});

/* --- Reveal on scroll --- */
const reveals = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => { if(e.isIntersecting) { e.target.classList.add('visible'); } });
}, { threshold: 0.12 });
reveals.forEach(r => observer.observe(r));

/* --- PDF Download --- */
function downloadPDF() {
  const btn = document.querySelector('.pdf-fab');
  const orig = btn.innerHTML;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحضير...';
  btn.disabled = true;

  setTimeout(() => {
    window.print();
    btn.innerHTML = orig;
    btn.disabled = false;
  }, 600);
}

/* --- Smooth scroll for nav links --- */
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const target = document.querySelector(a.getAttribute('href'));
    if (target) {
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});

/* --- Counter animation for hero stats --- */
function animateCounters() {
  document.querySelectorAll('.hero-stat-num').forEach(el => {
    const text = el.innerHTML;
    const num = parseFloat(text.replace(/<[^>]+>/g,''));
    if (isNaN(num)) return;
    const suffix = el.querySelector('span') ? el.querySelector('span').outerHTML : '';
    let start = 0;
    const step = num / 40;
    const timer = setInterval(() => {
      start += step;
      if (start >= num) { start = num; clearInterval(timer); }
      const display = num < 10 ? start.toFixed(2) : Math.floor(start);
      el.innerHTML = display + suffix;
    }, 40);
  });
}

/* Trigger counter when hero is visible */
new IntersectionObserver((entries) => {
  if (entries[0].isIntersecting) animateCounters();
}, { threshold: 0.5 }).observe(document.querySelector('.hero-stats'));
</script>

</body>
</html>
