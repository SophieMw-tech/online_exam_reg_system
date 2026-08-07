<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$error = "";

/* =========================================
   DELETE EXAM
   ========================================= */

if (isset($_GET['delete'])) {

    $exam_id = intval($_GET['delete']);

    /* Check whether the exam has registrations */
    $check_sql = "SELECT COUNT(*) AS total
                  FROM registrations
                  WHERE exam_id = ?";

    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $exam_id);
    mysqli_stmt_execute($check_stmt);

    $check_result = mysqli_stmt_get_result($check_stmt);
    $check = mysqli_fetch_assoc($check_result);

    if ($check['total'] > 0) {

        $error = "This examination cannot be deleted because students have already registered for it.";

    } else {

        $delete_sql = "DELETE FROM examinations
                       WHERE exam_id = ?";

        $delete_stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $exam_id);
        mysqli_stmt_execute($delete_stmt);

        header("Location: manage_exams.php?deleted=1");
        exit();
    }
}


/* =========================================
   ADD / EDIT EXAM
   ========================================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action'];

    $course_name = trim($_POST['course_name']);
    $course_code = trim($_POST['course_code']);
    $venue = trim($_POST['venue']);
    $exam_date = $_POST['exam_date'];
    $exam_time = $_POST['exam_time'];
    $semester = trim($_POST['semester']);
    $academic_year = trim($_POST['academic_year']);

    if (
        empty($course_name) ||
        empty($course_code) ||
        empty($venue) ||
        empty($exam_date) ||
        empty($exam_time) ||
        empty($semester) ||
        empty($academic_year)
    ) {

        $error = "Please fill in all examination details.";

    } else {

        /* ADD */
        if ($action == "add") {

            $sql = "INSERT INTO examinations
                    (
                        course_code,
                        course_name,
                        exam_date,
                        exam_time,
                        venue,
                        semester,
                        academic_year
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sssssss",
                $course_code,
                $course_name,
                $exam_date,
                $exam_time,
                $venue,
                $semester,
                $academic_year
            );

            if (mysqli_stmt_execute($stmt)) {

                header("Location: manage_exams.php?added=1");
                exit();

            } else {

                $error = "Failed to add examination.";
            }
        }


        /* EDIT */
        if ($action == "edit") {

            $exam_id = intval($_POST['exam_id']);

            $sql = "UPDATE examinations
                    SET
                        course_code = ?,
                        course_name = ?,
                        exam_date = ?,
                        exam_time = ?,
                        venue = ?,
                        semester = ?,
                        academic_year = ?
                    WHERE exam_id = ?";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sssssssi",
                $course_code,
                $course_name,
                $exam_date,
                $exam_time,
                $venue,
                $semester,
                $academic_year,
                $exam_id
            );

            if (mysqli_stmt_execute($stmt)) {

                header("Location: manage_exams.php?updated=1");
                exit();

            } else {

                $error = "Failed to update examination.";
            }
        }
    }
}


/* =========================================
   SUCCESS MESSAGES
   ========================================= */

if (isset($_GET['added'])) {
    $message = "Examination created successfully.";
}

if (isset($_GET['updated'])) {
    $message = "Examination updated successfully.";
}

if (isset($_GET['deleted'])) {
    $message = "Examination deleted successfully.";
}


/* =========================================
   EDIT MODE
   ========================================= */

$edit_exam = null;

if (isset($_GET['edit'])) {

    $exam_id = intval($_GET['edit']);

    $sql = "SELECT *
            FROM examinations
            WHERE exam_id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $exam_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $edit_exam = mysqli_fetch_assoc($result);
    }
}


/* =========================================
   GET EXISTING EXAMS
   ========================================= */

$sql = "SELECT *
        FROM examinations
        ORDER BY exam_date ASC, exam_time ASC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Manage Examinations</title>

<link rel="stylesheet"
      href="../css/style.css">

<link rel="stylesheet"
      href="../css/manage_exams.css">

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>


<body>


<div class="phone">

<div class="notch"></div>


<div class="screen">


<!-- HEADER -->

<div class="page-header">

<a href="dashboard.php">

<i class="fa-solid fa-chevron-left"></i>

</a>

<h2>Manage Examinations</h2>

</div>


<!-- MESSAGES -->

<?php if (!empty($message)): ?>

<div class="success-message">

<?php echo htmlspecialchars($message); ?>

</div>

<?php endif; ?>


<?php if (!empty($error)): ?>

