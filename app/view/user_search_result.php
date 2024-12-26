<?php
require_once '../controller/user_controller.php';

$userController = new UserController();
$type = $_GET['type'] ?? '';
$keyword = $_GET['keyword'] ?? '';
$users = $userController->search($type, $keyword);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
</head>
<body>
    <h1>Kết quả tìm kiếm</h1>
    <p>Số kết quả tìm thấy: <?= count($users) ?></p>
    <table border="1">
        <tr>
            <th>STT</th>
            <th>Tên thành viên</th>
            <th>Phân loại</th>
            <th>Mô tả chi tiết</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($users as $index => $user): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td>
                    <?= $user['type'] == 1 ? 'Sinh viên' : ($user['type'] == 2 ? 'Giáo viên' : 'Sinh viên cũ') ?>
                </td>
                <td><?= htmlspecialchars($user['description']) ?></td>
                <td>
                    <a href="../view/user_edit.php?id=<?= $user['id'] ?>">Sửa</a>
                    <a href="user_delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Bạn chắc chắn muốn xóa <?= htmlspecialchars($user['name']) ?>?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
