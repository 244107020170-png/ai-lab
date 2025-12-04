<?php
require_once __DIR__ . '/../models/members.php';
require_once __DIR__ . '/../models/Activities.php';
require_once __DIR__ . '/../models/News.php';

class HomeController
{

    private $members;
    private $activities;
    private $news;

    public function __construct()
    {
        $this->members = new Members();
        $this->activities = new Activities();
        $this->news = new News();
    }

    public function index()
    {

        // Members
        $members = $this->members->getAll();
        $totalmembers = count($members);
        $members2025 = count($this->members->getByYear(2025));

        // Activities
        // $projectspublished  = $this->activities->countAll(null, 'approved');
        // $projectsinprogress = $this->activities->countAll(null, 'pending');
        $projectspublished = count($this->activities->getByStatus('published'));

        // FIX: Ensure the variable name matches what is used in extract() below
        $projectsinprogress = count($this->activities->getByStatus('progress'));

        extract([
            "totalmembers"       => $totalmembers,
            "members2025"        => $members2025,
            "projectspublished"  => $projectspublished,
            "projectsinprogress" => $projectsinprogress
        ]);

        // News
        $news = $this->news->getAll();
        $newscount = count($news);
        $newscountapproved = count($this->news->findByStatus('approved'));
        $newscountpending = count($this->news->findByStatus('pending'));

        include __DIR__ . '/../views/home.php';
    }
}
