<?php $c = $customer ?? []; $isEdit=!empty($c); ?>
<form class="panel" method="post" action="<?= $isEdit?'/admin/customers/'.(int)$c['id']:'/admin/customers' ?>">
  <?= csrf_field() ?>
  <div class="form-grid">
    <?php if (\App\Core\Auth::isAdmin()): ?>
    <div><label>الشركة</label>
      <select name="company_id" required>
        <?php foreach ($companies as $co): ?>
          <option value="<?= (int)$co['id'] ?>" <?= (($c['company_id'] ?? '')==$co['id'])?'selected':'' ?>><?= e($co['name_ar']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php endif; ?>
    <div><label>اسم العميل عربي</label><input name="name_ar" value="<?= e($c['name_ar'] ?? '') ?>" required></div>
    <div><label>اسم العميل إنجليزي</label><input name="name_en" value="<?= e($c['name_en'] ?? '') ?>"></div>
    <div><label>البريد</label><input type="email" name="email" value="<?= e($c['email'] ?? '') ?>"></div>
    <div><label>الهاتف</label><input name="phone" value="<?= e($c['phone'] ?? '') ?>"></div>
    <div><label>واتساب</label><input name="whatsapp" value="<?= e($c['whatsapp'] ?? '') ?>"></div>
    <div><label>المدينة</label><input name="city" value="<?= e($c['city'] ?? '') ?>"></div>
    <div><label>الدولة</label><input name="country" value="<?= e($c['country'] ?? '') ?>"></div>
    <div><label>الرقم الضريبي</label><input name="tax_number" value="<?= e($c['tax_number'] ?? '') ?>"></div>
    <div class="full"><label>العنوان عربي</label><textarea name="address_ar"><?= e($c['address_ar'] ?? '') ?></textarea></div>
    <div class="full"><label>العنوان إنجليزي</label><textarea name="address_en"><?= e($c['address_en'] ?? '') ?></textarea></div>
    <div class="full"><label>ملاحظات</label><textarea name="notes"><?= e($c['notes'] ?? '') ?></textarea></div>
  </div>
  <div class="actions" style="margin-top:16px">
    <button class="btn btn-primary">حفظ</button>
    <a class="btn btn-ghost" href="/admin/customers">رجوع</a>
  </div>
</form>
