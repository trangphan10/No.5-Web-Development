<?php
session_start();
$error = [];
$success = "";

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách các record trong bảng admins có reset_password_token <> rỗng
$sql = "SELECT id, name, reset_password_token FROM admins WHERE reset_password_token IS NOT NULL AND reset_password_token <> ''";
$result = $conn->query($sql);

// Xử lý yêu cầu reset mật khẩu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $new_password = $_POST['new_password'];

    // Validate dữ liệu nhập
    if (empty($new_password)) {
        $error[$id] = "Hãy nhập mật khẩu mới!";
    } elseif (strlen($new_password) < 6) {
        $error[$id] = "Hãy nhập mật khẩu có tối thiểu 6 ký tự!";
    } else {
        // Mã hóa mật khẩu mới và cập nhật DB
        $hashed_password = md5($new_password);
        $update_sql = "UPDATE admins SET password = ?, reset_password_token = '' WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $hashed_password, $id);
        if ($stmt->execute()) {
            $success = "Mật khẩu đã được cập nhật thành công!";
        } else {
            $error[$id] = "Cập nhật mật khẩu thất bại!";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .error {
            color: red;
            font-size: 12px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<h2>Reset Password</h2>

<?php if (!empty($success)) : ?>
    <div class="success"><?php echo $success; ?></div>
<?php endif; ?>

<table>
    <thead>
    <tr>
        <th>NO</th>
        <th>Tên người dùng</th>
        <th>Mật khẩu mới</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($result->num_rows > 0) : ?>
        <?php $no = 1; ?>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <form method="POST">
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>
                        <input type="password" name="new_password" placeholder="Mật khẩu mới">
                        <?php if (!empty($error[$row['id']])) : ?>
                            <div class="error"><?php echo $error[$row['id']]; ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit">Reset</button>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    <?php else : ?>
        <tr>
            <td colspan="4">Không có tài khoản nào cần reset mật khẩu.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</body>
</html>
