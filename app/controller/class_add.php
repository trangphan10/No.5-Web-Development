<?php
require_once 'models/classroom.php';

class RegisterController {
    public function showForm() {
        include 'view/classroom_add_input.php';
    }
}
class ConfirmController {
    public function confirm() {
        $fullname = $_POST['fullname'];
        $id = $_POST['id'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $avatar = $_FILES['avatar']['tmp_name'];

        // Lưu file ảnh vào thư mục uploads
        $avatarPath = 'uploads/' . $_FILES['avatar']['name'];
        move_uploaded_file($avatar, $avatarPath);

        include 'view/classroom_add_confirm.php';
    }
}
class CompleteController {
    public function complete() {
        $user = new User();
        $result = $user->insertUser($_POST['fullname'], $_POST['id'], $_POST['category'], $_POST['description'], $_POST['avatar']);

        if ($result) {
            include 'view/complete.php';
        } else {
            echo "Có lỗi xảy ra khi lưu dữ liệu!";
        }
    }
}

