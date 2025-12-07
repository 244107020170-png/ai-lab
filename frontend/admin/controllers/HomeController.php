<?php
require_once __DIR__ . '/../models/Members.php';
require_once __DIR__ . '/../models/Activities.php';
require_once __DIR__ . '/../models/News.php';
require_once __DIR__ . '/../models/LabPermit.php';   // <-- ADD THIS

class HomeController
{
    private $members;
    private $activities;
    private $news;
    private $permit;   // <-- ADD THIS

    public function __construct()
    {
        $this->members = new Members();
        $this->activities = new Activities();
        $this->news = new News();
        $this->permit = new LabPermit();  // <-- ADD THIS
    }

    public function index()
    {
        /* -----------------------------------
           MEMBERS
        ----------------------------------- */
        $members = $this->members->getAll();
        $totalmembers = count($members);
        $members2025 = count($this->members->getByYear(2025));

        /* -----------------------------------
           ACTIVITIES / PROJECTS
        ----------------------------------- */
        $projectspublished  = count($this->activities->getByStatus('published'));
        $projectsinprogress = count($this->activities->getByStatus('progress'));

        $recentmembers  = $this->members->sortBy('created_at', 'DESC', 8, 0);
        $recentprojects = $this->activities->sortBy('created_at', 'DESC', 8, 0);

        /* -----------------------------------
           LAB PERMIT (NEW)
        ----------------------------------- */
        $permitPending  = $this->permit->countByStatus("pending");
        $permitAccepted = $this->permit->countByStatus("accepted");
        $permitRejected = $this->permit->countByStatus("rejected");

        /* -----------------------------------
           NEWS
        ----------------------------------- */
        $news = $this->news->getAll();
        $newscount = count($news);
        $newscountapproved = count($this->news->findByStatus('approved'));
        $newscountpending  = count($this->news->findByStatus('pending'));

        /* -----------------------------------
           EXPORT VARIABLES TO VIEW
        ----------------------------------- */
        extract([
            "totalmembers"       => $totalmembers,
            "members2025"        => $members2025,
            "projectspublished"  => $projectspublished,
            "projectsinprogress" => $projectsinprogress,

            // NEW
            "permitPending"      => $permitPending,
            "permitAccepted"     => $permitAccepted,
            "permitRejected"     => $permitRejected,

            "recentmembers"      => $recentmembers,
            "recentprojects"     => $recentprojects,

            "newscount"          => $newscount,
            "newscountapproved"  => $newscountapproved,
            "newscountpending"   => $newscountpending,
        ]);

        include __DIR__ . '/../views/home.php';
    }
}
