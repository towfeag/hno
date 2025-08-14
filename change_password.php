<?php
session_start();
require 'conn.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $new_password = $_POST['new_password'];
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password = '$hashed', must_change_password = 0 WHERE username = '$username'";
    if (mysqli_query($conn, $sql)) {
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: iua_faculties_page.php');
        }
        exit();
    } else {
        $error = 'فشل في تحديث كلمة المرور.';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تغيير كلمة المرور</title>
    <link href="css/bootstrap5.0.1.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(to right, #003366, #336699); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
<div class="container" style="max-width: 400px; background: #fff; border-radius: 16px; box-shadow: 0 6px 20px rgba(0,0,0,0.15); padding: 32px 28px; margin-top: 40px;">
    <h3 class="mb-4 text-center" style="color:#1c4a8f;">تغيير كلمة المرور</h3>
    <?php if(isset($error)): ?>
      <div class="alert alert-danger text-center fw-bold" role="alert" style="font-size:16px;letter-spacing:1px;">
        <?=$error?>
      </div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
        <div class="mb-3">
            <label class="form-label" for="new_password">كلمة المرور الجديدة</label>
            <input type="password" name="new_password" id="new_password" class="form-control form-control-lg" placeholder="••••••">
        </div>
        <button type="submit" class="btn btn-primary w-100" style="font-size:17px;">تغيير</button>
    </form>
    <div class="text-center mt-3">
        <small style="color:#888;">اختر كلمة مرور قوية وسهلة التذكر</small>
    </div>
</div>
</body>
</html>
