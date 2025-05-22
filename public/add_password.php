<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

require_once '../config/Database.php';
require_once '../classes/PasswordEncryption.php';
require_once '../classes/PasswordGenerator.php';

$db = new Database();
$conn = $db->connect();
$user = $_SESSION['user'];

// Here is the initialize encryption with user's key
$encryption = new PasswordEncryption($user['username']); // In real apps, use a secret password/key

$generatedPassword = '';
$message = '';

// Here, handle password generation 
if (isset($_POST['generate'])) {
    $length = (int)$_POST['length'];
    $uppercase = (int)$_POST['uppercase'];
    $lowercase = (int)$_POST['lowercase'];
    $numbers = (int)$_POST['numbers'];
    $special = (int)$_POST['special'];

    try {
        $generatedPassword = PasswordGenerator::generate($length, $uppercase, $lowercase, $numbers, $special);
    } catch (Exception $e) {
        $message = "❌ Error: " . $e->getMessage();
    }
}

// Handle password save are given below
if (isset($_POST['save'])) {
    $site = $_POST['site'];
    $password = $_POST['password'];
    $encryptedPassword = $encryption->encrypt($password);

    $stmt = $conn->prepare("INSERT INTO passwords (user_id, site_name, encrypted_password, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$user['id'], $site, $encryptedPassword]);

    $message = "✅ Password saved successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Password - Password Manager</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; padding: 30px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        input, button { width: 100%; padding: 10px; margin-top: 10px; }
        .message { color: green; margin-top: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Add & Generate Password</h2>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <h4>Generate Password</h4>
        <input type="number" name="length" placeholder="Total Length" required>
        <input type="number" name="uppercase" placeholder="Uppercase Letters" required>
        <input type="number" name="lowercase" placeholder="Lowercase Letters" required>
        <input type="number" name="numbers" placeholder="Numbers" required>
        <input type="number" name="special" placeholder="Special Characters" required>
        <button type="submit" name="generate">Generate Password</button>
    </form>

    <?php if ($generatedPassword): ?>
        <form method="POST">
            <h4>Save Generated Password</h4>
            <input type="text" name="site" placeholder="Site or App Name" required>
            <input type="text" name="password" value="<?= htmlspecialchars($generatedPassword) ?>" required>
            <button type="submit" name="save">Save Password</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
