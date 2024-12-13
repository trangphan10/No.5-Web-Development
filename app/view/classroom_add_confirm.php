<?php
session_start();
$userData = $_SESSION['user_data'] ?? null;
if (!$userData) {
    header('Location: classroom_add_input.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận thông tin</title>
</head>
<body>
    <h1>Xác nhận thông tin</h1>
    <p>Họ và tên: <?= htmlspecialchars($userData['name']) ?></p>
    <p>Loại người dùng: <?= htmlspecialchars($userData['category']) ?></p>
    <p>Mã người dùng: <?= htmlspecialchars($userData['id']) ?></p>
    <p>Mô tả: <?= htmlspecialchars($userData['description']) ?></p>
    <p><img src="<?= $userData['avatar'] ?>" alt="Avatar" width="100"></p>
    <form action="../controller/user_confirm.php" method="post">
        <button type="submit">Đăng ký</button>
        <a href="classroom_add_input.php">Quay lại</a>
    </form>
</body>
</html>
