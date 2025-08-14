<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$host = "localhost";
$dbname = "iua_database";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}
?>