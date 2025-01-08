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

    // Xử lý file avatar
    $avatarPath = $_SESSION['form_data']['avatar'] ?? ''; // Lấy đường dẫn ảnh từ session nếu có
    if (empty($avatar['name']) && empty($avatarPath)) {
        // Nếu không có ảnh mới và cũng không có ảnh cũ trong session
        $errors['avatar'] = "Hãy chọn avatar";
    } else {
        if (!empty($avatar['name'])) {
            // Xử lý upload file mới
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($avatar["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
            // Kiểm tra loại file
            $allowedTypes = ["jpg", "jpeg", "png", "gif"];
            if (!in_array($imageFileType, $allowedTypes)) {
                $errors['avatar'] = "Chỉ chấp nhận file JPG, JPEG, PNG hoặc GIF";
            } elseif (!move_uploaded_file($avatar["tmp_name"], $targetFile)) {
                $errors['avatar'] = "Lỗi khi tải file lên";
            } else {
                $avatarPath = $targetFile; // Cập nhật đường dẫn file mới
            }
        }
    }

   
// Lưu avatar (cũ hoặc mới) vào session
$_SESSION['form_data']['avatar'] = $avatarPath;

    $_SESSION['form_data'] = [
        'ten_su_kien' => $_POST['ten_su_kien'],
        'slogan' => $_POST['slogan'],
        'leader' => $_POST['leader'],
        'mo_ta_chi_tiet' => $_POST['mo_ta_chi_tiet'],
        'avatar' => $avatarPath
    ];
    
    
    // Lưu dữ liệu vào session khi không có lỗi
    if (empty($errors)) {
        // $_SESSION['form_data'] = [
        //     'ten_su_kien' => $tenSuKien,
        //     'slogan' => $slogan,
        //     'leader' => $leader,
        //     'mo_ta_chi_tiet' => $moTaChiTiet,
        //     'avatar' => $targetFile
        // ];
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

    a:hover {
        color: #0056b3;
    }

    a {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        color: white;
        text-decoration: none;
        margin-left: 100px;
        font-size: 0.9em;
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
                <?php if (!empty($formData['avatar'])): ?>
                <div class="form-group">

                    <img src="<?php echo htmlspecialchars($formData['avatar']); ?>" alt="Avatar"
                        style="max-width: 200px; height: auto; display: block; margin-top: 10px;">
                </div>
                <?php endif; ?>

                <div class="error"><?php echo $errors['avatar'] ?? ''; ?></div>
            </div>
            <button type="submit">Xác Nhận</button>
            <a href="../../../HOME.php">Huỷ</a>

        </form>
    </div>
</body>

</html>