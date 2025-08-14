<?php
include 'conn.php';
$id = $_POST['id'];
if ($id <= 0) {
    echo json_encode(['status'=>'false','message'=>'معرف غير صالح']);
    exit;
}
$stmt = $conn->prepare("DELETE FROM faculties WHERE id=?");
$stmt->bind_param('i', $id);
if ($stmt->execute()) {
    // No admin action logging
    echo json_encode(['status'=>'success']);
} else {
    echo json_encode(['status'=>'false','message'=>'فشل في حذف الكلية']);
}
$stmt->close();
