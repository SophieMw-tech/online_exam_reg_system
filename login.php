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

<div class="phone-container">

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

<script src="js/script.js"></script>

</body>

</html>