<?php
include 'conn.php';
// Ensure variables are set before use
$name = $_POST['name'];
$desc = $_POST['description'];
$link = $_POST['link'];
if ($name === '') {
    echo json_encode(['status'=>'false','message'=>'اسم الكلية مطلوب']);
    exit;
}
$stmt = $conn->prepare("INSERT INTO faculties (name, description, link) VALUES (?, ?, ?)");
$stmt->bind_param('sss', $name, $desc, $link);
if ($stmt->execute()) {
    // No admin action logging
    echo json_encode(['status' => 'true', 'message' => 'faculty added successfully']);
} else {
    echo json_encode(['status' => 'false', 'message' => 'Insert failed: ' . mysqli_error($conn)]);
}
$stmt->close();
exit;