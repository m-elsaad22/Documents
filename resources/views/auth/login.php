<h1>تسجيل الدخول</h1>
<p>أدخل بياناتك للوصول إلى لوحة التحكم</p>
<form method="post" action="/login">
  <?= csrf_field() ?>
  <label>البريد الإلكتروني</label>
  <input type="email" name="email" value="<?= e(old('email','admin@kdms.local')) ?>" required>
  <label>كلمة المرور</label>
  <input type="password" name="password" value="admin123" required>
  <button type="submit">دخول</button>
</form>
