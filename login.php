<?php
session_start();
include "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? '');

    $stmt = mysqli_prepare($conn, "SELECT user_id, name, role FROM users WHERE email = ? AND password = ? AND role = ? LIMIT 1");
    if (!$stmt) {
        $error = "Database error: " . mysqli_error($conn);
    } else {
        mysqli_stmt_bind_param($stmt, 'sss', $email, $password, $role);
        if (!mysqli_stmt_execute($stmt)) {
            $error = "Database error: " . mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
        } else {
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) === 1) {
                mysqli_stmt_bind_result($stmt, $user_id, $name, $user_role);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);

                $_SESSION['user_id'] = (int)$user_id;
                $_SESSION['name'] = $name;
                $_SESSION['role'] = $user_role;

                if ($role === 'student') {
                    header("Location: student/dashboard.php");
                } else {
                    header("Location: faculty/dashboard.php");
                }
                exit();
            } else {
                mysqli_stmt_close($stmt);
                $error = "Invalid email, password, or role";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container" style="max-width: 500px;">

    <h2 style="text-align:center;">Login</h2>

    <?php if ($error): ?>
        <div class="success-msg" style="background:#f8d7da;color:#842029;">
            ❌ <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="post">

        <label>Email</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Password</label>
        <input type="password" name="password" required>

        <br><br>

<label>Login As</label>
<br>
<select name="role" style="width:100%; padding:10px; border-radius:6px; margin-top:6px;">
    <option value="student">Student</option>
    <option value="faculty">Faculty</option>
</select>


        <br><br>

        <button type="submit" style="width:100%;">Login</button>

    </form>

</div>

</body>
</html>
