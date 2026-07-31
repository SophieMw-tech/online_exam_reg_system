<?php
include 'includes/db.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $admission_number = trim($_POST['admission_number']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);
    $year_of_study = trim($_POST['year_of_study']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {

        $error = "Passwords do not match.";

    } else {

        // Check if admission number or email already exists
        $check = mysqli_prepare($conn,
            "SELECT student_id FROM students
             WHERE admission_number = ? OR email = ?");

        mysqli_stmt_bind_param($check, "ss",
            $admission_number,
            $email);

        mysqli_stmt_execute($check);

        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) > 0) {

            $error = "Admission number or email already exists.";

        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert = mysqli_prepare($conn,
                "INSERT INTO students
                (first_name,
                last_name,
                admission_number,
                email,
                password,
                course,
                year_of_study)
                VALUES (?, ?, ?, ?, ?, ?, ?)");

            mysqli_stmt_bind_param(
                $insert,
                "ssssssi",
                $first_name,
                $last_name,
                $admission_number,
                $email,
                $hashed_password,
                $course,
                $year_of_study
            );

            if (mysqli_stmt_execute($insert)) {

                $success = "Account created successfully!";

            } else {

                $error = "Registration failed.";

            }

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | Online Examination Registration System</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

<body>

<div class="phone-container">

    <div class="login-card">

        <h3 class="system-title">
            Online Examination Registration System
        </h3>

        <div class="logo-box">
            OERS
        </div>

        <h1>Create Account</h1>

        <p class="subtitle">
            Register to continue
        </p>
<?php if(!empty($error)): ?>

<div class="error-message">
    <?php echo $error; ?>
</div>

<?php endif; ?>

<?php if(!empty($success)): ?>

<div class="success-message">
    <?php echo $success; ?>
</div>

<?php endif; ?>

        <form action="" method="POST">

            <div class="form-group">

                <label>First Name</label>

                <div class="input-box">

                    <span class="material-icons">person</span>

                    <input
                        type="text"
                        name="first_name"
                        placeholder="Enter First Name"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label>Last Name</label>

                <div class="input-box">

                    <span class="material-icons">person</span>

                    <input
                        type="text"
                        name="last_name"
                        placeholder="Enter Last Name"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label>Admission Number</label>

                <div class="input-box">

                    <span class="material-icons">badge</span>

                    <input
                        type="text"
                        name="admission_number"
                        placeholder="Enter Admission Number"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label>Email Address</label>

                <div class="input-box">

                    <span class="material-icons">email</span>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter Email Address"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label>Course</label>

                <div class="input-box">

                    <span class="material-icons">school</span>

                    <input
                        type="text"
                        name="course"
                        placeholder="Enter Course"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label>Year of Study</label>

                <div class="input-box">

                    <span class="material-icons">calendar_today</span>

                    <input
                        type="number"
                        name="year_of_study"
                        min="1"
                        max="6"
                        placeholder="Enter Year"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label>Password</label>

                <div class="input-box">

                    <span class="material-icons">lock</span>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Create Password"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label>Confirm Password</label>

                <div class="input-box">

                    <span class="material-icons">lock</span>

                    <input
                        type="password"
                        name="confirm_password"
                        placeholder="Confirm Password"
                        required>

                </div>

            </div>

            <button class="login-btn">

                Create Account

            </button>

        </form>

        <div class="signup">

            Already have an account?

            <a href="login.php">

                Login

            </a>

        </div>

    </div>

</div>

</body>

</html>