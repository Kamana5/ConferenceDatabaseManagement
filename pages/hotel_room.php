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
    <title>Hotel Room Assignments</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="title-box">
    <h1>Students in a Hotel Room</h1>
</div>

<div class="title-box">
    <form method="post" action="">
        <label for="room">Choose a room:</label>
        <select name="room_num" id="room">
            <option value="">-- Select --</option>
            <?php
            $stmt = $pdo->query("SELECT DISTINCT num FROM Room");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['num']}'>{$row['num']}</option>";
            }
            ?>
        </select>
        <button type="submit">View Students</button>
    </form>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["room_num"])) {
    $room_num = $_POST["room_num"];

    $stmt = $pdo->prepare("SELECT fname, lname FROM Student WHERE room_num = ?");
    $stmt->execute([$room_num]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<div class='title-box'>";
    echo "<h2 style='color: white;'>Students in Room $room_num</h2>";

    if (count($students) > 0) {
        foreach ($students as $s) {
            echo "<p style='color: black; font-size: 1.1em; margin: 6px 0;'>" . 
                htmlspecialchars($s['fname']) . " " . htmlspecialchars($s['lname']) . 
                "</p>";
        }
    } else {
        echo "<p style='color: black;'>No students are assigned to this room.</p>";
    }

    echo "</div>";
}
?>


<div class="title-box2">
    <h2><a href="../conference.php">⬅ Back to Home</a></h2>
</div>

</body>
</html>
