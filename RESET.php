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

// Lấy tên người dùng từ session
$reset_username = isset($_SESSION['reset_username']) ? $_SESSION['reset_username'] : null;

// Kiểm tra nếu không có tên người dùng, chuyển hướng về trang yêu cầu reset
if (!$reset_username) {
    header("Location: REQUEST.php");
    exit();
}

// Xử lý khi người dùng nhấn nút Reset
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];

    // Validate mật khẩu
    if (empty($new_password)) {
        $error = "Hãy nhập mật khẩu mới.";
    } elseif (strlen($new_password) < 6) {
        $error = "Mật khẩu phải có tối thiểu 6 ký tự.";
    } else {
        // Mã hóa mật khẩu mới bằng MD5
        $hashed_password = md5($new_password);

        // Cập nhật mật khẩu mới và xóa reset_password_token
        $update_sql = "UPDATE admins SET password = ?, reset_password_token = '' WHERE name = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ss", $hashed_password, $reset_username);

        if ($stmt->execute()) {
            // Xóa session sau khi hoàn tất
            unset($_SESSION['reset_username']);
            // Chuyển hướng về màn hình đăng nhập
            header("Location: login.php");
            exit();
        } else {
            $error = "Không thể đặt lại mật khẩu. Vui lòng thử lại.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
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
<h2>Đặt lại mật khẩu</h2>
<?php if ($reset_username): ?>
    <p>Tên người dùng: <strong><?php echo htmlspecialchars($reset_username); ?></strong></p>
<?php endif; ?>
<form method="POST">
    <?php if (!empty($error)) : ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <div class="form-group">
        <label for="new_password">Mật khẩu mới:</label>
        <input type="password" name="new_password" id="new_password" placeholder="Nhập mật khẩu mới" required>
    </div>
    <button type="submit">Reset</button>
</form>
</body>
</html>
