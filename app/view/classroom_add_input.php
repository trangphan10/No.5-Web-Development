<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="/No.5-Web-Development/web/index.css">
    <script src="/No.5-Web-Development/web/index.js" defer></script>
    
</head>
<body>
    <div class="form-container">
        <h2>Đăng Ký Thông Tin</h2>
        <form method="POST" enctype="multipart/form-data" id="register-form">
            <div class="form-group">
                <label for="fullname">Họ và Tên</label>
                <input type="text" id="fullname" name="fullname" required />
                <span id="fullname-error" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="id">ID</label>
                <input type="text" id="id" name="id" required />
                <span id="id-error" class="error-message"></span>
            </div>

            <div class="form-group">
                <label>Phân loại</label>
                <div class="radio-group">
                    <label><input type="radio" name="category" value="Giáo viên" /> Giáo viên</label>
                    <label><input type="radio" name="category" value="Sinh viên" /> Sinh viên</label>
                    <label><input type="radio" name="category" value="Cựu sinh viên" /> Cựu sinh viên</label>
                </div>
                <span id="category-error" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="avatar">Avatar</label>
                <input type="file" id="avatar" name="avatar" required />
                <span id="avatar-error" class="error-message"></span>
            </div>

            <div class="form-group">
                <label for="description">Mô tả thêm</label>
                <textarea id="description" name="description"></textarea>
                <span id="description-error" class="error-message"></span>
            </div>

            <!-- Nút submit -->
            <button type="submit" class="submit-btn" onclick="validateInfo(event)">Xác Nhận</button>
        </form>
    </div>

</body>
</html>
