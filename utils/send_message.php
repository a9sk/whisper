<?php
session_start();
include '../config/db_connection.php';

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php');
    exit();
}

// check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/chat.php');
    exit();
}

// get form data
$sender_id = $_SESSION['user_id'];
$receiver_username = trim($_POST['receiver']);
$content = trim($_POST['message']);

// validate input
if (empty($receiver_username) || empty($content)) {
    $_SESSION['error'] = 'Please fill in all fields';
    header('Location: ../public/chat.php');
    exit();
}

try {
    // get receiver id
    $stmt = $db_conn->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->bind_param('s', $receiver_username);
    $stmt->execute();
    $result = $stmt->get_result();
    $receiver = $result->fetch_assoc();

    if (!$receiver) {
        $_SESSION['error'] = 'User not found';
        header('Location: ../public/chat.php');
        exit();
    }

    // get current date and time
    $current_date = date('Y-m-d');
    $current_time = date('H:i:s');
    $is_read = 0;

    // insert message
    $stmt = $db_conn->prepare('INSERT INTO messages (sender, receiver, encrypted_content, sent_at_day, sent_at_hour, is_read) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('iisssi', $sender_id, $receiver['id'], $content, $current_date, $current_time, $is_read);
    $stmt->execute();

    $_SESSION['success'] = 'Message sent successfully';
} catch (Exception $e) {
    $_SESSION['error'] = 'Failed to send message';
}

header('Location: ../public/chat.php');
exit(); 