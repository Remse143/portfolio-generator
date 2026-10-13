
<?php

session_start();

require_once "config/database.php";

$message = "";
$success = false;


// =====================================================
// REGISTRATION
// =====================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email =
        trim($_POST['email'] ?? '');

    $password =
        $_POST['password'] ?? '';

    $confirmPassword =
        $_POST['confirm_password'] ?? '';


    // =================================================
    // VALIDATION
    // =================================================

    if (
        $email === '' ||
        $password === '' ||
        $confirmPassword === ''
    ) {

        $message =
            "Please fill in all fields.";

    } elseif (
        $password !== $confirmPassword
    ) {

        $message =
            "Passwords do not match.";

    } elseif (
        strlen($password) < 6
    ) {

        $message =
            "Password must be at least 6 characters.";

    } else {


        // =================================================
        // SUPABASE SIGN UP
        // =================================================

        $url =
            $supabaseUrl .
            "/auth/v1/signup";


        $data = [

            "email" =>
                $email,

            "password" =>
                $password

        ];


        $ch =
            curl_init($url);


        curl_setopt(
            $ch,
            CURLOPT_RETURNTRANSFER,
            true
        );


        curl_setopt(
            $ch,
            CURLOPT_POST,
            true
        );


        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            json_encode($data)
        );


        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                "Content-Type: application/json",
                "apikey: " . $supabaseKey
            ]
        );


        $response =
            curl_exec($ch);


        $httpCode =
            curl_getinfo(
                $ch,
                CURLINFO_HTTP_CODE
            );


        curl_close($ch);


        $result =
            json_decode(
                $response,
                true
            );


        // =================================================
        // SUCCESS
        // =================================================

        if (
            $httpCode >= 200 &&
            $httpCode < 300
        ) {

            /*
             * If email confirmation is enabled
             * in Supabase, the user may need to
             * confirm their email first.
             */

            header(
                "Location: login.php?registered=1"
            );

            exit;

        }


        // =================================================
        // ERROR
        // =================================================

        else {

            $message =
                $result['msg']
                ?? $result['error_description']
                ?? $result['message']
                ?? "Registration failed. Please try again.";

        }

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <title>
        Sign Up - PortfolioGen
    </title>


    <!-- MAIN WEBSITE CSS -->

    <link
        rel="stylesheet"
        href="css/style.css"
    >


    <!-- FOOTER CSS -->

    <link
        rel="stylesheet"
        href="css/footer.css"
    >

</head>


<body>


<!-- =====================================================
     ANIMATED BACKGROUND
     ===================================================== -->

<div class="animated-background">

    <div class="background-orb orb-one"></div>

    <div class="background-orb orb-two"></div>

    <div class="background-orb orb-three"></div>

    <div class="background-orb orb-four"></div>

    <div class="background-grid"></div>


    <div class="background-particle particle-one"></div>

    <div class="background-particle particle-two"></div>

    <div class="background-particle particle-three"></div>

    <div class="background-particle particle-four"></div>

    <div class="background-particle particle-five"></div>

    <div class="background-particle particle-six"></div>

    <div class="background-particle particle-seven"></div>

    <div class="background-particle particle-eight"></div>

</div>


<!-- =====================================================
     NAVBAR
     ===================================================== -->

<nav class="navbar">

    <div class="container navbar-content">


        <!-- LOGO -->

        <a
            href="index.php"
            class="logo"
        >
            PortfolioGen
        </a>


        <!-- NAVIGATION -->

        <div class="nav-links">

            <a href="index.php">
                Home
            </a>


            <a href="login.php">
                Sign In
            </a>


            <button
                type="button"
                id="themeToggle"
                class="theme-toggle"
            >
                🌙
            </button>

        </div>

    </div>

</nav>


<!-- =====================================================
     SIGN UP SECTION
     ===================================================== -->

<section class="section">

    <div class="container">

        <div class="auth-wrapper">

            <div class="auth-card">


                <!-- HEADER -->

                <div class="form-header">

                    <span class="hero-badge">
                        👤 Create Account
                    </span>


                    <h1>
                        Sign Up
                    </h1>


                    <p>
                        Create an account to start
                        building your portfolio.
                    </p>

                </div>


                <!-- =================================================
                     ERROR MESSAGE
                     ================================================= -->

                <?php if ($message): ?>

                    <div class="message">

                        <?= htmlspecialchars(
                            $message
                        ) ?>

                    </div>

                <?php endif; ?>


                <!-- =================================================
                     REGISTRATION FORM
                     ================================================= -->

                <form method="POST">


                    <!-- EMAIL -->

                    <div class="form-group">

                        <label for="email">
                            📧 Email
                        </label>


                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            placeholder="Enter your email"
                            value="<?= htmlspecialchars(
                                $_POST['email']
                                ?? ''
                            ) ?>"
                            required
                        >

                    </div>


                    <!-- PASSWORD -->

                    <div class="form-group">

                        <label for="password">
                            🔒 Password
                        </label>


                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Create a password"
                            minlength="6"
                            required
                        >

                    </div>


                    <!-- CONFIRM PASSWORD -->

                    <div class="form-group">

                        <label for="confirm_password">
                            🔐 Confirm Password
                        </label>


                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            class="form-control"
                            placeholder="Confirm your password"
                            minlength="6"
                            required
                        >

                    </div>


                    <!-- SUBMIT -->

                    <button
                        type="submit"
                        class="btn btn-primary btn-full"
                    >
                        Create Account →
                    </button>

                </form>


                <!-- =================================================
                     LOGIN LINK
                     ================================================= -->

                <p class="auth-footer">

                    Already have an account?

                    <a href="login.php">
                        Sign In
                    </a>

                </p>


            </div>

        </div>

    </div>

</section>


<!-- =====================================================
     EXISTING FOOTER
     ===================================================== -->

<?php include 'footer.php'; ?>


<!-- =====================================================
     JAVASCRIPT
     ===================================================== -->

<script src="js/script.js"></script>


</body>

</html>
