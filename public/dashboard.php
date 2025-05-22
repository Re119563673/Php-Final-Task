<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

require_once '../config/Database.php';
require_once '../classes/PasswordEncryption.php';

$db = new Database();
$conn = $db->connect();

$user = $_SESSION['user'];
$encryption = new PasswordEncryption($_POST['plain_password'] ?? $user['username']); // Use username or prompt password for real case

// Here, Handle password save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['site'], $_POST['password'])) {
    $site = $_POST['site'];
    $plainPassword = $_POST['password'];
    $encryptedPassword = $encryption->encrypt($plainPassword);
    $stmt = $conn->prepare("INSERT INTO passwords (user_id, site_name, encrypted_password, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$user['id'], $site, $encryptedPassword]);
}

// Fetch saved passwords are given below
$stmt = $conn->prepare("SELECT * FROM passwords WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user['id']]);
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Password Manager</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f8f9fa; }
        .container { max-width: 700px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; text-align: left; }
        h2 { text-align: center; }
    </style>
</head>
<body>
<div class="container">
    <h2>Welcome, <?= htmlspecialchars($user['username']) ?>!</h2>
    <form method="POST">
        <input type="text" name="site" placeholder="Site or App Name" required>
        <input type="text" name="password" placeholder="Password to Encrypt" required>
        <button type="submit">Save Password</button>
    </form>

    <h3>Your Saved Passwords</h3>
    <table>
        <thead>
            <tr>
                <th>Site</th>
                <th>Encrypted Password</th>
                <th>Date Saved</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($entries as $entry): ?>
            <tr>
                <td><?= htmlspecialchars($entry['site_name']) ?></td>
                <td><?= htmlspecialchars($entry['encrypted_password']) ?></td>
                <td><?= $entry['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>