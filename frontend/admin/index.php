<?php

require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/MembersController.php';
require_once __DIR__ . '/controllers/ProjectsController.php';
require_once __DIR__ . '/controllers/NewsController.php';
require_once __DIR__ . '/controllers/AuthController.php';

$action = $_GET['action'] ?? 'home';
$op     = $_GET['op'] ?? 'index';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0);
    session_start();
}

/* LOGIN VALIDATION */
if (
    (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') &&
    $action !== 'login_process' &&
    $action !== 'member_dashboard_api'
) {
    header("Location: views/login.php");
    exit;
}

switch ($action) {

    /* LOGIN */
    case 'login_process':
        (new AuthController())->login();
        break;

    case 'logout':
        (new AuthController())->logout();
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
        (new MembersController())->delete($_GET['id'] ?? null);
        break;

    case 'members_form_save':
        (new MembersController())->save();
        break;

    case 'members_delete_background':
        (new MembersController())->deleteBackground($_GET['id'] ?? null);
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

    /* NEWS */
    case 'news':
        (new NewsController())->index();
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

    /* MEMBER PAGES */
    case "member_dashboard_api":
    require_once "controllers/MemberDashboardController.php";
    exit;

    case "member_dashboard":
        include "views/member_dashboard.php";
        break;

    case "member_profile_api":
    require_once "controllers/MemberProfileController.php";
    (new MemberProfileController())->api();
    exit;

    case "member_profile_update":
    require_once "controllers/MemberProfileController.php";
    (new MemberProfileController())->update();
    exit;    

    case "member_profile":
        include "views/member_profile.php";
        break;

    case "member_research":
    include "views/member_research.php";
    break;

/* ==== ALL MEMBER RESEARCH API ROUTES ==== */
default:
    if (strpos($action, "member_research_") === 0) {
        require_once "controllers/MemberResearchController.php";
        exit;
    }

    /* DEFAULT ROUTE AFTER ALL MATCHES */
    if (isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
        (new HomeController())->index();
    } else {
        header("Location: views/login.php");
    }
    break;

}
