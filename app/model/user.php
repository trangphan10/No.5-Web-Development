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
}
?>
