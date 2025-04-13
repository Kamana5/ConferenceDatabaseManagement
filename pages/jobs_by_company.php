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
    <title>Jobs by Company</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="title-box">
        <h1>View Jobs by Company</h1>
    </div>

    <div class="title-box2">
        <form method="post" action="">
            <label for="company">Select a Company:</label>
            <select name="company_name" id="company">
                <option value="">-- Select --</option>
                <?php
                $stmt = $pdo->query("SELECT DISTINCT Name FROM Company");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$row['Name']}'>{$row['Name']}</option>";
                }
                ?>
            </select>
            <button type="submit">Show Jobs</button>
        </form>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["company_name"])) {
        $company = $_POST["company_name"];
        $stmt = $pdo->prepare("SELECT Title, Location, Salary FROM JobAd WHERE CompanyName = ?");
        $stmt->execute([$company]);
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<div class='title-box'>";
        echo "<h2 style='color: white;'>Job Ads from {$company}</h2>";
        if (count($jobs) > 0) {
            echo "<table border='1' style='margin: 0 auto; background-color: white; color: black;'>";
            echo "<tr><th>Title</th><th>Location</th><th>Salary ($/yr)</th></tr>";
            foreach ($jobs as $job) {
                echo "<tr><td>{$job['Title']}</td><td>{$job['Location']}</td><td>{$job['Salary']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No job ads found for {$company}.</p>";
        }
        echo "</div>";
    }
    ?>

    <h2><a href="../conference.php">⬅ Back to Home</a></h2>

</body>
</html>
