<?php
session_start();
require_once '../config/db_connection.php';

// only admin shall enter
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// for deleting users
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $deleteUserId = intval($_POST['delete_user_id']);

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $deleteUserId]);

    $message = "User with ID $deleteUserId has been deleted successfully.";
}

// for updating users
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user_id'])) {
    $updateUserId = intval($_POST['update_user_id']);
    $newUsername = htmlspecialchars($_POST['username']);
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("UPDATE users SET username = :username, password = :password WHERE id = :id");
    $stmt->execute([
        'username' => $newUsername,
        'password' => $newPassword,
        'id' => $updateUserId
    ]);

    $message = "User with ID $updateUserId has been updated successfully.";
}

// to query search
$searchResults = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search_query'])) {
    $searchQuery = htmlspecialchars($_GET['search_query']);

    // search only by username (removed search by password hash)
    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE username LIKE :query");
    $stmt->execute(['query' => "%$searchQuery%"]);
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Whisper</a>
        <!-- no need to check if you are logged in here... -->
        <div class="d-flex align-items-center ms-auto">
            <a href="index.php" class="btn btn-outline-light me-2">Home</a>
            <a href="logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="text-center mb-4">Admin Panel</h1>
    <?php if (isset($message)): ?>
        <div class="alert alert-success text-center">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="GET" class="mb-5">
        <div class="input-group">
            <input type="text" name="search_query" class="form-control" placeholder="Search by username" value="<?= isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : '' ?>" required>
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <?php if (!empty($searchResults)): ?>
        <h2 class="mb-3">Search Results:</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($searchResults as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal<?= $user['id'] ?>">Edit</button>
                            <form method="POST" class="d-inline-block">
                                <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <!-- this might be brutal but i know no better ways -->
                    <div class="modal fade" id="updateModal<?= $user['id'] ?>" tabindex="-1" aria-labelledby="updateModalLabel<?= $user['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateModalLabel<?= $user['id'] ?>">Update User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="update_user_id" value="<?= $user['id'] ?>">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">New Username</label>
                                            <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">New Password</label>
                                            <input type="password" class="form-control" name="password" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif (isset($_GET['search_query'])): ?>
        <div class="alert alert-warning text-center">No results found for "<?= htmlspecialchars($_GET['search_query']) ?>".</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>