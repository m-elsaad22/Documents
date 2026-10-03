<h1>تثبيت KDMS</h1>
<p>إعداد قاعدة البيانات لأول مرة</p>
<form method="post" action="/install">
  <label>رابط الموقع</label>
  <input type="url" name="app_url" value="http://localhost:8080" required>
  <label>نوع قاعدة البيانات</label>
  <select name="db_driver" id="db_driver" onchange="document.getElementById('mysql').style.display=this.value==='mysql'?'block':'none'">
    <option value="sqlite">SQLite (تجريبي سريع)</option>
    <option value="mysql">MySQL (cPanel)</option>
  </select>
  <div id="mysql" style="display:none">
    <label>Host</label><input name="db_host" value="localhost">
    <label>Port</label><input name="db_port" value="3306">
    <label>Database</label><input name="db_name" value="kdms">
    <label>User</label><input name="db_user" value="root">
    <label>Password</label><input name="db_pass" type="password">
  </div>
  <button type="submit">تثبيت الآن</button>
</form>
