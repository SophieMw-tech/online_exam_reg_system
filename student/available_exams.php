<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$sql = "SELECT *
        FROM examinations
        WHERE exam_id NOT IN
        (
            SELECT exam_id
            FROM registrations
            WHERE student_id=?
        )
        ORDER BY exam_date ASC";

$stmt = mysqli_prepare($conn,$sql);
mysqli_stmt_bind_param($stmt,"i",$student_id);
mysqli_stmt_execute($stmt);

$result=mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Available Exams</title>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/available_exams.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<div class="phone">

<div class="notch"></div>

<div class="screen">

<h2>Available Exams</h2>

<?php

if(mysqli_num_rows($result)>0){

while($exam=mysqli_fetch_assoc($result)){

?>

<div class="exam-card">

<h3><?php echo $exam['course_code']; ?></h3>

<p><strong><?php echo $exam['course_name']; ?></strong></p>

<p>📅 <?php echo date("d M Y",strtotime($exam['exam_date'])); ?></p>

<p>🕒 <?php echo date("h:i A",strtotime($exam['exam_time'])); ?></p>

<p>📍 <?php echo $exam['venue']; ?></p>

<p>Semester: <?php echo $exam['semester']; ?></p>

</div>

<?php

}

}else{

?>

<div class="exam-card">

<p>You have registered for all available examinations. 🎉</p>

</div>

<?php } ?>

<a href="exam_registration.php" class="btn">

Register an Exam

</a>

<br>

<a href="dashboard.php" class="btn">

Back to Dashboard

</a>

</div>

</div>

</body>

</html>