<div class="error-message">

<?php echo htmlspecialchars($error); ?>

</div>

<?php endif; ?>


<!-- CREATE / EDIT -->

<div class="form-heading">

<?php if ($edit_exam): ?>

<strong>Edit Examination</strong>

<?php else: ?>

<strong>Create an Exam</strong>

<?php endif; ?>

</div>


<form method="POST"
      class="exam-form">


<input type="hidden"
       name="action"
       value="<?php echo $edit_exam ? 'edit' : 'add'; ?>">


<?php if ($edit_exam): ?>

<input type="hidden"
       name="exam_id"
       value="<?php echo $edit_exam['exam_id']; ?>">

<?php endif; ?>


<!-- NAME -->

<label>Course Name</label>

<input
    type="text"
    name="course_name"
    placeholder="Create an exam"
    value="<?php echo $edit_exam ? htmlspecialchars($edit_exam['course_name']) : ''; ?>"
    required
>


<!-- FIRST / EDIT ROW -->

<div class="two-column">

<div>

<label>Course Code</label>

<input
    type="text"
    name="course_code"
    placeholder="e.g. BIT2101"
    value="<?php echo $edit_exam ? htmlspecialchars($edit_exam['course_code']) : ''; ?>"
    required
>

</div>


<div>

<label>Exam Date</label>

<input
    type="date"
    name="exam_date"
    value="<?php echo $edit_exam ? $edit_exam['exam_date'] : ''; ?>"
    required
>

</div>

</div>


<!-- DEPARTMENT / VENUE -->

<label>Venue</label>

<input
    type="text"
    name="venue"
    placeholder="Exam venue"
    value="<?php echo $edit_exam ? htmlspecialchars($edit_exam['venue']) : ''; ?>"
    required
>


<!-- ADDITIONAL DATABASE FIELDS -->

<div class="two-column">

<div>

<label>Exam Time</label>

<input
    type="time"
    name="exam_time"
    value="<?php echo $edit_exam ? $edit_exam['exam_time'] : ''; ?>"
    required
>

</div>


<div>

<label>Semester</label>

<input
    type="text"
    name="semester"
    placeholder="Semester 1"
    value="<?php echo $edit_exam ? htmlspecialchars($edit_exam['semester']) : ''; ?>"
    required
>

</div>

</div>


<label>Academic Year</label>

<input
    type="text"
    name="academic_year"
    placeholder="2026/2027"
    value="<?php echo $edit_exam ? htmlspecialchars($edit_exam['academic_year']) : ''; ?>"
    required
>


<button type="submit"
        class="submit-btn">

<?php echo $edit_exam ? 'Update Exam' : 'Create Exam'; ?>

</button>


<?php if ($edit_exam): ?>

<a href="manage_exams.php"
   class="cancel-btn">

Cancel Edit

</a>

<?php endif; ?>

</form>


<!-- EXISTING -->

<h3 class="existing-title">

Existing

</h3>


<?php if (mysqli_num_rows($result) > 0): ?>


<?php while ($exam = mysqli_fetch_assoc($result)): ?>


<div class="existing-card">


<div class="tool-icon">

<i class="fa-solid fa-screwdriver-wrench"></i>

</div>


<div class="exam-info">

<strong>

<?php echo htmlspecialchars($exam['course_name']); ?>

</strong>

<p>

<?php echo htmlspecialchars($exam['course_code']); ?>

</p>

</div>


<div class="card-actions">


<a href="manage_exams.php?edit=<?php echo $exam['exam_id']; ?>">

<i class="fa-solid fa-pen"></i>

</a>


<a href="manage_exams.php?delete=<?php echo $exam['exam_id']; ?>"
   onclick="return confirm('Are you sure you want to delete this examination?');">

<i class="fa-solid fa-trash"></i>

</a>

</div>


</div>


<?php endwhile; ?>


<?php else: ?>


<div class="empty">

No examinations available.

</div>


<?php endif; ?>


<!-- BOTTOM NAVIGATION -->

<div class="admin-nav">

<a href="dashboard.php">

<i class="fa-solid fa-house"></i>

</a>


<a href="manage_exams.php"
   class="active">

<i class="fa-solid fa-file-signature"></i>

</a>


<a href="view_registrations.php">

<i class="fa-solid fa-magnifying-glass"></i>

</a>


<a href="#">

<i class="fa-solid fa-user"></i>

</a>

</div>


</div>

</div>


</body>

</html>