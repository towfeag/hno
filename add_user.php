<?php
include('conn.php');

$fullname = $_POST['fullname'];
$username = $_POST['username'];
$email    = $_POST['email'];
$password = password_hash('123', PASSWORD_DEFAULT);
$role     = $_POST['role'];
$must_change_password = 1;

$check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username' OR email = '$email'");
if (mysqli_num_rows($check) > 0) {
    echo json_encode(['status' => 'false', 'message' => 'المستخدم موجود مسبقاً']);
    exit;
}


$sql = "INSERT INTO `users` (`fullname`, `username`, `email`, `password`, `role`, `must_change_password`) 
        VALUES ('$fullname', '$username', '$email', '$password', '$role', $must_change_password)";

$query = mysqli_query($conn, $sql);

if ($query) {
    echo json_encode(['status' => 'true', 'message' => 'User added successfully']);
} else {
    echo json_encode(['status' => 'false', 'message' => 'Insert failed: ' . mysqli_error($conn)]);
}
