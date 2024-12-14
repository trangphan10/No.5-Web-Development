<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tìm kiếm người dùng</title>
</head>
<body>
    <h1>Tìm kiếm người dùng</h1>
    <form method="GET" action="../controller/user_search.php">
        <label for="type">Phân loại:</label>
        <select name="type" id="type">
            <option value="">Tất cả</option>
            <option value="1" <?= $type == '1' ? 'selected' : '' ?>>Sinh viên</option>
            <option value="2" <?= $type == '2' ? 'selected' : '' ?>>Giáo viên</option>
            <option value="3" <?= $type == '3' ? 'selected' : '' ?>>Sinh viên cũ</option>
        </select>

        <label for="keyword">Từ khóa:</label>
        <input type="text" name="keyword" id="keyword" value="<?= htmlspecialchars($keyword) ?>">

        <button type="submit">Tìm kiếm</button>
    </form>

    <h2>Số thành viên tìm thấy: <?= count($users) ?></h2>
    <table border="1">
        <tr>
            <th>STT</th>
            <th>Tên thành viên</th>
            <th>Phân loại</th>
            <th>Mô tả chi tiết</th>
            <th>Action</th>
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
                    <a href="../controller/user_edit.php?id=<?= $user['id'] ?>">Sửa</a>
                    <a href="../controller/user_delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Bạn chắc chắn muốn xóa <?= htmlspecialchars($user['name']) ?>?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
