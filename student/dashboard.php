<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

/* Student Details */
$sql = "SELECT * FROM students WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

/* Upcoming Exam */
$exam_sql = "SELECT e.course_name,
                    e.exam_date
             FROM registrations r
             JOIN examinations e
             ON r.exam_id = e.exam_id
             WHERE r.student_id = ?
             ORDER BY e.exam_date ASC
             LIMIT 1";

$exam_stmt = mysqli_prepare($conn, $exam_sql);
mysqli_stmt_bind_param($exam_stmt, "i", $student_id);
mysqli_stmt_execute($exam_stmt);
$exam_result = mysqli_stmt_get_result($exam_stmt);
$exam = mysqli_fetch_assoc($exam_result);

/* Exams Not Yet Registered */

$pending_sql = "SELECT *
                FROM examinations
                WHERE exam_id NOT IN
                (
                    SELECT exam_id
                    FROM registrations
                    WHERE student_id = ?
                )
                ORDER BY exam_date ASC";

$pending_stmt = mysqli_prepare($conn, $pending_sql);
mysqli_stmt_bind_param($pending_stmt,"i",$student_id);
mysqli_stmt_execute($pending_stmt);

$pending_result = mysqli_stmt_get_result($pending_stmt);

?>
<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Student Dashboard</title>

<link rel="stylesheet" href="../css/dashboard.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="container">

<div class="top">

<i class="fa-solid fa-circle-user profile">
  <a href="profile.php" class="profile-link">Profile</a>
</i>

<i class="fa-regular fa-bell bell"></i>

</div>

<h2>

Welcome,

<?php echo $student['first_name']; ?>

👋

</h2>
<a href="my_registrations.php" class="card-link">
<div class="card">

<div class="card-header">

<h3>My Upcoming Exams</h3>

<i class="fa-solid fa-chevron-right"></i>

</div>

<?php if($exam){ ?>

<h4>

<?php echo $exam['course_name']; ?>

</h4>

<p>

<?php echo date("d M Y",strtotime($exam['exam_date'])); ?>

</p>

<?php }else{ ?>

<p>No upcoming examinations.</p>

<?php } ?>

</div>
</a>

<a href="available_exams.php" class="card-link">

<div class="card">

<div class="card-header">

<h3>Pending Registrations</h3>

<i class="fa-solid fa-chevron-right"></i>

</div>

<?php

if(mysqli_num_rows($pending_result)>0){

while($pending=mysqli_fetch_assoc($pending_result)){

?>


<p>

<strong>

<?php echo $pending['course_code']; ?>

</strong>

-

<?php echo $pending['course_name']; ?>

</p>

<?php

}

}else{

?>

<p>

🎉 You have registered for all available examinations.

</p>

<?php } ?>

</div>

</a>

<h3 class="quick">

Quick Links</h3>

<div class="quick-links">

<a href="exam_registration.php">

<i class="fa-solid fa-file-signature"></i>

</a>

<a href="my_registrations.php">

<i class="fa-solid fa-link"></i>

</a>

<a href="profile.php">

<i class="fa-regular fa-user"></i>

</a>

</div>

<div class="bottom-nav">

<a href="dashboard.php">

<i class="fa-solid fa-house"></i>

</a>

<a href="exam_registration.php">

<i class="fa-solid fa-file-signature"></i>

</a>

<a href="my_registrations.php">

<i class="fa-solid fa-magnifying-glass"></i>

</a>

<a href="profile.php">

<i class="fa-regular fa-user"></i>

</a>

</div>

</div>

</body>

</html>