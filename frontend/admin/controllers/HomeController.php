<?php
require_once __DIR__ . '/../models/members.php';
require_once __DIR__ . '/../models/projects.php';
class HomeController {

    private $members;
    private $projects;

    public function __construct() {
        $this->members = new Members();
        $this->projects = new Activities();
    }

    public function index() {
        $members = $this->members->getAll();
        $totalmembers = count($members);
        $members2025 = count($this->members->getByYear(2025));
        $projectspublished = count($this->projects->getByStatus('Published'));
        $projectsinprogress = count($this->projects->getByStatus('Work In Progress'));
        include __DIR__ . '/../views/home.php';
    }
}