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
            exit;
        }

        $sql = "
            SELECT 
                m.full_name,
                u.email,
                m.photo,
                m.expertise,
                m.description,
                m.linkedin,
                m.scholar,
                m.researchgate,
                m.orcid
            FROM members m
            JOIN users u ON m.user_id = u.id
            WHERE m.user_id = $1
        ";

        $res = pg_query_params($this->conn, $sql, [$_SESSION["user_id"]]);
        $member = pg_fetch_assoc($res);

        $member["photo_url"] = !empty($member["photo"])
            ? "/pbl/frontend/img/profile-photos/" . $member["photo"]
            : "/pbl/views/img/memberavatar.png";

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
        $expertise   = $_POST["expertise"] ?? "";
        $description = $_POST["description"] ?? "";
        $linkedin    = $_POST["linkLinkedIn"] ?? "";
        $scholar     = $_POST["linkScholar"] ?? "";
        $orcid       = $_POST["linkOrcid"] ?? "";
        $rg          = $_POST["linkRG"] ?? "";

        /* ==== AVATAR LOGIC ==== */
        $removeAvatar = isset($_POST["removeAvatar"]);
        $photoFile = null;

        if (!empty($_FILES["avatarFile"]["name"])) {

            $ext = strtolower(pathinfo($_FILES["avatarFile"]["name"], PATHINFO_EXTENSION));
            $allowed = ["jpg", "jpeg", "png"];

            if (!in_array($ext, $allowed)) {
                die("Invalid image type");
            }

            $photoFile = "avatar_" . $id . "_" . time() . "." . $ext;

            $uploadDir = __DIR__ . "/../../frontend/img/profile-photos/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file(
                $_FILES["avatarFile"]["tmp_name"],
                $uploadDir . $photoFile
            );
        }

        /* ==== UPDATE QUERY ==== */
        $sql = "
            UPDATE members SET
                full_name = $1,
                expertise = $2,
                description = $3,
                linkedin = $4,
                scholar = $5,
                orcid = $6,
                researchgate = $7,
                updated_at = NOW()
                " . (
                    $removeAvatar ? ", photo = NULL" :
                    ($photoFile ? ", photo = '$photoFile'" : "")
                ) . "
            WHERE user_id = $8
        ";

        pg_query_params($this->conn, $sql, [
            $full_name,
            $expertise,
            $description,
            $linkedin,
            $scholar,
            $orcid,
            $rg,
            $id
        ]);

        header("Location: index.php?action=member_profile&saved=1");
    }
}
