<?php
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/MembersController.php';
require_once __DIR__ . '/controllers/ProjectsController.php';
require_once __DIR__ . '/controllers/NewsController.php'; // <-- WAJIB TAMBAH

$action = $_GET['action'] ?? 'home';
$op     = $_GET['op'] ?? 'index';

switch ($action) {

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

    /* DEFAULT */
    default:
        (new HomeController())->index();
        break;
}
