<?php
require_once __DIR__ . '/../models/members.php';
class HomeController {

    private $members;

    public function __construct() {
        $this->members = new Members();
    }
    public function index() {
        $members = $this->members->getAll();
        $totalmembers = count($members);
        $members2025 = count($this->members->getByYear(2025));
        include __DIR__ . '/../views/home.php';
    }
}