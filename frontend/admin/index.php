<?php
// Include semua controller
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/MembersController.php';
require_once __DIR__ . '/controllers/ProjectsController.php';

// Ambil param action & op
$action = $_GET['action'] ?? 'home';
$op     = $_GET['op'] ?? 'index';

// Routing utama
switch ($action) {

    case 'home':
        $ctrl = new HomeController();
        $ctrl->index();
        break;

    case 'members':
        $ctrl = new MembersController();
        // operations
        if ($op === 'index')      $ctrl->members();
        elseif ($op === 'save')   $ctrl->save();
        elseif ($op === 'delete') $ctrl->delete($_GET['id'] ?? null);
        else                      $ctrl->members();
        break;

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

    default:
        $ctrl = new HomeController();
        $ctrl->index();
        break;
}
