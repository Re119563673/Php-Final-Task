<?php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/User.php';

$db = new Database();
$conn = $db->connect();
$user = new User($conn);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($_POST['login'])) {
        if ($user->login($username, $password)) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    } elseif (isset($_POST['register'])) {
        if ($user->register($username, $password)) {
            $success = 'Registration successful. You can now log in.';
        } else {
            $error = 'Registration failed. Username may already exist.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Password Manager - Login/Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h1>Password Manager</h1>
        <?php if ($error): ?>
            <p class="error">❌ Error: <?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success">✅ <?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" required>
            
            <label>Password</label>
            <input type="password" name="password" required>
            
            <button type="submit" name="login">🔐 Login</button>
            <button type="submit" name="register">📝 Register</button>
        </form>
    </div>
</body>
</html>
