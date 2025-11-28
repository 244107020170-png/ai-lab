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
    $sql = "INSERT INTO members (
                full_name, role, photo, expertise, description, 
                linkedin, scholar, researchgate, orcid, 
                created_at, updated_at
            ) 
            VALUES (
                $1,$2,$3,$4,$5,$6,$7,$8,$9,NOW(),NULL
            )";

    return pg_query_params($this->conn, $sql, $data);
}
    public function getByYear($year)
    {
        // EXTRACT(YEAR FROM column) gets just the year part of the timestamp
        $sql = "SELECT * FROM members 
                WHERE EXTRACT(YEAR FROM created_at) = $1 
                ORDER BY id ASC";

        $res = pg_query_params($this->conn, $sql, [$year]);
        return pg_fetch_all($res) ?: [];
    }
    public function update($id, $data)
    {
        $sql = "UPDATE members SET 
        full_name=$1, role=$2, photo=$3, expertise=$4, description=$5, 
        linkedin=$6, scholar=$7, researchgate=$8, orcid=$9, updated_at=NOW() 
        WHERE id=$10";
        $data[] = $id;
        return pg_query_params($this->conn, $sql, $data);
    }
    public function delete($id)
    {
        return pg_query_params($this->conn, "DELETE FROM members WHERE
                                id=$1", [$id]);
    }
}
