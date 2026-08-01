<form class="panel" method="post" action="/admin/settings">
  <?= csrf_field() ?>
  <div class="form-grid">
    <div><label>اسم النظام</label><input name="system_name" value="<?= e($settings['system_name'] ?? 'KDMS') ?>"></div>
    <div><label>بريد الدعم</label><input name="support_email" value="<?= e($settings['support_email'] ?? '') ?>"></div>
    <div><label>العملة الافتراضية</label><input name="default_currency" value="<?= e($settings['default_currency'] ?? 'AED') ?>"></div>
  </div>
  <p class="help" style="margin-top:12px">لإعدادات الشركة (شعار، ختم، توقيع، ألوان...) استخدم صفحة الشركة من قائمة الشركات.</p>
  <div class="actions" style="margin-top:16px"><button class="btn btn-primary">حفظ</button></div>
</form>
