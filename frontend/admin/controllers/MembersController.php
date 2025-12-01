<?php
require_once __DIR__ . '/../models/members.php';
class MembersController
{
    private $model;
    public function __construct()
    {
        $this->model = new Members();
    }
    public function members() {
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
        $fields = [
            $_POST['nama'],
            $_POST['nip'],
            $_POST['nidn'],
            $_POST['nomor_ponsel'],
            $_POST['status'],
            $_POST['kode_prodi']
        ];

        if ($_POST['dosen_id'] === "") {
            $this->model->create($fields);
        } else {
            $this->model->update($_POST['dosen_id'], $fields);
        }
        header("Location: index.php");
    }
    public function delete($id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=members");
    }
}
