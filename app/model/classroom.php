<?php
require_once '../common/db.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database(); // Khởi tạo đối tượng Database
    }

    public function insertUser($fullname, $id, $category, $description, $avatar) {
        // Kết nối cơ sở dữ liệu
        $conn = $this->db->connect();

        // Câu lệnh SQL sử dụng placeholders cho các tham số
        $sql = "INSERT INTO users (fullname, id, category, description, avatar) 
                VALUES (?, ?, ?, ?, ?)";

        // Chuẩn bị câu lệnh SQL
        if ($stmt = $conn->prepare($sql)) {
            // Bind tham số vào câu lệnh
            $stmt->bind_param("sssss", $fullname, $id, $category, $description, $avatar);  // 'sssss' vì tất cả đều là string

            // Thực thi câu lệnh
            if ($stmt->execute()) {
                $stmt->close();  // Đóng statement
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
            return false;
        }
    }
}
?>
