<?php
require_once 'db.php';

// Lấy danh sách comment
$event_id = 1; // ID của sự kiện (sửa theo nhu cầu)
$sql = "SELECT * FROM event_comments WHERE event_id = $event_id ORDER BY id DESC";
$result = $conn->query($sql);

// Xử lý thêm mới comment
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content']);
    $avatar = $_FILES['avatar'];

    // Validate dữ liệu
    if (empty($avatar['name'])) {
        $errors['avatar'] = "Hãy chọn avatar.";
    }
    if (empty($content)) {
        $errors['content'] = "Hãy nhập nội dung comment.";
    } elseif (strlen($content) > 1000) {
        $errors['content'] = "Không nhập quá 1000 ký tự.";
    }

    // Nếu không có lỗi, xử lý upload file và lưu vào database
    if (empty($errors)) {
        $upload_dir = 'web/avatar/';
        $avatar_name = time() . '_' . basename($avatar['name']);
        $upload_file = $upload_dir . $avatar_name;

        if (move_uploaded_file($avatar['tmp_name'], $upload_file)) {
            $stmt = $conn->prepare("INSERT INTO event_comments (event_id, avatar, content) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $event_id, $avatar_name, $content);
            $stmt->execute();
            $stmt->close();

            // Sau khi thêm mới, refresh trang
            header("Location: comments.php");
            exit();
        } else {
            $errors['avatar'] = "Upload avatar thất bại.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Comments</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Tên sự kiện: Chào đón sinh viên K60</h1>

    <!-- Phần danh sách comment -->
    <h2>Comments đã đăng ký</h2>
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>Avatar</th>
                <th>Nội dung comment</th>
                <th>Sửa</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php $no = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>No<?= $no++; ?></td>
                        <td><img src="web/avatar/<?= htmlspecialchars($row['avatar']); ?>" alt="Avatar" width="50"></td>
                        <td><?= htmlspecialchars($row['content']); ?></td>
                        <td><a href="edit_comment.php?id=<?= $row['id']; ?>">Sửa</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Không có comment nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Phần thêm mới comment -->
    <h2>Thêm mới comment</h2>
    <form method="POST" enctype="multipart/form-data">
        <div>
            <label for="avatar">Avatar:</label>
            <input type="file" name="avatar" id="avatar">
            <?php if (isset($errors['avatar'])): ?>
                <span class="error"><?= $errors['avatar']; ?></span>
            <?php endif; ?>
        </div>
        <div>
            <label for="content">Nội dung:</label>
            <textarea name="content" id="content" rows="4" cols="50"></textarea>
            <?php if (isset($errors['content'])): ?>
                <span class="error"><?= $errors['content']; ?></span>
            <?php endif; ?>
        </div>
        <button type="submit">Thêm mới</button>
    </form>
</body>
</html>