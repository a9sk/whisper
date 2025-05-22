<?php
// get database credentials from environment variables or use defaults
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASSWORD') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'whisper');

// create database connection
$db_conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// check connection
if (!$db_conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
