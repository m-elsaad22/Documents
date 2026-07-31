<?php
/**
 * KDMS — Application Configuration
 * Kayan Documents Management System
 */

return [
    'name'      => 'KDMS',
    'full_name' => 'Kayan Documents Management System',
    'version'   => '1.0.0',
    'url'       => getenv('APP_URL') ?: 'http://localhost:8080',
    'timezone'  => 'Asia/Dubai',
    'locale'    => 'ar',
    'debug'     => (getenv('APP_DEBUG') ?: 'true') === 'true',
    'key'       => getenv('APP_KEY') ?: 'kdms-change-this-secret-key-in-production',

    // Session
    'session_name'     => 'KDMSSESSID',
    'session_lifetime' => 7200, // 2 hours

    // Uploads
    'upload_max_mb'    => 10,
    'allowed_images'   => ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'],
    'allowed_files'    => ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'],

    // Document number prefixes
    'doc_prefixes' => [
        'invoice'   => 'INV',
        'quotation' => 'QTN',
        'contract'  => 'CON',
        'receipt'   => 'REC',
        'report'    => 'REP',
        'warranty'  => 'WAR',
    ],

    // Document statuses
    'statuses' => [
        'draft'     => ['ar' => 'مسودة',     'en' => 'Draft'],
        'pending'   => ['ar' => 'قيد الانتظار', 'en' => 'Pending'],
        'approved'  => ['ar' => 'معتمد',     'en' => 'Approved'],
        'rejected'  => ['ar' => 'مرفوض',     'en' => 'Rejected'],
        'paid'      => ['ar' => 'مدفوع',     'en' => 'Paid'],
        'completed' => ['ar' => 'مكتمل',     'en' => 'Completed'],
        'cancelled' => ['ar' => 'ملغي',      'en' => 'Cancelled'],
        'expired'   => ['ar' => 'منتهي',     'en' => 'Expired'],
    ],

    // Roles & permissions
    'roles' => [
        'admin'    => ['ar' => 'مدير النظام', 'en' => 'Admin'],
        'manager'  => ['ar' => 'مدير',       'en' => 'Manager'],
        'employee' => ['ar' => 'موظف',       'en' => 'Employee'],
    ],
];
