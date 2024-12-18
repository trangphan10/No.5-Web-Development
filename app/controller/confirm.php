<?php
session_start();

// Kiểm tra nếu không có dữ liệu trong session, quay về input.php
if (!isset($_SESSION['form_data'])) {
    header('Location: input.php');
    exit();
}

$formData = $_SESSION['form_data'];
$avatarPath = $formData['avatar'] ?? ''; // Đường dẫn avatar

// Kết nối cơ sở dữ liệu và xử lý khi nhấn nút "Đăng Ký"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    $conn = new mysqli("127.0.0.1", "root", "", "no5");

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Chèn dữ liệu vào bảng events
    $stmt = $conn->prepare("INSERT INTO events (username, slogan, leader, avatar, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssss",
        $formData['ten_su_kien'],
        $formData['slogan'],
        $formData['leader'],
        $avatarPath,
        $formData['mo_ta_chi_tiet']
    );

    if ($stmt->execute()) {
        // Xóa session sau khi lưu thành công
        unset($_SESSION['form_data']);
        echo "<p style='color:green; text-align:center;'>Đăng ký sự kiện thành công!</p>";
        echo "<p style='text-align:center;'><a href='input.php'>Quay về trang nhập liệu</a></p>";
    } else {
        echo "<p style='color:red; text-align:center;'>Lỗi khi lưu dữ liệu: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận Thông Tin</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    h1 {
        text-align: center;
        color: #007bff;
    }

    .container {
        max-width: 600px;
        margin: auto;
        border: 1px solid #ccc;
        padding: 20px;
        border-radius: 5px;
    }

    .field {
        margin-bottom: 15px;
    }

    .field label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    .field p {
        margin: 0;
        padding: 8px;
        background: #f9f9f9;
        border: 1px solid #ddd;
    }

    img {
        max-width: 200px;
        height: auto;
        display: block;
        margin-top: 10px;
    }

    .buttons {
        text-align: center;
        margin-top: 20px;
    }

    button {
        padding: 10px 20px;
        border: none;
        color: white;
        cursor: pointer;
        margin: 5px;
    }

    .btn-edit {
        background-color: #ffc107;
    }

    .btn-confirm {
        background-color: #28a745;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Xác Nhận Thông Tin Sự Kiện</h1>

        <div class="field">
            <label>Tên sự kiện:</label>
            <p><?php echo htmlspecialchars($formData['ten_su_kien']); ?></p>
        </div>

        <div class="field">
            <label>Slogan:</label>
            <p><?php echo htmlspecialchars($formData['slogan']); ?></p>
        </div>

        <div class="field">
            <label>Leader:</label>
            <p><?php echo htmlspecialchars($formData['leader']); ?></p>
        </div>

        <div class="field">
            <label>Mô tả chi tiết:</label>
            <p><?php echo nl2br(htmlspecialchars($formData['mo_ta_chi_tiet'])); ?></p>
        </div>

        <div class="field">
            <label>Avatar:</label>
            <?php if (!empty($avatarPath)): ?>
            <img src="<?php echo htmlspecialchars($avatarPath); ?>" alt="Avatar">
            <?php else: ?>
            <p>Không có ảnh.</p>
            <?php endif; ?>
        </div>

        <!-- Nút Xác nhận và Sửa lại -->
        <form method="post">
            <div class="buttons">
                <button type="submit" name="confirm" class="btn-confirm">Đăng Ký</button>
                <button type="button" onclick="window.location='input.php'" class="btn-edit">Sửa Lại</button>
            </div>
        </form>
    </div>
</body>

</html>