<?php
require_once __DIR__ . '/../models/members.php';
class MembersController
{
    private $model;
    public function __construct()
    {
        $this->model = new Members();
    }
    public function members()
    {
        // 1. Settings
        $limit = 8; // How many rows per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // 2. Get Data
        $members = $this->model->getPaginated($limit, $offset);
        $membersNameASC = $this->model->sortByNameASC($limit, $offset);
        $membersNameDESC = $this->model->sortByNameDESC($limit, $offset);
        $totalRows = $this->model->countAll();
        $totalPages = ceil($totalRows / $limit);

        // 3. Send to View
        include __DIR__ . '/../views/members.php';
    }
    public function form($id = null)
    {
        $data = $id ? $this->model->find($id) : null;
        include __DIR__ . '/../views/members_form.php';
    }
    public function save()
    {
        // 1. Capture the ID (Matches name="id" in your new form)
        $id = $_POST['id'] ?? '';

        // 2. Prepare Data Array 
        // keys match the input names in your NEW form view
        $data = [
            $_POST['full_name'] ?? '',
            $_POST['role'] ?? '',
            null,                           // photo
            $_POST['expertise'] ?? '',
            $_POST['description'] ?? '',
            $_POST['linkedin'] ?? '',
            $_POST['scholar'] ?? '',
            $_POST['researchgate'] ?? '',
            $_POST['orcid'] ?? ''
        ];

        // 3. Save
        if ($id === "") {
            $this->model->create($data);
        } else {
            $this->model->update($id, $data);
        }

        header("Location: index.php?action=members");
        exit;
    }

    public function delete($id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=members");
    }
}
