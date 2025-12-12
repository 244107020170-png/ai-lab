<?php
require_once __DIR__ . "/../Database.php";

class MemberProfileController {

    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    /* ============================
       GET PROFILE (API)
    ============================= */
    public function api() {
        if (!isset($_SESSION["user_id"])) {
            echo json_encode(["error" => "Not logged in"]);
            return;
        }

        $sql = "
            SELECT 
                m.id,
                m.full_name,
                u.email,
                m.role,
                m.photo,
                m.expertise,
                m.description,
                m.linkedin,
                m.scholar,
                m.researchgate,
                m.orcid,
                m.status
            FROM members m
            LEFT JOIN users u ON m.user_id = u.id
            WHERE m.user_id = $1
        ";

        $res = pg_query_params($this->conn, $sql, [$_SESSION["user_id"]]);
        $member = pg_fetch_assoc($res);

        echo json_encode($member);
    }


    /* ============================
       UPDATE PROFILE
    ============================= */
    public function update() {

        if (!isset($_SESSION["user_id"])) {
            die("Not logged in");
        }

        $id = $_SESSION["user_id"];

        $full_name   = $_POST["fullName"] ?? "";
        $role        = $_POST["role"] ?? "";
        $expertise   = $_POST["expertise"] ?? "";
        $description = $_POST["description"] ?? "";
        $linkedin    = $_POST["linkLinkedIn"] ?? "";
        $scholar     = $_POST["linkScholar"] ?? "";
        $orcid       = $_POST["linkOrcid"] ?? "";
        $rg          = $_POST["linkRG"] ?? "";

        /* ==== HANDLE AVATAR ==== */
        $photoFile = null;

        if (!empty($_FILES["avatarFile"]["name"])) {
            $ext = pathinfo($_FILES["avatarFile"]["name"], PATHINFO_EXTENSION);
            $photoFile = "avatar_" . $id . "_" . time() . "." . $ext;

            move_uploaded_file($_FILES["avatarFile"]["tmp_name"], "uploads/members/" . $photoFile);
        }

        /* ==== UPDATE QUERY ==== */
        $sql = "
            UPDATE members SET
                full_name = $1,
                role = $2,
                expertise = $3,
                description = $4,
                linkedin = $5,
                scholar = $6,
                orcid = $7,
                researchgate = $8,
                updated_at = NOW()
                " . ($photoFile ? ", photo = '$photoFile'" : "") . "
            WHERE user_id = $9
        ";

        pg_query_params($this->conn, $sql, [
            $full_name, $role, $expertise, $description,
            $linkedin, $scholar, $orcid, $rg,
            $id
        ]);

        header("Location: index.php?action=member_profile&saved=1");
    }
}
