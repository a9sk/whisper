<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Whisper</title>
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
    <h1 class="text-center mb-4">Privacy Policy</h1>
    <p class="lead text-center">We value your privacy and are committed to protecting your personal data.</p>

    <br><br><br>
    <p>Effective Date: 16/02/2025</p>

    <h4>1. Introduction</h4>
    <p>At Whisper, we value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, and secure your data.</p>

    <h4>2. Information We Collect</h4>
    <p>We collect only the minimum information required to provide our services:</p>
    <ul>
        <li><strong>Account Information:</strong> Username, hashed password, and public key.</li>
        <li><strong>Private Key:</strong> Encrypted private key stored securely.</li>
        <li><strong>Messages:</strong> Encrypted messages stored temporarily until read.</li>
        <li><strong>Logs:</strong> Metadata like timestamps for debugging purposes.</li>
    </ul>

    <h4>3. How We Use Your Information</h4>
    <p>We use your data to enable secure communication and improve our services. Whisper will never sell or share your information with third parties.</p>

    <h4>4. Security</h4>
    <p>Your data is secured using RSA-4096 and AES-256-GCM encryption, and all transmissions occur over HTTPS.</p>

    <h4>5. Contact Us</h4>
    <p>If you have any questions, reach out to us at: 920a9sk42f76c765@proton.me</p>
    <!-- this is my personal email, i should change it if i'll ever get a domain -->
</div>

<?php include 'partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
