<?php
require_once __DIR__ . '/controllers/MembersController.php';
require_once __DIR__ . '/controllers/HomeController.php';
$memberscontroller = new MembersController();
$homecontroller = new HomeController();
$action = $_GET['action'] ?? 'index';
switch ($action) {
    case 'members':
        $memberscontroller->members();
        break;
    case 'save':
        $memberscontroller->save();
        break;
    case 'delete':
        $membersontroller->delete($_GET['id']);
        break;
    default:
        $homecontroller->index();
}
