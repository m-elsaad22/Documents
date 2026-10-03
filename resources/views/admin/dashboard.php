<div class="grid-stats">
  <div class="stat"><div class="label">الشركات</div><div class="value"><?= (int)$stats['companies'] ?></div></div>
  <div class="stat"><div class="label">العملاء</div><div class="value"><?= (int)$stats['customers'] ?></div></div>
  <div class="stat"><div class="label">المستندات</div><div class="value"><?= (int)$stats['documents'] ?></div></div>
  <div class="stat"><div class="label">الفواتير</div><div class="value"><?= (int)$stats['invoices'] ?></div></div>
  <div class="stat"><div class="label">عروض الأسعار</div><div class="value"><?= (int)$stats['quotations'] ?></div></div>
  <div class="stat"><div class="label">العقود</div><div class="value"><?= (int)$stats['contracts'] ?></div></div>
</div>

<div class="panel">
  <div class="actions" style="justify-content:space-between;margin-bottom:10px">
    <h3 style="margin:0">أحدث المستندات</h3>
    <a class="btn btn-primary btn-sm" href="/admin/documents/create?type=invoice">إنشاء فاتورة</a>
  </div>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>الرقم</th><th>النوع</th><th>العنوان</th><th>العميل</th><th>الحالة</th><th>التاريخ</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($recent as $r): ?>
        <tr>
          <td><strong><?= e($r['document_number']) ?></strong></td>
          <td><?= e(doc_type_label($r['document_type'])) ?></td>
          <td><?= e($r['title']) ?></td>
          <td><?= e($r['customer_name'] ?? '—') ?></td>
          <td><span class="badge"><?= e(status_label($r['status'])) ?></span></td>
          <td><?= e($r['issue_date']) ?></td>
          <td><a class="btn btn-ghost btn-sm" href="/admin/documents/<?= (int)$r['id'] ?>">عرض</a></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$recent): ?><tr><td colspan="7">لا توجد مستندات بعد</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
