<?php
include '../config/db_connection.php';
include '../utils/filter.php';

$username = $password = $password_confirm = $email = "";
$username_err = $password_err = $email_err = $password_confirm_err = $generic_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_text($_POST['username']);
    if (empty($username) || !preg_match("/^[a-zA-Z\s]+$/", $username)) {
        $username_err = "Username must contain only letters.";
    }

    $email = filter_text($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "The email is not valid.";
    }

    $password = filter_text($_POST['password']); // i do not care about how strong your password is... for now
    if (strlen($password) < 8) {
        $password_err = "Password must be at least 8 characters long.";
    }

    $password_confirm = filter_text($_POST['password_confirm']);
    if ($password !== $password_confirm) {
        $password_confirm_err = "Passwords do not match.";
    }

    // check if any error occoured
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($password_confirm_err)) {
        try {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $db_conn->prepare("INSERT INTO users (username, email, hashed_password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                header('Location: login.php');
                exit;
            } else {
                if ($db_conn->errno === 1062) { // error number for duplicate entry
                    $email_err = "A user already exists with this email.";
                } else {
                    $generic_error = "Error during registration: " . $db_conn->error;
                }
            }
            $stmt->close();
        } catch (Exception $ex) {
            $generic_error = "An unexpected error occurred: " . $ex->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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
                <a href="login.php" class="btn btn-outline-light btn-register">Login</a> <!-- here i use btn-register for the color, even if it is for the login -->
            <?php else: ?>
                <a href="index.php" class="btn btn-outline-light btn-login">Home</a>
                <a href="chat.php" class="btn btn-outline-primary btn-register">Chat</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="register-container">
    <div class="register-card">
        <h2 class="text-center mb-4">Register to <span style="color: #00ffcc;">Whisper</span></h2>
        
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" require>
                <span class="error"><?= $username_err ?></span>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" require> <!-- email might not be required -->
                <span class="error"><?= $email_err ?></span>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" require>
                <span class="error"><?= $password_err ?></span>
            </div>

            <div class="mb-3">
                <label for="password_confirm" class="form-label">Confirm password</label>
                <input type="password" id="password_confirm" name="password_confirm" class="form-control" require>
                <span class="error"><?= $password_confirm_err ?></span>
            </div>

            <button type="submit" class="btn btn-form-register">Register</button>
        </form>
        <span class="<?= isset($generic_error) ? 'error' : ''?>"><?= $generic_error ?></span>
    </div>
</div>

<?php include 'partials/footer.php' ?>
</body>
</html>