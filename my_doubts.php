<?php
session_start();
include "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$student_id = (int)$_SESSION['user_id'];
$sql = "
    SELECT q.*, COALESCE(s.subject_name, '') AS subject_name
    FROM queries q
    LEFT JOIN subjects s ON q.subject_id = s.subject_id
    WHERE q.student_id = $student_id
    ORDER BY q.created_at DESC
";
$result = mysqli_query($conn, $sql);

if ($result === false) {
    die("Database error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Doubts</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">
    <h2>My Doubts</h2>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="card">
            <b>Subject:</b> <?php echo htmlspecialchars($row['subject_name']); ?><br>
            <b>Question:</b> <?php echo htmlspecialchars($row['question']); ?><br>
            <b>Status:</b> <?php echo ucfirst($row['status']); ?><br>
            <b>Answer:</b> <?php echo $row['answer'] ?: "Waiting for answer"; ?>
        </div>
    <?php } ?>

    <a href="dashboard.php"> Back</a>
</div>

</body>
</html>
