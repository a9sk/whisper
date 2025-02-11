<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whisper Chat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Whisper</a>
        <div class="d-flex align-items-center ms-auto">
            <a href="https://github.com/a9sk" class="github-link me-3" target="_blank">
                <i class="fab fa-github"></i>
            </a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="login.php" class="btn btn-outline-light btn-login">Login</a>
                <a href="register.php" class="btn btn-outline-light btn-register">Register</a>
            <?php else: ?>
                <a href="chat.php" class="btn btn-outline-primary">Chat</a>
                <a href="logout.php" class="btn btn-outline-danger">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<section class="hero">
    <div class="glassmorphism">
        <h1>Welcome to <span style="color: #00ffcc;">Whisper</span></h1>
        <p class="lead">A secure, private, and encrypted messaging platform.</p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="login.php" class="btn btn-success mt-3">Start Chatting Securely</a>
        <?php else: ?>
            <a href="chat.php" class="btn btn-primary mt-3">Start Chatting Securely</a>
        <?php endif; ?>
    </div>
</section>

<section class="features container text-center">
    <h2 class="mb-5">Why Choose Whisper?</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="feature-card">
                <i class="fas fa-lock feature-icon"></i>
                <h4>End-to-End Encryption</h4>
                <p>Your messages are encrypted and can only be read by the receiver.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <i class="fas fa-user-secret feature-icon"></i>
                <h4>Privacy First</h4>
                <p>No message storage after reading. Your data stays yours.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <i class="fas fa-fire-alt feature-icon"></i>
                <h4>Self-Destructing Messages</h4>
                <p>Messages disappear after reading to ensure complete privacy.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
