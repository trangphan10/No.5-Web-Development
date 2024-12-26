<!DOCTYPE html>
<html>
<head>
    <title>Search Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        label, select, input {
            margin: 5px;
        }

        button {
            margin: 10px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        .action-buttons a {
            margin: 0 5px;
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 3px;
        }

        .action-buttons a.delete {
            background-color: #e74c3c;
        }

        .action-buttons a.edit {
            background-color: #3498db;
        }

        .action-buttons a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <h1>Tìm kiếm người dùng</h1>
    <form method="GET" action="user_search_result.php">
        <div>
            <label for="type">Phân loại:</label>
            <select name="type" id="type">
                <option value="">--Chọn--</option>
                <option value="1">Sinh viên</option>
                <option value="2">Giáo viên</option>
                <option value="3">Sinh viên cũ</option>
            </select>
        </div>
        <div>
            <label for="keyword">Từ khóa:</label>
            <input type="text" id="keyword" name="keyword" placeholder="Nhập tên hoặc mô tả">
        </div>
        <button type="submit">Tìm kiếm</button>
    </form>

    <?php if (!empty($_GET['type']) || !empty($_GET['keyword'])): ?>
        <p style="text-align: center;">Số thành viên tìm thấy: <strong>XXX</strong></p>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tên thành viên</th>
                    <th>Phân loại</th>
                    <th>Mô tả chi tiết</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example rows (Replace with PHP loop for real data) -->
                <tr>
                    <td>1</td>
                    <td>Nguyễn Văn A</td>
                    <td>Giáo viên</td>
                    <td>Mô tả chi tiết về thành viên</td>
                    <td class="action-buttons">
                        <a href="user_delete.php?id=1" class="delete" onclick="return confirm('Bạn chắc chắn muốn xóa thành viên này?')">Xóa</a>
                        <a href="user_edit.php?id=1" class="edit">Sửa</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Trần Thị B</td>
                    <td>Sinh viên</td>
                    <td>Mô tả chi tiết về thành viên</td>
                    <td class="action-buttons">
                        <a href="user_delete.php?id=2" class="delete" onclick="return confirm('Bạn chắc chắn muốn xóa thành viên này?')">Xóa</a>
                        <a href="user_edit.php?id=2" class="edit">Sửa</a>
                    </td>
                </tr>
                <!-- Add more rows dynamically -->
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
