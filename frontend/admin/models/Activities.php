<?php
require_once __DIR__ . '/../Database.php';

class Activities {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * getAll with search, filter and pagination
     * $search: string (title)
     * $status: approved|pending|rejected|null
     * $limit, $offset: ints
     * returns array of rows
     */
    public function getAll($search = null, $status = null, $limit = 10, $offset = 0) {
        $params = [];
        $where = [];

        $sql = "SELECT * FROM activities";

        if ($search) {
            $where[] = "title ILIKE $" . (count($params)+1);
            $params[] = '%' . $search . '%';
        }

        if ($status) {
            $where[] = "status = $" . (count($params)+1);
            $params[] = $status;
        }

        if ($where) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY created_at DESC LIMIT $" . (count($params)+1) . " OFFSET $" . (count($params)+2);
        $params[] = $limit;
        $params[] = $offset;

        $res = @pg_query_params($this->db, $sql, $params);
        if (!$res) return [];

        $rows = [];
        while ($r = pg_fetch_assoc($res)) $rows[] = $r;
        return $rows;
    }

    public function countAll($search = null, $status = null) {
        $params = [];
        $where = [];

        $sql = "SELECT COUNT(*) AS cnt FROM activities";

        if ($search) {
            $where[] = "title ILIKE $" . (count($params)+1);
            $params[] = '%' . $search . '%';
        }

        if ($status) {
            $where[] = "status = $" . (count($params)+1);
            $params[] = $status;
        }

        if ($where) $sql .= " WHERE " . implode(' AND ', $where);

        $res = @pg_query_params($this->db, $sql, $params);
        if (!$res) return 0;
        $row = pg_fetch_assoc($res);
        return intval($row['cnt']);
    }

    public function getById($id) {
        $res = pg_query_params($this->db, "SELECT * FROM activities WHERE id = $1 LIMIT 1", [$id]);
        if (!$res) return null;
        return pg_fetch_assoc($res);
    }

    public function insert($data) {
        $sql = "INSERT INTO activities (title, label, short_description, full_description, published_at, thumbnail_image, banner_image, status, document_link, created_at, updated_at)
                VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,NOW(),NOW()) RETURNING id";
        $params = [
            $data['title'] ?? null,
            $data['label'] ?? null,
            $data['short_description'] ?? null,
            $data['full_description'] ?? null,
            $data['published_at'] ?? null,
            $data['thumbnail_image'] ?? null,
            $data['banner_image'] ?? null,
            $data['status'] ?? 'pending',
            $data['document_link'] ?? null
        ];
        $res = pg_query_params($this->db, $sql, $params);
        if (!$res) return false;
        $row = pg_fetch_assoc($res);
        return $row['id'] ?? false;
    }

    public function update($id, $data) {
        // Build set dynamically
        $set = [];
        $params = [];
        $i = 1;
        foreach (['title','label','short_description','full_description','published_at','thumbnail_image','banner_image','status','document_link'] as $col) {

    if (array_key_exists($col, $data) && $data[$col] !== '') {
        $set[] = "$col = $" . $i++;
        $params[] = $data[$col];
    }
}
        if (empty($set)) return false;
        $params[] = $id;
        $sql = "UPDATE activities SET " . implode(", ", $set) . ", updated_at = NOW() WHERE id = $" . $i;
        $res = pg_query_params($this->db, $sql, $params);
        return $res !== false;
    }

    public function delete($id) {
        $res = pg_query_params($this->db, "DELETE FROM activities WHERE id = $1", [$id]);
        return $res !== false;
    }
}
