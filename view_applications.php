<?php
// تضمين ملف التكوين
require_once 'config.php';

// التحقق من وجود طلبات
$applications = [];
$message = '';

try {
    // جلب جميع الطلبات مرتبة حسب التاريخ
    $stmt = $pdo->query("
        SELECT id, last_name, first_name, age, address, email, created_at 
        FROM list_login 
        ORDER BY created_at DESC
    ");
    
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($applications)) {
        $message = 'لا توجد طلبات عمل حالياً.';
    }
    
} catch (PDOException $e) {
    $message = 'حدث خطأ في قاعدة البيانات: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة طلبات العمل</title>
    <link rel="stylesheet" href="liststyle.css">
    <style>
        .applications-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }
        
        .applications-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .applications-table th,
        .applications-table td {
            padding: 15px;
            text-align: right;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .applications-table th {
            background: rgba(49, 42, 124, 0.8);
            color: white;
            font-weight: 600;
        }
        
        .applications-table tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .no-applications {
            text-align: center;
            padding: 50px;
            color: white;
            font-size: 18px;
        }
        
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #312a7c;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: background 0.3s ease;
        }
        
        .back-button:hover {
            background: #0056b3;
        }
        
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            color: white;
        }
        
        .stat-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            backdrop-filter: blur(5px);
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #312a7c;
        }
    </style>
</head>
<body>
    <div class="applications-container">
        <a href="listmogin.html" class="back-button">← العودة إلى النموذج</a>
        
        <h1 style="text-align: center; color: white; margin-bottom: 30px;">قائمة طلبات العمل</h1>
        
        <?php if (!empty($applications)): ?>
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number"><?php echo count($applications); ?></div>
                    <div>إجمالي الطلبات</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo count(array_filter($applications, function($app) { return $app['age'] >= 18 && $app['age'] <= 30; })); ?></div>
                    <div>المرشحون الشباب (18-30)</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo count(array_filter($applications, function($app) { return $app['age'] > 30; })); ?></div>
                    <div>المرشحون ذوو الخبرة (30+)</div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($message)): ?>
            <div class="no-applications"><?php echo htmlspecialchars($message); ?></div>
        <?php else: ?>
            <table class="applications-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم الكامل</th>
                        <th>العمر</th>
                        <th>العنوان</th>
                        <th>البريد الإلكتروني</th>
                        <th>تاريخ التقديم</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $index => $application): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($application['age']); ?></td>
                            <td><?php echo htmlspecialchars($application['address']); ?></td>
                            <td><?php echo htmlspecialchars($application['email']); ?></td>
                            <td><?php echo date('Y/m/d H:i', strtotime($application['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html> 