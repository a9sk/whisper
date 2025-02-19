<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    echo "Implement search panel";
} else {
    header('Location: index.php');
    exit();
}
?>
