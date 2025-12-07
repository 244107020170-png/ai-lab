<?php
require_once __DIR__ . '/../models/Activities.php';

class ProjectsController {
    private $model;
    public function __construct() {
        $this->model = new Activities();
    }

    public function index() {
        // Search & filter via GET
        $q = $_GET['q'] ?? null;
        $status = $_GET['status'] ?? null; // 'approved', 'pending', 'rejected'
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $total = $this->model->countAll($q, $status);
        $rows = $this->model->getAll($q, $status, $limit, $offset);

        // pass variables to view
        include __DIR__ . '/../views/projects/projects.php';
    }

    public function create() {
        // show form (empty)
        $activity = null;
        include __DIR__ . '/../views/projects/detail.php';
    }

    public function store() {
        // handle POST create
        $data = $this->sanitizePost();
        // handle uploads
        $uploads = $this->handleUploads();
        // if ($uploads['thumbnail']) $data['thumbnail_image'] = $uploads['thumbnail'];
        if ($uploads['banner']) $data['banner_image'] = $uploads['banner'];

        $id = $this->model->insert($data);
        if ($id) {
            header("Location: index.php?action=projects&msg=created");
            exit;
        } else {
            $error = "Failed to create.";
            $activity = null;
            include __DIR__ . '/../views/projects/detail.php';
        }
    }

    public function edit() {
        $id = intval($_GET['id'] ?? 0);
        $activity = $this->model->getById($id);
        if (!$activity) {
            header("Location: index.php?action=projects&msg=notfound");
            exit;
        }
        include __DIR__ . '/../views/projects/detail.php';
    }

    public function update() {
        $id = intval($_POST['id'] ?? 0);
        $data = $this->sanitizePost();
        $uploads = $this->handleUploads();
        // if ($uploads['thumbnail']) $data['thumbnail_image'] = $uploads['thumbnail'];
        if ($uploads['banner']) $data['banner_image'] = $uploads['banner'];

        $ok = $this->model->update($id, $data);
        if ($ok) {
            header("Location: index.php?action=projects&msg=updated");
            exit;
        } else {
            $error = "Update failed.";
            $activity = $this->model->getById($id);
            include __DIR__ . '/../views/projects/detail.php';
        }
    }

    public function delete() {
        $id = intval($_POST['id'] ?? 0);
        if ($id && $this->model->delete($id)) {
            // respond for AJAX
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Successfully Deleted!']);
            exit;
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Delete failed']);
        exit;
    }

    private function sanitizePost() {
    $status = trim($_POST['status'] ?? '');

    // validasi status untuk match database
    $valid = ['published','progress','cancelled'];
    if (!in_array($status, $valid)) {
        $status = 'progress';
    }

    return [
        'title'            => trim($_POST['title'] ?? ''),
        'label'            => trim($_POST['label'] ?? ''),
        'short_description'=> trim($_POST['short_description'] ?? ''),
        'full_description' => trim($_POST['full_description'] ?? ''),
        'published_at'     => !empty($_POST['published_at']) ? $_POST['published_at'] : null,
        'status'           => $status,
        'document_link'    => trim($_POST['document_link'] ?? '')
    ];
}

    private function handleUploads() {
        $result = ['thumbnail' => null, 'banner' => null];
        $uploadDir = __DIR__ . '/../../img/activities/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        // if (!empty($_FILES['thumbnail_image']['tmp_name'])) {
        //     $ext = pathinfo($_FILES['thumbnail_image']['name'], PATHINFO_EXTENSION);
        //     $fn = 'thumb_' . time() . '_' . rand(100,999) . '.' . $ext;
        //     $dest = $uploadDir . $fn;
        //     if (move_uploaded_file($_FILES['thumbnail_image']['tmp_name'], $dest)) {
        //         $result['thumbnail'] = 'uploads/activities/' . $fn;
        //     }
        // }
        if (!empty($_FILES['banner_image']['tmp_name'])) {
            $ext = pathinfo($_FILES['banner_image']['name'], PATHINFO_EXTENSION);
            $fn = 'banner_' . time() . '_' . rand(100,999) . '.' . $ext;
            $dest = $uploadDir . $fn;
            if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $dest)) {
                $result['banner'] = 'uploads/activities/' . $fn;
            }
        }
        return $result;
    }
}
