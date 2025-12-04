<?php

class Volunteer {

    private $db;

    public function __construct() {
        $this->db = new PDO (
            "pgsql:host=localhost;dbname=ai_lab_db", 
             "postgres", 
             "nasywa1010");//ngikut password db
    }

    // INSERT NEW VOLUNTEER
    public function insert($data) {
        $sql = "INSERT INTO volunteers 
        (full_name, nickname, study_program, semester, email, phone, areas, skills, motivation, availability, status, created_at, updated_at)
        VALUES 
        (:full_name, :nickname, :study_program, :semester, :email, :phone, :areas, :skills, :motivation, :availability, :status, NOW(), NOW())";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    // GET ALL VOLUNTEERS
    public function getAll() {
        return $this->db->query("SELECT * FROM volunteers ORDER BY created_at DESC")
                        ->fetchAll(PDO::FETCH_ASSOC);
    }

    // GET ONE VOLUNTEER BY ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM volunteers WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE STATUS (APPROVE / REJECT)
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE volunteers SET status = :status, updated_at = NOW() WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
}
