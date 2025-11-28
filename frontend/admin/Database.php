<?php
class Database
{
    private static $conn = null;
    public static function getConnection()
    {
        if (self::$conn === null) {

            $host = "127.0.0.1";   // sama .env Laravel
            $port = "5432";
            $db = "ai_lab_db";     // ganti
            $user = trim("postgres");
            $password = "nasywa1010";  // ganti
            $connStr = "host=$host port=$port dbname=$db user=$user password=$password";

            self::$conn = pg_connect($connStr);
            if (!self::$conn) {
                die("Database connection failed.");
            }
        }
        return self::$conn;
    }
}
