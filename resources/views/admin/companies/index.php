<div class="panel">
  <div class="actions" style="justify-content:space-between;margin-bottom:12px">
    <h3 style="margin:0">قائمة الشركات</h3>
    <?php if (\App\Core\Auth::isAdmin()): ?>
    <a class="btn btn-primary" href="/admin/companies/create"><i class="fas fa-plus"></i> شركة جديدة</a>
    <?php endif; ?>
  </div>
  <div class="table-wrap">
  <table class="data">
    <thead><tr><th>الاسم</th><th>المدينة</th><th>الهاتف</th><th>العملة</th><th>الحالة</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($companies as $c): ?>
      <tr>
        <td><strong><?= e($c['name_ar']) ?></strong><div class="help"><?= e($c['name_en'] ?? '') ?></div></td>
        <td><?= e($c['city'] ?? '') ?></td>
        <td><?= e($c['phone'] ?? '') ?></td>
        <td><?= e($c['currency'] ?? '') ?></td>
        <td><?= !empty($c['is_active']) ? '<span class="badge ok">نشط</span>' : '<span class="badge danger">معطل</span>' ?></td>
        <td><a class="btn btn-ghost btn-sm" href="/admin/companies/<?= (int)$c['id'] ?>/edit">إعدادات</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</div>
