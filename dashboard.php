<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">
    <h2>Welcome <?php echo htmlspecialchars($_SESSION['name']); ?></h2>

    <a href="ask_doubt.php">Ask a Doubt</a><br>
    <a href="my_doubts.php">My Doubts</a><br>
    <a href="../logout.php" class="logout-btn">Logout</a>
</div>

</body>
</html>
