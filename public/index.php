<?php
session_start();

require_once '../config/Database.php';
require_once '../classes/User.php';

// Here is the DB connection
$db = new Database();
$conn = $db->connect();
$user = new User($conn);

// Handle form submission are given below 
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($action === 'register') {
        try {
            $userId = $user->register($username, $password);
            $message = "✅ Registered successfully. You can now log in.";
        } catch (Exception $e) {
            $message = "❌ Error: " . $e->getMessage();
        }
    }

    if ($action === 'login') {
        $login = $user->login($username, $password);
        if ($login) {
            $_SESSION['user'] = $login;
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "❌ Invalid credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login / Register - Password Manager</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; background: #f4f4f4; }
        .container { max-width: 400px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        h2 { text-align: center; }
        input, button { width: 100%; padding: 10px; margin: 10px 0; }
        .message { color: red; text-align: center; }
    </style>
</head>
<body>
<div class="container">
    <h2>Password Manager</h2>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" name="action" value="login">🔐 Login</button>
        <button type="submit" name="action" value="register">📝 Register</button>
    </form>
</div>
</body>
</html>