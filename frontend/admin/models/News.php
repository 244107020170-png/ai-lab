<?php
require_once __DIR__ . '/../Database.php';

class News
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // --- Get all categories (unique)
    public function getUniqueCategories()
    {
        $sql = "SELECT DISTINCT category FROM news ORDER BY category ASC";
        $res = pg_query($this->conn, $sql);
        $rows = pg_fetch_all($res);
        return $rows ? array_column($rows, 'category') : [];
    }

    // --- Get news by ID (for edit)
    public function getById($id)
    {
        $sql = "SELECT * FROM news WHERE id = $1 LIMIT 1";
        $res = pg_query_params($this->conn, $sql, [$id]);
        return pg_fetch_assoc($res);
    }

    // --- List all news for index page
    public function getAll()
    {
        $sql = "SELECT * FROM news ORDER BY created_at DESC";
        $res = pg_query($this->conn, $sql);
        return pg_fetch_all($res) ?: [];
    }

    // --- Count all news
    public function countAll()
    {
        $sql = "SELECT COUNT(*) AS total FROM news";
        $res = pg_query($this->conn, $sql);
        $row = pg_fetch_assoc($res);
        return $row['total'] ?? 0;
    }

    public function getPaginated($limit, $offset) {
    $sql = "SELECT * FROM news ORDER BY created_at DESC LIMIT $1 OFFSET $2";
    $res = pg_query_params($this->conn, $sql, [$limit, $offset]);
    return pg_fetch_all($res) ?: [];
}

    // --- Insert news
    public function insert($data)
    {
        $sql = "INSERT INTO news 
            (title, category, excerpt, content, created_at, quote, status, image_thumb, image_detail, updated_at)
            VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9, NOW())
            RETURNING id";

        $params = [
            $data['title'],
            $data['category'],
            $data['excerpt'],
            $data['content'],
            $data['created_at'],
            $data['quote'],
            $data['status'],
            $data['image_thumb'],
            $data['image_detail']
        ];

        $res = pg_query_params($this->conn, $sql, $params);
        if (!$res) return false;

        return pg_fetch_result($res, 0, 'id');
    }

    // --- Update news
    public function update($id, $data)
    {
        $sql = "UPDATE news SET
            title=$1,
            category=$2,
            excerpt=$3,
            content=$4,
            created_at=$5,
            quote=$6,
            status=$7,
            image_thumb=$8,
            image_detail=$9,
            updated_at = NOW()
        WHERE id = $10";

        $params = [
            $data['title'],
            $data['category'],
            $data['excerpt'],
            $data['content'],
            $data['created_at'],
            $data['quote'],
            $data['status'],
            $data['image_thumb'],
            $data['image_detail'],
            $id
        ];

        return pg_query_params($this->conn, $sql, $params) ? true : false;
    }

    // --- Delete news
    public function delete($id)
    {
        return pg_query_params($this->conn, "DELETE FROM news WHERE id=$1", [$id]);
    }
}
