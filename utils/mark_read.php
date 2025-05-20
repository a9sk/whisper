<?php
session_start();
include '../config/db_connection.php';

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit('Unauthorized');
}

// check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// get message id
$message_id = filter_input(INPUT_POST, 'message_id', FILTER_VALIDATE_INT);

if (!$message_id) {
    http_response_code(400);
    exit('Invalid message id');
}

try {
    // verify message belongs to user and mark as read
    $stmt = $db_conn->prepare('
        UPDATE messages 
        SET is_read = 1 
        WHERE id = ? AND receiver = ?
    ');
    $stmt->bind_param('ii', $message_id, $_SESSION['user_id']);
    $stmt->execute();
    
    http_response_code(200);
    exit('Message marked as read');
} catch (Exception $e) {
    http_response_code(500);
    exit('Server error');
} 