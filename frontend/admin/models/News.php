<?php
require_once __DIR__ . '/../Database.php';

class News
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // 1. Get All News (Basic)
    public function getAll()
    {
        // Ordered by 'date' (publication date) by default
        $sql = "SELECT * FROM news ORDER BY date DESC";
        $res = pg_query($this->conn, $sql);
        return pg_fetch_all($res) ?: [];
    }

    // 2. Pagination with Sorting (Consistent with your Members model)
    public function getPaginated($limit, $offset, $col = 'date', $dir = 'DESC')
    {
        // Security: Whitelist allowed columns to prevent SQL injection
        $allowedCols = ['id', 'title', 'category', 'date', 'status'];
        $allowedDirs = ['ASC', 'DESC'];

        $orderBy = in_array($col, $allowedCols) ? $col : 'date';
        $orderDir = in_array(strtoupper($dir), $allowedDirs) ? strtoupper($dir) : 'DESC';

        $sql = "SELECT * FROM news ORDER BY $orderBy $orderDir LIMIT $1 OFFSET $2";
        $res = pg_query_params($this->conn, $sql, [$limit, $offset]);
        
        return pg_fetch_all($res) ?: [];
    }

    // 3. Count Total Rows (Needed for Pagination math)
    public function countAll()
    {
        $res = pg_query($this->conn, "SELECT COUNT(*) as total FROM news");
        $row = pg_fetch_assoc($res);
        return $row['total'];
    }

    // 4. Find Single News Item by ID
    public function findById($id)
    {
        $sql = "SELECT * FROM news WHERE id = $1";
        $res = pg_query_params($this->conn, $sql, [$id]);
        return pg_fetch_assoc($res);
    }

    // 5. Create News
    public function create($data)
    {
        // Expects $data array with 9 items:
        // [title, category, date, image_thumb, image_detail, excerpt, content, quote, status]
        
        $sql = "INSERT INTO news (title, category, date, image_thumb, image_detail, excerpt, content, quote, status, created_at, updated_at) 
                VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, NOW(), NULL)";
        
        return pg_query_params($this->conn, $sql, $data);
    }

    // 6. Update News
    public function update($id, $data)
    {
        // Expects $data array with the same 9 items as create.
        
        $sql = "UPDATE news SET 
                title=$1, category=$2, date=$3, image_thumb=$4, image_detail=$5, 
                excerpt=$6, content=$7, quote=$8, status=$9, updated_at=NOW() 
                WHERE id=$10";
        
        // Add the ID as the last parameter ($10)
        $data[] = $id; 
        return pg_query_params($this->conn, $sql, $data);
    }

    // 7. Delete News
    public function delete($id)
    {
        return pg_query_params($this->conn, "DELETE FROM news WHERE id=$1", [$id]);
    }

    // 8. Look for News based on status
    public function findByStatus($status)
    {
        $sql = "SELECT * FROM news WHERE status = $1 ORDER BY date DESC";
        $res = pg_query_params($this->conn, $sql, [$status]);
        return pg_fetch_all($res) ?: [];
    }
}