<?php
// إعدادات قاعدة البيانات
$host = 'localhost';
$dbname = 'list_login';
$username = 'root';
$password = '';

try {
    // إنشاء اتصال PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // تعيين وضع الأخطاء
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // تعيين الترميز
    $pdo->exec("SET NAMES utf8");
    
} catch(PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?> 