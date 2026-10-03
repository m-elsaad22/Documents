<div class="panel">
  <div class="actions" style="justify-content:space-between;margin-bottom:12px">
    <form method="get" class="actions">
      <input type="text" name="q" value="<?= e($q ?? '') ?>" placeholder="بحث عن عميل..." style="min-width:220px">
      <button class="btn btn-ghost btn-sm">بحث</button>
    </form>
    <a class="btn btn-primary" href="/admin/customers/create">عميل جديد</a>
  </div>
  <div class="table-wrap">
  <table class="data">
    <thead><tr><th>الاسم</th><th>الشركة</th><th>الهاتف</th><th>البريد</th><th>المدينة</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($customers as $c): ?>
      <tr>
        <td><strong><?= e($c['name_ar']) ?></strong></td>
        <td><?= e($c['company_name'] ?? '') ?></td>
        <td><?= e($c['phone'] ?? '') ?></td>
        <td><?= e($c['email'] ?? '') ?></td>
        <td><?= e($c['city'] ?? '') ?></td>
        <td><a class="btn btn-ghost btn-sm" href="/admin/customers/<?= (int)$c['id'] ?>/edit">تعديل</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</div>
