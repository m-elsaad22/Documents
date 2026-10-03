<form class="panel" method="post" action="/admin/templates" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <div class="form-grid">
    <div><label>اسم القالب</label><input name="name" required></div>
    <div><label>Slug</label><input name="slug"></div>
    <div><label>نوع المستند</label>
      <select name="document_type" required>
        <?php foreach (['invoice','quotation','contract','receipt','report','warranty'] as $t): ?>
          <option value="<?= $t ?>"><?= e(doc_type_label($t)) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div><label>اللغة</label>
      <select name="language"><option value="ar">العربية</option><option value="en">English</option></select>
    </div>
    <div class="full"><label>ملف القالب (HTML/PHP)</label><input type="file" name="template_file" accept=".html,.htm,.php" required></div>
    <div class="full"><label>الوصف</label><textarea name="description"></textarea></div>
    <div><label><input type="checkbox" name="is_active" value="1" checked> مفعّل</label></div>
    <div><label><input type="checkbox" name="is_default" value="1"> افتراضي لهذا النوع/اللغة</label></div>
  </div>
  <div class="actions" style="margin-top:16px">
    <button class="btn btn-primary">حفظ</button>
    <a class="btn btn-ghost" href="/admin/templates">رجوع</a>
  </div>
</form>
