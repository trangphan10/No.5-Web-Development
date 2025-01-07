<?php
require_once __DIR__ . '/../project_main/app/common/db.php';

session_start();

$error = "";

// Reset error khi load lại trang
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $error = ""; // Reset lỗi trên server
    echo "<script>document.addEventListener('DOMContentLoaded', () => { 
        document.getElementById('error-message').textContent = ''; 
    });</script>";
}



function create_capacha($text)
{
    $width = 200;
    $height = 100;
    $fontfile = "OpenSans-Regular.ttf";

    // Tạo ảnh gốc
    $image = imagecreatetruecolor($width, $height);

    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);

    imagefill($image, 0, 0, $white);
    imagettftext($image, 25, rand(-20, 20), $width / 4, 60, $black, $fontfile, $text);

    // tao nhieu den
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            if (mt_rand(0, 2) == 2) { // 33% xác suất
                imagesetpixel($image, $x, $y, $black);
            }
        }
    }

    // Tạo nhiễu trắng
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            if (mt_rand(0, 20) == 7) { // 5% xác suất
                imagesetpixel($image, $x, $y, $white);
            }
        }
    }

    // Tạo ảnh mới để áp dụng hiệu ứng gợn sóng
    $warped_image = imagecreatetruecolor($width, $height);
    imagefill($warped_image, 0, 0, imagecolorallocate($warped_image, 255, 255, 255));

    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            $index = imagecolorat($image, $x, $y);
            $color_comp = imagecolorsforindex($image, $index);

            $color = imagecolorallocate($warped_image, $color_comp['red'], $color_comp['green'], $color_comp['blue']);

            // Áp dụng gợn sóng
            $imageX = $x;
            $imageY = $y + sin($x / 8) * 8;

            // Đặt pixel lên ảnh mới
            if ($imageY >= 0 && $imageY < $height) {
                imagesetpixel($warped_image, $imageX, $imageY, $color);
            }
        }
    }

    // Vẽ viền trắngtrắng trên ảnh đã áp dụng gợn sóng
    $red = imagecolorallocate($warped_image, 255, 255, 255); // Màu trắngtrắng
    imagesetthickness($warped_image, 15); 
    imagerectangle($warped_image, 0, 0, $width - 5, $height - 5, $red); // Vẽ khung viền màu trắng

    // Lưu và giải phóng bộ nhớ
    $path = "capacha.jpg";
    imagejpeg($warped_image, $path);
    imagedestroy($warped_image);
    imagedestroy($image);

    return $path;
}
function reset_captcha() {
    $captcha_code = rand(10000, 99999);
    $_SESSION['captcha_code'] = $captcha_code;
    create_capacha($captcha_code);
}
$filename = session_id();

if (!isset($_SESSION['captcha_code'])) {
    $error = "";
    reset_captcha();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $database = new Database();
        $conn = $database->getConnection();

        $login_id = $_POST['username'];
        $password = $_POST['password'];

        // Kiểm tra thông tin đăng nhập trước
        $sql = "SELECT * FROM admins WHERE login_id = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $login_id, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Nếu thông tin đăng nhập đúng, kiểm tra CAPTCHA
            $user_captcha = $_POST['captcha'];
            $stored_captcha = $_SESSION['captcha_code'];

            if ($user_captcha != $stored_captcha) {
                $error = "CAPTCHA không chính xác. Vui lòng thử lại.";
                reset_captcha();
            } else {
                $user = $result->fetch_assoc();
                $_SESSION['login_id'] = $user['login_id'];
                header("Location: HOME.php");
                exit();
            }
        } else {
            $error = "Thông tin đăng nhập không chính xác.";
            reset_captcha();
        }

        $stmt->close();
    } catch (Exception $e) {
        $error = "Đã xảy ra lỗi: " . $e->getMessage();
    } finally {
        if (isset($conn)) {
            $conn->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        .login-container img {
            width: 160px;
            height: 80px;
            object-fit: contain;
            align-self: center;
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
                reset_captcha();
                return false;
            }
            if (username.length < 4) {
                errorMessage.textContent = "Tên người dùng phải có ít nhất 4 ký tự.";
                reset_captcha();
                return false;
            }
            if (password === "") {
                errorMessage.textContent = "Vui lòng nhập mật khẩu.";
                reset_captcha();
                return false;
            }
            if (password.length < 6) {
                errorMessage.textContent = "Mật khẩu phải có ít nhất 6 ký tự.";
                reset_captcha();
                return false;
            }

            errorMessage.textContent = "";
            event.target.submit();
        }
        
    </script>
</head>
<body>
<div class="login-container">
        <h2>Login</h2>
        <form method="POST" onsubmit="validateForm(event)">
            <label for="username">Người dùng:</label>
            <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
    
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
    
            <label for="captcha">CAPTCHA:</label>
            <input type="text" id="captcha" name="captcha" placeholder="Nhập CAPTCHA">
            <img src="capacha.jpg" alt="CAPTCHA">
    
            <div id="error-message" class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <button type="submit">Đăng nhập</button>
        </form>

        <div class="forgot-password">
            <a href="REQUEST.php"><i>Quên password</i></a>
        </div>
    </div>

</body>
</html>
