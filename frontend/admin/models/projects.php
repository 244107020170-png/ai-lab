<?php
require_once __DIR__ . '/../Database.php';

class Activities
{
    private $conn;

    public function __construct() 
    {
        $this->conn = Database::getConnection();
    }

    public function getAll() 
    {
        $res = pg_query($this->conn, "SELECT * FROM activities ORDER BY id ASC");
        return pg_fetch_all($res) ?: [];
    }

    public function find($id) 
    {
        $res = pg_query_params($this->conn, "SELECT * FROM activities WHERE id=$1", [$id]);
        return pg_fetch_assoc($res);
    }

    public function create($data) 
    {
        // Expects 7 items in $data array corresponding to:
        // title, label, short_description, full_description, published_at, thumbnail_image, banner_image
        $sql = "INSERT INTO activities (title, label, short_description, full_description, published_at, thumbnail_image, banner_image, status, created_at, updated_at) 
        VALUES ($1, $2, $3, $4, $5, $6, $7, $8, NOW(), NULL)";
        return pg_query_params($this->conn, $sql, $data);
    }

    public function update($id, $data) 
    {
        $sql = "UPDATE activities SET 
        title=$1, label=$2, short_description=$3, full_description=$4, 
        published_at=$5, thumbnail_image=$6, banner_image=$7, status=$8, updated_at=NOW() 
        WHERE id=$9";
        $data[] = $id;
        return pg_query_params($this->conn, $sql, $data);
    }

    // Find a single activity by its specific ID
    public function getById($id)
    {
        $sql = "SELECT * FROM activities WHERE id = $1";
        $res = pg_query_params($this->conn, $sql, [$id]);
        
        // Returns an associative array of the row, or false if not found
        return pg_fetch_all($res) ?: [];
    }

    // Find all activities that match a specific status (e.g., 'Published')
    public function getByStatus($status)
    {
        $sql = "SELECT * FROM activities WHERE status = $1 ORDER BY id ASC";
        $res = pg_query_params($this->conn, $sql, [$status]);
        
        // Returns an array of all matching rows, or an empty array if none found
        return pg_fetch_all($res) ?: [];
    }

    public function delete($id) 
    {
        return pg_query_params($this->conn, "DELETE FROM activities WHERE id=$1", [$id]);
    }
}