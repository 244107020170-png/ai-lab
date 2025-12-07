<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

require_once __DIR__ . '/models/LabPermit.php';

$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode(["success" => false, "message" => "No JSON received"]);
    exit;
}

$data = [
    "full_name" => $input["fullname"],
    "study_program" => $input["program"],
    "semester" => $input["semester"],
    "phone" => $input["phone"],
    "email" => $input["email"],
    "reason" => $input["reason"],
    "status" => "pending"
];

$permit = new LabPermit();

// --- INSERT DATA INTO DATABASE ---
$success = $permit->insert($data);

if ($success) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Database insert failed"]);
}
