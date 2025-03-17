<?php
session_start();

include '../config/db_connection.php';
$generic_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['username'])) {

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Whisper</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #fff;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            box-shadow: none;
        }

        .register-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
        }

        .btn-form-register {
            background-color: #00ffcc;
            color: #000;
            font-weight: bold;
            width: 100%;
            padding: 10px;
            border-radius: 20px;
            transition: 0.3s;
        }

        .btn-form-register:hover {
            background-color: #ff007f;
            border-color: #ff007f;
            color: #000;
        }

        .hero {
            height: 75vh;
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
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="login.php" class="btn btn-outline-light btn-login">Login</a>
                <a href="register.php" class="btn btn-outline-light btn-register">Register</a>
            <?php else: ?>
                <a href="chat.php" class="btn btn-outline-light btn-login">Chat</a>
                <a href="../utils/logout.php" class="btn btn-outline-danger btn-register">Logout</a>
            <?php endif; ?>
            <a href="edit_profile.php" class="btn btn-outline-light btn-register"><i class="fa fa-user-circle-o" aria-hidden="true"></i></a>
        </div>
    </div>
</nav>

<section class="hero">
    <div class="glassmorphism">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <h1>You are <span style="color: #ff6666;">not logged</span> in</h1>
            <p class="lead">Make sure you register and login before you can start to use the service.</p>

            <a href="login.php" class="btn btn-success mt-3">Start Chatting Securely</a>
        <?php else: ?>
            <h1>Welcome <span style="color: #00ffcc;"><?=$_SESSION['user_username']?></span></h1>
            
            <p class="lead">Modify your information or delete your profile with the following form.</p>

            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?= $_SESSION['user_username'] ?>" required>
                    <span class="error"><?= $generic_error ?></span>
                </div>


                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" id="email" name="email" class="form-control" value="<?= isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : '' ?>" required>
                    <span class="error"><?= $generic_error ?></span>
                </div>
                <hr>
                <button type="submit" class="btn btn-form-register">Modify</button>
            </form>

            <hr>
            
            <a href="change_password.php" class="btn btn-form-register">Change Password</a>
        <?php endif; ?>
    </div>
</section>

<?php include 'partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
