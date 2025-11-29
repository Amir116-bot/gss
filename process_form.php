<?php
// تضمين ملف التكوين
require_once 'config.php';

// التحقق من أن الطلب هو POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: listmogin.html');
    exit();
}

// تهيئة متغيرات الاستجابة
$response = [
    'success' => false,
    'message' => '',
    'errors' => []
];

try {
    // تنظيف وتحقق من البيانات
    $lastName = trim($_POST['last_name'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $address = trim($_POST['address'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // التحقق من صحة البيانات
    if (empty($lastName)) {
        $response['errors'][] = 'اللقب مطلوب';
    }

    if (empty($firstName)) {
        $response['errors'][] = 'الاسم مطلوب';
    }

    if ($age < 18 || $age > 100) {
        $response['errors'][] = 'العمر يجب أن يكون بين 18 و 100';
    }

    if (empty($address)) {
        $response['errors'][] = 'العنوان مطلوب';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['errors'][] = 'البريد الإلكتروني غير صحيح';
    }

    if (strlen($password) < 6) {
        $response['errors'][] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
    }

    // إذا كانت هناك أخطاء، إرجاع الاستجابة
    if (!empty($response['errors'])) {
        $response['message'] = 'يرجى تصحيح الأخطاء التالية:';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    // التحقق من عدم وجود البريد الإلكتروني مسبقاً
    $stmt = $pdo->prepare("SELECT id FROM list_login where email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        $response['message'] = 'البريد الإلكتروني مستخدم مسبقاً';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    // تشفير كلمة المرور
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // إدراج البيانات في قاعدة البيانات
    $stmt = $pdo->prepare("
        INSERT INTO list_loginme, first_name, age, address, email, password) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([$lastName, $firstName, $age, $address, $email, $hashedPassword]);

    // نجاح العملية
    $response['success'] = true;
    $response['message'] = 'تم تقديم طلبك بنجاح! سنتواصل معك قريباً.';

} catch (PDOException $e) {
    $response['message'] = 'حدث خطأ في قاعدة البيانات: ' . $e->getMessage();
} catch (Exception $e) {
    $response['message'] = 'حدث خطأ غير متوقع: ' . $e->getMessage();
}

// إرجاع الاستجابة كـ JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?> 