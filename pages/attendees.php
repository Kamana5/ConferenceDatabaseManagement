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
    <title>Conference Attendees</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="title-box">
        <h1>Conference Attendees</h1>
    </div>

    <?php
    // Students
    echo "<div class='title-box'>";
    echo "<h2 style='color: white;'>Students</h2>";
    $stmt = $pdo->query("SELECT fname, lname FROM Student");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($students) > 0) {
        foreach ($students as $s) {
            echo "<li>{$s['fname']} {$s['lname']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No students registered.</p>";
    }
    echo "</div>";

    // Professionals
    echo "<div class='title-box'>";
    echo "<h2 style='color: white;'>Professionals</h2>";
    $stmt = $pdo->query("SELECT fname, lname FROM Professional");
    $pros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($pros) > 0) {
        foreach ($pros as $p) {
            echo "<li>{$p['fname']} {$p['lname']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No professionals registered.</p>";
    }
    echo "</div>";

    // Sponsors
    echo "<div class='title-box'>";
    echo "<h2 style='color: white;'>Sponsors</h2>";
    $stmt = $pdo->query("SELECT fname, lname FROM Sponsor");
    $sponsors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($sponsors) > 0) {

        foreach ($sponsors as $s) {
            echo "<li>{$s['fname']} {$s['lname']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No sponsors registered.</p>";
    }
    echo "</div>";
    ?>

    <h2><a href="../conference.php">⬅ Back to Home</a></h2>

</body>
</html>
