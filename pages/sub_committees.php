<?php
$host = 'localhost';
$dbname = 'conferenceDB';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $subStmt = $pdo->query("SELECT name FROM Subcommittee ORDER BY name");
    $subcommittees = $subStmt->fetchAll(PDO::FETCH_ASSOC);

    $results = [];
    $selectedCommittee = '';

    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['subcommittee'])) {
        $selectedCommittee = $_POST['subcommittee'];

        $query = "
            SELECT Member.ID, Member.fname, Member.lname
            FROM Member
            JOIN MemberOf ON Member.ID = MemberOf.MemberID
            WHERE MemberOf.SubcommitteeName = ?
            ORDER BY Member.lname
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$selectedCommittee]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sub-Committee Members</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="title-box">
    <h1>Sub-Committee Members</h1>
</div>

<div class="title-box">
    <form method="post">
        <label for="subcommittee">Choose a sub-committee:</label>
        <select name="subcommittee" id="subcommittee" required>
            <option value="">-- Select --</option>
            <?php foreach ($subcommittees as $s): ?>
                <option value="<?= htmlspecialchars($s['name']) ?>" <?= ($s['name'] === $selectedCommittee) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">View Members</button>
    </form>
</div>

<?php if (!empty($selectedCommittee)): ?>
<div class="title-box">
    <?php if (count($results) > 0): ?>
            <?php foreach ($results as $member): ?>
                <li><?= htmlspecialchars($member['fname']) ?> <?= htmlspecialchars($member['lname']) ?> (ID: <?= $member['ID'] ?>)</li>
            <?php endforeach; ?>
    <?php else: ?>
        <p>No members found for this sub-committee.</p>
    <?php endif; ?>
</div>
<?php endif; ?>

<h2><a href="../conference.php">⬅ Back to Home</a></h2>

</body>
</html>
