<?php
require __DIR__ . '/../../common/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $name = $_POST['name'];
    $detail = $_POST['detail'];
    $PoC = $_POST['PoC'];
    $id_event = $_POST['id_event'];

    // Validation
    if (empty($from) || empty($to) || empty($name) || empty($detail) || empty($PoC)) {
        echo "Vui lòng nhập đầy đủ thông tin.";
        exit;
    }

    if (strlen($name) > 100 || strlen($detail) > 1000 || strlen($PoC) > 100) {
        echo "Thông tin nhập vượt quá giới hạn ký tự.";
        exit;
    }

    $query = "INSERT INTO event_timelines (id_event, `from`, `to`, name, detail, PoC) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssss", $id_event, $from, $to, $name, $detail, $PoC);
    $stmt->execute();

    echo "Thêm mới lịch trình thành công.";
    $stmt->close();
    $conn->close();
    header("Location: list_schedules.php?event_id=$id_event");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Mới Lịch Trình Sự Kiện</title>
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

    <h1>Thêm Mới Lịch Trình Sự Kiện</h1>

    <form action="" method="POST">
        <label for="from">Thời gian bắt đầu:</label>
        <input type="datetime-local" id="from" name="from" required><br><br>

        <label for="to">Thời gian kết thúc:</label>
        <input type="datetime-local" id="to" name="to" required><br><br>

        <label for="name">Tên sự kiện:</label>
        <input type="text" id="name" name="name" maxlength="100" required><br><br>

        <label for="detail">Chi tiết sự kiện:</label>
        <textarea id="detail" name="detail" maxlength="1000" required></textarea><br><br>

        <label for="PoC">Người phụ trách:</label>
        <input type="text" id="PoC" name="PoC" maxlength="100" required><br><br>

        <input type="hidden" name="id_event" value="<?php echo $_GET['event_id']; ?>">

        <button type="submit">Thêm Lịch Trình</button>
    </form>

</body>
</html>
