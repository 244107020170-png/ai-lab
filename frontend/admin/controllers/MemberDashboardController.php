<?php

require_once __DIR__ . "/../Database.php";

class MemberDashboardController
{
    public function api()
    {
        header("Content-Type: application/json");

        if (!isset($_SESSION["user_id"])) {
            echo json_encode(["error" => "Not logged in"]);
            exit;
        }

        $db = new Database();
        $conn = $db->getConnection();

        $user_id = $_SESSION["user_id"];

        // =====================
        // MEMBER DATA
        // =====================
        $member_sql = "
            SELECT id, full_name, photo, expertise, role
            FROM members
            WHERE user_id = $1
        ";
        $member_res = pg_query_params($conn, $member_sql, [$user_id]);
        $member = pg_fetch_assoc($member_res);

        if (!$member) {
            echo json_encode(["error" => "Member profile not found"]);
            exit;
        }

        $member_id = $member["id"];

        // =====================
        // AVATAR URL (INI TEMPAT YANG BENAR)
        // =====================
        $member["photo_url"] = !empty($member["photo"])
            ? "/.pbl/frontend/img/profile-photos/" . $member["photo"]
            : "/.pbl/views/img/memberavatar.png";

        // =====================
        // RESEARCH COUNT
        // =====================
        $research_sql = "SELECT COUNT(*) AS total FROM member_research WHERE member_id = $1";
        $research = pg_fetch_assoc(pg_query_params($conn, $research_sql, [$member_id]));

        // =====================
        // PUBLICATIONS COUNT
        // =====================
        $pub_sql = "SELECT COUNT(*) AS total FROM member_publications WHERE member_id = $1";
        $pub = pg_fetch_assoc(pg_query_params($conn, $pub_sql, [$member_id]));

        // =====================
        // RECENT ACTIVITIES
        // =====================
        $act_sql = "
            SELECT title, year, location, created_at
            FROM member_activities
            WHERE member_id = $1
            ORDER BY created_at DESC
            LIMIT 5
        ";
        $act_res = pg_query_params($conn, $act_sql, [$member_id]);

        $activities = [];
        while ($row = pg_fetch_assoc($act_res)) {
            $activities[] = $row;
        }

        // =====================
        // PROFILE COMPLETION
        // =====================
        $filled = 0;
        $total_fields = 5;

        if ($member["photo"]) $filled++;
        if ($member["expertise"]) $filled++;
        if ($research["total"] > 0) $filled++;
        if ($pub["total"] > 0) $filled++;
        if (!empty($activities)) $filled++;

        $completion = intval(($filled / $total_fields) * 100);

        // =====================
        // FINAL JSON RESPONSE
        // =====================
        echo json_encode([
            "member" => $member,
            "research_total" => (int)$research["total"],
            "publications_total" => (int)$pub["total"],
            "profile_completion" => $completion,
            "recent_activities" => $activities
        ]);
    }
}
