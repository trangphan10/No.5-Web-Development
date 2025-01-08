<?php
require __DIR__ . '/../../common/db.php';

$id = $_GET['id'] ?? 0;

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $name = $_POST['name'];
    $detail = $_POST['detail'];
    $PoC = $_POST['PoC'];

    // Cập nhật lịch trình trong cơ sở dữ liệu
    $query = "UPDATE event_timelines SET `from`=?, `to`=?, name=?, detail=?, PoC=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $from, $to, $name, $detail, $PoC, $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Chuyển hướng về trang danh sách lịch trình
    header("Location: list_schedules.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập Nhật Lịch Trình Sự Kiện</title>
    <style> 
        /* Tổng quát cho toàn bộ trang */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-top: 20px;
        }

        /* Các kiểu cho form */
        form {
            width: 60%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="datetime-local"],
        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        form textarea {
            resize: vertical;
            height: 150px;
        }

        form button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        form button:hover {
            background-color: #0056b3;
        }

        /* Bảng danh sách lịch trình */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Liên kết */
        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Liên kết thêm lịch trình mới */
        a[href^="add_schedules.php"] {
            display: block;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            background-color: #28a745;
            color: white;
            border-radius: 4px;
        }

        a[href^="add_schedules.php"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <h1>Cập Nhật Lịch Trình Sự Kiện</h1>

    <form method="POST">
        <label for="from">Thời gian bắt đầu:</label>
        <input type="datetime-local" name="from" value="<?= $row['from'] ?>" required><br><br>

        <label for="to">Thời gian kết thúc:</label>
        <input type="datetime-local" name="to" value="<?= $row['to'] ?>" required><br><br>

        <label for="name">Tên sự kiện:</label>
        <input type="text" name="name" value="<?= $row['name'] ?>" maxlength="100" required><br><br>

        <label for="detail">Chi tiết sự kiện:</label>
        <textarea name="detail" maxlength="1000" required><?= $row['detail'] ?></textarea><br><br>

        <label for="PoC">Người phụ trách:</label>
        <input type="text" name="PoC" value="<?= $row['PoC'] ?>" maxlength="100" required><br><br>

        <button type="submit">Cập nhật</button>
    </form>

</body>
</html>
