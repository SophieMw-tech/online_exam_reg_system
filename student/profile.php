<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$sql = "SELECT * FROM students WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Profile</title>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/profile.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="phone">

<div class="notch"></div>

<div class="screen">

<div class="profile-header">

<i class="fa-solid fa-circle-user profile-icon"></i>

<h2><?php echo $student['first_name']." ".$student['last_name']; ?></h2>

<p><?php echo $student['admission_number']; ?></p>

</div>

<div class="profile-card">

<div class="row">
<span>First Name</span>
<strong><?php echo $student['first_name']; ?></strong>
</div>

<div class="row">
<span>Last Name</span>
<strong><?php echo $student['last_name']; ?></strong>
</div>

<div class="row">
<span>Email</span>
<strong><?php echo $student['email']; ?></strong>
</div>

<div class="row">
<span>Course</span>
<strong><?php echo $student['course']; ?></strong>
</div>

<div class="row">
<span>Year of Study</span>
<strong><?php echo $student['year_of_study']; ?></strong>
</div>

</div>

<a href="dashboard.php" class="btn">
Back to Dashboard
</a>

</div>

</div>

</body>

</html>