<?php
// Include the database connection file
require_once __DIR__ . '/../../common/db.php';

// Lấy comment_id từ URL
$comment_id = $_GET['id'] ?? 0;

// Lấy thông tin comment hiện tại từ database
$sql = "SELECT * FROM event_comments WHERE id = :comment_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['comment_id' => $comment_id]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comment) {
    die("Comment không tồn tại.");
}

// Lấy danh sách tất cả comment thuộc sự kiện
$sql = "SELECT * FROM event_comments WHERE event_id = :event_id ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['event_id' => $comment['event_id']]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý cập nhật comment
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content']);
    $avatar = $_FILES['avatar'];

    // Validate dữ liệu
    if (empty($content)) {
        $errors['content'] = "Hãy nhập nội dung bình luận.";
    } elseif (strlen($content) > 1000) {
        $errors['content'] = "Không nhập quá 1000 ký tự.";
    }

    // Xử lý upload avatar mới (nếu có)
    if (!empty($avatar['name'])) {
        $upload_dir = 'web/avatar/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $avatar_name = time() . '_' . basename($avatar['name']);
        $upload_file = $upload_dir . $avatar_name;

        if (!move_uploaded_file($avatar['tmp_name'], $upload_file)) {
            $errors['avatar'] = "Upload avatar thất bại.";
        }
    } else {
        $avatar_name = $comment['avatar']; // Giữ nguyên avatar cũ nếu không thay đổi
    }

    // Nếu không có lỗi, cập nhật vào database
    if (empty($errors)) {
        $sql = "UPDATE event_comments SET avatar = :avatar, content = :content WHERE id = :comment_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'avatar' => $avatar_name,
            'content' => $content,
            'comment_id' => $comment_id
        ]);

        // Sau khi cập nhật, quay lại trang danh sách comment hoặc sự kiện
        header("Location: event_comments.php?id=" . $comment['event_id']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment của sự kiện</title>
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
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
        }
    </style>
</head>
<body>
<h1>Comment của sự kiện</h1>
<!-- Danh sách comment -->
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
    <?php if (count($comments) > 0): ?>
        <?php $no = 1; ?>
        <?php foreach ($comments as $row): ?>
            <tr>
                <td>No<?= $no++; ?></td>
                <td><img src="web/avatar/<?= htmlspecialchars($row['avatar']); ?>" alt="Avatar" width="50"></td>
                <td><?= htmlspecialchars($row['content']); ?></td>
                <td>
                    <?php if ($row['id'] == $comment_id): ?>
                        <strong>Đang sửa</strong>
                    <?php else: ?>
                        <a href="edit_comment.php?id=<?= $row['id']; ?>">Sửa</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">Không có comment nào.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<!-- Form sửa comment -->
<h2>Sửa comment</h2>
<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="avatar">Avatar:</label>
        <input type="file" name="avatar" id="avatar">
        <?php if (!empty($comment['avatar'])): ?>
            <p>Avatar hiện tại:</p>
            <img src="web/avatar/<?= htmlspecialchars($comment['avatar']); ?>" alt="Avatar" width="100">
        <?php endif; ?>
        <?php if (isset($errors['avatar'])): ?>
            <span class="error"><?= $errors['avatar']; ?></span>
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label for="content">Nội dung:</label>
        <textarea name="content" id="content" rows="5" required><?= htmlspecialchars($comment['content']); ?></textarea>
        <?php if (isset($errors['content'])): ?>
            <span class="error"><?= $errors['content']; ?></span>
        <?php endif; ?>
    </div>
    <button type="submit">Cập nhật</button>
    <a href="event_comments.php?id=<?= $comment['event_id']; ?>">Hủy</a>
</form>
</body>
</html>
