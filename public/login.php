<?php
session_start(); // in the login page we start the session

include '../config/db_connection.php';

// $username_email_err = $password_err = 
$generic_error = "";

// i should also implement CAPTCHA to avoid spamming
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_email = trim($_POST['username_email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username_email) || empty($password)) {
        $generic_error = "Invalid username/email or password.";
    } else {

        $query = filter_var($username_email, FILTER_VALIDATE_EMAIL) ? "SELECT id, username, email, hashed_password FROM users WHERE email = ? LIMIT 1" : "SELECT id, username, email, hashed_password FROM users WHERE username = ? LIMIT 1";

        if ($stmt = mysqli_prepare($db_conn, $query)) {
            mysqli_stmt_bind_param($stmt, 's', $username_email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($user = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $user['hashed_password'])) {
                    
                    session_regenerate_id(); // to avoid session fixation attacks
                    // if the loging is successful we set the session values 
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_username'] = $user['username'];
                    $_SESSION['user_email'] = $user['email'];

                    header('Location: index.php');
                    exit;
                } else {
                    $generic_error = "Invalid username/email or password.";
                }
            } else {
                $generic_error = "Invalid username/email or password.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $generic_error = "An unexpected error occurred. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
                <a href="index.php" class="btn btn-outline-light btn-login">Home</a>
                <a href="register.php" class="btn btn-outline-light btn-register">Register</a>
            <?php else: ?>
                <a href="index.php" class="btn btn-outline-light btn-login">Home</a>
                <a href="chat.php" class="btn btn-outline-primary btn-register">Chat</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="register-container">
    <div class="register-card">
        <h2 class="text-center mb-4">Login to <span style="color: #00ffcc;">Whisper</span></h2>
        
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="mb-3">
                <label for="username_email" class="form-label">Username/Email</label>
                <input type="text" id="username_email" name="username_email" class="form-control" value="<?= isset($username_email) ? htmlspecialchars($username_email) : '' ?>">
                <span class="error"><?= $generic_error ?></span>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control">
                <span class="error"><?= $generic_error ?></span>
            </div>

            <button type="submit" class="btn btn-form-register">Login</button>
            
            <span>
            <a href="reset_password.php" class="btn error">I forgot the password</a> <!-- using css btn and error classes to style this... -->
            <a href="register.php" class="btn error">Register</a>
            </span>
        </form>
    </div>
</div>

<?php include 'partials/footer.php' ?>
</body>
</html>