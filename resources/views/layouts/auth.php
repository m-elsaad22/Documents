<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title ?? 'KDMS') ?></title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
body{margin:0;min-height:100vh;display:grid;place-items:center;font-family:Cairo,sans-serif;color:#0B1F33;
background:radial-gradient(900px 400px at 10% 0%,rgba(201,162,39,.18),transparent 55%),linear-gradient(145deg,#071A30,#0B3A6E 50%,#1260A8);}
.card{width:min(420px,92vw);background:#fff;border-radius:18px;padding:28px;box-shadow:0 20px 60px rgba(0,0,0,.25)}
h1{margin:0 0 6px;font-size:24px} p{margin:0 0 18px;color:#5B708A;font-size:13px}
label{display:block;font-size:12px;font-weight:800;color:#5B708A;margin:12px 0 6px}
input,select{width:100%;padding:12px;border:1px solid rgba(11,31,51,.12);border-radius:10px;font:inherit}
button{width:100%;margin-top:18px;border:0;border-radius:10px;padding:12px;font:inherit;font-weight:800;cursor:pointer;background:linear-gradient(135deg,#0B3A6E,#1260A8);color:#fff}
.alert{padding:10px 12px;border-radius:10px;margin-bottom:12px;background:rgba(192,57,43,.1);color:#C0392B;font-size:13px;font-weight:700}
.brand{display:flex;gap:10px;align-items:center;margin-bottom:14px}
.mark{width:42px;height:42px;border-radius:12px;display:grid;place-items:center;font-weight:900;background:linear-gradient(145deg,#F0C040,#C9A227);color:#071A30}
</style>
</head>
<body>
<div class="card">
  <div class="brand"><div class="mark">KD</div><div><strong>KDMS</strong><div style="font-size:12px;color:#5B708A">Kayan Documents Management</div></div></div>
  <?php if ($msg = flash('error')): ?><div class="alert"><?= e($msg) ?></div><?php endif; ?>
  <?php if ($msg = flash('success')): ?><div class="alert" style="background:rgba(31,138,91,.1);color:#1F8A5B"><?= e($msg) ?></div><?php endif; ?>
  <?= $content ?>
</div>
</body>
</html>
