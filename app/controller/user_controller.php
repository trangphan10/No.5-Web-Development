<?php
require_once __DIR__ . '/../common/db.php';
require_once __DIR__ . '/../model/user.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function search($type, $keyword) {
        try {
            return $this->userModel->search($type, $keyword);
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function delete($id) {
        try {
            if (!is_numeric($id)) {
                throw new Exception("ID không hợp lệ.");
            }
            $this->userModel->delete($id);
            echo "Xóa người dùng thành công.";
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
}
?>
