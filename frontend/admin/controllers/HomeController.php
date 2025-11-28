<?php
require_once __DIR__ . '/../models/members.php';
require_once __DIR__ . '/../models/projects.php';

class HomeController {

    private $members;
    private $projects;

    public function __construct() {
        $this->members = new Members();
        $this->projects = new Projects(); // pastikan nama class benar
    }

    public function index() {
        // members
        $members = $this->members->getAll();
        $totalmembers = count($members);
        $members2025 = count($this->members->getByYear(2025));

        // projects/activity
        $projectspublished = count($this->projects->getByStatus('Published'));
        $projectsinprogress = count($this->projects->getByStatus('Work In Progress'));

        // kirim ke view
        extract([
            "totalmembers" => $totalmembers,
            "members2025" => $members2025,
            "projectspublished" => $projectspublished,
            "projectsinprogress" => $projectsinprogress
        ]);

        include __DIR__ . '/../views/home.php';
    }
}
