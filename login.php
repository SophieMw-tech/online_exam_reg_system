<?php
session_start();
include 'includes/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $admission_number = trim($_POST['admission_number']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM students WHERE admission_number = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $admission_number);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $student = mysqli_fetch_assoc($result);

        // Temporary password check
        if (password_verify($password, $student['password'])) {

            $_SESSION['student_id'] = $student['student_id'];
            $_SESSION['student_name'] = $student['first_name'] . " " . $student['last_name'];

            header("Location: student/dashboard.php");
            exit();

        } else {

            $error = "Incorrect password.";

        }

    } else {

        $error = "Admission number not found.";

    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login | Online Examination Registration System</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/login.css">

    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

<body>

<div class="phone">

<div class="notch"></div>

<div class="screen">

    <div class="login-card">

        <h3 class="system-title">
            Online Examination Registration System
        </h3>

        <div class="logo-box">
            OERS
        </div>

        <h1>Welcome Back!</h1>

        <p class="subtitle">
            Sign in to continue
        </p>
    <?php if(!empty($error)): ?>

<div class="error-message">

    <?php echo $error; ?>

</div>

<?php endif; ?>
        <form method="POST">

            <div class="form-group">

                <label>
                    Admission Number
                </label>

                <div class="input-box">

                    <span class="material-icons">
                        person
                    </span>

                    <input
                        type="text"
                        name="admission_number"
                        placeholder="Enter Admission Number"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label>
                    Password
                </label>

                <div class="input-box">

                    <span class="material-icons">
                        lock
                    </span>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter Password"
                        required>

                    <span
                        class="material-icons toggle-password">

                        visibility

                    </span>

                </div>

            </div>

            <div class="forgot-password">

                <a href="#">

                    Forgot Password?

                </a>

            </div>

            <button class="login-btn">

                Login

            </button>

        </form>

        <div class="signup">

            Don't have an account?

            <a href="register.php">

                Sign Up

            </a>

        </div>

    </div>

</div>
</div>

<script src="js/script.js"></script>

</body>

</html>