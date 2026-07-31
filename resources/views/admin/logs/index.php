<div class="panel">
  <div class="table-wrap">
  <table class="data">
    <thead><tr><th>الوقت</th><th>المستخدم</th><th>الإجراء</th><th>الوصف</th><th>IP</th></tr></thead>
    <tbody>
    <?php foreach ($logs as $l): ?>
      <tr>
        <td><?= e($l['created_at']) ?></td>
        <td><?= e($l['user_name'] ?? '—') ?></td>
        <td><span class="badge"><?= e($l['action']) ?></span></td>
        <td><?= e($l['description'] ?? '') ?></td>
        <td><?= e($l['ip_address'] ?? '') ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</div>
