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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['company_name'])) {
    $company = $_POST['company_name'];

    $delSponsors = $pdo->prepare("DELETE FROM Sponsor WHERE CompanyName = ?");
    $delSponsors->execute([$company]);

    $delCompany = $pdo->prepare("DELETE FROM Company WHERE Name = ?");
    $delCompany->execute([$company]);

    $message = "<p style='color:green;'>Company '$company' and its associated sponsors have been deleted.</p>";
}
$companies = $pdo->query("SELECT Name FROM Company")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Sponsoring Company</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="title-box">
    <h1>Delete Sponsoring Company</h1>
</div>

<div class="title-box">
    <form method="post">
        <label for="company_name">Select Company to Delete:</label>
        <select name="company_name" id="company_name" required>
            <option value="">--Select--</option>
            <?php foreach ($companies as $c): ?>
                <option value="<?= htmlspecialchars($c['Name']) ?>"><?= htmlspecialchars($c['Name']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit" onclick="return confirm('Are you sure you want to delete this company and its sponsors?')">
            Delete Company
        </button>
    </form>
    <?= $message ?>
</div>

<h2><a href="../conference.php">⬅ Back to Home</a></h2>
</body>
</html>
