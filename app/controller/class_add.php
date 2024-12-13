<?php
session_start();
require_once '../common/define.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $id = $_POST['id'];
    $description = $_POST['description'];
    $avatar = $_FILES['avatar'];

    $errors = [];

    // Validate dữ liệu
    if (empty($name)) $errors['name'] = 'Họ và tên không được để trống.';
    if (empty($category)) $errors['category'] = 'Vui lòng chọn loại người dùng.';
    if (!in_array($avatar['type'], ['image/jpeg', 'image/png'])) {
        $errors['avatar'] = 'Chỉ chấp nhận file JPEG hoặc PNG.';
    }

    if (empty($errors)) {
        // Upload avatar
        $avatarPath = AVATAR_PATH . basename($avatar['name']);
        move_uploaded_file($avatar['tmp_name'], $avatarPath);

        // Lưu dữ liệu vào session
        $_SESSION['user_data'] = [
            'name' => $name,
            'category' => $category,
            'id' => $id,
            'description' => $description,
            'avatar' => $avatarPath,
        ];
        header('Location: ../view/classroom_add_confirm.php');
        exit();
    }
}
?>
