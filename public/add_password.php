<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/database.php';
require_once '../classes/PasswordManager.php';
require_once '../classes/PasswordGenerator.php'; // Ensure this line is included

$userId = $_SESSION['user']['id'];
$key = $_SESSION['aes_key'];
$passwordManager = new PasswordManager($pdo, $key);

$generatedPassword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siteName = $_POST['site_name'];
    $customPassword = $_POST['custom_password'] ?? '';
    $generatedPassword = $_POST['generated_password'] ?? '';

    if (isset($_POST['generate'])) {
        $length = (int) $_POST['length'];
        $lower = (int) $_POST['lowercase'];
        $upper = (int) $_POST['uppercase'];
        $nums = (int) $_POST['numbers'];
        $special = (int) $_POST['specials'];

        $generatedPassword = PasswordGenerator::generate($length, $lower, $upper, $nums, $special);
    }

    if (isset($_POST['save'])) {
        $passwordToSave = !empty($customPassword) ? $customPassword : $generatedPassword;
        $passwordManager->savePassword($userId, $siteName, $passwordToSave);
        header("Location: dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New Password</title>
</head>
<body>
    <h2>Add New Password</h2>
    <form method="post">
        <label>Site Name:</label><br>
        <input type="text" name="site_name" required><br><br>

        <h3>Generate Password</h3>
        <label>Length:</label>
        <input type="number" name="length" value="12" required><br>
        <label>Lowercase:</label>
        <input type="number" name="lowercase" value="2"><br>
        <label>Uppercase:</label>
        <input type="number" name="uppercase" value="2"><br>
        <label>Numbers:</label>
        <input type="number" name="numbers" value="2"><br>
        <label>Specials:</label>
        <input type="number" name="specials" value="2"><br>
        <button type="submit" name="generate">Generate</button><br><br>

        <?php if ($generatedPassword): ?>
            <strong>Generated Password:</strong>
            <input type="text" name="generated_password" value="<?= htmlspecialchars($generatedPassword) ?>" readonly><br><br>
        <?php endif; ?>

        <h3>Or Enter Your Own Password</h3>
        <input type="text" name="custom_password"><br><br>

        <button type="submit" name="save">Save Password</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>


