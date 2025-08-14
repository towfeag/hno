
<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "universities";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}
?>
