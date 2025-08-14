<?php
header('Content-Type: application/json; charset=utf-8');
include 'conn.php';

$result = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $count = isset($row['cnt']) ? (int)$row['cnt'] : 0;
    echo json_encode(['count' => $count]);
} else {
    http_response_code(500);
    echo json_encode(['count' => 0, 'error' => mysqli_error($conn)]);
}
