<div class="panel">
  <form method="get" class="form-grid" style="margin-bottom:14px">
    <div><label>بحث</label><input type="text" name="q" value="<?= e($filters['q'] ?? '') ?>"></div>
    <div><label>الحالة</label>
      <select name="status">
        <option value="">الكل</option>
        <?php foreach ($statuses as $k=>$v): ?>
          <option value="<?= e($k) ?>" <?= ($filters['status']??'')===$k?'selected':'' ?>><?= e($v['ar']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div><label>من تاريخ</label><input type="date" name="date_from" value="<?= e($filters['date_from'] ?? '') ?>"></div>
    <div><label>إلى تاريخ</label><input type="date" name="date_to" value="<?= e($filters['date_to'] ?? '') ?>"></div>
    <div class="full actions">
      <button class="btn btn-ghost btn-sm">تصفية</button>
      <a class="btn btn-primary btn-sm" href="/admin/documents/create?type=<?= e($type ?: 'invoice') ?>">إنشاء <?= e($type ? doc_type_label($type) : 'مستند') ?></a>
    </div>
  </form>

  <div class="table-wrap">
  <table class="data">
    <thead><tr><th>الرقم</th><th>العنوان</th><th>العميل</th><th>الشركة</th><th>الإجمالي</th><th>الحالة</th><th>التاريخ</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($result['data'] as $r): ?>
      <tr>
        <td><a href="/<?= e($r['public_slug']) ?>" target="_blank"><strong><?= e($r['document_number']) ?></strong></a></td>
        <td><?= e($r['title']) ?></td>
        <td><?= e($r['customer_name'] ?? '—') ?></td>
        <td><?= e($r['company_name'] ?? '') ?></td>
        <td><?= e(number_format((float)$r['total'],2)) ?> <?= e($r['currency']) ?></td>
        <td><span class="badge"><?= e(status_label($r['status'])) ?></span></td>
        <td><?= e($r['issue_date']) ?></td>
        <td class="actions">
          <a class="btn btn-ghost btn-sm" href="/admin/documents/<?= (int)$r['id'] ?>">عرض</a>
          <a class="btn btn-ghost btn-sm" href="/admin/documents/<?= (int)$r['id'] ?>/edit">تعديل</a>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$result['data']): ?><tr><td colspan="8">لا توجد نتائج</td></tr><?php endif; ?>
    </tbody>
  </table>
  </div>
</div>
