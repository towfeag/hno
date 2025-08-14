<?php
session_start(); 
require 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            if ($user['must_change_password']) {
                header("Location: change_password.php");
                exit();
            }
            if($user['role'] == 'admin'){
                header("Location: admin.php");
            } else {
                header("Location: iua_faculties_page.php");
            }
            exit();
        } else {
            header("Location: index.html?error=invalid");
            exit();
        }
    } else {
        header("Location: index.html?error=invalid");
        exit();
    }
    $stmt->close();
}
$conn->close();
?>