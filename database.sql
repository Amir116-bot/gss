-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS job_applications CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- استخدام قاعدة البيانات
USE job_applications;

-- إنشاء جدول طلبات العمل
CREATE TABLE IF NOT EXISTS job_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    last_name VARCHAR(100) NOT NULL COMMENT 'اللقب',
    first_name VARCHAR(100) NOT NULL COMMENT 'الاسم',
    age INT NOT NULL COMMENT 'العمر',
    address TEXT NOT NULL COMMENT 'العنوان',
    email VARCHAR(255) NOT NULL UNIQUE COMMENT 'البريد الإلكتروني',
    password VARCHAR(255) NOT NULL COMMENT 'كلمة المرور',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'تاريخ الإنشاء',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'تاريخ التحديث'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- إنشاء فهرس للبريد الإلكتروني للبحث السريع
CREATE INDEX idx_email ON job_applications(email);

-- إنشاء فهرس للتاريخ للترتيب
CREATE INDEX idx_created_at ON job_applications(created_at); 