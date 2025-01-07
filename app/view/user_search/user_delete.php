<?php
require_once __DIR__ . '/../../controller/user_controller.php';

if (isset($_GET['id'])) {
    $userController = new UserController();
    $userController->delete($_GET['id']);
    header("Location: user_search.php");
    exit;
}
?>
