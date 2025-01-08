<?php
require __DIR__ . '/../../common/db.php';

$event_id = $_GET['event_id'] ?? 0;

$query = "SELECT * FROM event_timelines WHERE id_event = ? ORDER BY `from` ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Lịch Trình Sự Kiện</title>
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

    <h1>Danh Sách Lịch Trình Sự Kiện</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Thời gian</th>
                <th>Chi tiết</th>
                <th>Người phụ trách</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['from']} - {$row['to']}</td>
                    <td>{$row['detail']}</td>
                    <td>{$row['PoC']}</td>
                    <td><a href='edit_schedule.php?id={$row['id']}'>Sửa</a></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <br>
    <a href="add_schedules.php?event_id=<?= $event_id ?>">Thêm lịch trình mới</a>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
