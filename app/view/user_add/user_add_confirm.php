<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = $_POST['fullname'];
    $id = $_POST['id'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $avatar = $_FILES['avatar'];

    // Kiểm tra lỗi tải tệp lên
    if ($avatar['error'] !== UPLOAD_ERR_OK) {
        die("Lỗi khi tải tệp lên: " . $avatar['error']);
    }

    // Xử lý file avatar
    $avatarFile = $avatar['name'];
    $avatarTmp = $avatar['tmp_name'];

    // Tạo thư mục lưu ảnh tạm thời
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '../../web/avatar/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Di chuyển ảnh vào thư mục uploads
    $avatarPath = $uploadDir . basename($avatarFile);
    if (!move_uploaded_file($avatarTmp, $avatarPath)) {
        die("Không thể di chuyển tệp tải lên.");
    }
} else {
    header('Location: user_add_input.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../web/user_index.css">
    <script src="../../web/user_index.js" defer></script> 
    <title>Xác Nhận Thông Tin</title>
</head>
<body>
    <div class="container">
        <h2 class="form-title">Xác Nhận Thông Tin</h2>
        <form method="POST" action="/project_main/user.php?action=complete" class="confirmation-form">
            <div class="form-group">
                <label for="fullname">Họ và Tên</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" readonly />
            </div>

            <div class="form-group">
                <label for="id">ID</label>
                <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($id); ?>" readonly />
            </div>

            <div class="form-group">
                <label>Phân loại</label>
                <input type="text" id='category' name="category" value="<?php echo htmlspecialchars($category); ?>" readonly />
            </div>

            <div class="form-group">
                <label for="avatar">Avatar</label>
                <input type='hidden' name='avatarPath' value="<?php echo htmlspecialchars($avatarPath); ?>">
                <img src="<?php echo htmlspecialchars($avatarPath); ?>" alt="Avatar" style="width:100px;height:100px;">
            </div>

            <div class="form-group">
                <label for="description">Mô tả thêm</label>
                <textarea id="description" name="description" readonly><?php echo htmlspecialchars($description); ?></textarea>
            </div>

            <div class="form-group">
                <button type="button" class="edit-btn" onclick="window.location.href='../../app/view/user_add_input.php'">Sửa</button>
                <button type="submit" class="confirm-btn">Xác Nhận</button>
            </div>
        </form>
    </div>
</body>
</html>
