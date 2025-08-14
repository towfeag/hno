<?php 
include('conn.php');

$id = $_POST['id'];
$fullname = $_POST['fullname'];
$username = $_POST['username'];
$email = $_POST['email'];
$role = $_POST['role'];

$sql = "UPDATE `users` 
        SET 
            `fullname` = '$fullname', 
            `username` = '$username', 
            `email` = '$email', 
            `role` = '$role' 
        WHERE `id` = '$id'";

$query = mysqli_query($conn, $sql);

if ($query) {
    echo json_encode(['status' => 'true']);
} else {
    echo json_encode(['status' => 'false']);
}
?>
