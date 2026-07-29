<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
</head>
<body>

<h1>Welcome,
    <?php echo $_SESSION['student_name']; ?>
</h1>

<p>You have successfully logged in.</p>

</body>
</html>