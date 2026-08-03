<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['registration_id'])) {
    header("Location: dashboard.php");
    exit();
}

$registration_id = $_GET['registration_id'];

$sql = "SELECT r.registration_id,
               s.first_name,
               s.last_name,
               e.course_code,
               e.course_name
        FROM registrations r
        JOIN students s ON r.student_id = s.student_id
        JOIN examinations e ON r.exam_id = e.exam_id
        WHERE r.registration_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $registration_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Registration not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Confirmation</title>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/confirmation.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<div class="confirmation-card">

<div class="success-icon">

✔

</div>

<h2>Registration Confirmed!</h2>

<p class="reference">

Reference ID

<br>

<strong>

REG-<?php echo str_pad($data['registration_id'],5,"0",STR_PAD_LEFT); ?>

</strong>

</p>

<div class="summary">

<h3>Summary</h3>

<p>

Your registration for

<strong>

<?php echo $data['course_code']; ?>

-

<?php echo $data['course_name']; ?>

</strong>

has been received successfully and is currently being processed.

</p>

</div>

<a href="dashboard.php" class="btn">

Go to Dashboard

</a>

</div>

</body>

</html>