<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service | Whisper</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
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
                <a href="index.php" class="btn btn-outline-light btn-login">Home</a>
                <a href="login.php" class="btn btn-outline-light btn-register">Login</a>
            <?php else: ?>
                <a href="index.php" class="btn btn-outline-light btn-login">Home</a>
                <a href="chat.php" class="btn btn-outline-primary btn-register">Chat</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="text-center mb-4">Terms of Service</h1>
    <br><br><br>
    <p>Effective Date: 16/02/2025</p>

    <h4>1. Acceptance of Terms</h4>
    <p>By using Whisper, you agree to these Terms of Service. If you do not agree, you may not use the application.</p>

    <h4>2. User Responsibilities</h4>
    <ul>
        <li>Safeguard your account credentials and private key.</li>
        <li>Do not use the app for unlawful activities or share prohibited content.</li>
    </ul>

    <h4>3. Account Registration</h4>
    <p>You are responsible for securing your private key and password. Whisper cannot recover lost keys or passwords.</p>

    <h4>4. Prohibited Activities</h4>
    <p>You may not reverse-engineer the app, send spam, or engage in any activity that harms Whisper or its users.</p>

    <h4>5. Limitation of Liability</h4>
    <p>Whisper is provided "as-is." We are not liable for data loss, unauthorized access, or system errors.</p>

    <h4>6. Contact Us</h4>
    <p>If you have questions about these Terms, contact us at: 920a9sk42f76c765@proton.me</p>
</div>

<?php include 'partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
