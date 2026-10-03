<?php $c = $company ?? []; $isEdit = !empty($c); ?>
<form method="post" enctype="multipart/form-data" action="<?= $isEdit ? '/admin/companies/'.(int)$c['id'] : '/admin/companies' ?>" class="panel">
  <?= csrf_field() ?>
  <h3><?= $isEdit ? 'إعدادات الشركة' : 'إضافة شركة' ?></h3>
  <div class="form-grid">
    <div><label>اسم الشركة (عربي)</label><input type="text" name="name_ar" value="<?= e($c['name_ar'] ?? '') ?>" required></div>
    <div><label>اسم الشركة (إنجليزي)</label><input type="text" name="name_en" value="<?= e($c['name_en'] ?? '') ?>"></div>
    <div><label>المعرّف (slug)</label><input type="text" name="slug" value="<?= e($c['slug'] ?? '') ?>"></div>
    <div><label>المدينة</label><input type="text" name="city" value="<?= e($c['city'] ?? '') ?>"></div>
    <div><label>الدولة</label><input type="text" name="country" value="<?= e($c['country'] ?? 'UAE') ?>"></div>
    <div><label>البريد</label><input type="email" name="email" value="<?= e($c['email'] ?? '') ?>"></div>
    <div><label>الموقع</label><input type="url" name="website" value="<?= e($c['website'] ?? '') ?>"></div>
    <div><label>الهاتف</label><input type="text" name="phone" value="<?= e($c['phone'] ?? '') ?>"></div>
    <div><label>واتساب</label><input type="text" name="whatsapp" value="<?= e($c['whatsapp'] ?? '') ?>"></div>
    <div><label>الرقم الضريبي</label><input type="text" name="tax_number" value="<?= e($c['tax_number'] ?? '') ?>"></div>
    <div><label>السجل التجاري</label><input type="text" name="cr_number" value="<?= e($c['cr_number'] ?? '') ?>"></div>
    <div><label>العملة</label><input type="text" name="currency" value="<?= e($c['currency'] ?? 'AED') ?>"></div>
    <div><label>تسمية العملة عربي</label><input type="text" name="currency_label_ar" value="<?= e($c['currency_label_ar'] ?? 'درهم إماراتي') ?>"></div>
    <div><label>تسمية العملة إنجليزي</label><input type="text" name="currency_label_en" value="<?= e($c['currency_label_en'] ?? 'UAE Dirham') ?>"></div>
    <div><label>اللغة الافتراضية</label>
      <select name="default_lang">
        <option value="ar" <?= ($c['default_lang'] ?? '')==='ar'?'selected':'' ?>>العربية</option>
        <option value="en" <?= ($c['default_lang'] ?? '')==='en'?'selected':'' ?>>English</option>
      </select>
    </div>
    <div><label>اللون الأساسي</label><input type="color" name="primary_color" value="<?= e($c['primary_color'] ?? '#003087') ?>"></div>
    <div><label>لون ثانوي</label><input type="color" name="secondary_color" value="<?= e($c['secondary_color'] ?? '#D4A017') ?>"></div>
    <div><label>لون مميز</label><input type="color" name="accent_color" value="<?= e($c['accent_color'] ?? '#0070CC') ?>"></div>
    <div class="full"><label>العنوان عربي</label><textarea name="address_ar"><?= e($c['address_ar'] ?? '') ?></textarea></div>
    <div class="full"><label>العنوان إنجليزي</label><textarea name="address_en"><?= e($c['address_en'] ?? '') ?></textarea></div>

    <div><label>الشعار</label><input type="file" name="logo" accept="image/*"><?php if(!empty($c['logo'])): ?><img src="<?= e(upload_url($c['logo'])) ?>" style="height:48px;margin-top:8px"><?php endif; ?></div>
    <div><label>الختم</label><input type="file" name="seal" accept="image/*"><?php if(!empty($c['seal'])): ?><img src="<?= e(upload_url($c['seal'])) ?>" style="height:48px;margin-top:8px"><?php endif; ?></div>
    <div><label>التوقيع</label><input type="file" name="signature" accept="image/*"><?php if(!empty($c['signature'])): ?><img src="<?= e(upload_url($c['signature'])) ?>" style="height:48px;margin-top:8px"><?php endif; ?></div>
    <div><label>صورة Header</label><input type="file" name="header_image" accept="image/*"></div>
    <div><label>صورة Footer</label><input type="file" name="footer_image" accept="image/*"></div>
    <div><label>QR Code</label><input type="file" name="qr_code" accept="image/*"></div>

    <div><label>Facebook</label><input type="url" name="social_facebook" value="<?= e($c['social_facebook'] ?? '') ?>"></div>
    <div><label>Instagram</label><input type="url" name="social_instagram" value="<?= e($c['social_instagram'] ?? '') ?>"></div>
    <div><label>Twitter / X</label><input type="url" name="social_twitter" value="<?= e($c['social_twitter'] ?? '') ?>"></div>
    <div><label>LinkedIn</label><input type="url" name="social_linkedin" value="<?= e($c['social_linkedin'] ?? '') ?>"></div>
    <div><label>YouTube</label><input type="url" name="social_youtube" value="<?= e($c['social_youtube'] ?? '') ?>"></div>
    <div><label><input type="checkbox" name="is_active" value="1" <?= !isset($c['is_active']) || !empty($c['is_active'])?'checked':'' ?>> نشط</label></div>
  </div>

  <h3 style="margin-top:22px">الفروع / المكاتب</h3>
  <div id="offices">
    <?php
      $offices = [];
      if (!empty($c['offices_json'])) $offices = json_decode($c['offices_json'], true) ?: [];
      if (!$offices) $offices = [['city_ar'=>'','address_ar'=>'']];
      foreach ($offices as $o):
    ?>
    <div class="form-grid" style="margin-bottom:8px">
      <div><label>المدينة</label><input type="text" name="office_city[]" value="<?= e($o['city_ar'] ?? '') ?>"></div>
      <div><label>العنوان</label><input type="text" name="office_address[]" value="<?= e($o['address_ar'] ?? '') ?>"></div>
    </div>
    <?php endforeach; ?>
  </div>
  <button type="button" class="btn btn-ghost btn-sm" onclick="addOffice()">+ مكتب</button>

  <div class="actions" style="margin-top:18px">
    <button class="btn btn-primary" type="submit">حفظ</button>
    <a class="btn btn-ghost" href="/admin/companies">رجوع</a>
  </div>
</form>
<script>
function addOffice(){
  const wrap=document.getElementById('offices');
  const div=document.createElement('div');
  div.className='form-grid';
  div.style.marginBottom='8px';
  div.innerHTML=`<div><label>المدينة</label><input type="text" name="office_city[]"></div><div><label>العنوان</label><input type="text" name="office_address[]"></div>`;
  wrap.appendChild(div);
}
</script>
