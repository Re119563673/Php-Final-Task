<?php
session_start();
require_once '../config/database.php';
require_once '../classes/User.php';

$user = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $loggedUser = $user->login($username, $password);
    if ($loggedUser) {
        $_SESSION['user'] = $loggedUser;
        $_SESSION['aes_key'] = $user->getKey($loggedUser, $password);
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Login failed. Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <p><a href="index.php">Back to Home</a></p>
</body>
</html>