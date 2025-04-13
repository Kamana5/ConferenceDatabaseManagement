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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sponsors</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="title-box">
        <h1>Conference Sponsors</h1>
    </div>

    <?php
    $stmt = $pdo->query("SELECT Name, Level FROM Company");
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<div class='title-box2'>";
    if (count($companies) > 0) {
        echo "<table border='1' style='margin: 0 auto; background-color: white; color: black;'>";
        echo "<tr><th>Company Name</th><th>Sponsorship Level</th></tr>";
        foreach ($companies as $c) {
            $levelText = match ($c['Level']) {
                1 => "Platinum",
                2 => "Gold",
                3 => "Silver",
                4 => "Bronze",
                default => "Unknown"
            };
            echo "<tr><td>{$c['Name']}</td><td>{$levelText}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No sponsors found.</p>";
    }
    echo "</div>";
    ?>

    <h2><a href="../conference.php">⬅ Back to Home</a></h2>
</body>
</html>
