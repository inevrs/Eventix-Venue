<?php
mysqli_report(MYSQLI_REPORT_OFF);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "eventix_db";

$connect = mysqli_connect($host, $user, $pass, $db);

if (!$connect) {
    error_log("Database connection failed: " . mysqli_connect_error());
    http_response_code(500);
    die("Database unavailable.");
}

if (!mysqli_set_charset($connect, 'utf8mb4')) {
    error_log("Failed to set database charset: " . mysqli_error($connect));
}
?>