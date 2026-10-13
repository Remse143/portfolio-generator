
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>PortfolioGen - Online Portfolio Generator</title>

    <!-- Main Website CSS -->
    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <!-- Footer CSS - KEEP YOUR EXISTING FOOTER DESIGN -->
    <link
        rel="stylesheet"
        href="css/footer.css"
    >

</head>

<body>


<!-- =====================================================
     AKATSUKI-INSPIRED ANIMATED BACKGROUND
     ===================================================== -->

<div class="animated-background">

    <!-- Moving glowing orbs -->
    <div class="background-orb orb-one"></div>
    <div class="background-orb orb-two"></div>
    <div class="background-orb orb-three"></div>
    <div class="background-orb orb-four"></div>

    <!-- Background atmosphere -->
    <div class="background-grid"></div>

    <!-- Floating crimson particles -->
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
     NAVIGATION
     ===================================================== -->

<nav class="navbar">

    <div class="container navbar-content">

        <!-- Logo -->

        <a
            href="index.php"
            class="logo"
        >
            PortfolioGen
        </a>


        <!-- Navigation Links -->

        <div class="nav-links">

            <a href="index.php">
                Home
            </a>


            <?php if (isset($_SESSION['access_token'])): ?>

                <a href="dashboard.php">
                    Dashboard
                </a>

                <a href="create.php">
                    Create Portfolio
                </a>

                <a href="manage.php">
                    My Portfolios
                </a>

                <a href="logout.php">
                    Logout
                </a>


            <?php else: ?>

                <a href="login.php">
                    Sign In
                </a>

                <a
                    href="register.php"
                    class="btn btn-primary"
                >
                    Sign Up
                </a>

            <?php endif; ?>


            <!-- Theme Toggle -->

            <button
                type="button"
                id="themeToggle"
                class="theme-toggle"
                onclick="toggleTheme()"
            >
                🌙
            </button>

        </div>

    </div>

</nav>


<!-- =====================================================
     HERO SECTION
     ===================================================== -->

<section class="hero">

    <div class="container">

        <span class="hero-badge">
            ✨ Online Portfolio Generator
        </span>


        <h1>

            Create Your Professional

            <span>
                Portfolio
            </span>

        </h1>


        <p>

            Build a beautiful online portfolio in minutes.
            Enter your information, choose a template,
            and generate your portfolio.

        </p>


        <!-- Hero Buttons -->

        <div class="hero-buttons">


            <?php if (isset($_SESSION['access_token'])): ?>

                <a
                    href="create.php"
                    class="btn btn-primary"
                >
                    Create Portfolio
                </a>


                <a
                    href="manage.php"
                    class="btn btn-secondary"
                >
                    My Portfolios
                </a>


            <?php else: ?>

                <a
                    href="register.php"
                    class="btn btn-primary"
                >
                    Get Started
                </a>


                <a
                    href="login.php"
                    class="btn btn-secondary"
                >
                    Sign In
                </a>

            <?php endif; ?>


        </div>

    </div>

</section>


<!-- =====================================================
     FEATURES SECTION
     ===================================================== -->

<section class="section">

    <div class="container">


        <!-- Section Header -->

        <div class="section-header">

            <span class="hero-badge">
                Features
            </span>


            <h2>
                Everything You Need
            </h2>


            <p>
                Create and manage your professional
                portfolio easily.
            </p>

        </div>


        <!-- Feature Cards -->

        <div class="card-grid">


            <!-- Feature 1 -->

            <div class="card">

                <div class="card-icon">
                    📝
                </div>


                <h3>
                    Easy Information Form
                </h3>


                <p>
                    Enter your personal information,
                    education, skills, projects, and experience.
                </p>

            </div>


            <!-- Feature 2 -->

            <div class="card">

                <div class="card-icon">
                    🎨
                </div>


                <h3>
                    Three Templates
                </h3>


                <p>
                    Choose between Simple, Modern,
                    and Creative portfolio designs.
                </p>

            </div>


            <!-- Feature 3 -->

            <div class="card">

                <div class="card-icon">
                    ☁️
                </div>


                <h3>
                    Online Database
                </h3>


                <p>
                    Your portfolio information is stored
                    safely in an online database.
                </p>

            </div>


            <!-- Feature 4 -->

            <div class="card">

                <div class="card-icon">
                    📱
                </div>


                <h3>
                    Responsive Design
                </h3>


                <p>
                    Your portfolio works on computers,
                    tablets, and mobile devices.
                </p>

            </div>


        </div>

    </div>

</section>


<!-- =====================================================
     HOW IT WORKS SECTION
     ===================================================== -->

<section class="section section-alt">

    <div class="container">


        <!-- Section Header -->

        <div class="section-header">

            <span class="hero-badge">
                How It Works
            </span>


            <h2>
                Create Your Portfolio in 3 Steps
            </h2>

        </div>


        <!-- Steps -->

        <div class="card-grid">


            <!-- Step 1 -->

            <div class="card">

                <div class="card-icon">
                    1
                </div>


                <h3>
                    Enter Information
                </h3>


                <p>
                    Add your personal and professional
                    information.
                </p>

            </div>


            <!-- Step 2 -->

            <div class="card">

                <div class="card-icon">
                    2
                </div>


                <h3>
                    Choose Template
                </h3>


                <p>
                    Select one of the three available
                    portfolio designs.
                </p>

            </div>


            <!-- Step 3 -->

            <div class="card">

                <div class="card-icon">
                    3
                </div>


                <h3>
                    Generate Portfolio
                </h3>


                <p>
                    Preview your completed portfolio
                    and manage it anytime.
                </p>

            </div>


        </div>

    </div>

</section>


<!-- =====================================================
     CALL TO ACTION
     ===================================================== -->

<section class="section">

    <div class="container">


        <div class="cta">


            <h2>
                Ready to Build Your Portfolio?
            </h2>


            <p>
                Start creating your professional portfolio today.
            </p>


            <?php if (isset($_SESSION['access_token'])): ?>

                <a
                    href="create.php"
                    class="btn btn-primary"
                >
                    Create Portfolio
                </a>


            <?php else: ?>

                <a
                    href="register.php"
                    class="btn btn-primary"
                >
                    Get Started
                </a>

            <?php endif; ?>


        </div>

    </div>

</section>


<!-- =====================================================
     FOOTER
     
     IMPORTANT:
     Your existing footer.php and footer.css
     are NOT changed.
     ===================================================== -->

<?php include 'footer.php'; ?>


<!-- =====================================================
     JAVASCRIPT
     ===================================================== -->

<script src="js/script.js"></script>


</body>

</html>