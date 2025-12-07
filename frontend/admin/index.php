<?php

require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/MembersController.php';
require_once __DIR__ . '/controllers/ProjectsController.php';
require_once __DIR__ . '/controllers/NewsController.php'; // <-- WAJIB TAMBAH
require_once __DIR__ . '/controllers/AuthController.php';

$action = $_GET['action'] ?? 'home';
$op     = $_GET['op'] ?? 'index';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ((!isset($_SESSION['status']) || $_SESSION['status'] != 'login') && $action !== 'login_process') {
    // Redirect to the VIEW (the form), not the handler
    header("Location: views/login.php");
    exit;
}

switch ($action) {

    // Login Processes
    case 'login_process':
        $auth = new AuthController(); // You might need to create this controller
        $auth->login();
        break;

    case 'logout':
        $auth = new AuthController();
        $auth->logout();
        break;

    /* HOME */
    case 'home':
        (new HomeController())->index();
        break;

    /* MEMBERS */
    case 'members':
        $ctrl = new MembersController();
        if ($op === 'index')      $ctrl->members();
        elseif ($op === 'save')   $ctrl->save();
        elseif ($op === 'delete') $ctrl->delete($_GET['id'] ?? null);
        else                      $ctrl->members();
        break;

    case 'members_form':
        (new MembersController())->form($_GET['id'] ?? null);
        break;

    case 'members_delete':
        $ctrl = new MembersController();
        $ctrl->delete($_GET['id'] ?? null);
        break;

    case 'members_form_save':
        (new MembersController())->save();
        break;

    case 'members_delete_background':
        $ctrl = new MembersController();
        $ctrl->deleteBackground($_GET['id'] ?? null);
        break;

    /* PROJECTS */
    case 'projects':
        $ctrl = new ProjectsController();
        if ($op === 'index')      $ctrl->index();
        elseif ($op === 'create') $ctrl->create();
        elseif ($op === 'store')  $ctrl->store();
        elseif ($op === 'edit')   $ctrl->edit();
        elseif ($op === 'update') $ctrl->update();
        elseif ($op === 'delete') $ctrl->delete();
        else                      $ctrl->index();
        break;

    /* NEWS (FIXED) */
    case 'news':
        $ctrl = new NewsController();
        $ctrl->index();
        break;

    case 'news_create':
        (new NewsController())->create();
        break;

    case 'news_edit':
        (new NewsController())->edit();
        break;

    case 'news_store':
        (new NewsController())->store();
        break;

    case 'news_update':
        (new NewsController())->update();
        break;

    case 'news_delete':
        (new NewsController())->delete();
        break;

    case 'volunteer_approve':
        require_once "models/Volunteer.php";
        (new Volunteer())->updateStatus($_GET['id'], "Approved");
        header("Location: index.php?action=members");
        exit;

    case 'volunteer_reject':
        require_once "models/Volunteer.php";
        (new Volunteer())->updateStatus($_GET['id'], "Rejected");
        header("Location: index.php?action=members");
        exit;

    case 'volunteer_view':
        require_once "models/Volunteer.php";
        $v = (new Volunteer())->getById($_GET['id']);
        echo json_encode($v);
        exit;

    case 'lab_permit':
        require_once 'controllers/LabPermitController.php';
        $controller = new LabPermitController();
        $controller->index();
        break;

    /* DEFAULT */
    default:
        // If logged in, go home. If not, go to login.
        if (isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
            (new HomeController())->index();
        } else {
            header("Location: views/login.php");
        }
        break;
}
