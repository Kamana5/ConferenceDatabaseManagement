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

$studentFees = $pdo->query("SELECT COUNT(*) FROM Student")->fetchColumn() * 50;
$proFees = $pdo->query("SELECT COUNT(*) FROM Professional")->fetchColumn() * 100;

$levels = [
    4 => ['name' => 'Platinum', 'amount' => 10000],
    3 => ['name' => 'Gold', 'amount' => 5000],
    2 => ['name' => 'Silver', 'amount' => 3000],
    1 => ['name' => 'Bronze', 'amount' => 1000]
];

$totalSponsorAmount = 0;
$sponsorBreakdown = [];

foreach ($levels as $levelNum => $info) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Company WHERE Level = ?");
    $stmt->execute([$levelNum]);
    $count = $stmt->fetchColumn();
    $sponsorBreakdown[$info['name']] = $count * $info['amount'];
    $totalSponsorAmount += $count * $info['amount'];
}

$totalIntake = $studentFees + $proFees + $totalSponsorAmount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Conference Intake</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="title-box">
    <h1>Conference Income Summary</h1>
</div>

<div class="title-box">
    <h2 style='color: white;'>Registration Income</h2>
    <p>Students: $<?= $studentFees ?></p>
    <p>Professionals: $<?= $proFees ?></p>
    <p><strong>Total Registration: $<?= $studentFees + $proFees ?></strong></p>
</div>

<div class="title-box">
    <h2 style='color: white;'>Sponsorship Income</h2>
        <?php foreach ($sponsorBreakdown as $name => $amount): ?>
            <p><?= $name ?> Sponsors: $<?= $amount ?></p>
        <?php endforeach; ?>
    <p><strong>Total Sponsorship: $<?= $totalSponsorAmount ?></strong></p>
</div>

<div class="title-box">
    <h2 style='color: white;'>Total Conference Intake</h2>
    <p><strong>$<?= $totalIntake ?></strong></p>
</div>

<h2><a href="../conference.php">⬅ Back to Home</a></h2>
</body>
</html>
