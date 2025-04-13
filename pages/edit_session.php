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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldLocation = $_POST["old_location"];
    $oldDate = $_POST["old_date"];
    $oldStart = $_POST["old_start"];
    $oldEnd = $_POST["old_end"];
    $oldSpeaker = $_POST["old_speaker"];

    $newLocation = $_POST["location"];
    $newDate = $_POST["session_date"];
    $newStart = $_POST["stime"];
    $newEnd = $_POST["etime"];

    $stmt = $pdo->prepare("UPDATE Session 
        SET Location = ?, SessionDate = ?, Stime = ?, Etime = ?
        WHERE Location = ? AND SessionDate = ? AND Stime = ? AND Etime = ? AND SpeakerID = ?");

    $stmt->execute([
        $newLocation, $newDate, $newStart, $newEnd,
        $oldLocation, $oldDate, $oldStart, $oldEnd, $oldSpeaker
    ]);

    echo "<div class='title-box2' 
            style='background-color: #d4edda; color: #155724; border: 2px solid #c3e6cb; padding: 10px; margin: 15px auto; max-width: 500px; border-radius: 6px;'>
            <strong>Session updated successfully!</strong>
        </div>";
}


$sessions = $pdo->query("SELECT * FROM Session")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Session</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="title-box">
    <h1>🛠 Edit Session Details</h1>
</div>

<div class="title-box2">
    <form method="post">
        <label for="session">Choose a session:</label>
        <select name="session_key" id="session" onchange="fillForm(this.value)" required>
            <option value="">--Select a session--</option>
            <?php foreach ($sessions as $s): 
                $key = implode('|', [$s['Location'], $s['SessionDate'], $s['Stime'], $s['Etime'], $s['SpeakerID']]);
                $label = "{$s['Location']} on {$s['SessionDate']} from {$s['Stime']} to {$s['Etime']} (SpeakerID: {$s['SpeakerID']})";
            ?>
                <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
            <?php endforeach; ?>
        </select>

        <div id="edit-fields" style="margin-top: 20px; display:none;">
            <input type="hidden" name="old_location" id="old_location">
            <input type="hidden" name="old_date" id="old_date">
            <input type="hidden" name="old_start" id="old_start">
            <input type="hidden" name="old_end" id="old_end">
            <input type="hidden" name="old_speaker" id="old_speaker">

            <label for="location">New Location:</label>
            <input type="text" name="location" id="location" required><br>

            <label for="session_date">New Date:</label>
            <input type="date" name="session_date" id="session_date" required><br>

            <label for="stime">New Start Time:</label>
            <input type="time" name="stime" id="stime" required><br>

            <label for="etime">New End Time:</label>
            <input type="time" name="etime" id="etime" required><br>

            <button type="submit">Update Session</button>
        </div>
    </form>
</div>

<h2><a href="../conference.php">⬅ Back to Home</a></h2>

<script>
function fillForm(val) {
    if (!val) return;

    const [loc, date, start, end, speaker] = val.split('|');

    document.getElementById("old_location").value = loc;
    document.getElementById("old_date").value = date;
    document.getElementById("old_start").value = start;
    document.getElementById("old_end").value = end;
    document.getElementById("old_speaker").value = speaker;

    document.getElementById("location").value = loc;
    document.getElementById("session_date").value = date;
    document.getElementById("stime").value = start;
    document.getElementById("etime").value = end;

    document.getElementById("edit-fields").style.display = 'block';
}
</script>

</body>
</html>
