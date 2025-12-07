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
        $status = $_GET['status'] ?? '';
        
        if ($status) {
            $rows = $this->permit->getByStatus($status);
        } else {
            $rows = $this->permit->getAll();
        }

        include __DIR__ . '/../views/lab_permit.php';
    }
}
