<?php
header('Content-Type: application/json; charset=utf-8');
include 'conn.php';
$res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM faculties");
$row = mysqli_fetch_assoc($res);
echo json_encode(['count'=>(int)$row['cnt']]);
