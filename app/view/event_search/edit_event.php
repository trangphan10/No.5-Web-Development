<?php
require_once __DIR__ . '/../../common/db.php';
session_start();
 // Tệp kết nối cơ sở dữ liệu

// Lấy ID sự kiện từ URL
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID sự kiện không hợp lệ!");
}

// Lấy thông tin sự kiện hiện tại từ DB
$query = "SELECT * FROM events WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die("Sự kiện không tồn tại!");
}

// Biến lưu thông báo lỗi
$errors = [];

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $tenSuKien = $_POST['ten_su_kien'] ?? '';
    $slogan = $_POST['slogan'] ?? '';
    $leader = $_POST['leader'] ?? '';
    $moTaChiTiet = $_POST['mo_ta_chi_tiet'] ?? '';
    $avatar = $_FILES['avatar'] ?? null;

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

    // Xử lý avatar (nếu có upload mới)
    $avatarPath = $event['avatar']; // Sử dụng avatar cũ làm mặc định
    if (!empty($avatar['name'])) {
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
            $avatarPath = $targetFile; // Cập nhật avatar mới
        }
    }

    // Nếu không có lỗi, cập nhật vào DB
    if (empty($errors)) {
        $query = "UPDATE events 
                  SET name = :name, slogan = :slogan, leader = :leader, description = :description, avatar = :avatar, updated = NOW() 
                  WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'name' => $tenSuKien,
            'slogan' => $slogan,
            'leader' => $leader,
            'description' => $moTaChiTiet,
            'avatar' => $avatarPath,
            'id' => $id,
        ]);

        $_SESSION['success'] = "Cập nhật sự kiện thành công!";
        header("Location: search_events.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa sự kiện</title>
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
<h1>Chỉnh sửa thông tin sự kiện</h1>
<div id="form-container">

    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="ten_su_kien">Tên sự kiện:</label>
            <input type="text" id="ten_su_kien" name="ten_su_kien"
                   value="<?php echo htmlspecialchars($event['name']); ?>">
            <div class="error"><?php echo $errors['ten_su_kien'] ?? ''; ?></div>
        </div>

        <div class="form-group">
            <label for="slogan">Slogan:</label>
            <input type="text" id="slogan" name="slogan"
                   value="<?php echo htmlspecialchars($event['slogan']); ?>">
            <div class="error"><?php echo $errors['slogan'] ?? ''; ?></div>
        </div>

        <div class="form-group">
            <label for="leader">Leader:</label>
            <input type="text" id="leader" name="leader"
                   value="<?php echo htmlspecialchars($event['leader']); ?>">
            <div class="error"><?php echo $errors['leader'] ?? ''; ?></div>
        </div>

        <div class="form-group">
            <label for="mo_ta_chi_tiet">Mô tả chi tiết:</label>
            <textarea id="mo_ta_chi_tiet" name="mo_ta_chi_tiet"
                      rows="5"><?php echo htmlspecialchars($event['description']); ?></textarea>
            <div class="error"><?php echo $errors['mo_ta_chi_tiet'] ?? ''; ?></div>
        </div>

        <div class="form-group">
            <label for="avatar">Avatar:</label>
            <input type="file" id="avatar" name="avatar">
            <div class="error"><?php echo $errors['avatar'] ?? ''; ?></div>
            <?php if (!empty($event['avatar'])): ?>
                <p>Avatar hiện tại:</p>
                <img src="<?php echo $event['avatar']; ?>" alt="Avatar" width="100">
            <?php endif; ?>
        </div>

        <button type="submit">Cập nhật</button>
    </form>
</div>
</body>

</html>
