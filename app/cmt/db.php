<?php
// db.php: Kết nối cơ sở dữ liệu
$host = "localhost";
$user = "root"; // Tài khoản database
$password = ""; // Mật khẩu database
$database = "event_management"; // Tên database

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('Kết nối thất bại: ' . $conn->connect_error);
} else {
    echo 'Kết nối thành công!';
}
?>