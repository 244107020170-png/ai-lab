<?php

require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/MembersController.php';
require_once __DIR__ . '/controllers/ProjectsController.php';
require_once __DIR__ . '/controllers/NewsController.php';
require_once __DIR__ . '/controllers/AuthController.php';

$action = $_GET['action'] ?? 'home';
$op     = $_GET['op'] ?? 'index';

/* SESSION START */
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0);
    session_start();
}

/* ==========================================================
   ROLE GUARD FUNCTION
========================================================== */
function requireRole($role) {
    if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== strtolower($role)) {
        header("Location: views/403.php");
        exit;
    }
}

/* ==========================================================
   LOGIN BLOCKER (EXCEPT login_process)
========================================================== */
if (
    (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') &&
    $action !== 'login_process'
) {
    header("Location: views/login.php");
    exit;
}

/* ==========================================================
   ROUTING SYSTEM
========================================================== */
switch ($action) {

    /* =============================
       AUTH
    ============================= */
    case 'login_process':
        (new AuthController())->login();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    /* =============================
       ADMIN-ONLY PAGES
    ============================= */

    case 'home':
        requireRole('admin');
        (new HomeController())->index();
        break;

    case 'members':
        requireRole('admin');
        $ctrl = new MembersController();
        if ($op === 'index')      $ctrl->members();
        elseif ($op === 'save')   $ctrl->save();
        elseif ($op === 'delete') $ctrl->delete($_GET['id'] ?? null);
        else                      $ctrl->members();
        break;

    case 'members_form':
        requireRole('admin');
        (new MembersController())->form($_GET['id'] ?? null);
        break;

    case 'members_delete':
        requireRole('admin');
        (new MembersController())->delete($_GET['id'] ?? null);
        break;

    case 'members_form_save':
        requireRole('admin');
        (new MembersController())->save();
        break;

    case 'members_delete_background':
        requireRole('admin');
        (new MembersController())->deleteBackground($_GET['id'] ?? null);
        break;

    case 'projects':
        requireRole('admin');
        $ctrl = new ProjectsController();
        if ($op === 'index')      $ctrl->index();
        elseif ($op === 'create') $ctrl->create();
        elseif ($op === 'store')  $ctrl->store();
        elseif ($op === 'edit')   $ctrl->edit();
        elseif ($op === 'update') $ctrl->update();
        elseif ($op === 'delete') $ctrl->delete();
        else                      $ctrl->index();
        break;

    case 'news':
        requireRole('admin');
        (new NewsController())->index();
        break;

    case 'news_create':
        requireRole('admin');
        (new NewsController())->create();
        break;

    case 'news_edit':
        requireRole('admin');
        (new NewsController())->edit();
        break;

    case 'news_store':
        requireRole('admin');
        (new NewsController())->store();
        break;

    case 'news_update':
        requireRole('admin');
        (new NewsController())->update();
        break;

    case 'news_delete':
        requireRole('admin');
        (new NewsController())->delete();
        break;

    /* =============================
       MEMBER-ONLY PAGES
    ============================= */

    case "member_dashboard_api":
        requireRole('member');
        require_once "controllers/MemberDashboardController.php";
        exit;

    case "member_dashboard":
        requireRole('member');
        include "views/member_dashboard.php";
        break;

    case "member_profile_api":
        requireRole('member');
        require_once "controllers/MemberProfileController.php";
        (new MemberProfileController())->api();
        exit;

    case "member_profile_update":
        requireRole('member');
        require_once "controllers/MemberProfileController.php";
        (new MemberProfileController())->update();
        exit;    

    case "member_profile":
        requireRole('member');
        include "views/member_profile.php";
        break;

    case "member_research":
        requireRole('member');
        include "views/member_research.php";
        break;

    /* =============================
       MEMBER RESEARCH API ROUTES
    ============================= */
    default:

        if (strpos($action, "member_research_") === 0) {
            requireRole('member');
            require_once "controllers/MemberResearchController.php";
            exit;
        }

        /* FALLBACK */
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            (new HomeController())->index();
        } else {
            header("Location: views/login.php");
        }
        break;
}
