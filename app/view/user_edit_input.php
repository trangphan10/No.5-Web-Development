<?php
require_once __DIR__ . '/../common/db.php';

if (!isset($_GET['id'])) {
    die("User ID is required.");
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $type = intval($_POST['type']);
    $name = $conn->real_escape_string($_POST['name']);
    $unique_id = $conn->real_escape_string($_POST['unique_id']);
    $avatar = $conn->real_escape_string($_POST['avatar']);
    $description = $conn->real_escape_string($_POST['description']);

    $update_sql = "UPDATE users SET 
        type = ?, 
        name = ?, 
        unique_id = ?, 
        avatar = ?, 
        description = ? 
        WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("issssi", $type, $name, $unique_id, $avatar, $description, $id);

    if ($update_stmt->execute()) {
        echo "<p style='color: green;'>User updated successfully.</p>";
    } else {
        echo "<p style='color: red;'>Error updating user: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .form-container {
            width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group input[type="radio"] {
            margin-right: 10px;
        }
        .form-group .avatar {
            display: flex;
            align-items: center;
        }
        .form-group .avatar img {
            width: 100px;
            height: 100px;
            margin-right: 10px;
        }
        .form-group .avatar input[type="file"] {
            display: block;
        }
        .form-group textarea {
            height: 100px;
        }
        .form-group .button-container { 
            text-align: center; 
        }
        .form-group button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form action="user_edit_confirm.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <div class="form-group">
                <label for="name">Họ và Tên</label>
                <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="form-group">
                <label>Phân loại</label>
                <input type="radio" id="teacher" name="type" value="1" <?php echo ($user['type'] == '1') ? 'checked' : ''; ?> required>
                <label for="teacher">Giáo viên</label>
                <input type="radio" id="student" name="type" value="2" <?php echo ($user['type'] == '2') ? 'checked' : ''; ?>>
                <label for="student">Sinh viên</label>
                <input type="radio" id="alumni" name="type" value="3" <?php echo ($user['type'] == '3') ? 'checked' : ''; ?>>
                <label for="alumni">Cựu sinh viên</label>
            </div>
            <div class="form-group">
                <label for="unique_id">ID</label>
                <input type="text" id="unique_id" name="unique_id" value="<?php echo $user['id']; ?>" required>
            </div>
            <div class="form-group avatar">
                <label for="avatar">Avatar</label>
                <img src="placeholder.png" alt="Avatar" id="avatarPreview">
                <input type="file" id="avatar" name="avatar" accept="image/*" onchange="previewAvatar(event)">
            </div>
            <div class="form-group">
                <label for="description">Mô tả thêm</label>
                <textarea id="description" name="description"><?php echo $user['description']; ?></textarea>
            </div>
            <div class="form-group button-container">
                <button type="submit">Xác Nhận</button>
            </div>
        </form>
    </div>

    <script>
        function previewAvatar(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('avatarPreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>