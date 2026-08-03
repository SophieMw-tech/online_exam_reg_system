<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$sql = "SELECT
            e.course_code,
            e.course_name,
            e.exam_date,
            e.exam_time,
            e.venue,
            r.status
        FROM registrations r
        JOIN examinations e
        ON r.exam_id = e.exam_id
        WHERE r.student_id = ?
        ORDER BY e.exam_date ASC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>My Registrations</title>

<link rel="stylesheet" href="../css/style.css">

<link rel="stylesheet" href="../css/my_registrations.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<div class="phone">

<div class="notch"></div>

<div class="screen">

<h2>My Registrations</h2>

<?php

if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

?>

<div class="registration-card">

<h3>

<?php echo $row['course_code']; ?>

</h3>

<p>

<?php echo $row['course_name']; ?>

</p>

<p>

📅

<?php echo date("d M Y",strtotime($row['exam_date'])); ?>

</p>

<p>

🕒

<?php echo date("h:i A",strtotime($row['exam_time'])); ?>

</p>

<p>

📍

<?php echo $row['venue']; ?>

</p>

<span class="status">

<?php echo $row['status']; ?>

</span>

</div>

<?php

}

}else{

?>

<p>No registrations found.</p>

<?php } ?>

<a href="dashboard.php" class="btn">

← Back to Dashboard

</a>

</div>

</div>

</body>

</html>