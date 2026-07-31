<div class="panel">
  <div class="actions" style="justify-content:space-between;margin-bottom:12px">
    <h3 style="margin:0">إدارة القوالب</h3>
    <a class="btn btn-primary" href="/admin/templates/create">رفع قالب</a>
  </div>
  <div class="table-wrap">
  <table class="data">
    <thead><tr><th>الاسم</th><th>النوع</th><th>اللغة</th><th>الشركة</th><th>الحالة</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($templates as $t): ?>
      <tr>
        <td><strong><?= e($t['name']) ?></strong><?= $t['is_default']?' <span class="badge ok">افتراضي</span>':'' ?></td>
        <td><?= e(doc_type_label($t['document_type'])) ?></td>
        <td><?= e($t['language']) ?></td>
        <td><?= e($t['company_name'] ?? 'عام') ?></td>
        <td><?= $t['is_active']?'<span class="badge ok">مفعّل</span>':'<span class="badge danger">معطّل</span>' ?></td>
        <td class="actions">
          <form method="post" action="/admin/templates/<?= (int)$t['id'] ?>/toggle"><?= csrf_field() ?><button class="btn btn-ghost btn-sm"><?= $t['is_active']?'تعطيل':'تفعيل' ?></button></form>
          <form method="post" action="/admin/templates/<?= (int)$t['id'] ?>/default"><?= csrf_field() ?><button class="btn btn-ghost btn-sm">افتراضي</button></form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</div>
