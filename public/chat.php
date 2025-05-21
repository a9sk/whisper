<?php
session_start();
include '../config/db_connection.php';

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// get user info
$user_id = $_SESSION['user_id'];
$stmt = $db_conn->prepare('SELECT username FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// get inbox messages
$stmt = $db_conn->prepare('
    SELECT m.*, 
           s.username as sender_username,
           r.username as receiver_username,
           COALESCE(m.is_read, 0) as is_read
    FROM messages m
    JOIN users s ON m.sender = s.id
    JOIN users r ON m.receiver = r.id
    WHERE m.receiver = ?
    ORDER BY m.sent_at_day DESC, m.sent_at_hour DESC
');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Whisper</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-content {
            flex: 1 0 auto;
        }
        .footer {
            flex-shrink: 0;
        }
        .chat-container {
            padding: 20px 0;
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
        }
        .card-header {
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px 15px 0 0 !important;
        }
        .card-header h5 {
            color: #00ffcc;
            font-weight: bold;
            margin: 0;
        }
        .card-header i {
            color: #00ffcc;
        }
        .list-group-item {
            background: transparent;
            border-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            transition: 0.3s;
        }
        .list-group-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .list-group-item h6 {
            color: #00ffcc;
        }
        .list-group-item p {
            color: #fff;
            white-space: pre-wrap;
        }
        .list-group-item small {
            color: rgba(255, 255, 255, 0.7);
        }
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #00ffcc;
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(0, 255, 204, 0.25);
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        .form-label {
            color: #fff;
        }
        .modal-content {
            background: rgba(15, 15, 15, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        .badge {
            background: #00ffcc;
            color: #000;
        }
        .text-muted {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        #modalContent {
            color: #fff;
            font-size: 1.1rem;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        #modalTime {
            color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Whisper</a>
        <div class="d-flex align-items-center ms-auto">
            <a href="https://github.com/a9sk" class="github-link me-3" target="_blank">
                <i class="fab fa-github"></i>
            </a>
            <a href="edit_profile.php" class="btn btn-outline-light btn-register me-2">
                <i class="fa fa-user-circle-o" aria-hidden="true"></i>
            </a>
            <a href="../utils/logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
    </div>
</nav>

<div class="main-content">
    <div class="container chat-container">
        <div class="row">
            <!-- Inbox Section -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-inbox me-2"></i>Inbox</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if (empty($messages)): ?>
                                <div class="list-group-item text-center text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>No messages yet</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($messages as $message): ?>
                                    <a href="#" class="list-group-item list-group-item-action message-item" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#messageModal"
                                       data-message-id="<?php echo $message['id']; ?>"
                                       data-sender="<?php echo htmlspecialchars($message['sender_username']); ?>"
                                       data-content="<?php echo htmlspecialchars($message['encrypted_content']); ?>"
                                       data-time="<?php echo $message['sent_at_day'] . ' ' . $message['sent_at_hour']; ?>">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($message['sender_username']); ?></h6>
                                            <small><?php echo date('M d, H:i', strtotime($message['sent_at_day'] . ' ' . $message['sent_at_hour'])); ?></small>
                                        </div>
                                        <p class="mb-1 text-truncate"><?php echo nl2br(htmlspecialchars($message['encrypted_content'])); ?></p>
                                        <?php if (!$message['is_read']): ?>
                                            <span class="badge">New</span>
                                        <?php endif; ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Send Message Section -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Send Message</h5>
                    </div>
                    <div class="card-body">
                        <form id="messageForm" action="../utils/send_message.php" method="POST">
                            <div class="mb-3">
                                <label for="receiver" class="form-label">To:</label>
                                <input type="text" class="form-control" id="receiver" name="receiver" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message:</label>
                                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message from <span id="modalSender" class="text-success"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="modalContent" class="mb-2"></p>
                <small class="text-muted" id="modalTime"></small>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// handle message modal
document.querySelectorAll('.message-item').forEach(item => {
    item.addEventListener('click', function() {
        const sender = this.dataset.sender;
        const content = this.dataset.content;
        const time = this.dataset.time;
        const messageId = this.dataset.messageId;

        document.getElementById('modalSender').textContent = sender;
        document.getElementById('modalContent').textContent = content;
        document.getElementById('modalTime').textContent = new Date(time).toLocaleString();

        // mark message as read
        fetch('../utils/mark_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `message_id=${messageId}`
        });
    });
});
</script>
</body>
</html> 