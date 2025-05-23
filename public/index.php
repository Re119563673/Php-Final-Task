<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP OOP Password Manager</title>
</head>
<body>
    <h2>Welcome to PHP OOP Password Manager</h2>
    <a href="signup.php">Register</a> | <a href="login.php">Login</a>
</body>
</html>
