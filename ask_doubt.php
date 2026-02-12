<?php
session_start();
include "../config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int)$_SESSION['user_id'];
    $subject_name = trim($_POST['subject'] ?? '');
    $question = trim($_POST['question'] ?? '');

    if ($subject_name === '' || $question === '') {
        $error = "Subject and question are required";
    } else {
        $subject_id = null;

        $find_subject = mysqli_prepare($conn, "SELECT subject_id FROM subjects WHERE subject_name = ? LIMIT 1");
        if (!$find_subject) {
            $error = "Database error: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($find_subject, 's', $subject_name);
            if (!mysqli_stmt_execute($find_subject)) {
                $error = "Database error: " . mysqli_stmt_error($find_subject);
            } else {
                mysqli_stmt_bind_result($find_subject, $found_subject_id);
                if (mysqli_stmt_fetch($find_subject)) {
                    $subject_id = (int)$found_subject_id;
                }
            }
            mysqli_stmt_close($find_subject);
        }

        if ($subject_id === null) {
            $insert_subject = mysqli_prepare($conn, "INSERT INTO subjects (subject_name) VALUES (?)");
            if (!$insert_subject) {
                $error = "Database error: " . mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param($insert_subject, 's', $subject_name);
                if (!mysqli_stmt_execute($insert_subject)) {
                    $error = "Database error: " . mysqli_stmt_error($insert_subject);
                } else {
                    $subject_id = (int)mysqli_insert_id($conn);
                }
                mysqli_stmt_close($insert_subject);
            }
        }

        if ($error === "" && $subject_id !== null) {
            $insert_query = mysqli_prepare($conn, "INSERT INTO queries (student_id, subject_id, question, status) VALUES (?, ?, ?, 'pending')");
            if (!$insert_query) {
                $error = "Database error: " . mysqli_error($conn);
            } else {
                mysqli_stmt_bind_param($insert_query, 'iis', $student_id, $subject_id, $question);
                if (mysqli_stmt_execute($insert_query)) {
                    $success = "Doubt submitted successfully";
                } else {
                    $error = "Database error: " . mysqli_stmt_error($insert_query);
                }
                mysqli_stmt_close($insert_query);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ask a Doubt</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">

<h2>Ask a Doubt</h2>

<?php if ($success): ?>
    <div class="success-msg"><?php echo $success; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="success-msg" style="background:#f8d7da;color:#842029;">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<form method="post">
    <label>Subject</label>
    <input type="text" name="subject" required>

    <label>Your Doubt</label>
    <textarea name="question" required></textarea>

    <button type="submit">Submit Doubt</button>
</form>

<a href="dashboard.php">← Back to Dashboard</a>

</div>
</body>
</html>
