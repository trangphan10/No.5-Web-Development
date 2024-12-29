<?php
require_once('C:/xampp/htdocs/No.5-Web-Development/app/controller/class_add.php');
$action = isset($_GET['action']) ? $_GET['action'] : 'register';

switch ($action) {
    case 'register':
        $controller = new RegisterController();
        $controller->showForm();
        break;
    case 'confirm':
        $controller = new ConfirmController();
        $controller->confirm();
        break;
    case 'complete':
        $controller = new CompleteController();
        $controller->complete();
        break;
    default:
        echo "Action không hợp lệ!";
}
