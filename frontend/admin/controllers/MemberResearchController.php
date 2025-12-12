<?php
require_once __DIR__ . "/../Database.php";

class MemberResearchController {

    private $conn;
    private $member_id;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION["user_id"])) {
            echo json_encode(["error" => "Not logged in"]);
            exit;
        }

        $this->conn = (new Database())->getConnection();

        $res = pg_query_params($this->conn,
            "SELECT id FROM members WHERE user_id = $1",
            [$_SESSION["user_id"]]
        );

        $row = pg_fetch_assoc($res);
        $this->member_id = $row["id"] ?? null;

        if (!$this->member_id) {
            echo json_encode(["error" => "Member not found"]);
            exit;
        }
    }

    /* ROUTER */
    public function route($action) {
        switch ($action) {

            /* PROJECTS */
            case "member_research_get_projects": return $this->getProjects();
            case "member_research_add_project": return $this->addProject();
            case "member_research_delete_project": return $this->deleteProject();

            /* PUBLICATIONS */
            case "member_research_get_publications": return $this->getPublications();
            case "member_research_add_publication": return $this->addPublication();
            case "member_research_delete_publication": return $this->deletePublication();

            /* PPM */
            case "member_research_get_ppm": return $this->getPPM();
            case "member_research_add_ppm": return $this->addPPM();
            case "member_research_delete_ppm": return $this->deletePPM();

            /* BACKGROUNDS */
            case "member_research_get_backgrounds": return $this->getBackgrounds();
            case "member_research_add_background": return $this->addBackground();
            case "member_research_delete_background": return $this->deleteBackground();

            /* IPS */
            case "member_research_get_ips": return $this->getIPS();
            case "member_research_add_ips": return $this->addIPS();
            case "member_research_delete_ips": return $this->deleteIPS();

            /* ACTIVITIES */
            case "member_research_get_activities": return $this->getActivities();
            case "member_research_add_activity": return $this->addActivity();
            case "member_research_delete_activity": return $this->deleteActivity();

            default:
                echo json_encode(["error" => "Unknown action"]);
        }
    }

    /* ---------------- PROJECTS ---------------- */

    public function getProjects() {
        $res = pg_query_params($this->conn,
            "SELECT * FROM member_research WHERE member_id=$1 ORDER BY year DESC",
            [$this->member_id]
        );
        echo json_encode([ "projects" => pg_fetch_all($res) ?: [] ]);
    }

    public function addProject() {
        pg_query_params($this->conn,
            "INSERT INTO member_research (member_id,title,description,year) VALUES ($1,$2,$3,$4)",
            [$this->member_id, $_POST["title"], $_POST["description"], $_POST["year"]]
        );
        echo json_encode(["success"=>true]);
    }

    public function deleteProject() {
        pg_query_params($this->conn,
            "DELETE FROM member_research WHERE id=$1 AND member_id=$2",
            [$_POST["id"], $this->member_id]
        );
        echo json_encode(["success"=>true]);
    }

    /* ---------------- PUBLICATIONS ---------------- */

    public function getPublications() {
        $res = pg_query_params($this->conn,
            "SELECT * FROM member_publications WHERE member_id=$1 ORDER BY year DESC",
            [$this->member_id]
        );
        echo json_encode([ "publications" => pg_fetch_all($res) ?: [] ]);
    }

    public function addPublication() {
        pg_query_params($this->conn,
            "INSERT INTO member_publications (member_id,title,publisher,year,link)
             VALUES ($1,$2,$3,$4,$5)",
            [$this->member_id, $_POST["title"], $_POST["publisher"], $_POST["year"], $_POST["link"]]
        );
        echo json_encode(["success"=>true]);
    }

    public function deletePublication() {
        pg_query_params($this->conn,
            "DELETE FROM member_publications WHERE id=$1 AND member_id=$2",
            [$_POST["id"], $this->member_id]
        );
        echo json_encode(["success"=>true]);
    }

    /* ---------------- PPM ---------------- */

    public function getPPM() {
        $res = pg_query_params($this->conn,
            "SELECT * FROM member_ppm WHERE member_id=$1 ORDER BY year DESC",
            [$this->member_id]
        );
        echo json_encode([ "ppm" => pg_fetch_all($res) ?: [] ]);
    }

    public function addPPM() {
        pg_query_params($this->conn,
            "INSERT INTO member_ppm (member_id,title,year,description)
             VALUES ($1,$2,$3,$4)",
            [$this->member_id, $_POST["title"], $_POST["year"], $_POST["description"]]
        );
        echo json_encode(["success"=>true]);
    }

    public function deletePPM() {
        pg_query_params($this->conn,
            "DELETE FROM member_ppm WHERE id=$1 AND member_id=$2",
            [$_POST["id"], $this->member_id]
        );
        echo json_encode(["success"=>true]);
    }

    /* ---------------- BACKGROUNDS ---------------- */

    public function getBackgrounds() {
        $res = pg_query_params($this->conn,
            "SELECT * FROM member_backgrounds WHERE member_id=$1 ORDER BY year DESC",
            [$this->member_id]
        );
        echo json_encode([ "backgrounds" => pg_fetch_all($res) ?: [] ]);
    }

    public function addBackground() {
        pg_query_params($this->conn,
            "INSERT INTO member_backgrounds (member_id,institute,academic_title,year,degree)
             VALUES ($1,$2,$3,$4,$5)",
            [$this->member_id, $_POST["institute"], $_POST["academic_title"], $_POST["year"], $_POST["degree"]]
        );
        echo json_encode(["success"=>true]);
    }

    public function deleteBackground() {
        pg_query_params($this->conn,
            "DELETE FROM member_backgrounds WHERE id=$1 AND member_id=$2",
            [$_POST["id"], $this->member_id]
        );
        echo json_encode(["success"=>true]);
    }

    /* ---------------- IPS ---------------- */

    public function getIPS() {
        $res = pg_query_params($this->conn,
            "SELECT * FROM member_ips WHERE member_id=$1 ORDER BY year DESC",
            [$this->member_id]
        );
        echo json_encode([ "ips" => pg_fetch_all($res) ?: [] ]);
    }

    public function addIPS() {
        pg_query_params($this->conn,
            "INSERT INTO member_ips (member_id,title,year,registration_number)
             VALUES ($1,$2,$3,$4)",
            [$this->member_id, $_POST["title"], $_POST["year"], $_POST["reg_number"]]
        );
        echo json_encode(["success"=>true]);
    }

    public function deleteIPS() {
        pg_query_params($this->conn,
            "DELETE FROM member_ips WHERE id=$1 AND member_id=$2",
            [$_POST["id"], $this->member_id]
        );
        echo json_encode(["success"=>true]);
    }

    /* ---------------- ACTIVITIES ---------------- */

    public function getActivities() {
        $res = pg_query_params($this->conn,
            "SELECT * FROM member_activities WHERE member_id=$1 ORDER BY year DESC",
            [$this->member_id]
        );
        echo json_encode([ "activities" => pg_fetch_all($res) ?: [] ]);
    }

    public function addActivity() {
        pg_query_params($this->conn,
            "INSERT INTO member_activities (member_id,title,year,location)
             VALUES ($1,$2,$3,$4)",
            [$this->member_id, $_POST["title"], $_POST["year"], $_POST["location"]]
        );
        echo json_encode(["success"=>true]);
    }

    public function deleteActivity() {
        pg_query_params($this->conn,
            "DELETE FROM member_activities WHERE id=$1 AND member_id=$2",
            [$_POST["id"], $this->member_id]
        );
        echo json_encode(["success"=>true]);
    }
}

/* AUTO EXECUTE */
$controller = new MemberResearchController();
$controller->route($_GET["action"] ?? "");
