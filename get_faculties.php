<?php
include 'conn.php';
$result = mysqli_query($conn, "SELECT * FROM faculties");
$faculties = [];
while ($row = mysqli_fetch_assoc($result)) {
    $faculties[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'description' => $row['description'],
        'link' => $row['link']
    ];
}
echo json_encode($faculties, JSON_UNESCAPED_UNICODE);
