<?php
$host = 'localhost';
$dbname = 'conferenceDB';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $companyStmt = $pdo->query("SELECT Name FROM Company");
    $companyOptions = $companyStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Attendee</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        function toggleFields() {
            const type = document.getElementById('type').value;
            document.getElementById('room').style.display = (type === 'Student') ? 'block' : 'none';
            document.getElementById('company').style.display = (type === 'Sponsor') ? 'block' : 'none';
        }
    </script>
</head>

<body>
<div class="title-box">
    <h1>Add New Attendee</h1>
</div>

<div class="title-box">
    <form method="post">
        <label>ID (must be unique): <input type="number" name="id" required></label><br><br>
        <label>First Name: <input type="text" name="fname" required></label><br><br>
        <label>Last Name: <input type="text" name="lname" required></label><br><br>
        <label>Attendee Type:
            <select name="type" id="type" onchange="toggleFields()" required>
                <option value="">--Select--</option>
                <option value="Student">Student</option>
                <option value="Professional">Professional</option>
                <option value="Sponsor">Sponsor</option>
            </select>
        </label><br><br>

        <div id="room" style="display:none;">
            <label>Room Number (only available rooms shown):
                <select name="room_num">
                    <option value="">-- Select Room --</option>
                    <?php
                    $stmt = $pdo->query("
                        SELECT r.num
                        FROM Room r
                        LEFT JOIN (
                            SELECT room_num, COUNT(*) as student_count
                            FROM Student
                            GROUP BY room_num
                        ) s ON r.num = s.room_num
                        WHERE IFNULL(s.student_count, 0) < r.num_beds
                    ");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['num']}'>{$row['num']}</option>";
                    }
                    ?>
                </select>
            </label><br>
        </div>

        <div id="company" style="display:none;">
            <label for="company_name">Company Name (for sponsors):</label>
            <select name="company_name" id="company_name">
                <option value="">-- Select Company --</option>
                <?php foreach ($companyOptions as $companyName): ?>
                    <option value="<?= htmlspecialchars($companyName) ?>"><?= htmlspecialchars($companyName) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>
        <button type="submit" name="submit">Add Attendee</button>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $type = $_POST['type'];

    $check = null;
    if ($type === 'Student') {
        $check = $pdo->prepare("SELECT ID FROM Student WHERE ID = ?");
    } elseif ($type === 'Professional') {
        $check = $pdo->prepare("SELECT ID FROM Professional WHERE ID = ?");
    } elseif ($type === 'Sponsor') {
        $check = $pdo->prepare("SELECT ID FROM Sponsor WHERE ID = ?");
    }

    if ($check) {
        $check->execute([$id]);
        if ($check->rowCount() > 0) {
            echo "<p style='color:red;'>This ID already exists. Please use a different ID.</p>";
            exit();
        }
    }

    if ($type === 'Student') {
        $room = !empty($_POST['room_num']) ? $_POST['room_num'] : null;
        $stmt = $pdo->prepare("INSERT INTO Student (ID, Fee, fname, lname, room_num) VALUES (?, 50.00, ?, ?, ?)");
        $stmt->execute([$id, $fname, $lname, $room]);
        echo "<p style='color:green;'>Student added successfully.</p>";
    }

    elseif ($type === 'Professional') {
        $stmt = $pdo->prepare("INSERT INTO Professional (ID, Fee, fname, lname) VALUES (?, 100.00, ?, ?)");
        $stmt->execute([$id, $fname, $lname]);
        echo "<p style='color:green;'>Professional added successfully.</p>";
    }

    elseif ($type === 'Sponsor') {
        $company = $_POST['company_name'];
        if (empty($company)) {
            echo "<p style='color:red;'>Company name is required for sponsors.</p>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO Sponsor (ID, Fee, fname, lname, CompanyName) VALUES (?, 0.00, ?, ?, ?)");
            $stmt->execute([$id, $fname, $lname, $company]);
            echo "<p style='color:green;'>Sponsor added successfully.</p>";
        }
    }
}
?>

<h2><a href="../conference.php">⬅ Back to Home</a></h2>
</body>
</html>
