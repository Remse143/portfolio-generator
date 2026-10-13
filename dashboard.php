
<?php

session_start();

if (!isset($_SESSION['access_token'])) {

    header("Location: login.php");

    exit;
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

    <title>Dashboard - PortfolioGen</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <!-- EXISTING FOOTER DESIGN -->
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
     NAVIGATION
     ===================================================== -->

<nav class="navbar">

    <div class="container navbar-content">

        <a
            href="dashboard.php"
            class="logo"
        >
            PortfolioGen
        </a>


        <div class="nav-links">

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="create.php">
                Create Portfolio
            </a>

            <a href="manage.php">
                My Portfolios
            </a>

            <a href="templates.php">
                Templates
            </a>

            <a href="logout.php">
                Logout
            </a>


            <!-- Theme Button -->

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
     DASHBOARD HERO
     ===================================================== -->

<section class="hero dashboard-hero">

    <div class="container">

        <span class="hero-badge">
            ✨ Your Portfolio Dashboard
        </span>

        <h1>
            Build Your
            <span>Professional Portfolio</span>
        </h1>

        <p>
            Create a professional online portfolio that
            showcases your skills, education, projects,
            experience, and achievements in one place.
        </p>

        <div class="hero-buttons">

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

        </div>

    </div>

</section>


<!-- =====================================================
     PORTFOLIO MANAGEMENT
     ===================================================== -->

<section class="section">

    <div class="container">

        <div class="section-header">

            <span class="hero-badge">
                Portfolio Management
            </span>

            <h2>
                Everything You Need
            </h2>

            <p>
                Build, customize, and manage your professional
                portfolio using simple tools designed for you.
            </p>

        </div>


        <div class="card-grid">


            <!-- =================================================
                 CREATE PORTFOLIO
                 ================================================= -->

            <div class="card">

                <div class="card-icon">
                    ➕
                </div>

                <h3>
                    Create Portfolio
                </h3>

                <p>
                    Start building your portfolio by entering
                    your personal information, education,
                    skills, projects, work experience, and
                    other professional details.
                </p>

                <a
                    href="create.php"
                    class="btn btn-primary"
                >
                    Create Portfolio
                </a>

            </div>


            <!-- =================================================
                 CHOOSE TEMPLATE
                 ================================================= -->

            <div class="card">

                <div class="card-icon">
                    🎨
                </div>

                <h3>
                    Choose Template
                </h3>

                <p>
                    Select a portfolio design that matches
                    your style. Choose from Simple, Modern,
                    and Creative templates for your portfolio.
                </p>

                <a
                    href="templates.php"
                    class="btn btn-primary"
                >
                    View Templates
                </a>

            </div>


            <!-- =================================================
                 MANAGE PORTFOLIOS
                 ================================================= -->

            <div class="card">

                <div class="card-icon">
                    📁
                </div>

                <h3>
                    Manage Portfolios
                </h3>

                <p>
                    View your saved portfolios, update your
                    information, edit your portfolio details,
                    or delete portfolios that you no longer need.
                </p>

                <a
                    href="manage.php"
                    class="btn btn-primary"
                >
                    Manage Portfolios
                </a>

            </div>


        </div>

    </div>

</section>


<!-- =====================================================
     HOW IT WORKS
     ===================================================== -->

<section class="section section-alt">

    <div class="container">

        <div class="section-header">

            <span class="hero-badge">
                How It Works
            </span>

            <h2>
                Create Your Portfolio in 3 Easy Steps
            </h2>

            <p>
                Follow these simple steps to create your
                professional online portfolio.
            </p>

        </div>


        <div class="card-grid">


            <!-- STEP 1 -->

            <div class="card">

                <div class="card-icon">
                    1
                </div>

                <h3>
                    Enter Your Information
                </h3>

                <p>
                    Add your name, profile picture, contact
                    information, education, skills, projects,
                    and professional experience.
                </p>

            </div>


            <!-- STEP 2 -->

            <div class="card">

                <div class="card-icon">
                    2
                </div>

                <h3>
                    Choose a Template
                </h3>

                <p>
                    Select from three different portfolio
                    designs and choose the layout that best
                    represents your personal style.
                </p>

            </div>


            <!-- STEP 3 -->

            <div class="card">

                <div class="card-icon">
                    3
                </div>

                <h3>
                    Generate Your Portfolio
                </h3>

                <p>
                    Preview your information in your selected
                    template and manage your portfolio anytime
                    from your dashboard.
                </p>

            </div>


        </div>

    </div>

</section>


<!-- =====================================================
     PORTFOLIO BENEFITS
     ===================================================== -->

<section class="section">

    <div class="container">

        <div class="section-header">

            <span class="hero-badge">
                Why PortfolioGen?
            </span>

            <h2>
                Build Your Online Presence
            </h2>

            <p>
                PortfolioGen helps you present your skills,
                achievements, and experience in a professional
                and organized way.
            </p>

        </div>


        <div class="card-grid">


            <!-- BENEFIT 1 -->

            <div class="card">

                <div class="card-icon">
                    💼
                </div>

                <h3>
                    Professional Presentation
                </h3>

                <p>
                    Present your skills, projects, education,
                    and experience using a clean and organized
                    portfolio.
                </p>

            </div>


            <!-- BENEFIT 2 -->

            <div class="card">

                <div class="card-icon">
                    ☁️
                </div>

                <h3>
                    Online Storage
                </h3>

                <p>
                    Your portfolio information is stored
                    online so you can manage your portfolio
                    whenever you need it.
                </p>

            </div>


            <!-- BENEFIT 3 -->

            <div class="card">

                <div class="card-icon">
                    📱
                </div>

                <h3>
                    Responsive Design
                </h3>

                <p>
                    Your portfolio is designed to work across
                    computers, tablets, and mobile devices.
                </p>

            </div>


        </div>

    </div>

</section>


<!-- =====================================================
     FINAL CALL TO ACTION
     ===================================================== -->

<section class="section">

    <div class="container">

        <div class="cta">

            <span class="hero-badge">
                Ready to Start?
            </span>

            <h2>
                Create Your Professional Portfolio Today
            </h2>

            <p>
                Showcase your skills, projects, education,
                and experience with PortfolioGen.
            </p>

            <a
                href="create.php"
                class="btn btn-primary"
            >
                Start Creating
            </a>

        </div>

    </div>

</section>


<!-- =====================================================
     FOOTER
     ===================================================== -->

<?php include 'footer.php'; ?>


<!-- =====================================================
     JAVASCRIPT
     ===================================================== -->

<script src="js/script.js"></script>


</body>

</html>