-- ============================================================
-- KDMS — Kayan Documents Management System
-- Professional Multi-Company Database Schema
-- Compatible with MySQL 5.7+ / MariaDB 10.3+ / SQLite (via installer)
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------
-- Companies (Multi-tenant root)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS companies (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name_ar         VARCHAR(255) NOT NULL,
    name_en         VARCHAR(255) DEFAULT NULL,
    slug            VARCHAR(100) NOT NULL UNIQUE,
    logo            VARCHAR(255) DEFAULT NULL,
    seal            VARCHAR(255) DEFAULT NULL,
    signature       VARCHAR(255) DEFAULT NULL,
    header_image    VARCHAR(255) DEFAULT NULL,
    footer_image    VARCHAR(255) DEFAULT NULL,
    address_ar      TEXT DEFAULT NULL,
    address_en      TEXT DEFAULT NULL,
    city            VARCHAR(120) DEFAULT NULL,
    country         VARCHAR(120) DEFAULT 'UAE',
    email           VARCHAR(180) DEFAULT NULL,
    website         VARCHAR(180) DEFAULT NULL,
    phone           VARCHAR(60) DEFAULT NULL,
    whatsapp        VARCHAR(60) DEFAULT NULL,
    tax_number      VARCHAR(80) DEFAULT NULL,
    cr_number       VARCHAR(80) DEFAULT NULL,
    currency        VARCHAR(10) DEFAULT 'AED',
    currency_label_ar VARCHAR(60) DEFAULT 'درهم إماراتي',
    currency_label_en VARCHAR(60) DEFAULT 'UAE Dirham',
    default_lang    ENUM('ar','en') DEFAULT 'ar',
    primary_color   VARCHAR(20) DEFAULT '#003087',
    secondary_color VARCHAR(20) DEFAULT '#D4A017',
    accent_color    VARCHAR(20) DEFAULT '#0070CC',
    social_facebook VARCHAR(255) DEFAULT NULL,
    social_instagram VARCHAR(255) DEFAULT NULL,
    social_twitter  VARCHAR(255) DEFAULT NULL,
    social_linkedin VARCHAR(255) DEFAULT NULL,
    social_youtube  VARCHAR(255) DEFAULT NULL,
    qr_code         VARCHAR(255) DEFAULT NULL,
    offices_json    LONGTEXT DEFAULT NULL,
    settings_json   LONGTEXT DEFAULT NULL,
    is_active       TINYINT(1) DEFAULT 1,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Extra logos / seals per company
CREATE TABLE IF NOT EXISTS company_assets (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id  INT UNSIGNED NOT NULL,
    type        ENUM('logo','seal','signature','header','footer','other') NOT NULL,
    title       VARCHAR(180) DEFAULT NULL,
    file_path   VARCHAR(255) NOT NULL,
    is_default  TINYINT(1) DEFAULT 0,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    INDEX idx_ca_company (company_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Users & Roles
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id      INT UNSIGNED DEFAULT NULL,
    name            VARCHAR(180) NOT NULL,
    email           VARCHAR(180) NOT NULL UNIQUE,
    password        VARCHAR(255) NOT NULL,
    role            ENUM('admin','manager','employee') DEFAULT 'employee',
    phone           VARCHAR(60) DEFAULT NULL,
    avatar          VARCHAR(255) DEFAULT NULL,
    permissions_json LONGTEXT DEFAULT NULL,
    is_active       TINYINT(1) DEFAULT 1,
    last_login_at   DATETIME DEFAULT NULL,
    last_login_ip   VARCHAR(45) DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL,
    INDEX idx_users_company (company_id),
    INDEX idx_users_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Customers
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS customers (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id      INT UNSIGNED NOT NULL,
    name_ar         VARCHAR(255) NOT NULL,
    name_en         VARCHAR(255) DEFAULT NULL,
    email           VARCHAR(180) DEFAULT NULL,
    phone           VARCHAR(60) DEFAULT NULL,
    whatsapp        VARCHAR(60) DEFAULT NULL,
    address_ar      TEXT DEFAULT NULL,
    address_en      TEXT DEFAULT NULL,
    city            VARCHAR(120) DEFAULT NULL,
    country         VARCHAR(120) DEFAULT NULL,
    tax_number      VARCHAR(80) DEFAULT NULL,
    notes           TEXT DEFAULT NULL,
    is_active       TINYINT(1) DEFAULT 1,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    INDEX idx_cust_company (company_id),
    INDEX idx_cust_name (name_ar)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Document Templates
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS templates (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id      INT UNSIGNED DEFAULT NULL,
    name            VARCHAR(180) NOT NULL,
    slug            VARCHAR(120) NOT NULL,
    document_type   ENUM('invoice','quotation','contract','receipt','report','warranty') NOT NULL,
    language        ENUM('ar','en') NOT NULL DEFAULT 'ar',
    file_path       VARCHAR(255) NOT NULL,
    preview_image   VARCHAR(255) DEFAULT NULL,
    is_active       TINYINT(1) DEFAULT 1,
    is_default      TINYINT(1) DEFAULT 0,
    description     TEXT DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    UNIQUE KEY uq_tpl_slug_lang (slug, language, company_id),
    INDEX idx_tpl_type (document_type),
    INDEX idx_tpl_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Documents (unified)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS documents (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id      INT UNSIGNED NOT NULL,
    customer_id     INT UNSIGNED DEFAULT NULL,
    template_id     INT UNSIGNED DEFAULT NULL,
    created_by      INT UNSIGNED DEFAULT NULL,
    document_type   ENUM('invoice','quotation','contract','receipt','report','warranty') NOT NULL,
    document_number VARCHAR(60) NOT NULL,
    public_slug     VARCHAR(80) NOT NULL UNIQUE,
    language        ENUM('ar','en') NOT NULL DEFAULT 'ar',
    title           VARCHAR(255) NOT NULL,
    status          ENUM('draft','pending','approved','rejected','paid','completed','cancelled','expired') DEFAULT 'draft',
    issue_date      DATE DEFAULT NULL,
    due_date        DATE DEFAULT NULL,
    valid_until     DATE DEFAULT NULL,
    currency        VARCHAR(10) DEFAULT 'AED',
    subtotal        DECIMAL(14,2) DEFAULT 0,
    discount        DECIMAL(14,2) DEFAULT 0,
    tax_rate        DECIMAL(5,2) DEFAULT 0,
    tax_amount      DECIMAL(14,2) DEFAULT 0,
    total           DECIMAL(14,2) DEFAULT 0,
    amount_paid     DECIMAL(14,2) DEFAULT 0,
    amount_words_ar VARCHAR(255) DEFAULT NULL,
    amount_words_en VARCHAR(255) DEFAULT NULL,
    payment_method  VARCHAR(120) DEFAULT NULL,
    project_address TEXT DEFAULT NULL,
    notes           TEXT DEFAULT NULL,
    terms           LONGTEXT DEFAULT NULL,
    conditions      LONGTEXT DEFAULT NULL,
    custom_fields   LONGTEXT DEFAULT NULL,
    show_signature  TINYINT(1) DEFAULT 1,
    show_seal       TINYINT(1) DEFAULT 1,
    pdf_path        VARCHAR(255) DEFAULT NULL,
    views_count     INT UNSIGNED DEFAULT 0,
    shared_at       DATETIME DEFAULT NULL,
    approved_at     DATETIME DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      DATETIME DEFAULT NULL,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    FOREIGN KEY (template_id) REFERENCES templates(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY uq_doc_number_company (company_id, document_number),
    INDEX idx_doc_type (document_type),
    INDEX idx_doc_status (status),
    INDEX idx_doc_date (issue_date),
    INDEX idx_doc_customer (customer_id),
    INDEX idx_doc_search (document_number, title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Document line items
CREATE TABLE IF NOT EXISTS document_items (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    document_id     INT UNSIGNED NOT NULL,
    sort_order      INT UNSIGNED DEFAULT 0,
    item_number     VARCHAR(40) DEFAULT NULL,
    title           VARCHAR(500) NOT NULL,
    description     TEXT DEFAULT NULL,
    quantity        DECIMAL(12,2) DEFAULT 1,
    unit            VARCHAR(60) DEFAULT NULL,
    unit_price      DECIMAL(14,2) DEFAULT 0,
    discount        DECIMAL(14,2) DEFAULT 0,
    tax_rate        DECIMAL(5,2) DEFAULT 0,
    total           DECIMAL(14,2) DEFAULT 0,
    meta_json       LONGTEXT DEFAULT NULL,
    FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE,
    INDEX idx_items_doc (document_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Document images / attachments
CREATE TABLE IF NOT EXISTS document_media (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    document_id     INT UNSIGNED NOT NULL,
    type            ENUM('image','file','table','section') DEFAULT 'image',
    title           VARCHAR(255) DEFAULT NULL,
    caption         TEXT DEFAULT NULL,
    file_path       VARCHAR(255) DEFAULT NULL,
    content_html    LONGTEXT DEFAULT NULL,
    sort_order      INT UNSIGNED DEFAULT 0,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE,
    INDEX idx_media_doc (document_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Auto-number sequences per company / type / year
CREATE TABLE IF NOT EXISTS document_sequences (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id      INT UNSIGNED NOT NULL,
    document_type   VARCHAR(40) NOT NULL,
    year            SMALLINT UNSIGNED NOT NULL,
    last_number     INT UNSIGNED DEFAULT 0,
    UNIQUE KEY uq_seq (company_id, document_type, year),
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Activity Logs
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS activity_logs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id      INT UNSIGNED DEFAULT NULL,
    user_id         INT UNSIGNED DEFAULT NULL,
    action          VARCHAR(80) NOT NULL,
    entity_type     VARCHAR(80) DEFAULT NULL,
    entity_id       INT UNSIGNED DEFAULT NULL,
    description     TEXT DEFAULT NULL,
    ip_address      VARCHAR(45) DEFAULT NULL,
    user_agent      VARCHAR(255) DEFAULT NULL,
    meta_json       LONGTEXT DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_log_company (company_id),
    INDEX idx_log_user (user_id),
    INDEX idx_log_action (action),
    INDEX idx_log_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Settings (global + per company)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS settings (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id      INT UNSIGNED DEFAULT NULL,
    setting_key     VARCHAR(120) NOT NULL,
    setting_value   LONGTEXT DEFAULT NULL,
    UNIQUE KEY uq_setting (company_id, setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Backups registry
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS backups (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company_id      INT UNSIGNED DEFAULT NULL,
    created_by      INT UNSIGNED DEFAULT NULL,
    file_path       VARCHAR(255) NOT NULL,
    file_size       BIGINT UNSIGNED DEFAULT 0,
    notes           VARCHAR(255) DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
