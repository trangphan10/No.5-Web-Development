<?php
require_once __DIR__ . '/../../common/db.php';

$database = new Database();
$conn = $database->getConnection();


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = [
        'id' => intval($_POST['id']),
        'name' => htmlspecialchars($_POST['name']),
        'type' => htmlspecialchars($_POST['type']),
        'unique_id' => htmlspecialchars($_POST['unique_id']),
        'description' => htmlspecialchars($_POST['description']),
        'avatar' => htmlspecialchars($_POST['avatar']),
    ];
} else {
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
    $name = $user['name'];
    $type = $user['type'];
    $unique_id = $user['unique_id'];
    $description = $user['description'];
    $avatar = $user['avatar'];
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
            object-fit: cover;
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
                <label for="teacher">Sinh viên</label>
                <input type="radio" id="student" name="type" value="2" <?php echo ($user['type'] == '2') ? 'checked' : ''; ?>>
                <label for="student">Giáo viên</label>
                <input type="radio" id="alumni" name="type" value="3" <?php echo ($user['type'] == '3') ? 'checked' : ''; ?>>
                <label for="alumni">Cựu sinh viên</label>
            </div>
            <div class="form-group">
                <label for="unique_id">ID</label>
                <input type="text" id="unique_id" name="unique_id" value="<?php echo $user['id']; ?>" required>
            </div>
            <div class="form-group avatar">
                <label for="avatar">Avatar</label>
                <img src="../uploads/<?php echo $user['avatar']; ?>" alt="Avatar" id="avatarPreview">
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
                output.style.width = '100px'; // Set the desired width
                output.style.height = '100px'; // Set the desired height
                output.style.objectFit = 'cover'; // Ensure the image covers the area without distortion
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>