require_once 'D:\LTWeb_Final\No.5-Web-Development\app\common\db.php';
require_once 'D:\LTWeb_Final\No.5-Web-Development\app\model\user.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

$users = searchUsers($type, $keyword);

// Gọi view để hiển thị kết quả
include 'D:\LTWeb_Final\No.5-Web-Development\app\view\user_search.php';
