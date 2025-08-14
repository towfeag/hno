<?php
require 'conn.php';

$fullname = $_POST['fullname'];
$user = $_POST['username'];
$email = $_POST['email'];
$pass = $_POST['password'];
$role = 'student';

$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

// تحقق من تكرار البريد الإلكتروني أو اسم المستخدم
$check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
$check_stmt->bind_param("ss", $email, $user);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo "البريد الإلكتروني أو اسم المستخدم مستخدم من قبل.";
    exit();
}

// تنفيذ الإدخال
$sql = "INSERT INTO users (fullname, username, email, password, role) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $fullname, $user, $email, $hashed_password, $role);

if ($stmt->execute()) {
    header("Location: index.html");
    exit();
} else {
    echo "خطأ أثناء التسجيل: " . $stmt->error;
}

$conn->close();
?>
