<?php
namespace Database;

use App\Core\Database;
use PDO;

/**
 * Database installer / seeder for MySQL and SQLite
 */
class Installer
{
    public static function runSqliteDemo(): void
    {
        $lock = dirname(__DIR__) . '/storage/installed.lock';
        if (is_file($lock)) {
            return;
        }

        // Ensure App helpers available
        if (!function_exists('base_path')) {
            require dirname(__DIR__) . '/app/Helpers/functions.php';
        }

        putenv('DB_DRIVER=sqlite');
        $_ENV['DB_DRIVER'] = 'sqlite';

        // Reset singleton by creating schema via raw PDO first
        $path = dirname(__DIR__) . '/storage/kdms.sqlite';
        if (is_file($path)) {
            @unlink($path);
        }

        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('PRAGMA foreign_keys = ON');
        self::createSqliteSchema($pdo);
        self::seed($pdo, 'sqlite');

        file_put_contents($lock, date('c'));
    }

    public static function runMysql(array $config): void
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;charset=utf8mb4',
            $config['host'],
            $config['port'] ?? 3306
        );
        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $db = preg_replace('/[^a-zA-Z0-9_]/', '', $config['database']);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$db`");

        $schema = file_get_contents(dirname(__DIR__) . '/database/schema.sql');
        // Split by semicolon carefully
        foreach (self::splitSql($schema) as $stmt) {
            $pdo->exec($stmt);
        }

        self::seed($pdo, 'mysql');
        file_put_contents(dirname(__DIR__) . '/storage/installed.lock', date('c'));
    }

    public static function createSqliteSchema(PDO $pdo): void
    {
        $pdo->exec("
        CREATE TABLE companies (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name_ar TEXT NOT NULL,
            name_en TEXT,
            slug TEXT NOT NULL UNIQUE,
            logo TEXT, seal TEXT, signature TEXT, header_image TEXT, footer_image TEXT,
            address_ar TEXT, address_en TEXT, city TEXT, country TEXT DEFAULT 'UAE',
            email TEXT, website TEXT, phone TEXT, whatsapp TEXT,
            tax_number TEXT, cr_number TEXT,
            currency TEXT DEFAULT 'AED',
            currency_label_ar TEXT DEFAULT 'درهم إماراتي',
            currency_label_en TEXT DEFAULT 'UAE Dirham',
            default_lang TEXT DEFAULT 'ar',
            primary_color TEXT DEFAULT '#003087',
            secondary_color TEXT DEFAULT '#D4A017',
            accent_color TEXT DEFAULT '#0070CC',
            social_facebook TEXT, social_instagram TEXT, social_twitter TEXT,
            social_linkedin TEXT, social_youtube TEXT, qr_code TEXT,
            offices_json TEXT, settings_json TEXT,
            is_active INTEGER DEFAULT 1,
            created_at TEXT, updated_at TEXT
        );

        CREATE TABLE company_assets (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company_id INTEGER NOT NULL,
            type TEXT NOT NULL,
            title TEXT, file_path TEXT NOT NULL,
            is_default INTEGER DEFAULT 0,
            created_at TEXT
        );

        CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company_id INTEGER,
            name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            role TEXT DEFAULT 'employee',
            phone TEXT, avatar TEXT, permissions_json TEXT,
            is_active INTEGER DEFAULT 1,
            last_login_at TEXT, last_login_ip TEXT,
            created_at TEXT, updated_at TEXT
        );

        CREATE TABLE customers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company_id INTEGER NOT NULL,
            name_ar TEXT NOT NULL, name_en TEXT,
            email TEXT, phone TEXT, whatsapp TEXT,
            address_ar TEXT, address_en TEXT, city TEXT, country TEXT,
            tax_number TEXT, notes TEXT,
            is_active INTEGER DEFAULT 1,
            created_at TEXT, updated_at TEXT
        );

        CREATE TABLE templates (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company_id INTEGER,
            name TEXT NOT NULL, slug TEXT NOT NULL,
            document_type TEXT NOT NULL, language TEXT NOT NULL DEFAULT 'ar',
            file_path TEXT NOT NULL, preview_image TEXT,
            is_active INTEGER DEFAULT 1, is_default INTEGER DEFAULT 0,
            description TEXT, created_at TEXT, updated_at TEXT
        );

        CREATE TABLE documents (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company_id INTEGER NOT NULL,
            customer_id INTEGER, template_id INTEGER, created_by INTEGER,
            document_type TEXT NOT NULL,
            document_number TEXT NOT NULL,
            public_slug TEXT NOT NULL UNIQUE,
            language TEXT NOT NULL DEFAULT 'ar',
            title TEXT NOT NULL,
            status TEXT DEFAULT 'draft',
            issue_date TEXT, due_date TEXT, valid_until TEXT,
            currency TEXT DEFAULT 'AED',
            subtotal REAL DEFAULT 0, discount REAL DEFAULT 0,
            tax_rate REAL DEFAULT 0, tax_amount REAL DEFAULT 0, total REAL DEFAULT 0,
            amount_paid REAL DEFAULT 0,
            amount_words_ar TEXT, amount_words_en TEXT,
            payment_method TEXT, project_address TEXT,
            notes TEXT, terms TEXT, conditions TEXT, custom_fields TEXT,
            show_signature INTEGER DEFAULT 1, show_seal INTEGER DEFAULT 1,
            pdf_path TEXT, views_count INTEGER DEFAULT 0,
            shared_at TEXT, approved_at TEXT,
            created_at TEXT, updated_at TEXT, deleted_at TEXT
        );

        CREATE TABLE document_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            document_id INTEGER NOT NULL,
            sort_order INTEGER DEFAULT 0,
            item_number TEXT, title TEXT NOT NULL, description TEXT,
            quantity REAL DEFAULT 1, unit TEXT,
            unit_price REAL DEFAULT 0, discount REAL DEFAULT 0,
            tax_rate REAL DEFAULT 0, total REAL DEFAULT 0, meta_json TEXT
        );

        CREATE TABLE document_media (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            document_id INTEGER NOT NULL,
            type TEXT DEFAULT 'image',
            title TEXT, caption TEXT, file_path TEXT, content_html TEXT,
            sort_order INTEGER DEFAULT 0, created_at TEXT
        );

        CREATE TABLE document_sequences (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company_id INTEGER NOT NULL,
            document_type TEXT NOT NULL,
            year INTEGER NOT NULL,
            last_number INTEGER DEFAULT 0,
            UNIQUE(company_id, document_type, year)
        );

        CREATE TABLE activity_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company_id INTEGER, user_id INTEGER,
            action TEXT NOT NULL, entity_type TEXT, entity_id INTEGER,
            description TEXT, ip_address TEXT, user_agent TEXT, meta_json TEXT,
            created_at TEXT
        );

        CREATE TABLE settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company_id INTEGER, setting_key TEXT NOT NULL, setting_value TEXT,
            UNIQUE(company_id, setting_key)
        );

        CREATE TABLE backups (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            company_id INTEGER, created_by INTEGER,
            file_path TEXT NOT NULL, file_size INTEGER DEFAULT 0,
            notes TEXT, created_at TEXT
        );
        ");
    }

    public static function seed(PDO $pdo, string $driver = 'mysql'): void
    {
        $now = date('Y-m-d H:i:s');

        $companies = [
            [
                'name_ar' => 'ركن التطور',
                'name_en' => 'Rukn El-Tatawer',
                'slug' => 'rukn-eltatawer',
                'city' => 'أبوظبي',
                'email' => 'info@rukn-eltatawer.com',
                'phone' => '+971500000001',
                'whatsapp' => '971500000001',
                'website' => 'https://www.rukn-eltatawer.com',
                'address_ar' => 'مكتب 306، مجمع مزيد، مدينة محمد بن زايد، أبوظبي',
                'address_en' => 'Office 306, Mazyad Mall, MBZ City, Abu Dhabi',
                'tax_number' => '100000000000003',
                'cr_number' => 'CN-1234567',
            ],
            [
                'name_ar' => 'كيان ويب',
                'name_en' => 'Kayan Web',
                'slug' => 'kayan-web',
                'city' => 'دبي',
                'email' => 'info@kayanweb.com',
                'phone' => '+971500000002',
                'whatsapp' => '971500000002',
                'website' => 'https://kayanweb.com',
                'address_ar' => 'دبي، الإمارات العربية المتحدة',
                'address_en' => 'Dubai, United Arab Emirates',
                'tax_number' => '100000000000004',
                'cr_number' => 'CN-7654321',
            ],
            [
                'name_ar' => 'شركة السعد',
                'name_en' => 'Al Saad Company',
                'slug' => 'al-saad',
                'city' => 'الشارقة',
                'email' => 'info@alsaad.ae',
                'phone' => '+971500000003',
                'whatsapp' => '971500000003',
                'website' => 'https://alsaad.ae',
                'address_ar' => 'الشارقة، الإمارات العربية المتحدة',
                'address_en' => 'Sharjah, United Arab Emirates',
                'tax_number' => '100000000000005',
                'cr_number' => 'CN-9988776',
            ],
        ];

        $companyIds = [];
        foreach ($companies as $c) {
            $offices = json_encode([
                ['city_ar' => 'أبوظبي', 'city_en' => 'Abu Dhabi', 'address_ar' => $c['address_ar'], 'address_en' => $c['address_en']],
                ['city_ar' => 'دبي', 'city_en' => 'Dubai', 'address_ar' => 'دبي، الإمارات', 'address_en' => 'Dubai, UAE'],
            ], JSON_UNESCAPED_UNICODE);

            $stmt = $pdo->prepare('INSERT INTO companies
                (name_ar,name_en,slug,city,country,email,phone,whatsapp,website,address_ar,address_en,tax_number,cr_number,currency,default_lang,offices_json,is_active,created_at,updated_at)
                VALUES
                (:name_ar,:name_en,:slug,:city,:country,:email,:phone,:whatsapp,:website,:address_ar,:address_en,:tax_number,:cr_number,:currency,:default_lang,:offices_json,1,:created_at,:updated_at)');
            $stmt->execute([
                'name_ar' => $c['name_ar'],
                'name_en' => $c['name_en'],
                'slug' => $c['slug'],
                'city' => $c['city'],
                'country' => 'UAE',
                'email' => $c['email'],
                'phone' => $c['phone'],
                'whatsapp' => $c['whatsapp'],
                'website' => $c['website'],
                'address_ar' => $c['address_ar'],
                'address_en' => $c['address_en'],
                'tax_number' => $c['tax_number'],
                'cr_number' => $c['cr_number'],
                'currency' => 'AED',
                'default_lang' => 'ar',
                'offices_json' => $offices,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $companyIds[] = (int) $pdo->lastInsertId();
        }

        // Admin user (global)
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->prepare('INSERT INTO users (company_id,name,email,password,role,is_active,created_at,updated_at)
            VALUES (NULL,:name,:email,:password,\'admin\',1,:c,:u)')
            ->execute([
                'name' => 'System Admin',
                'email' => 'admin@kdms.local',
                'password' => $hash,
                'c' => $now,
                'u' => $now,
            ]);

        // Manager for first company
        $pdo->prepare('INSERT INTO users (company_id,name,email,password,role,is_active,created_at,updated_at)
            VALUES (:cid,:name,:email,:password,\'manager\',1,:c,:u)')
            ->execute([
                'cid' => $companyIds[0],
                'name' => 'Company Manager',
                'email' => 'manager@kdms.local',
                'password' => password_hash('manager123', PASSWORD_DEFAULT),
                'c' => $now,
                'u' => $now,
            ]);

        // Sample customers
        $customers = [
            ['خادم الرميثي', 'Khadim Al Rumaithi', 'فيلا 12، شخبوط، أبوظبي'],
            ['جمال النعيمي', 'Jamal Al Naimi', 'عجمان، الإمارات'],
            ['عادل عمار على سالم', 'Adel Ammar Ali Salem', 'فيلا 23b، أبوظبي'],
        ];
        $customerIds = [];
        foreach ($customers as $cu) {
            $pdo->prepare('INSERT INTO customers (company_id,name_ar,name_en,address_ar,city,country,is_active,created_at,updated_at)
                VALUES (:cid,:na,:ne,:addr,\'أبوظبي\',\'UAE\',1,:c,:u)')
                ->execute([
                    'cid' => $companyIds[0],
                    'na' => $cu[0],
                    'ne' => $cu[1],
                    'addr' => $cu[2],
                    'c' => $now,
                    'u' => $now,
                ]);
            $customerIds[] = (int) $pdo->lastInsertId();
        }

        // Templates registry
        $templates = [
            ['فاتورة عربية', 'invoice-ar', 'invoice', 'ar', 'resources/templates/documents/invoice-ar.php', 1],
            ['سند قبض عربي', 'receipt-ar', 'receipt', 'ar', 'resources/templates/documents/receipt-ar.php', 1],
            ['سند قبض تنسيق حدائق', 'receipt-landscaping-ar', 'receipt', 'ar', 'resources/templates/documents/receipt-landscaping-ar.php', 0],
            ['عرض سعر عربي', 'quotation-ar', 'quotation', 'ar', 'resources/templates/documents/quotation-ar.php', 1],
            ['عرض سعر Cool Roof', 'quotation-coolroof-ar', 'quotation', 'ar', 'resources/templates/documents/quotation-coolroof-ar.php', 0],
            ['عرض سعر Roof Protection', 'quotation-roof-ar', 'quotation', 'ar', 'resources/templates/documents/quotation-roof-ar.php', 0],
            ['عرض سعر ري', 'quotation-irrigation-ar', 'quotation', 'ar', 'resources/templates/documents/quotation-irrigation-ar.php', 0],
            ['عقد عربي', 'contract-ar', 'contract', 'ar', 'resources/templates/documents/contract-ar.php', 1],
            ['عقد غطاء مسبح', 'contract-pool-cover-ar', 'contract', 'ar', 'resources/templates/documents/contract-pool-cover-ar.php', 0],
            ['عقد صيانة مسبح', 'contract-pool-maintenance-ar', 'contract', 'ar', 'resources/templates/documents/contract-pool-maintenance-ar.php', 0],
            ['تقرير تسربات عربي', 'report-leak-ar', 'report', 'ar', 'resources/templates/documents/report-leak-ar.php', 1],
            ['تقرير تسربات إنجليزي', 'report-leak-en', 'report', 'en', 'resources/templates/documents/report-leak-en.php', 1],
            ['تقرير فحص عربي', 'report-inspection-ar', 'report', 'ar', 'resources/templates/documents/report-inspection-ar.php', 0],
            ['تقرير فحص إنجليزي', 'report-inspection-en', 'report', 'en', 'resources/templates/documents/report-inspection-en.php', 0],
            ['شهادة ضمان عربي', 'warranty-ar', 'warranty', 'ar', 'resources/templates/documents/warranty-ar.php', 1],
            ['شهادة ضمان إنجليزي', 'warranty-en', 'warranty', 'en', 'resources/templates/documents/warranty-en.php', 1],
            ['فاتورة إنجليزي', 'invoice-en', 'invoice', 'en', 'resources/templates/documents/invoice-en.php', 1],
            ['سند قبض إنجليزي', 'receipt-en', 'receipt', 'en', 'resources/templates/documents/receipt-en.php', 1],
            ['عرض سعر إنجليزي', 'quotation-en', 'quotation', 'en', 'resources/templates/documents/quotation-en.php', 1],
            ['عقد إنجليزي', 'contract-en', 'contract', 'en', 'resources/templates/documents/contract-en.php', 1],
        ];

        foreach ($templates as $t) {
            $pdo->prepare('INSERT INTO templates
                (company_id,name,slug,document_type,language,file_path,is_active,is_default,created_at,updated_at)
                VALUES (NULL,:name,:slug,:type,:lang,:path,1,:def,:c,:u)')
                ->execute([
                    'name' => $t[0],
                    'slug' => $t[1],
                    'type' => $t[2],
                    'lang' => $t[3],
                    'path' => $t[4],
                    'def' => $t[5],
                    'c' => $now,
                    'u' => $now,
                ]);
        }

        // Get default invoice template id
        $tplId = (int) $pdo->query("SELECT id FROM templates WHERE slug='invoice-ar' LIMIT 1")->fetchColumn();
        $year = (int) date('Y');

        $pdo->prepare('INSERT INTO document_sequences (company_id,document_type,year,last_number) VALUES (:c,\'invoice\',:y,1)')
            ->execute(['c' => $companyIds[0], 'y' => $year]);

        $docNo = sprintf('INV-%d-000001', $year);
        $pdo->prepare('INSERT INTO documents
            (company_id,customer_id,template_id,created_by,document_type,document_number,public_slug,language,title,status,issue_date,currency,subtotal,total,amount_words_ar,project_address,notes,show_signature,show_seal,created_at,updated_at)
            VALUES
            (:cid,:cuid,:tid,1,\'invoice\',:dn,:ps,\'ar\',:title,\'approved\',:dt,\'AED\',2000,2000,:words,:addr,:notes,1,1,:c,:u)')
            ->execute([
                'cid' => $companyIds[0],
                'cuid' => $customerIds[0],
                'tid' => $tplId,
                'dn' => $docNo,
                'ps' => $docNo,
                'title' => 'فاتورة أعمال تنسيق الحدائق',
                'dt' => date('Y-m-d'),
                'words' => 'ألفا درهم إماراتي لا غير',
                'addr' => 'فيلا 12، شخبوط، أبوظبي',
                'notes' => 'يشمل السعر جميع أعمال القلع والتحميل والنقل وإعادة الزراعة.',
                'c' => $now,
                'u' => $now,
            ]);
        $docId = (int) $pdo->lastInsertId();
        $pdo->prepare('INSERT INTO document_items (document_id,sort_order,title,description,quantity,unit,unit_price,total)
            VALUES (:d,0,:t,:desc,1,\'شجرة\',2000,2000)')
            ->execute([
                'd' => $docId,
                't' => 'أعمال إزالة شجرة الغاف من أبوظبي ونقلها إلى العين',
                'desc' => 'تشمل: القلع الكامل للشجرة مع جذورها — التحميل — النقل — إعادة الزراعة',
            ]);

        $pdo->prepare('INSERT INTO settings (company_id,setting_key,setting_value) VALUES (NULL,:k,:v)')
            ->execute(['k' => 'system_name', 'v' => 'KDMS']);
    }

    private static function splitSql(string $sql): array
    {
        $parts = [];
        $buffer = '';
        foreach (explode("\n", $sql) as $line) {
            if (str_starts_with(trim($line), '--')) {
                continue;
            }
            $buffer .= $line . "\n";
            if (str_ends_with(trim($line), ';')) {
                $stmt = trim($buffer);
                if ($stmt !== '' && $stmt !== ';') {
                    $parts[] = rtrim($stmt, "; \n\r\t");
                }
                $buffer = '';
            }
        }
        return array_filter($parts);
    }
}
