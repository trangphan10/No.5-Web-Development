<?php

class Database {
    public $host = "localhost";       // Tên máy chủ
    public $username = "root";        // Tên đăng nhập mặc định
    public $password = "";            // Mật khẩu mặc định
    public $dbname = "no5";          // Tên cơ sở dữ liệu
    public $conn = null;

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
    public function connectPDO() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
            $pdo = new PDO($dsn, $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Could not connect to the database {$this->dbname}: " . $e->getMessage());
        }
    }
}
$db = new Database();
$pdo = $db->connectPDO();


?>
