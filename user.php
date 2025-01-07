<?php
 require_once($_SERVER['DOCUMENT_ROOT'] . '/project_main/app/controller/user_controller.php');
$action = isset($_GET['action']) ? $_GET['action'] : 'register';

switch ($action) {
    case 'complete':
        $controller = new UserController();
        $controller->complete();
        break;
    default:
        echo "Action không hợp lệ!";
}
