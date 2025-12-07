<?php
require_once __DIR__ . '/../Database.php';

class Volunteer
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /* INSERT NEW VOLUNTEER */
    public function insert($data)
    {
        $sql = "INSERT INTO volunteer_registrations 
        (full_name, nickname, study_program, semester, email, phone, areas, skills, motivation, availability, status, created_at, updated_at)
        VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,NOW(),NOW())";

        return pg_query_params($this->db, $sql, [
            $data['full_name'],
            $data['nickname'],
            $data['study_program'],
            $data['semester'],
            $data['email'],
            $data['phone'],
            $data['areas'],
            $data['skills'],
            $data['motivation'],
            $data['availability'],
            $data['status']
        ]);
    }

    /* GET ALL VOLUNTEERS */
    public function getAll()
    {
        $sql = "SELECT * FROM volunteer_registrations ORDER BY created_at DESC";
        $res = pg_query($this->db, $sql);
        return pg_fetch_all($res) ?: [];
    }

    /* GET VOLUNTEER BY ID */
    public function getById($id)
    {
        $sql = "SELECT * FROM volunteer_registrations WHERE id = $1 LIMIT 1";
        $res = pg_query_params($this->db, $sql, [$id]);
        return pg_fetch_assoc($res) ?: null;
    }

    /* UPDATE STATUS (Approve / Reject) */
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE volunteer_registrations 
                SET status = $1, updated_at = NOW() 
                WHERE id = $2";

        return pg_query_params($this->db, $sql, [$status, $id]);
    }
}
