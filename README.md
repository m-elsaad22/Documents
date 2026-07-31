# KDMS — Kayan Documents Management System

نظام احترافي متعدد الشركات لإدارة وإنشاء المستندات (فواتير، عروض أسعار، عقود، سندات قبض، تقارير، شهادات ضمان) باللغة العربية والإنجليزية.

## التقنيات

- PHP 8+
- MySQL (إنتاج cPanel) أو SQLite (تجريبي)
- HTML5 / CSS3 / Vanilla JS / Ajax
- بدون WordPress / Laravel / CMS

## التثبيت على cPanel

1. ارفع محتويات المشروع إلى `public_html` (أو مجلد فرعي).
2. تأكد أن `mod_rewrite` مفعّل وأن ملف `.htaccess` موجود.
3. انسخ `.env.example` إلى `.env` وعدّل بيانات MySQL:

```env
APP_URL=https://your-domain.com
APP_DEBUG=false
DB_DRIVER=mysql
DB_HOST=localhost
DB_NAME=your_db
DB_USER=your_user
DB_PASS=your_pass
```

4. افتح `/install` وأكمل التثبيت، أو استورد `database/schema.sql` ثم شغّل المثبّت.
5. اجعل المجلدات قابلة للكتابة:
   - `storage/`
   - `storage/uploads/`
   - `storage/backups/`
   - `storage/logs/`

## بيانات الدخول الافتراضية

| الدور | البريد | كلمة المرور |
|--------|---------|-------------|
| Admin | `admin@kdms.local` | `admin123` |
| Manager | `manager@kdms.local` | `manager123` |

**يجب تغيير كلمات المرور فوراً بعد التثبيت.**

## التشغيل المحلي

```bash
php -S localhost:8080 index.php
```

ثم افتح `http://localhost:8080/login`

## الروابط العامة للمستندات

كل مستند يحصل على رابط مباشر مثل:

- `/INV-2026-000001`
- `/QTN-2026-000001`
- `/CON-2026-000001`

## القوالب

التصاميم الرسمية محفوظة في:

- `designs/original/` — الملفات الأصلية كما رُفعت
- `resources/templates/documents/` — قوالب PHP ديناميكية بنفس التصميم 100%

يمكن رفع/تفعيل/تعطيل/تعيين القالب الافتراضي من لوحة التحكم → القوالب.

## PDF والطباعة

- جميع المستندات بحجم **A4 Portrait**
- أزرار: طباعة/PDF، نسخ الرابط، واتساب، بريد
- CSS طباعة يمنع كسر العناصر المهمة ويكرر رأس الجداول
- لا تُنشأ صفحات فارغة؛ الترقيم يتبع حجم المحتوى فقط

## الهيكل

```
app/            Core, Controllers, Services, Helpers
config/         إعدادات التطبيق وقاعدة البيانات
database/       schema.sql + Installer
resources/      views + document templates
designs/        التصاميم الأصلية
public/assets/  CSS/JS لوحة التحكم
storage/        uploads, logs, backups, sqlite
index.php       Front controller
```

## الأمان

- Password hashing (`password_hash`)
- CSRF tokens
- XSS escaping (`e()`)
- PDO prepared statements
- Secure uploads (extension + image validation)
- Activity logs
- Session hardening
