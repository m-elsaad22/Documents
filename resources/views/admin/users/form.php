<?php $u=$user??[]; $isEdit=!empty($u); ?>
<form class="panel" method="post" action="<?= $isEdit?'/admin/users/'.(int)$u['id']:'/admin/users' ?>">
  <?= csrf_field() ?>
  <div class="form-grid">
    <div><label>الاسم</label><input name="name" value="<?= e($u['name'] ?? '') ?>" required></div>
    <div><label>البريد</label><input type="email" name="email" value="<?= e($u['email'] ?? '') ?>" required></div>
    <div><label>كلمة المرور<?= $isEdit?' (اتركها فارغة للإبقاء)':'' ?></label><input type="password" name="password" <?= $isEdit?'':'required' ?>></div>
    <div><label>الهاتف</label><input name="phone" value="<?= e($u['phone'] ?? '') ?>"></div>
    <div><label>الدور</label>
      <select name="role">
        <?php foreach ($roles as $k=>$v): if(!\App\Core\Auth::isAdmin() && $k==='admin') continue; ?>
          <option value="<?= e($k) ?>" <?= ($u['role'] ?? '')===$k?'selected':'' ?>><?= e($v['ar']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php if (\App\Core\Auth::isAdmin()): ?>
    <div><label>الشركة</label>
      <select name="company_id">
        <option value="">— عامة / Admin —</option>
        <?php foreach ($companies as $co): ?>
          <option value="<?= (int)$co['id'] ?>" <?= (($u['company_id'] ?? '')==$co['id'])?'selected':'' ?>><?= e($co['name_ar']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php endif; ?>
    <div><label><input type="checkbox" name="is_active" value="1" <?= !isset($u['is_active']) || !empty($u['is_active'])?'checked':'' ?>> نشط</label></div>
  </div>
  <div class="actions" style="margin-top:16px">
    <button class="btn btn-primary">حفظ</button>
    <a class="btn btn-ghost" href="/admin/users">رجوع</a>
  </div>
</form>
