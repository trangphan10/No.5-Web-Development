<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "user_management";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $login_id = trim($_POST['username']);

    // Kiểm tra dữ liệu nhập
    if (empty($login_id)) {
        $error = "Hãy nhập login ID!";
    } elseif (strlen($login_id) < 4) {
        $error = "Hãy nhập login ID tối thiểu 4 ký tự!";
    } else {
        // Kiểm tra login_id có tồn tại không
        $check_sql = "SELECT * FROM users WHERE login_id = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $login_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Tạo token reset_password
            $reset_token = microtime(true);
            $update_sql = "UPDATE users SET reset_password_token = ? WHERE login_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ds", $reset_token, $login_id);
            $stmt->execute();

            // Chuyển về màn hình login
            header("Location: login.php");
            exit();
        } else {
            $error = "Login ID không tồn tại trong hệ thống!";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            width: 350px;
            text-align: center;
        }
        .container h2 {
            margin-bottom: 20px;
        }
        .container form {
            display: flex;
            flex-direction: column;
        }
        .container input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .container button {
            padding: 8px 60px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .container button:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Reset Password</h2>
    <form method="POST">
        <label for="username">Người dùng:</label>
        <input type="text" id="username" name="username">
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <button type="submit">Gửi yêu cầu reset password</button>
    </form>
</div>
</body>
</html>
