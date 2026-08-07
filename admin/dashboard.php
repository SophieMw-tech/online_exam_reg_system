<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

/* Total Registrations */
$sql = "SELECT COUNT(*) AS total FROM registrations";
$result = mysqli_query($conn, $sql);
$total_registrations = mysqli_fetch_assoc($result)['total'];

/* Active Exams */
$sql = "SELECT COUNT(*) AS total
        FROM examinations
        WHERE exam_date >= CURDATE()";
$result = mysqli_query($conn, $sql);
$active_exams = mysqli_fetch_assoc($result)['total'];

/* Pending Registrations */
$sql = "SELECT COUNT(*) AS total
        FROM registrations
        WHERE status = 'Pending'";
$result = mysqli_query($conn, $sql);
$pending_registrations = mysqli_fetch_assoc($result)['total'];

/* Recent Registration */
$sql = "SELECT
            r.registration_date,
            s.first_name,
            s.last_name,
            s.course,
            e.course_code
        FROM registrations r
        JOIN students s
            ON r.student_id = s.student_id
        JOIN examinations e
            ON r.exam_id = e.exam_id
        ORDER BY r.registration_date DESC
        LIMIT 1";

$result = mysqli_query($conn, $sql);
$recent = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard</title>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/admin_dashboard.css">

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
rel="stylesheet">

</head>

<body>

<div class="phone">

<div class="notch"></div>

<div class="screen">

<!-- Header -->

<div class="admin-header">

<h2>Admin Dashboard</h2>

<i class="fa-solid fa-circle-user"></i>

</div>


<!-- Statistics -->

<div class="stats">

<div class="stat-card">

<i class="fa-solid fa-users"></i>

<h3><?php echo $total_registrations; ?></h3>

<p>Total Registrations</p>

</div>


<div class="stat-card">

<i class="fa-solid fa-calendar-days"></i>

<h3><?php echo $active_exams; ?></h3>

<p>Active Exams</p>

</div>

</div>


<!-- Recent Activity -->

<h3 class="section-title">
Recent Activity Feed
</h3>

<div class="activity-card">

<?php if ($recent) { ?>

<div class="activity">

<div class="activity-icon">
<i class="fa-solid fa-user-plus"></i>
</div>

<div>

<strong>
New student registration
</strong>

<p>
<?php
echo htmlspecialchars(
$recent['first_name'] . " " .
$recent['last_name']
);
?>
</p>

<small>
<?php echo htmlspecialchars($recent['course']); ?>
</small>

</div>

</div>

<?php } else { ?>

<p class="empty">
No recent registrations.
</p>

<?php } ?>


<div class="activity">

<div class="activity-icon pending">
<i class="fa-solid fa-clock"></i>
</div>

<div>

<strong>
<?php echo $pending_registrations; ?>
Pending approvals
</strong>

<p>
Registration requests awaiting review
</p>

</div>

</div>

</div>


<!-- Quick Actions -->

<h3 class="section-title">
Quick Actions
</h3>

<div class="quick-actions">

<a href="view_registrations.php">

<i class="fa-solid fa-book-open"></i>

<span>Approve</span>

</a>


<a href="#">

<i class="fa-solid fa-paper-plane"></i>

<span>Send Message</span>

</a>


<a href="manage_exams.php">

<i class="fa-solid fa-list-check"></i>

<span>To-do</span>

</a>

</div>


<!-- Bottom Navigation -->

<div class="admin-nav">

<a href="dashboard.php" class="active">
<i class="fa-solid fa-house"></i>
</a>

<a href="view_registrations.php">
<i class="fa-solid fa-file-signature"></i>
</a>

<a href="manage_exams.php">
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