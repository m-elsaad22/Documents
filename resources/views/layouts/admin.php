<?php
$user = auth_user();
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
function nav_active(string $path): string {
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
    return str_starts_with($uri, $path) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title ?? 'KDMS') ?> | KDMS</title>
<?= \App\Core\Csrf::meta() ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body>
<div class="mobile-bar no-print">
  <div class="brand-mini"><span>KD</span> KDMS</div>
  <button type="button" class="menu-toggle" id="menuToggle" aria-label="القائمة"><i class="fas fa-bars"></i> القائمة</button>
</div>
<div class="app">
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div class="brand-mark">KD</div>
      <div>
        <h1>KDMS</h1>
        <small>Kayan Documents</small>
      </div>
    </div>
    <nav class="nav">
      <div class="nav-section">الرئيسية</div>
      <a class="<?= nav_active('/admin') === 'active' && $uri === '/admin' ? 'active' : '' ?>" href="/admin"><i class="fas fa-gauge"></i> لوحة التحكم</a>

      <div class="nav-section">الإدارة</div>
      <a class="<?= nav_active('/admin/companies') ?>" href="/admin/companies"><i class="fas fa-building"></i> الشركات</a>
      <a class="<?= nav_active('/admin/customers') ?>" href="/admin/customers"><i class="fas fa-users"></i> العملاء</a>

      <div class="nav-section">المستندات</div>
      <a class="<?= nav_active('/admin/invoices') ?>" href="/admin/invoices"><i class="fas fa-file-invoice"></i> الفواتير</a>
      <a class="<?= nav_active('/admin/quotations') ?>" href="/admin/quotations"><i class="fas fa-file-lines"></i> عروض الأسعار</a>
      <a class="<?= nav_active('/admin/contracts') ?>" href="/admin/contracts"><i class="fas fa-file-contract"></i> العقود</a>
      <a class="<?= nav_active('/admin/receipts') ?>" href="/admin/receipts"><i class="fas fa-receipt"></i> سندات القبض</a>
      <a class="<?= nav_active('/admin/reports') ?>" href="/admin/reports"><i class="fas fa-clipboard-list"></i> التقارير</a>
      <a class="<?= nav_active('/admin/warranty') ?>" href="/admin/warranty"><i class="fas fa-certificate"></i> شهادات الضمان</a>

      <div class="nav-section">النظام</div>
      <a class="<?= nav_active('/admin/templates') ?>" href="/admin/templates"><i class="fas fa-layer-group"></i> القوالب</a>
      <a class="<?= nav_active('/admin/settings') ?>" href="/admin/settings"><i class="fas fa-gear"></i> الإعدادات</a>
      <a class="<?= nav_active('/admin/users') ?>" href="/admin/users"><i class="fas fa-user-shield"></i> المستخدمون</a>
      <a class="<?= nav_active('/admin/logs') ?>" href="/admin/logs"><i class="fas fa-clock-rotate-left"></i> السجلات</a>
      <a class="<?= nav_active('/admin/backups') ?>" href="/admin/backups"><i class="fas fa-database"></i> النسخ الاحتياطي</a>
      <a href="/logout"><i class="fas fa-right-from-bracket"></i> خروج</a>
    </nav>
  </aside>

  <main class="main">
    <div class="topbar">
      <div>
        <h2><?= e($title ?? '') ?></h2>
        <div class="meta"><?= e($user['name'] ?? '') ?> — <?= e(config('roles')[$user['role'] ?? '']['ar'] ?? '') ?></div>
      </div>
      <form class="search-box" action="/admin/documents/search" method="get">
        <i class="fas fa-magnifying-glass" style="opacity:.5"></i>
        <input type="text" name="q" placeholder="بحث سريع في المستندات..." value="<?= e(input('q','')) ?>">
      </form>
      <a class="btn btn-accent" href="/admin/documents/create"><i class="fas fa-plus"></i> مستند جديد</a>
    </div>

    <?php if ($msg = flash('success')): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
    <?php if ($msg = flash('error')): ?><div class="alert alert-error"><?= e($msg) ?></div><?php endif; ?>

    <?= $content ?>
  </main>
</div>
<script src="<?= e(asset('js/admin.js')) ?>"></script>
</body>
</html>
