<?php
session_start();

// check if the user_id is set to avoid people from ending unexisting sessions
if (isset($_SESSION['user_id'])) {
    $_SESSION = [];
    session_destroy();

    // delete the php session cookie set when the session started
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
}

header("Location: ../public/index.php");
exit;
