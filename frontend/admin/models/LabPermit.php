<?php
require_once __DIR__ . '/../Database.php';

class LabPermit
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /* Count by status */
    public function countByStatus($status)
    {
        $sql = "SELECT COUNT(*) FROM lab_permit_requests WHERE status = $1";
        $result = pg_query_params($this->db, $sql, [$status]);
        return pg_fetch_result($result, 0, 0) ?: 0;
    }

    /* Get all requests */
    public function getAll()
    {
        $sql = "SELECT * FROM lab_permit_requests ORDER BY created_at DESC";
        $result = pg_query($this->db, $sql);
        return pg_fetch_all($result) ?: [];
    }

    /* Get requests by status */
    public function getByStatus($status)
    {
        $sql = "SELECT * FROM lab_permit_requests WHERE status = $1 ORDER BY created_at DESC";
        $result = pg_query_params($this->db, $sql, [$status]);
        return pg_fetch_all($result) ?: [];
    }

    /* Get detail */
    public function find($id)
    {
        $sql = "SELECT * FROM lab_permit_requests WHERE id = $1";
        $result = pg_query_params($this->db, $sql, [$id]);
        return pg_fetch_assoc($result) ?: null;
    }

    /* Approve request */
    public function approve($id)
    {
        $sql = "UPDATE lab_permit_requests SET status = 'accepted' WHERE id = $1";
        return pg_query_params($this->db, $sql, [$id]);
    }

    /* Reject request with reason */
    public function reject($id, $reason)
    {
        $sql = "UPDATE lab_permit_requests SET status = 'rejected', reject_reason = $1 WHERE id = $2";
        return pg_query_params($this->db, $sql, [$reason, $id]);
    }

    public function getRecent($limit = 3)   
    {
    $sql = "SELECT * FROM lab_permit_requests ORDER BY submitted_at DESC LIMIT $1";
    $res = pg_query_params($this->db, $sql, [$limit]);
    return pg_fetch_all($res) ?: [];
    }
    public function insert($data)
    {
    $sql = "
        INSERT INTO lab_permit_requests
        (full_name, study_program, semester, phone, email, reason, status)
        VALUES ($1, $2, $3, $4, $5, $6, $7)
    ";

    return pg_query_params($this->db, $sql, [
        $data['full_name'],
        $data['study_program'],
        $data['semester'],
        $data['phone'],
        $data['email'],
        $data['reason'],
        $data['status']
    ]);
    }
}
