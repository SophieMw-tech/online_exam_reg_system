<?php
session_start();

include("../includes/db.php");

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Get logged-in student details
$sql = "SELECT * FROM students WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

// Message variables
$message = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $exam_id = $_POST['exam_id'];

    // Check if already registered
    $check = mysqli_prepare(
        $conn,
        "SELECT * FROM registrations
         WHERE student_id = ? AND exam_id = ?"
    );

    mysqli_stmt_bind_param(
        $check,
        "ii",
        $student_id,
        $exam_id
    );

    mysqli_stmt_execute($check);

    $check_result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($check_result) > 0) {

        $error = "You have already registered for this examination.";

    } else {

        $insert = mysqli_prepare(
            $conn,
            "INSERT INTO registrations
            (student_id, exam_id)
            VALUES (?, ?)"
        );

        mysqli_stmt_bind_param(
            $insert,
            "ii",
            $student_id,
            $exam_id
        );

        if (mysqli_stmt_execute($insert)) {

            $message = "Exam registration successful!";

        } else {

            $error = "Registration failed.";

        }
    }
}
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Exam Registration</title>

<link rel="stylesheet" href="../css/login.css">

<link rel="stylesheet" href="../css/exam_registration.css">

<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
rel="stylesheet">

</head>

<body>

<div class="phone-container">

<div class="login-card">

<a href="dashboard.php" class="back-button">

<span class="material-icons">

arrow_back

</span>

</a>

<h2 class="page-title">

Personal Details

</h2>

<?php if($error!=""){ ?>

<div class="error-message">

<?php echo $error; ?>

</div>

<?php } ?>

<?php if($message!=""){ ?>

<div class="success-message">

<?php echo $message; ?>

</div>

<?php } ?>

<form method="POST">

<div class="form-group">

<label>First Name</label>

<div class="input-box">

<input
type="text"
value="<?php echo htmlspecialchars($student['first_name']); ?>"
readonly>

</div>

</div>

<div class="form-group">

<label>Last Name</label>

<div class="input-box">

<input
type="text"
value="<?php echo htmlspecialchars($student['last_name']); ?>"
readonly>

</div>

</div>

<div class="form-group">

<label>Email</label>

<div class="input-box">

<input
type="email"
value="<?php echo htmlspecialchars($student['email']); ?>"
readonly>

</div>

</div>

<div class="form-group">

<label>Department / Course</label>

<div class="input-box">

<input
type="text"
value="<?php echo htmlspecialchars($student['course']); ?>"
readonly>

</div>

</div>

<div class="form-group">

<label>Selected Examination</label>

<div class="input-box">

<select name="exam_id" required>

<option value="">Select Examination</option>

<?php

$exam_query = mysqli_query($conn,"SELECT * FROM examinations ORDER BY course_code ASC");

while($exam = mysqli_fetch_assoc($exam_query)){

?>

<option value="<?php echo $exam['exam_id']; ?>">

<?php
echo htmlspecialchars($exam['course_code'])
     ." - ".
     htmlspecialchars($exam['course_name']);
?>

</option>

<?php } ?>

</select>

</div>

</div>

<div class="terms">

<label>

<input
type="checkbox"
required>

I have read and understood the Terms & Conditions.

</label>

<label>

<input
type="checkbox"
required>

I agree to all examination regulations.

</label>

<label>

<input
type="checkbox"
required>

I have read the Privacy Policy.

</label>

</div>

<button
type="submit"
class="login-btn">

Proceed to Confirmation

</button>

</form>
</div>

</div>

</body>

</html>