<div class="panel">
  <div class="actions" style="justify-content:space-between;margin-bottom:12px">
    <h3 style="margin:0">النسخ الاحتياطي</h3>
    <form method="post" action="/admin/backups"><?= csrf_field() ?><button class="btn btn-primary">إنشاء نسخة الآن</button></form>
  </div>
  <div class="table-wrap">
  <table class="data">
    <thead><tr><th>الملف</th><th>الحجم</th><th>التاريخ</th></tr></thead>
    <tbody>
    <?php foreach ($backups as $b): ?>
      <tr>
        <td><?= e($b['file_path']) ?></td>
        <td><?= number_format(((int)$b['file_size'])/1024,1) ?> KB</td>
        <td><?= e($b['created_at']) ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$backups): ?><tr><td colspan="3">لا توجد نسخ بعد</td></tr><?php endif; ?>
    </tbody>
  </table>
  </div>
</div>
