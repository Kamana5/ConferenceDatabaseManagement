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
<html>
<head>
    <meta charset="UTF-8">
    <title>Conference Schedule</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="title-box">
    <h1>View Conference Schedule</h1>
</div>

<div class="title-box">
    <form method="post">
        <label for="day">Choose a date:</label>
        <input type="date" name="day" id="day" required>
        <button type="submit">View Schedule</button>
    </form>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["day"])) {
    $day = $_POST["day"];

    $stmt = $pdo->prepare("
        SELECT 
            Session.Stime,
            Session.Etime,
            Session.Location,
            CONCAT(Speaker.fname, ' ', Speaker.lname) AS SpeakerName
        FROM Session
        JOIN Speaker ON Session.SpeakerID = Speaker.ID
        WHERE Session.SessionDate = ?
        ORDER BY Session.Stime
    ");
    $stmt->execute([$day]);
    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<div class='title-box'>";
    if (count($sessions) > 0) {
        echo "<h2 style='color: white;'>Schedule for $day</h2>";
        echo "<table border='1' style='margin: 0 auto; background-color: white; color: black;'>";
        echo "<tr><th>Start Time</th><th>End Time</th><th>Location</th><th>Speaker</th></tr>";
        foreach ($sessions as $s) {
            echo "<tr>
                    <td>{$s['Stime']}</td>
                    <td>{$s['Etime']}</td>
                    <td>{$s['Location']}</td>
                    <td>{$s['SpeakerName']}</td>
                  </tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No sessions scheduled for this day.</p>";
    }
    echo "</div>";
}
?>

<div class="title-box2">
    <h2><a href="../conference.php">⬅ Back to Home</a></h2>
</div>

</body>
</html>
