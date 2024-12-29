<?php
// Nhận dữ liệu từ form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = $_POST['fullname'];
    $id = $_POST['id'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $avatar = $_FILES['avatar'];

    // Xử lý file avatar
    $avatarFile = $avatar['name'];
    $avatarTmp = $avatar['tmp_name'];

    // Tạo thư mục lưu ảnh tạm thời
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/No.5-Web-Development/web/avatar/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Di chuyển ảnh vào thư mục uploads
    $avatarPath = $uploadDir . basename($avatarFile);
    move_uploaded_file($avatarTmp, $avatarPath);
} else {
    // Nếu không phải POST, chuyển hướng về trang đăng ký
    header('Location: classroom_add_input.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/No.5-Web-Development/web/index.css">
    <script src="/No.5-Web-Development/web/index.js" defer></script> 
    <title>Xác Nhận Thông Tin</title>
</head>
<body>
    <div class="container">
        <h2 class="form-title">Xác Nhận Thông Tin</h2>
        <form method="POST" action="/No.5-Web-Development/classroom.php?action=complete" class="confirmation-form">
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
                <!-- Hiển thị ảnh avatar -->
                <input type='hidden' name='avatar' value="<?php echo htmlspecialchars($avatarPath); ?>">
    <!-- Hiển thị ảnh avatar nếu có -->
                <?php if (isset($avatarFile)) : ?>
                    <img src="/No.5-Web-Development/web/avatar/<?php echo basename($avatarFile); ?>" alt="Avatar" style="width:100px;height:100px;">
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="description">Mô tả thêm</label>
                <textarea id="description" name="description" readonly><?php echo htmlspecialchars($description); ?></textarea>
            </div>

            <div class="form-group">
                <button type="button" class="edit-btn" onclick="window.location.href='/No.5-Web-Development/app/view/classroom_add_input.php'">Sửa</button>
                <button type="submit" class="confirm-btn">Xác Nhận</button>
            </div>
        </form>
    </div>
</body>
</html>
