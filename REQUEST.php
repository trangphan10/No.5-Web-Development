<?php
session_start();
$error = "";
$success = "";

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "no5";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý yêu cầu reset mật khẩu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    // Kiểm tra tên người dùng có tồn tại trong bảng admins không
    $sql = "SELECT id FROM admins WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Sinh reset token
        $reset_token = uniqid("", true);

        // Cập nhật reset_password_token vào DB
        $update_sql = "UPDATE admins SET reset_password_token = ? WHERE name = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $reset_token, $username);

        if ($update_stmt->execute()) {
            // Chuyển hướng đến form đặt lại mật khẩu trong RESET.php
            $_SESSION['reset_username'] = $username; // Lưu tên người dùng vào session
            header("Location: RESET.php");
            exit();
        } else {
            $error = "Không thể gửi yêu cầu reset mật khẩu. Vui lòng thử lại!";
        }
        $update_stmt->close();
    } else {
        $error = "Tên người dùng không tồn tại!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu đặt lại mật khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            padding: 20px;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .error {
            color: red;
            font-size: 12px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            font-size: 14px;
            margin-bottom: 10px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<h2>Yêu cầu đặt lại mật khẩu</h2>
<form method="POST">
    <?php if (!empty($error)) : ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <div class="form-group">
        <label for="username">Tên người dùng:</label>
        <input type="text" name="username" id="username" required placeholder="Nhập tên người dùng">
    </div>
    <button type="submit">Gửi yêu cầu</button>
</form>
</body>
</html>
