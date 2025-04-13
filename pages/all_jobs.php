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
    <title>All Job Ads</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="title-box">
        <h1>All Available Job Ads</h1>
    </div>

    <?php
    $stmt = $pdo->query("SELECT CompanyName, Title, Location, Salary FROM JobAd ORDER BY CompanyName");
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<div class='title-box2'>";
    if (count($jobs) > 0) {
        echo "<table border='1' style='margin: 0 auto; background-color: white; color: black;'>";
        echo "<tr><th>Company</th><th>Title</th><th>Location</th><th>Salary ($/yr)</th></tr>";
        foreach ($jobs as $job) {
            echo "<tr>
                    <td>{$job['CompanyName']}</td>
                    <td>{$job['Title']}</td>
                    <td>{$job['Location']}</td>
                    <td>{$job['Salary']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No job ads available right now.</p>";
    }
    echo "</div>";
    ?>

    <h2><a href="../conference.php">⬅ Back to Home</a></h2>

</body>
</html>
