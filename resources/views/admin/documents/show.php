<div class="panel">
  <div class="actions" style="justify-content:space-between">
    <div>
      <h3 style="margin:0"><?= e($doc['document_number']) ?></h3>
      <div class="help"><?= e($doc['title']) ?> — <span class="badge"><?= e(status_label($doc['status'])) ?></span></div>
    </div>
    <div class="actions">
      <a class="btn btn-accent" href="/admin/documents/<?= (int)$doc['id'] ?>/preview" target="_blank"><i class="fas fa-print"></i> طباعة / PDF</a>
      <a class="btn btn-primary" href="/<?= e($doc['public_slug']) ?>" target="_blank">الرابط العام</a>
      <button class="btn btn-ghost" type="button" onclick="navigator.clipboard.writeText('<?= e($publicUrl) ?>');alert('تم نسخ الرابط')">نسخ الرابط</button>
      <a class="btn btn-ghost" href="https://wa.me/?text=<?= urlencode($doc['title'].' '.$publicUrl) ?>" target="_blank">واتساب</a>
      <a class="btn btn-ghost" href="mailto:?subject=<?= rawurlencode($doc['title']) ?>&body=<?= rawurlencode($publicUrl) ?>">بريد</a>
      <a class="btn btn-ghost" href="/admin/documents/<?= (int)$doc['id'] ?>/edit">تعديل</a>
    </div>
  </div>
</div>
<div class="panel" style="padding:0;overflow:hidden">
  <iframe src="/admin/documents/<?= (int)$doc['id'] ?>/preview" style="width:100%;min-height:80vh;border:0;background:#0A1628"></iframe>
</div>
