<?php
require_once __DIR__ . '/../models/LabPermit.php';

class LabPermitController
{
    private $permit;

    public function __construct()
    {
        $this->permit = new LabPermit();
    }

    public function index()
    {
        // Check if an operation (op) is requested
        $op = $_GET['op'] ?? null;

        /* HANDLE AJAX OPERATIONS */
        if ($op === "detail") {
            $id = $_GET['id'] ?? null;
            $data = $this->permit->find($id);

            header("Content-Type: application/json");
            echo json_encode($data);
            return;
        }

        if ($op === "approve") {
            $id = $_POST['id'] ?? null;
            $this->permit->approve($id);
            echo "OK";
            return;
        }

        if ($op === "reject") {
            $id = $_POST['id'] ?? null;
            $reason = $_POST['reason'] ?? '';
            $this->permit->reject($id, $reason);
            echo "OK";
            return;
        }

        /* NORMAL PAGE LOAD */
        $status = $_GET['status'] ?? '';

        if ($status !== '') {
            $rows = $this->permit->getByStatus($status);
        } else {
            $rows = $this->permit->getAll();
        }

        include __DIR__ . '/../views/lab_permit.php';
    }
}
