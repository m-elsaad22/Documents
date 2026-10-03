<?php
$d = $doc ?? [];
$isEdit = !empty($d);
$action = $isEdit ? '/admin/documents/'.(int)$d['id'] : '/admin/documents';
?>
<form class="panel" method="post" action="<?= $action ?>" enctype="multipart/form-data" id="docForm">
  <?= csrf_field() ?>
  <input type="hidden" name="document_type" value="<?= e($type) ?>">
  <h3>بيانات المستند — <?= e(doc_type_label($type)) ?></h3>
  <div class="form-grid">
    <?php if (\App\Core\Auth::isAdmin()): ?>
    <div><label>الشركة</label>
      <select name="company_id" required>
        <?php foreach ($companies as $co): ?>
        <option value="<?= (int)$co['id'] ?>" <?= (($d['company_id'] ?? \App\Core\Auth::companyId())==$co['id'])?'selected':'' ?>><?= e($co['name_ar']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php endif; ?>
    <div><label>العميل</label>
      <select name="customer_id">
        <option value="">— بدون —</option>
        <?php foreach ($customers as $cu): ?>
        <option value="<?= (int)$cu['id'] ?>" <?= (($d['customer_id'] ?? '')==$cu['id'])?'selected':'' ?>><?= e($cu['name_ar']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div><label>اللغة</label>
      <select name="language">
        <option value="ar" <?= ($d['language'] ?? 'ar')==='ar'?'selected':'' ?>>العربية</option>
        <option value="en" <?= ($d['language'] ?? '')==='en'?'selected':'' ?>>English</option>
      </select>
    </div>
    <div><label>القالب</label>
      <select name="template_id">
        <?php foreach ($templates as $t): ?>
        <option value="<?= (int)$t['id'] ?>" <?= (($d['template_id'] ?? '')==$t['id'] || (!isset($d['template_id']) && $t['is_default']))?'selected':'' ?>>
          <?= e($t['name']) ?> (<?= e($t['language']) ?>)<?= $t['is_default']?' ★':'' ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="full"><label>عنوان المستند</label><input type="text" name="title" value="<?= e($d['title'] ?? '') ?>" required></div>
    <div><label>التاريخ</label><input type="date" name="issue_date" value="<?= e($d['issue_date'] ?? date('Y-m-d')) ?>"></div>
    <div><label>تاريخ الاستحقاق / الصلاحية</label><input type="date" name="due_date" value="<?= e($d['due_date'] ?? '') ?>"></div>
    <div><label>صالح حتى</label><input type="date" name="valid_until" value="<?= e($d['valid_until'] ?? '') ?>"></div>
    <div><label>الحالة</label>
      <select name="status">
        <?php foreach ($statuses as $k=>$v): ?>
        <option value="<?= e($k) ?>" <?= ($d['status'] ?? 'draft')===$k?'selected':'' ?>><?= e($v['ar']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div><label>العملة</label><input type="text" name="currency" value="<?= e($d['currency'] ?? 'AED') ?>"></div>
    <div><label>الخصم</label><input type="number" step="0.01" name="discount" value="<?= e($d['discount'] ?? 0) ?>"></div>
    <div><label>نسبة الضريبة %</label><input type="number" step="0.01" name="tax_rate" value="<?= e($d['tax_rate'] ?? 0) ?>"></div>
    <div><label>المبلغ المدفوع</label><input type="number" step="0.01" name="amount_paid" value="<?= e($d['amount_paid'] ?? 0) ?>"></div>
    <div><label>طريقة الدفع</label><input type="text" name="payment_method" value="<?= e($d['payment_method'] ?? '') ?>"></div>
    <div class="full"><label>عنوان المشروع</label><input type="text" name="project_address" value="<?= e($d['project_address'] ?? '') ?>"></div>
    <div><label>المبلغ كتابةً (عربي)</label><input type="text" name="amount_words_ar" value="<?= e($d['amount_words_ar'] ?? '') ?>"></div>
    <div><label>Amount in words (EN)</label><input type="text" name="amount_words_en" value="<?= e($d['amount_words_en'] ?? '') ?>"></div>
  </div>

  <h3 style="margin-top:22px">البنود</h3>
  <div class="items-builder" id="items">
    <?php
      $rows = $items ?: [['title'=>'','description'=>'','quantity'=>1,'unit'=>'','unit_price'=>0]];
      foreach ($rows as $item):
    ?>
    <div class="item-row">
      <input type="text" name="item_title[]" placeholder="البند" value="<?= e($item['title'] ?? '') ?>">
      <input type="text" name="item_description[]" placeholder="الوصف" value="<?= e($item['description'] ?? '') ?>">
      <input type="number" step="0.01" name="item_qty[]" placeholder="كمية" value="<?= e($item['quantity'] ?? 1) ?>">
      <input type="text" name="item_unit[]" placeholder="وحدة" value="<?= e($item['unit'] ?? '') ?>">
      <input type="number" step="0.01" name="item_price[]" placeholder="السعر" value="<?= e($item['unit_price'] ?? 0) ?>">
      <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">×</button>
    </div>
    <?php endforeach; ?>
  </div>
  <button type="button" class="btn btn-ghost btn-sm" onclick="addItem()">+ بند</button>

  <h3 style="margin-top:22px">صور / مرفقات</h3>
  <div class="form-grid">
    <div class="full"><label>رفع صور (غير محدود)</label><input type="file" name="media[]" accept="image/*" multiple></div>
    <?php if (!empty($media)): foreach ($media as $m): if(!empty($m['file_path'])): ?>
      <div><img src="<?= e(upload_url($m['file_path'])) ?>" style="max-width:100%;height:100px;object-fit:cover;border-radius:8px"></div>
    <?php endif; endforeach; endif; ?>
  </div>

  <h3 style="margin-top:22px">شروط وملاحظات</h3>
  <div class="form-grid">
    <div class="full"><label>ملاحظات</label><textarea name="notes"><?= e($d['notes'] ?? '') ?></textarea></div>
    <div class="full"><label>الشروط</label><textarea name="terms"><?= e($d['terms'] ?? '') ?></textarea></div>
    <div class="full"><label>الشروط الإضافية / الجداول</label><textarea name="conditions" placeholder="يمكن وضع جداول HTML أو نص"><?= e($d['conditions'] ?? '') ?></textarea></div>
    <div class="full">
      <label>حقول إضافية (JSON) — للتقارير والعقود المتقدمة</label>
      <?php
        $cf = $d['custom_fields'] ?? '';
        if (is_array($cf)) { $cf = json_encode($cf, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); }
      ?>
      <textarea name="custom_fields_json" placeholder='{"site_area":"120","layers":"3","warranty_years":"10"}'><?= e($cf) ?></textarea>
      <div class="help">تُمرَّر إلى القالب عبر متغير <code>$custom</code> مع الحفاظ على تصميم القالب الرسمي.</div>
    </div>
    <div><label><input type="checkbox" name="show_signature" value="1" <?= !isset($d['show_signature']) || !empty($d['show_signature'])?'checked':'' ?>> إظهار التوقيع</label></div>
    <div><label><input type="checkbox" name="show_seal" value="1" <?= !isset($d['show_seal']) || !empty($d['show_seal'])?'checked':'' ?>> إظهار الختم</label></div>
  </div>

  <div class="actions" style="margin-top:18px">
    <button class="btn btn-primary" type="submit">حفظ المستند</button>
    <?php if ($isEdit): ?><a class="btn btn-accent" target="_blank" href="/admin/documents/<?= (int)$d['id'] ?>/preview">معاينة PDF</a><?php endif; ?>
    <a class="btn btn-ghost" href="/admin/documents">رجوع</a>
  </div>
</form>
<script>
function addItem(){
  const wrap=document.getElementById('items');
  const div=document.createElement('div');
  div.className='item-row';
  div.innerHTML=`<input type="text" name="item_title[]" placeholder="البند"><input type="text" name="item_description[]" placeholder="الوصف"><input type="number" step="0.01" name="item_qty[]" value="1" placeholder="كمية"><input type="text" name="item_unit[]" placeholder="وحدة"><input type="number" step="0.01" name="item_price[]" value="0" placeholder="السعر"><button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">×</button>`;
  wrap.appendChild(div);
}
</script>
