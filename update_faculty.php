<?php
include 'conn.php';

$id =   $_POST['id'];
$name = $_POST['name'];
$desc = $_POST['description'];
$link = $_POST['link'];


if ($id <= 0 || $name === '') {
    echo json_encode(['status'=>'false','message'=>'بيانات غير صالحة']);
    exit;
}

$stmt = $conn->prepare("UPDATE faculties SET name=?, description=?, link=? WHERE id=?");
$stmt->bind_param('sssi', $name, $desc, $link, $id);
if ($stmt->execute()) {
    // No admin action logging
    echo json_encode(['status'=>'true']);
} else {
    echo json_encode(['status'=>'false','message'=>'فشل في تحديث الكلية']);
}
$stmt->close();
