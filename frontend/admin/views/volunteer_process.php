<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

require_once __DIR__ . '/models/Volunteer.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $areas = $_POST["area"] ?? [];
    $areasJson = json_encode($areas);

    $data = [
        "full_name"     => $_POST["fullname"],
        "nickname"      => $_POST["nickname"],
        "study_program" => $_POST["dept"],
        "semester"      => $_POST["semester"],
        "email"         => $_POST["email"],
        "phone"         => $_POST["phone"],
        "areas"         => $areasJson,
        "skills"        => $_POST["skills"],
        "motivation"    => $_POST["motivation"],
        "availability"  => $_POST["availability"],
        "status"        => "Pending",
    ];

    $vol = new Volunteer();
    $vol->insert($data);

    echo json_encode(["success" => true]);
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid request"]);
