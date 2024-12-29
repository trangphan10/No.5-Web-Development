<?php 
require_once 'C:/xampp/htdocs/No.5-Web-Development/app/common/db.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database(); // Khởi tạo đối tượng Database
    }

    // Phương thức để thêm người dùng mới và cập nhật created_date
    public function insertUser($fullname, $id, $category, $description, $avatar) {
        // Kết nối cơ sở dữ liệu
        $conn = $this->db->connect();

        // Câu lệnh SQL sử dụng placeholders cho các tham số
        $sql = "INSERT INTO users (name, unique_id, type, description, avatar, created) 
                VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";

        // Chuẩn bị câu lệnh SQL
        if ($stmt = $conn->prepare($sql)) {
            // Bind tham số vào câu lệnh
            $stmt->bind_param("sssss", $fullname, $id, $category, $description, $avatar);  // 'sssss' vì tất cả đều là string

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
