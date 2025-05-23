<?php
session_start();
require_once '../config/database.php';
require_once '../classes/User.php';

$user = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user->register($username, $password)) {
        echo "User registered successfully. <a href='login.php'>Login here</a>.";
    } else {
        echo "Username already exists. Try a different one.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
</head>
<body>
    <h2>Register</h2>
    <form method="post">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Register</button>
    </form>
    <p><a href="index.php">Back to Home</a></p>
</body>
</html>
