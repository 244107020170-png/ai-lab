<?php
require_once __DIR__ . '/../Database.php';
class Members
{
    private $conn;
    public function __construct()
    {
        $this->conn = Database::getConnection();
    }
    public function getAll()

    {
        $res = pg_query($this->conn, "SELECT * FROM members ORDER BY id ASC");
        return pg_fetch_all($res) ?: [];
    }
    public function find($id)
    {
        $res = pg_query_params($this->conn, "SELECT * FROM members WHERE id=$1", [$id]);
        return pg_fetch_assoc($res);
    }
    public function create($data)
    {
        // Added 'status' and 'RETURNING id'
        $sql = "INSERT INTO members (full_name, role, photo, expertise, description, linkedin, scholar, researchgate, orcid, status, created_at, updated_at) 
                VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, NOW(), NULL)
                RETURNING id";

        $res = pg_query_params($this->conn, $sql, $data);
        $row = pg_fetch_assoc($res);
        return $row['id']; // Returns the new ID (e.g., 15)
    }
    public function getByYear($year)
    {
        $sql = "SELECT * FROM members 
                WHERE EXTRACT(YEAR FROM created_at) = $1 
                ORDER BY id ASC";

        $res = pg_query_params($this->conn, $sql, [$year]);
        return pg_fetch_all($res) ?: [];
    }

    public function sortBy($sort, $order, $limit, $offset)
    {
        // 1. Whitelist allowed columns to prevent SQL Injection
        // (Add any other columns you want to sort by here)
        $allowedCols = ['id', 'full_name', 'role', 'expertise', 'status', 'created_at'];
        $allowedDirs = ['ASC', 'DESC'];

        // 2. Validate inputs (Default to 'id' and 'ASC' if invalid)
        $sortCol = in_array($sort, $allowedCols) ? $sort : 'id';
        $sortDir = in_array(strtoupper($order), $allowedDirs) ? strtoupper($order) : 'ASC';

        // 3. Inject the VALIDATED column/direction directly into the string
        // Note: We used $1 and $2 for LIMIT and OFFSET now.
        $sql = "SELECT * FROM members ORDER BY $sortCol $sortDir LIMIT $1 OFFSET $2";

        // 4. Bind only the values (limit and offset)
        $res = pg_query_params($this->conn, $sql, [$limit, $offset]);

        return pg_fetch_all($res) ?: [];
    }

    public function getPaginated($limit, $offset)
    {
        $sql = "SELECT * FROM members ORDER BY id ASC LIMIT $1 OFFSET $2";
        $res = pg_query_params($this->conn, $sql, [$limit, $offset]);
        return pg_fetch_all($res) ?: [];
    }

    public function countAll()
    {
        $res = pg_query($this->conn, "SELECT COUNT(*) as total FROM members");
        $row = pg_fetch_assoc($res);
        return $row['total'];
    }

    public function update($id, $data)
    {
        $sql = "UPDATE members SET 
                full_name=$1, role=$2, photo=$3, expertise=$4, description=$5, 
                linkedin=$6, scholar=$7, researchgate=$8, orcid=$9, status=$10, updated_at=NOW() 
                WHERE id=$11";

        $data[] = $id; // Add ID as the 11th parameter
        return pg_query_params($this->conn, $sql, $data);
    }
    public function delete($id)
    {
        return pg_query_params($this->conn, "DELETE FROM members WHERE
                                id=$1", [$id]);
    }

    public function resetID()
    {
        return "SELECT setval('members_id_seq', COALESCE((SELECT MAX(id) FROM members), 1))";
    }

    public function getStudyBackground($id)
    {
        $res = pg_query_params($this->conn, "SELECT * FROM member_backgrounds WHERE member_id=$1", [$id]);
        return pg_fetch_all($res) ?: [];
    }

    public function createBackground($memberId, $data)
    {
        $sql = "INSERT INTO member_backgrounds (member_id, institute, academic_title, year, degree) 
                VALUES ($1, $2, $3, $4, $5)";

        return pg_query_params($this->conn, $sql, [
            $memberId,                // $1 (Links to the parent member)
            $data['institute'],       // $2
            $data['academic_title'],  // $3
            $data['year'],            // $4
            $data['degree']           // $5
        ]);
    }

    public function updateBackground($id, $data)
    {
        // We update all 4 columns at once for this specific row ID
        $sql = "UPDATE member_backgrounds SET 
                institute = $1, 
                academic_title = $2, 
                year = $3, 
                degree = $4 
                WHERE id = $5"; // Target the specific background row, NOT the member_id

        return pg_query_params($this->conn, $sql, [
            $data['institute'],       // $1
            $data['academic_title'],  // $2
            $data['year'],            // $3
            $data['degree'],          // $4
            $id                       // $5 (The Background ID)
        ]);
    }

    public function deleteBackground($id)
    {
        return pg_query_params($this->conn, "DELETE FROM member_backgrounds WHERE id=$1", [$id]);
    }

    public function updatephoto($id, $photoName)
    {
        $sql = "UPDATE members SET photo=$1 WHERE id=$2";
        return pg_query_params($this->conn, $sql, [$photoName, $id]);
    }
}
