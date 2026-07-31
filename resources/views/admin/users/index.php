<div class="panel">
  <div class="actions" style="justify-content:space-between;margin-bottom:12px">
    <h3 style="margin:0">المستخدمون</h3>
    <a class="btn btn-primary" href="/admin/users/create">مستخدم جديد</a>
  </div>
  <div class="table-wrap">
  <table class="data">
    <thead><tr><th>الاسم</th><th>البريد</th><th>الدور</th><th>الشركة</th><th>الحالة</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?= e($u['name']) ?></td>
        <td><?= e($u['email']) ?></td>
        <td><?= e($roles[$u['role']]['ar'] ?? $u['role']) ?></td>
        <td><?= e($u['company_name'] ?? '—') ?></td>
        <td><?= $u['is_active']?'<span class="badge ok">نشط</span>':'<span class="badge danger">معطل</span>' ?></td>
        <td><a class="btn btn-ghost btn-sm" href="/admin/users/<?= (int)$u['id'] ?>/edit">تعديل</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</div>
