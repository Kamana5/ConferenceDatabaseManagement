<?php
$host = 'localhost';
$dbname = 'conferenceDB';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['company_name']) && !empty($_POST['level'])) {
    $companyName = $_POST['company_name'];
    $level = $_POST['level'];

    $stmt = $pdo->prepare("INSERT INTO Company (Name, Level, EmailSent) VALUES (?, ?, 0)");
    if ($stmt->execute([$companyName, $level])) {
        $message = "<p style='color:green;'>Company '$companyName' added successfully!</p>";
    } else {
        $message = "<p style='color:red;'> Failed to add company.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Sponsoring Company</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="title-box">
    <h1>Add a New Sponsoring Company</h1>
</div>

<div class="title-box">
    <form method="post">
        <label for="company_name">Company Name:</label>
        <input type="text" name="company_name" id="company_name" required><br><br>

        <label for="level">Sponsorship Level:</label>
        <select name="level" id="level" required>
            <option value="">--Select Level--</option>
            <option value="4">Platinum ($10,000)</option>
            <option value="3">Gold ($5,000)</option>
            <option value="2">Silver ($3,000)</option>
            <option value="1">Bronze ($1,000)</option>
        </select><br><br>

        <button type="submit">Add Company</button>
    </form>
    <?= $message ?>
</div>

<h2><a href="../conference.php">⬅ Back to Home</a></h2>

</body>
</html>
