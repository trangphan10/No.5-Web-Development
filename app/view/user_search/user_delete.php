<?php
require_once '../controller/user_controller.php';

if (isset($_GET['id'])) {
    $userController = new UserController();
    $userController->delete($_GET['id']);
    header("Location: user_search_result.php");
    exit;
}
?>
