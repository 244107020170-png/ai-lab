<?php
require_once "Database.php";

try {
    $db = Database::getConnection();
    echo "<h1>Connection OK</h1>";

    $result = pg_query($db, "SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
    while ($row = pg_fetch_assoc($result)) {
        echo $row['table_name'] . "<br>";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
