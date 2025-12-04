<?php
require_once __DIR__ . '/../Database.php';

class Activities {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // GET ALL
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

        $sql .= " ORDER BY id ASC LIMIT $" . (count($params)+1) . " OFFSET $" . (count($params)+2);
        $params[] = $limit;
        $params[] = $offset;

        $res = pg_query_params($this->db, $sql, $params);
        return pg_fetch_all($res) ?: [];
    }

    // COUNT
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

        if ($where) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $res = pg_query_params($this->db, $sql, $params);
        $row = pg_fetch_assoc($res);
        return intval($row['cnt']);
    }

    // GET BY ID
    public function getById($id) {
        $res = pg_query_params($this->db, "SELECT * FROM activities WHERE id = $1", [$id]);
        return pg_fetch_assoc($res);
    }

    // Get by Status
    public function getByStatus($status) {
    // FIX: Added a comma after the SQL string
    $res = pg_query_params($this->db, "SELECT * FROM activities WHERE status = $1", [$status]);
    
    $data = pg_fetch_all($res);
    
    // Safety check: pg_fetch_all returns FALSE if no rows found.
    // Returning an empty array [] prevents count() from crashing in the controller.
    return $data ? $data : []; 
}

    // INSERT
    public function insert($data) {

        // auto fix label
        if (empty($data['label'])) {
            $data['label'] = $data['status'];
        }

        $sql = "INSERT INTO activities 
                (title, label, short_description, full_description, published_at, 
                 thumbnail_image, banner_image, status, document_link, created_at, updated_at)
                VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,NOW(),NOW())
                RETURNING id";

        $params = [
            $data['title'] ?? null,
            $data['label'] ?? null,
            $data['short_description'] ?? null,
            $data['full_description'] ?? null,
            $data['published_at'] ?? null,
            $data['thumbnail_image'] ?? null,
            $data['banner_image'] ?? null,
            $data['status'] ?? 'progress',
            $data['document_link'] ?? null
        ];

        $res = pg_query_params($this->db, $sql, $params);
        $row = pg_fetch_assoc($res);
        return $row['id'];
    }

    // UPDATE (final fixed)
    public function update($id, $data) {

        $set = [];
        $params = [];
        $i = 1;

        $columns = [
            'title','label','short_description','full_description',
            'published_at','thumbnail_image','banner_image',
            'status','document_link'
        ];

        foreach ($columns as $col) {

            // fix label NULL → same as status
            if ($col === 'label' && empty($data['label'])) {
                $data['label'] = $data['status'];
            }

            // empty string to NULL
            if (isset($data[$col]) && $data[$col] === '') {
                $data[$col] = null;
            }

            // only update if exists
            if (array_key_exists($col, $data)) {
                $set[] = "$col = $" . $i;
                $params[] = $data[$col];
                $i++;
            }
        }

        if (empty($set)) return false;

        $params[] = $id;
        $sql = "UPDATE activities SET " . implode(", ", $set) . 
               ", updated_at = NOW() WHERE id = $" . $i;

        $res = pg_query_params($this->db, $sql, $params);
        return $res !== false;
    }

    // DELETE
    public function delete($id) {
        return pg_query_params($this->db, "DELETE FROM activities WHERE id = $1", [$id]) !== false;
    }
}
