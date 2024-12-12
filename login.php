<?php

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

    
    $login_id = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn kiểm tra thông tin đăng nhập
    $sql = "SELECT * FROM users WHERE login_id = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $login_id, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        session_start();

        // Lấy thông tin người dùng
        $user = $result->fetch_assoc();

        $_SESSION['login_id'] = $user['login_id'];

        // Chuyển hướng tới trang HOME
        header("Location: https://localhost/project2/HOME.php");
        exit();
    } else {
        
        $error = "Tên đăng nhập hoặc mật khẩu không chính xác!";
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
        .login-container {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 350px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .login-container form {
            display: flex;
            flex-direction: column;
        }
        .login-container label {
            text-align: left;
            margin-bottom: 5px;
        }
        .login-container input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .g-recaptcha {
            display: flex;
            justify-content: center;
        }
        .login-container button {
            padding: 8px 60px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            align-self: center;
        }
        .login-container button:hover {
            background-color: #0056b3;
        }
        .forgot-password {
            margin-top: 15px;
            font-size: 14px;
        }
        .forgot-password a {
            color: #0056b3;
            text-decoration: none;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
    <script>
        function validateForm(event) {
            
            event.preventDefault();

            
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const errorMessage = document.getElementById('error-message');

            
            if (username === "") {
                errorMessage.textContent = "Vui lòng nhập tên người dùng.";
                return false;
            }
            if (username.length < 4) {
                errorMessage.textContent = "Tên người dùng phải có ít nhất 4 ký tự.";
                return false;
            }
            if (password === "") {
                errorMessage.textContent = "Vui lòng nhập mật khẩu.";
                return false;
            }
            if (password.length < 6) {
                errorMessage.textContent = "Mật khẩu phải có ít nhất 6 ký tự.";
                return false;
            }

            // Nếu hợp lệ gửi 
            errorMessage.textContent = "";
            event.target.submit();
        }
    </script>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form onsubmit="validateForm(event)" method="POST">
            <label for="username">Người dùng:</label>
            <input type="text" id="username" name="username" >
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" >
            <div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY"></div>
            <div id="error-message" class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <button type="submit">Đăng nhập</button>
        </form>
        <div class="forgot-password">
            <a href="/forgot-password"><i>Quên password</i></a>
        </div>
    </div>
</body>
</html>
