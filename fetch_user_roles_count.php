<?php
header('Content-Type: application/json; charset=utf-8');
include 'conn.php';

$facultyRes = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE role='faculty'");
$studentRes = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE role='student'");

$facultyCount = 0;
$studentCount = 0;

if ($facultyRes) {
    $row = mysqli_fetch_assoc($facultyRes);
    $facultyCount = isset($row['cnt']) ? (int)$row['cnt'] : 0;
}
if ($studentRes) {
    $row = mysqli_fetch_assoc($studentRes);
    $studentCount = isset($row['cnt']) ? (int)$row['cnt'] : 0;
}

echo json_encode([
    'faculty' => $facultyCount,
    'student' => $studentCount
]);
