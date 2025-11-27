<?php
require_once __DIR__ . '/../models/projects.php';

class ProjectsController {
    private $model;
    public function __construct() {
        $this->model = new Activities();
    }

    public function projects() {
        $projects = $this->model->getAll();
        include __DIR__ . '/../views/projects.php';
    }
}