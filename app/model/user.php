<?php
require_once __DIR__ . '/../common/db.php';


class User {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function search($type, $keyword) {
        $query = "SELECT * FROM users WHERE (? = '' OR type = ?) AND (name LIKE ? OR description LIKE ?)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Chuẩn bị truy vấn thất bại: " . $this->conn->error);
        }

        // Thay thế tham số bằng giá trị thực
        $keyword = "%$keyword%";
        $stmt->bind_param("ssss", $type, $type, $keyword, $keyword);
        $stmt->execute();

        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $users;
    }

    public function delete($id) {
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Chuẩn bị truy vấn thất bại: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $success = $stmt->execute();

        $stmt->close();
        return $success;
    }
    public function insertUser($fullname, $id, $category, $description, $avatar) {
        $categoryMapping = [
            'Sinh viên' => 1,
            'Giáo viên' =>2,
            'Cựu sinh viên' => 3
        ];
        $type = $categoryMapping[$category] ?? null;
        if (!$type) {
            echo "Invalid category.";
            return false;
        }
        
        // Kết nối cơ sở dữ liệu
        $conn = $this->conn;

        // Câu lệnh SQL sử dụng placeholders cho các tham số
        $sql = "INSERT INTO users (name, unique_id, type, description, avatar, created) 
                VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";

        // Chuẩn bị câu lệnh SQL
        if ($stmt = $conn->prepare($sql)) {
            // Bind tham số vào câu lệnh
            $stmt->bind_param("ssiss", $fullname, $id, $type, $description, $avatar);  // 'sssss' vì tất cả đều là string

            // Thực thi câu lệnh
            if ($stmt->execute()) {
                $stmt->close();  // Đóng statement
                
                // Cập nhật lại trường created_date sau khi INSERT
                $this->updateCreatedDate($id, $conn);
                
                $conn->close();  // Đóng kết nối
                return true;  // Trả về true nếu thành công
            } else {
                // Nếu có lỗi trong quá trình thực thi
                echo "Error: " . $stmt->error;
                $stmt->close();  // Đóng statement
                $conn->close();  // Đóng kết nối
                return false;  // Trả về false nếu có lỗi
            }
        } else {
            // Nếu không thể chuẩn bị câu lệnh
            echo "Error preparing statement: " . $conn->error;
            $conn->close();  // Đóng kết nối
            return false;  // Trả về false nếu có lỗi
        }
    }

    // Phương thức cập nhật created_date sau khi insert thành công
    private function updateCreatedDate($id, $conn) {
        // Câu lệnh SQL để cập nhật created_date
        $updateSql = "UPDATE users SET created = CURRENT_TIMESTAMP WHERE unique_id = ?";

        // Chuẩn bị câu lệnh SQL
        if ($stmt = $conn->prepare($updateSql)) {
            // Bind tham số vào câu lệnh
            $stmt->bind_param("s", $id);  // 's' vì ID là kiểu string

            // Thực thi câu lệnh
            if (!$stmt->execute()) {
                echo "Error updating created_date: " . $stmt->error;
            }
            $stmt->close();  // Đóng statement
        } else {
            echo "Error preparing update statement: " . $conn->error;
        }
    }
}
?>
