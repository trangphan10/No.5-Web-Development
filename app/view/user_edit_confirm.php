<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid access.");
}

$id = intval($_POST['id']);
$name = htmlspecialchars($_POST['name']);
$type = htmlspecialchars($_POST['type']);
$unique_id = htmlspecialchars($_POST['unique_id']);
$description = htmlspecialchars($_POST['description']);

$avatar = $_FILES['avatar']['name'] ? $_FILES['avatar']['name'] : 'placeholder.png';
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
            font-weight: bold;
        }
        .form-group img {
            width: 100px;
            height: 100px;
            display: block;
            margin: 10px 0;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
        }
        .form-actions button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-actions .edit-button {
            background-color: #007bff;
            color: #fff;
        }
        .form-actions .confirm-button {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-group">
            <label>Họ và Tên:</label>
            <p><?php echo $name; ?></p>
        </div>
        <div class="form-group">
            <label>Phân loại:</label>
            <p><?php echo $type; ?></p>
        </div>
        <div class="form-group">
            <label>ID:</label>
            <p><?php echo $unique_id; ?></p>
        </div>
        <div class="form-group">
            <label>Avatar:</label>
            <img src="../uploads/<?php echo $avatar; ?>" alt="Avatar">
        </div>
        <div class="form-group">
            <label>Mô tả thêm:</label>
            <p><?php echo nl2br($description); ?></p>
        </div>

        <div class="form-actions">
            <form action="user_edit_input.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="name" value="<?php echo $name; ?>">
                <input type="hidden" name="type" value="<?php echo $type; ?>">
                <input type="hidden" name="unique_id" value="<?php echo $unique_id; ?>">
                <input type="hidden" name="description" value="<?php echo $description; ?>">
                <input type="hidden" name="avatar" value="<?php echo $avatar; ?>">
                <button type="submit" class="edit-button">Sửa lại</button>
            </form>
            <form action="user_edit_complete.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="name" value="<?php echo $name; ?>">
                <input type="hidden" name="type" value="<?php echo $type; ?>">
                <input type="hidden" name="unique_id" value="<?php echo $unique_id; ?>">
                <input type="hidden" name="description" value="<?php echo $description; ?>">
                <input type="hidden" name="avatar" value="<?php echo $avatar; ?>">
                <button type="submit" class="confirm-button">Xác nhận</button>
            </form>
        </div>
    </div>
</body>
</html>
