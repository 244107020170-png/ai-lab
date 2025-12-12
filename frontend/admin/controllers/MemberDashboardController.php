<?php
header("Content-Type: application/json");
require_once "Database.php"; 

$db = new Database();
$conn = $db->getConnection();

//user login
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$user_id = $_SESSION["user_id"];

// Ambil member yang terkait user login
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

// Research count
$research_sql = "SELECT COUNT(*) AS total FROM member_research WHERE member_id = $1";
$research = pg_fetch_assoc(pg_query_params($conn, $research_sql, [$member_id]));

// Publications count
$pub_sql = "SELECT COUNT(*) AS total FROM member_publications WHERE member_id = $1";
$pub = pg_fetch_assoc(pg_query_params($conn, $pub_sql, [$member_id]));

// Recent activities
$act_sql = "
    SELECT title, year, location, created_at
    FROM member_activities
    WHERE member_id = $1
    ORDER BY created_at DESC";
$act_res = pg_query_params($conn, $act_sql, [$member_id]);

$activities = [];
while ($row = pg_fetch_assoc($act_res)) {
    $activities[] = $row;
}

// Profile completion (simple)
$filled = 0;
$total_fields = 5;

if ($member["photo"]) $filled++;
if ($member["expertise"]) $filled++;
if ($research["total"] > 0) $filled++;
if ($pub["total"] > 0) $filled++;
if (!empty($activities)) $filled++;

$completion = intval(($filled / $total_fields) * 100);

// Output JSON
echo json_encode([
    "member" => $member,
    "research_total" => $research["total"],
    "publications_total" => $pub["total"],
    "profile_completion" => $completion,
    "recent_activities" => $activities
]);
