<?php
class Database {
    private $host = "localhost";       // Tên máy chủ
    private $username = "root";        // Tên đăng nhập mặc định
    private $password = "";            // Mật khẩu mặc định
    private $dbname = "no5";          // Tên cơ sở dữ liệu
    private $conn = null;

    // Phương thức kết nối cơ sở dữ liệu
    private function connect() {
        if (!$this->conn) { // Chỉ khởi tạo kết nối khi chưa có
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

            // Kiểm tra kết nối
            if ($this->conn->connect_error) {
                die("Kết nối thất bại: " . $this->conn->connect_error);
            }
        }
    }

    // Phương thức lấy kết nối
    public function getConnection() {
        $this->connect();
        return $this->conn;
    }
}
?>
