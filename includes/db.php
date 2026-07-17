<?php
require_once __DIR__ . '/env.php';
mysqli_report(MYSQLI_REPORT_OFF);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "eventix_db";
$port = 3306;

// Check if DigitalOcean App Platform DATABASE_URL is set
$db_url = getenv("DATABASE_URL");
if ($db_url) {
    $parsed_url = parse_url($db_url);
    if ($parsed_url) {
        $host = $parsed_url["host"] ?? $host;
        $user = $parsed_url["user"] ?? $user;
        $pass = $parsed_url["pass"] ?? $pass;
        $db   = isset($parsed_url["path"]) ? ltrim($parsed_url["path"], '/') : $db;
        $port = $parsed_url["port"] ?? $port;
    }
} else {
    // Check individual environment variables if DATABASE_URL is not set
    $host = getenv("DB_HOST") ?: $host;
    $user = getenv("DB_USER") ?: $user;
    $pass = getenv("DB_PASS") ?: $pass;
    $db   = getenv("DB_NAME") ?: $db;
    $port = getenv("DB_PORT") ?: $port;
}

$connect = mysqli_connect($host, $user, $pass, $db, $port);

if (!$connect) {
    error_log("Database connection failed: " . mysqli_connect_error());
    http_response_code(500);
    die("Database unavailable.");
}

if (!mysqli_set_charset($connect, 'utf8mb4')) {
    error_log("Failed to set database charset: " . mysqli_error($connect));
}
?>