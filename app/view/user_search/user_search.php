<?php 
require_once '../../controller/user_controller.php';

$userController = new UserController();
$type = $_GET['type'] ?? '';
$keyword = $_GET['keyword'] ?? '';
$users = [];

if (!empty($type) || !empty($keyword)) {
    $users = $userController->search($type, $keyword);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tìm kiếm người dùng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 40px;
        }

        label, select, input {
            margin: 15px;
        }

        select, input[type="text"] {
            border: 2px solid black; /* Viền đen */
            background-color: white; /* Nền trắng */
            padding: 10px; /* Khoảng cách bên trong */
            border-radius: 5px; /* Góc bo tròn */
            width: 220px; /* Độ rộng */
            font-size: 14px; /* Kích thước chữ */
        }

        button {
            margin: 10px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        .action-buttons a {
            margin: 0 10px;
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 3px;
            font-size: 13px;
        }

        .action-buttons a.delete {
            background-color: #e74c3c;
        }

        .action-buttons a.edit {
            background-color: #3498db;
        }

        .action-buttons a:hover {
            opacity: 0.8;
        }

        p {
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <h1>Tìm kiếm người dùng</h1>
    <form method="GET" action="">
        <div>
            <label for="type">Phân loại:</label>
            <select name="type" id="type">
                <option value="">--Chọn--</option>
                <option value="1" <?= $type == '1' ? 'selected' : '' ?>>Sinh viên</option>
                <option value="2" <?= $type == '2' ? 'selected' : '' ?>>Giáo viên</option>
                <option value="3" <?= $type == '3' ? 'selected' : '' ?>>Cựu sinh viên</option>
            </select>
        </div>
        <div>
            <label for="keyword">Từ khóa:</label>
            <input type="text" id="keyword" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Nhập tên hoặc mô tả">
        </div>
        <button type="submit">Tìm kiếm</button>
    </form>

    <?php if (!empty($type) || !empty($keyword)): ?>
        <p>Số thành viên tìm thấy: <strong><?= count($users) ?></strong></p>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tên thành viên</th>
                    <th>Phân loại</th>
                    <th>Mô tả chi tiết</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $index => $user): ?>
                    <tr>
                        <td><?= intval($index) + 1 ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td>
                            <?= $user['type'] == 1 ? 'Sinh viên' : ($user['type'] == 2 ? 'Giáo viên' : 'Cựu sinh viên') ?>
                        </td>
                        <td><?= htmlspecialchars($user['description']) ?></td>
                        <td class="action-buttons">
                            <a href="user_edit_input.php?id=<?= htmlspecialchars($user['id']) ?>" class="edit">Sửa</a>
                            <a href="user_delete.php?id=<?= htmlspecialchars($user['id']) ?>" class="delete" onclick="return confirm('Bạn chắc chắn muốn xóa <?= htmlspecialchars($user['name']) ?>?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Không tìm thấy thành viên nào.</p>
    <?php endif; ?>
</body>
</html>
