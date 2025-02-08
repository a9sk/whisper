<?php
// credentials placeholder, replace with your own
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'whisper_db');

$db_conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$db_conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
