<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $tenSuKien = $_POST['ten_su_kien'] ?? '';
    $slogan = $_POST['slogan'] ?? '';
    $leader = $_POST['leader'] ?? '';
    $moTaChiTiet = $_POST['mo_ta_chi_tiet'] ?? '';
    $avatar = $_FILES['avatar'] ?? null;

    // Biến lưu thông báo lỗi
    $errors = [];

    // Validate dữ liệu
    if (empty($tenSuKien)) {
        $errors['ten_su_kien'] = "Hãy nhập tên sự kiện";
    } elseif (strlen($tenSuKien) > 100) {
        $errors['ten_su_kien'] = "Không nhập quá 100 ký tự";
    }

    if (empty($slogan)) {
        $errors['slogan'] = "Hãy nhập slogan";
    } elseif (strlen($slogan) > 100) {
        $errors['slogan'] = "Không nhập quá 100 ký tự";
    }

    if (empty($leader)) {
        $errors['leader'] = "Hãy nhập tên Leader";
    } elseif (strlen($leader) > 250) {
        $errors['leader'] = "Không nhập quá 250 ký tự";
    }

    if (empty($moTaChiTiet)) {
        $errors['mo_ta_chi_tiet'] = "Hãy nhập mô tả chi tiết";
    } elseif (strlen($moTaChiTiet) > 1000) {
        $errors['mo_ta_chi_tiet'] = "Không nhập quá 1000 ký tự";
    }

    if (empty($avatar['name'])) {
        $errors['avatar'] = "Hãy chọn avatar";
    } else {
        // Xử lý upload file
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($avatar["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Kiểm tra loại file
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedTypes)) {
            $errors['avatar'] = "Chỉ chấp nhận file JPG, JPEG, PNG hoặc GIF";
        } elseif (!move_uploaded_file($avatar["tmp_name"], $targetFile)) {
            $errors['avatar'] = "Lỗi khi tải file lên";
        }
    }

    // Lưu dữ liệu vào session khi không có lỗi
    if (empty($errors)) {
        $_SESSION['form_data'] = [
            'ten_su_kien' => $tenSuKien,
            'slogan' => $slogan,
            'leader' => $leader,
            'mo_ta_chi_tiet' => $moTaChiTiet,
            'avatar' => $targetFile
        ];
        header('Location: confirm.php');
        exit();
    }
}

$formData = $_SESSION['form_data'] ?? [];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhập Thông Tin Sự Kiện</title>
    <style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        font-family: Arial, sans-serif;
        width: 100%;
        background-color: white;
    }

    h1 {
        text-align: center;
    }

    #form-container {
        width: 50%;
        display: flex;
        align-items: center;
        background-color: white;
        justify-content: center;
        padding: 20px 10px;
    }

    .form-group {
        margin-bottom: 15px;

    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 8px;
    }

    .form-group .error {
        color: red;
        font-size: 0.9em;
    }

    button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }
    </style>
</head>

<body>
    <h1>Nhập Thông Tin Sự Kiện</h1>
    <div id="form-container">

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="ten_su_kien">Tên sự kiện:</label>
                <input type="text" id="ten_su_kien" name="ten_su_kien"
                    value="<?php echo htmlspecialchars($formData['ten_su_kien'] ?? ''); ?>">
                <div class="error"><?php echo $errors['ten_su_kien'] ?? ''; ?></div>
            </div>

            <div class="form-group">
                <label for="slogan">Slogan:</label>
                <input type="text" id="slogan" name="slogan"
                    value="<?php echo htmlspecialchars($formData['slogan'] ?? ''); ?>">
                <div class="error"><?php echo $errors['slogan'] ?? ''; ?></div>
            </div>

            <div class="form-group">
                <label for="leader">Leader:</label>
                <input type="text" id="leader" name="leader"
                    value="<?php echo htmlspecialchars($formData['leader'] ?? ''); ?>">
                <div class="error"><?php echo $errors['leader'] ?? ''; ?></div>
            </div>

            <div class="form-group">
                <label for="mo_ta_chi_tiet">Mô tả chi tiết:</label>
                <textarea id="mo_ta_chi_tiet" name="mo_ta_chi_tiet"
                    rows="5"><?php echo htmlspecialchars($formData['mo_ta_chi_tiet'] ?? ''); ?></textarea>
                <div class="error"><?php echo $errors['mo_ta_chi_tiet'] ?? ''; ?></div>
            </div>

            <div class="form-group">
                <label for="avatar">Avatar:</label>
                <input type="file" id="avatar" name="avatar">
                <div class="error"><?php echo $errors['avatar'] ?? ''; ?></div>
            </div>

            <button type="submit">Xác Nhận</button>
        </form>
    </div>
</body>

</html>