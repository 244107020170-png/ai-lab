<?php
require_once __DIR__ . '/../models/members.php';
require_once __DIR__ . '/../models/Activities.php';

class HomeController {

    private $members;
    private $activities;

    public function __construct() {
        $this->members = new Members();
        $this->activities = new Activities();
    }

    public function index() {

        // Members
        $members = $this->members->getAll();
        $totalmembers = count($members);
        $members2025 = count($this->members->getByYear(2025));

        // Activities
        $projectspublished  = $this->activities->countAll(null, 'approved');
        $projectsinprogress = $this->activities->countAll(null, 'pending');

        extract([
            "totalmembers"       => $totalmembers,
            "members2025"        => $members2025,
            "projectspublished"  => $projectspublished,
            "projectsinprogress" => $projectsinprogress
        ]);

        include __DIR__ . '/../views/home.php';
    }
}
