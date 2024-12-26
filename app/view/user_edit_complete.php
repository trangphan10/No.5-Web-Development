<?php
require_once '../common/db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid access.");
}

$id = intval($_POST['id']);
$name = $conn->real_escape_string($_POST['name']);
$type = $conn->real_escape_string($_POST['type']);
$unique_id = $conn->real_escape_string($_POST['unique_id']);
$description = $conn->real_escape_string($_POST['description']);
$avatar = $conn->real_escape_string($_POST['avatar']);

$sql = "UPDATE users SET 
    type = ?, 
    name = ?, 
    unique_id = ?, 
    avatar = ?, 
    description = ? 
    WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issssi", $type, $name, $unique_id, $avatar, $description, $id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #fff;
        }

        .form-container {
            width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f4f4f4;
            text-align: center;
        }

        .message {
            margin-bottom: 20px;
            font-size: 16px;
        }

        .success {
            color: black;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <?php
        if ($stmt->execute()) {
            echo "<p class='message success'>Bạn đã chỉnh sửa thành công người dùng.</p>";
            echo '<a href="user_list.php">Trở về trang chủ</a>';
        } else {
            echo "<p class='message error'>Error updating user: " . $conn->error . "</p>";
        }
        ?>

        <?php
        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>