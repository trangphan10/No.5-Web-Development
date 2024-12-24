<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="./app/web/index.css">
    <script src="./app/web/index.js"></script>
</head>
<body>
    <h2>Đăng Ký Thông Tin</h2>
    <form method="POST" enctype="multipart/form-data" action="/classroom.php?action=confirm">
        <label for="fullname">Họ và Tên</label>
        <input type="text" id="fullname" name="fullname" required/><br/>

        <label for="id">ID</label>
        <input type="text" id="id" name="id" required/><br/>

        <label>Phân loại</label>
        <input type="radio" name="category" value="Giáo viên" /> Giáo viên
        <input type="radio" name="category" value="Sinh viên" /> Sinh viên
        <input type="radio" name="category" value="Cựu sinh viên" /> Cựu sinh viên<br/>

        <label for="avatar">Avatar</label>
        <input type="file" id="avatar" name="avatar" required/><br/>

        <label for="description">Mô tả thêm</label>
        <textarea id="description" name="description"></textarea><br/>

        <button type="submit" class="submit-btn" onclick="validateInfo(event)">Xác Nhận</button>
    </form>
</body>
</html>
