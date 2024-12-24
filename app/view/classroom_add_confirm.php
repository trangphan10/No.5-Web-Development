<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận Thông Tin</title>
</head>
<body>
    <h2>Xác Nhận Thông Tin</h2>
    <form method="POST" action="/classroom.php?action=complete">
        <label for="fullname">Họ và Tên</label>
        <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($fullname) ?>" readonly/><br/>

        <label for="id">ID</label>
        <input type="text" id="id" name="id" value="<?= htmlspecialchars($id) ?>" readonly/><br/>

        <label>Phân loại</label>
        <input type="text" value="<?= htmlspecialchars($category) ?>" readonly/><br/>

        <label for="avatar">Avatar</label>
        <img src="<?= $avatar ?>" alt="Avatar" style="width:100px;height:100px;"><br/>

        <label for="description">Mô tả thêm</label>
        <textarea id="description" name="description" readonly><?= htmlspecialchars($description) ?></textarea><br/>

        <button type="button" onclick="window.location.href='/classroom.php?action=register'">Sửa</button>
        <button type="submit" class="confirm-btn" onclick="confirmForm()">Xác Nhận</button>
    </form>
</body>
</html>
