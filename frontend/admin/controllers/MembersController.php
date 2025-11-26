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
        $members = $this->model->getAll();
        include __DIR__ . '/../views/members.php';
    }
    public function form()
    {
        $members = $this->model->getAll();
        include __DIR__ . '/../views/dosen_form.php';
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
        header("Location: index.php");
    }
}
