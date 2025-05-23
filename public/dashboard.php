<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/database.php';
require_once '../classes/PasswordManager.php';

$userId = $_SESSION['user']['id'];
$key = $_SESSION['aes_key'];
$passwordManager = new PasswordManager($pdo, $key);
$passwords = $passwordManager->getPasswords($userId);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?></h2>
    <p><a href="add_password.php">Add New Password</a> | <a href="logout.php">Logout</a></p>

    <h3>Saved Passwords</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>Site</th>
            <th>Encrypted Password</th>
            <th>Created At</th>
        </tr>
        <?php foreach ($passwords as $pw): ?>
            <tr>
                <td><?= htmlspecialchars($pw['site_name']) ?></td>
                <td><?= htmlspecialchars($pw['password_encrypted']) ?></td>
                <td><?= htmlspecialchars($pw['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
