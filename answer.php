<?php
session_start();
include "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$query_id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer = trim($_POST['answer'] ?? '');

    $update = mysqli_prepare($conn, "UPDATE queries SET answer = ?, status = 'answered' WHERE query_id = ?");
    if (!$update) {
        die("Database error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($update, 'si', $answer, $query_id);
    if (!mysqli_stmt_execute($update)) {
        $stmt_error = mysqli_stmt_error($update);
        mysqli_stmt_close($update);
        die("Database error: " . $stmt_error);
    }
    mysqli_stmt_close($update);

    header("Location: dashboard.php");
    exit();
}

$sql = "
    SELECT q.question, u.name
    FROM queries q
    JOIN users u ON q.student_id = u.user_id
    WHERE q.query_id = $query_id
";
$result = mysqli_query($conn, $sql);
$doubt = $result ? mysqli_fetch_assoc($result) : null;

if ($result === false || !$doubt) {
    die("Database error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Answer Doubt</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">
    <h2>Answer Doubt</h2>

    <p><b>Student:</b> <?php echo htmlspecialchars($doubt['name']); ?></p>
    <p><b>Question:</b> <?php echo htmlspecialchars($doubt['question']); ?></p>

    <form method="post">
        <label>Your Answer</label><br>
        <textarea name="answer" rows="5" required></textarea><br><br>
        <button type="submit">Submit Answer</button>
    </form>

    <br>
    <a href="dashboard.php">Back to Dashboard</a><br><br>
    <a href="../logout.php" class="logout-btn">Logout</a>
</div>

</body>
</html>
