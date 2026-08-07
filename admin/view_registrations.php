<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

/* Selected exam */
$selected_exam = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 0;

/* Search */
$search = isset($_GET['search']) ? trim($_GET['search']) : "";


/* Get all examinations for dropdown */
$exam_sql = "SELECT exam_id, course_code, course_name
             FROM examinations
             ORDER BY exam_date ASC";

$exam_result = mysqli_query($conn, $exam_sql);


/* Get registrations */
$sql = "SELECT
            r.registration_id,
            r.status,
            r.registration_date,
            s.first_name,
            s.last_name,
            s.admission_number,
            e.exam_id,
            e.course_code,
            e.course_name
        FROM registrations r
        JOIN students s
            ON r.student_id = s.student_id
        JOIN examinations e
            ON r.exam_id = e.exam_id
        WHERE 1=1";


/* Filter by exam */
if ($selected_exam > 0) {
    $sql .= " AND e.exam_id = ?";
}


/* Search */
if ($search !== "") {
    $sql .= " AND (
                s.first_name LIKE ?
                OR s.last_name LIKE ?
                OR s.admission_number LIKE ?
              )";
}


$sql .= " ORDER BY r.registration_date DESC";


/* Prepare query */
$stmt = mysqli_prepare($conn, $sql);


/* Bind parameters */
if ($selected_exam > 0 && $search !== "") {

    $search_term = "%" . $search . "%";

    mysqli_stmt_bind_param(
        $stmt,
        "isss",
        $selected_exam,
        $search_term,
        $search_term,
        $search_term
    );

} elseif ($selected_exam > 0) {

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $selected_exam
    );

} elseif ($search !== "") {

    $search_term = "%" . $search . "%";

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $search_term,
        $search_term,
        $search_term
    );
}


mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>View Registrations</title>

<link rel="stylesheet"
      href="../css/style.css">

<link rel="stylesheet"
      href="../css/view_registrations.css">

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

<h2>View Registrations</h2>

</div>


<!-- SELECT EXAM -->

<form method="GET"
      class="filter-form">

<label>Select exam</label>

<div class="select-wrapper">

<select name="exam_id"
        onchange="this.form.submit()">

<option value="0">
Select an exam
</option>


<?php while ($exam = mysqli_fetch_assoc($exam_result)): ?>

<option
    value="<?php echo $exam['exam_id']; ?>"
    <?php
    if ($selected_exam == $exam['exam_id']) {
        echo "selected";
    }
    ?>
>

<?php echo htmlspecialchars(
    $exam['course_code'] . " - " . $exam['course_name']
); ?>

</option>

<?php endwhile; ?>

</select>

<i class="fa-solid fa-chevron-down"></i>

</div>


<!-- SEARCH -->

<div class="search-box">

<i class="fa-solid fa-magnifying-glass"></i>

<input
    type="text"
    name="search"
    placeholder="Search"
    value="<?php echo htmlspecialchars($search); ?>"
>

</div>

<button type="submit"
        class="search-button">

Search

</button>

</form>


<!-- COLUMN HEADERS -->

<div class="list-header">

<strong>Student</strong>

<strong>Status</strong>

</div>


<!-- REGISTRATIONS -->

<div class="registrations-list">

<?php if (mysqli_num_rows($result) > 0): ?>


<?php while ($row = mysqli_fetch_assoc($result)): ?>


<div class="student-row">


<!-- STUDENT -->

<div class="student-info">

<div class="student-icon">

<i class="fa-solid fa-user"></i>

</div>


<div>

<strong>

<?php echo htmlspecialchars(
    $row['first_name'] . " " . $row['last_name']
); ?>

</strong>

<p>

ID:
<?php echo htmlspecialchars(
    $row['admission_number']
); ?>

</p>

</div>

</div>


<!-- STATUS -->

<?php

$status = strtolower(trim($row['status']));

if ($status == "pending") {

    $display_status = "Pending";
    $status_class = "pending";

} else {

    $display_status = "Confirmed";
    $status_class = "confirmed";

}

?>


<span class="status <?php echo $status_class; ?>">

<?php echo $display_status; ?>

</span>


</div>


<?php endwhile; ?>


<?php else: ?>


<div class="no-results">

<i class="fa-solid fa-clipboard-list"></i>

<p>No registrations found.</p>

</div>


<?php endif; ?>

</div>


<!-- BOTTOM NAVIGATION -->

<div class="admin-nav">

<a href="dashboard.php">

<i class="fa-solid fa-house"></i>

</a>


<a href="manage_exams.php">

<i class="fa-solid fa-file-signature"></i>

</a>


<a href="view_registrations.php"
   class="active">

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