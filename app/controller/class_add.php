<?php
require_once 'C:/xampp/htdocs/No.5-Web-Development/app/model/classroom.php';

class RegisterController {
    public function showForm() {
        include 'C:/xampp/htdocs/No.5-Web-Development/app/view/classroom_add_input.php';
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
        $avatarPath = 'C:/xampp/htdocs/No.5-Web-Development/web/avatar/' . $_FILES['avatar']['name'];
        move_uploaded_file($avatar, $avatarPath);

        include 'C:/xampp/htdocs/No.5-Web-Development/app/view/classroom_add_confirm.php';
    }
}
class CompleteController {
    public function complete() {
        $user = new User();
        $result = $user->insertUser($_POST['fullname'], $_POST['id'], $_POST['category'], $_POST['description'], $_POST['avatar']);

        if ($result) {
            include 'C:/xampp/htdocs/No.5-Web-Development/app/view/classroom_add_complete.php';
        } else {
            echo "Có lỗi xảy ra khi lưu dữ liệu!";
        }
    }
}

