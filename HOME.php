<?php
session_start();
// Lấy thông tin đăng nhập từ session
date_default_timezone_set('Asia/Ho_Chi_Minh'); // Đặt múi giờ Việt Nam

$username = $_SESSION['login_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 20px;
        }
        .container {
            text-align: center;
            border: 1px solid #ddd;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
        }
        .container h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .container table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 80%;
        }
        .container td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .container a {
            text-decoration: none;
            color: #007bff;
        }
        .container a:hover {
            text-decoration: underline;
        }
        .HOME-info {
            text-align: left; /* Căn văn bản bên trái */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>HOME</h1>
        <div class="HOME-info">
            <p>Tên login: <?php echo $username; ?></p>
            <p>Thời gian login: <?php echo date('Y-m-d H:i'); ?></p>

        </div>

        <table>
            <tr>
                <td>Phòng học</td>
                <td>Người dùng</td>
                <td>Sự kiện</td>
                <td>Tổ chức sự kiện</td>
            </tr>
            <tr>
                <td>
                    <a href="search_classroom.php">Tìm kiếm</a><br>
                    <a href="add_classroom.php">Thêm mới</a>
                </td>
                <td>
                    <a href="search_user.php">Tìm kiếm</a><br>
                    <a href="add_user.php">Thêm mới</a>
                </td>
                <td>
                    <a href="search_event.php">Tìm kiếm</a><br>
                    <a href="add_event.php">Thêm mới</a>
                </td>
                <td>
                    <a href="search_organizer.php">Tìm kiếm</a><br>
                    <a href="add_organizer.php">Thêm mới</a>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
