
<?php

session_start();


// =====================================================
// CHECK LOGIN
// =====================================================

if (!isset($_SESSION['access_token'])) {

    header("Location: login.php");

    exit;
}


require_once "config/database.php";

$userId = $_SESSION['user_id'];


// =====================================================
// GET USER'S PORTFOLIOS
// =====================================================

$url = $supabaseUrl
    . "/rest/v1/portfolios?user_id=eq."
    . urlencode($userId)
    . "&select=*"
    . "&order=created_at.desc";


$ch = curl_init($url);


curl_setopt(
    $ch,
    CURLOPT_RETURNTRANSFER,
    true
);


curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    [
        "Content-Type: application/json",
        "apikey: " . $supabaseKey,
        "Authorization: Bearer "
            . $_SESSION['access_token']
    ]
);


$response = curl_exec($ch);


$httpCode = curl_getinfo(
    $ch,
    CURLINFO_HTTP_CODE
);


curl_close($ch);


$portfolios = json_decode(
    $response,
    true
);


if (!is_array($portfolios)) {

    $portfolios = [];

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
        Choose Template - PortfolioGen
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


    <style>

        /* =================================================
           TEMPLATE PAGE
           ================================================= */

        .template-hero {

            text-align: center;

            max-width: 850px;

            margin: 0 auto 50px;

        }


        .template-hero h1 {

            font-size: clamp(
                2.2rem,
                5vw,
                4rem
            );

            margin: 18px 0 15px;

        }


        .template-hero p {

            max-width: 700px;

            margin: 0 auto;

            opacity: .8;

            font-size: 1.05rem;

            line-height: 1.8;

        }


        /* =================================================
           TEMPLATE SELECTOR
           ================================================= */

        .portfolio-selector-box {

            max-width: 850px;

            margin: 0 auto 55px;

            padding: 28px;

            border: 1px solid
                rgba(255, 255, 255, .1);

            border-radius: 20px;

            background:
                rgba(10, 10, 16, .75);

            backdrop-filter: blur(15px);

            box-shadow:
                0 20px 60px
                rgba(0, 0, 0, .35);

        }


        .portfolio-selector-box label {

            display: block;

            margin-bottom: 10px;

            font-weight: 700;

        }


        .portfolio-selector-box select {

            width: 100%;

            padding: 15px 18px;

            border-radius: 12px;

            border: 1px solid
                rgba(255, 255, 255, .15);

            background: rgba(255, 255, 255, .06);

            color: inherit;

            font-size: 1rem;

        }


        /* =================================================
           TEMPLATE GRID
           ================================================= */

        .template-grid {

            display: grid;

            grid-template-columns:
                repeat(
                    3,
                    minmax(0, 1fr)
                );

            gap: 28px;

            align-items: stretch;

        }


        /* =================================================
           TEMPLATE CARD
           ================================================= */

        .template-card {

            position: relative;

            overflow: hidden;

            display: flex;

            flex-direction: column;

            border-radius: 24px;

            border: 1px solid
                rgba(255, 255, 255, .1);

            background:
                rgba(10, 10, 15, .82);

            backdrop-filter: blur(18px);

            box-shadow:
                0 20px 60px
                rgba(0, 0, 0, .35);

            transition:
                transform .35s ease,
                border-color .35s ease,
                box-shadow .35s ease;

        }


        .template-card:hover {

            transform:
                translateY(-10px);

            border-color:
                rgba(225, 29, 72, .65);

            box-shadow:
                0 30px 80px
                rgba(225, 29, 72, .16);

        }


        /* =================================================
           RECOMMENDED BADGE
           ================================================= */

        .recommended-badge {

            position: absolute;

            top: 18px;

            right: 18px;

            z-index: 5;

            padding: 7px 12px;

            border-radius: 999px;

            background:
                linear-gradient(
                    135deg,
                    #e11d48,
                    #7c3aed
                );

            color: white;

            font-size: .75rem;

            font-weight: 800;

            letter-spacing: .5px;

        }


        /* =================================================
           TEMPLATE PREVIEW
           ================================================= */

        .template-preview {

            min-height: 310px;

            position: relative;

            overflow: hidden;

            padding: 25px;

            display: flex;

            flex-direction: column;

        }


        .preview-window {

            position: absolute;

            top: 14px;

            left: 18px;

            right: 18px;

            display: flex;

            gap: 6px;

        }


        .window-dot {

            width: 8px;

            height: 8px;

            border-radius: 50%;

            background: rgba(
                255,
                255,
                255,
                .35
            );

        }


        .preview-line {

            height: 8px;

            border-radius: 99px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    .18
                );

            margin: 8px 0;

        }


        .preview-line.large {

            width: 80%;

            height: 13px;

        }


        .preview-line.medium {

            width: 55%;

        }


        .preview-line.short {

            width: 35%;

        }


        .preview-line.tiny {

            width: 25%;

        }


        .preview-avatar {

            width: 64px;

            height: 64px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background:
                rgba(
                    255,
                    255,
                    255,
                    .12
                );

            border: 2px solid
                rgba(
                    255,
                    255,
                    255,
                    .18
                );

            font-size: 1.6rem;

            flex-shrink: 0;

        }


        /* =================================================
           SIMPLE PREVIEW
           ================================================= */

        .simple-preview {

            background:
                linear-gradient(
                    145deg,
                    #17171d,
                    #09090d
                );

        }


        .simple-header {

            margin-top: 35px;

            display: flex;

            align-items: center;

            gap: 18px;

        }


        .simple-content {

            margin-top: 30px;

            padding: 20px;

            border-radius: 14px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    .04
                );

        }


        .mini-section {

            margin-top: 20px;

        }


        .mini-grid {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 8px;

            margin-top: 15px;

        }


        .mini-box {

            height: 45px;

            border-radius: 8px;

            background:
                rgba(
                    225,
                    29,
                    72,
                    .16
                );

            border: 1px solid
                rgba(
                    225,
                    29,
                    72,
                    .18
                );

        }


        /* =================================================
           MODERN PREVIEW
           ================================================= */

        .modern-preview {

            display: grid;

            grid-template-columns: 85px 1fr;

            gap: 18px;

            background:
                linear-gradient(
                    145deg,
                    #160b18,
                    #08070c
                );

        }


        .modern-sidebar {

            margin-top: 20px;

            border-radius: 15px;

            padding: 15px 10px;

            background:
                linear-gradient(
                    180deg,
                    rgba(
                        225,
                        29,
                        72,
                        .22
                    ),
                    rgba(
                        124,
                        58,
                        237,
                        .12
                    )
                );

            display: flex;

            align-items: center;

            flex-direction: column;

            gap: 8px;

        }


        .modern-sidebar .preview-avatar {

            width: 45px;

            height: 45px;

            font-size: 1rem;

            margin-bottom: 15px;

        }


        .modern-main {

            margin-top: 30px;

        }


        .modern-box-row {

            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 10px;

            margin-top: 20px;

        }


        .modern-box {

            height: 85px;

            border-radius: 12px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    .06
                );

            border: 1px solid
                rgba(
                    255,
                    255,
                    255,
                    .08
                );

        }


        .modern-box.wide {

            margin-top: 10px;

            height: 65px;

        }


        /* =================================================
           CREATIVE PREVIEW
           ================================================= */

        .creative-preview {

            background:
                radial-gradient(
                    circle at 20% 20%,
                    rgba(
                        225,
                        29,
                        72,
                        .35
                    ),
                    transparent 35%
                ),
                radial-gradient(
                    circle at 90% 80%,
                    rgba(
                        124,
                        58,
                        237,
                        .35
                    ),
                    transparent 35%
                ),
                #09080d;

        }


        .creative-avatar {

            width: 90px;

            height: 90px;

            margin: 35px auto 15px;

            border-radius: 28px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 2rem;

            background:
                linear-gradient(
                    135deg,
                    #e11d48,
                    #7c3aed
                );

            transform:
                rotate(-5deg);

        }


        .creative-title {

            text-align: center;

        }


        .creative-card-row {

            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 10px;

            margin-top: 25px;

        }


        .creative-mini-card {

            min-height: 70px;

            padding: 12px;

            border-radius: 14px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    .07
                );

            border: 1px solid
                rgba(
                    255,
                    255,
                    255,
                    .08
                );

        }


        /* =================================================
           TEMPLATE INFORMATION
           ================================================= */

        .template-info {

            padding: 28px;

            flex: 1;

            display: flex;

            flex-direction: column;

        }


        .template-number {

            font-size: .75rem;

            font-weight: 800;

            letter-spacing: 1.5px;

            opacity: .55;

            margin-bottom: 8px;

        }


        .template-info h2 {

            margin: 0 0 10px;

            font-size: 1.7rem;

        }


        .template-info > p {

            line-height: 1.7;

            opacity: .72;

            margin-bottom: 20px;

        }


        .feature-list {

            list-style: none;

            padding: 0;

            margin: 0 0 25px;

            display: grid;

            gap: 10px;

        }


        .feature-list li {

            display: flex;

            gap: 9px;

            align-items: center;

            font-size: .9rem;

            opacity: .82;

        }


        .feature-list li span {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            width: 20px;

            height: 20px;

            border-radius: 50%;

            background:
                rgba(
                    225,
                    29,
                    72,
                    .15
                );

            color: #fb7185;

            font-size: .7rem;

        }


        .template-info .btn {

            width: 100%;

            margin-top: auto;

        }


        /* =================================================
           EMPTY STATE
           ================================================= */

        .empty-template-state {

            max-width: 650px;

            margin: 30px auto;

            text-align: center;

            padding: 55px 30px;

            border-radius: 24px;

            border: 1px solid
                rgba(
                    255,
                    255,
                    255,
                    .1
                );

            background:
                rgba(
                    10,
                    10,
                    15,
                    .78
                );

            backdrop-filter: blur(15px);

        }


        .empty-icon {

            font-size: 4rem;

            margin-bottom: 15px;

        }


        .empty-template-state h2 {

            margin-bottom: 10px;

        }


        .empty-template-state p {

            opacity: .7;

            line-height: 1.7;

            margin-bottom: 25px;

        }


        /* =================================================
           TEMPLATE BENEFITS
           ================================================= */

        .template-benefits {

            margin-top: 75px;

            text-align: center;

        }


        .template-benefits h2 {

            font-size: 2rem;

            margin-bottom: 12px;

        }


        .template-benefits > p {

            opacity: .7;

            margin-bottom: 35px;

        }


        .benefit-grid {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;

        }


        .benefit-card {

            padding: 28px;

            border-radius: 18px;

            border: 1px solid
                rgba(
                    255,
                    255,
                    255,
                    .08
                );

            background:
                rgba(
                    10,
                    10,
                    15,
                    .65
                );

        }


        .benefit-icon {

            font-size: 2rem;

            margin-bottom: 12px;

        }


        .benefit-card h3 {

            margin-bottom: 8px;

        }


        .benefit-card p {

            opacity: .65;

            line-height: 1.6;

            margin: 0;

        }


        /* =================================================
           RESPONSIVE
           ================================================= */

        @media (
            max-width: 1000px
        ) {

            .template-grid {

                grid-template-columns:
                    1fr 1fr;

            }

            .benefit-grid {

                grid-template-columns:
                    1fr;

            }

        }


        @media (
            max-width: 700px
        ) {

            .template-grid {

                grid-template-columns:
                    1fr;

            }


            .template-preview {

                min-height: 280px;

            }


            .template-info {

                padding: 22px;

            }

        }

    </style>

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
     MAIN TEMPLATE PAGE
     ===================================================== -->

<section class="section">

    <div class="container">


        <!-- =================================================
             HERO
             ================================================= -->

        <div class="template-hero">

            <span class="hero-badge">
                🎨 Design Studio
            </span>


            <h1>
                Choose Your Perfect Template
            </h1>


            <p>
                Your portfolio represents your skills,
                experience, and personality. Choose a
                design that matches the way you want
                to present yourself professionally.
            </p>

        </div>


        <?php if (empty($portfolios)): ?>


            <!-- =================================================
                 NO PORTFOLIO
                 ================================================= -->

            <div class="empty-template-state">

                <div class="empty-icon">
                    🎨
                </div>


                <h2>
                    Create Your Portfolio First
                </h2>


                <p>
                    You currently don't have a portfolio.
                    Create one first, then you can choose
                    from our available portfolio designs.
                </p>


                <a
                    href="create.php"
                    class="btn btn-primary"
                >
                    ➕ Create Portfolio
                </a>

            </div>


        <?php else: ?>


            <!-- =================================================
                 PORTFOLIO SELECTOR
                 ================================================= -->

            <div class="portfolio-selector-box">

                <label for="portfolio_id">
                    📁 Select the portfolio you want to customize
                </label>


                <select
                    id="portfolio_id"
                    class="form-control"
                >

                    <?php foreach (
                        $portfolios
                        as $portfolio
                    ): ?>

                        <option
                            value="<?= htmlspecialchars(
                                $portfolio['id']
                            ) ?>"
                        >

                            <?= htmlspecialchars(
                                $portfolio['full_name']
                                ?? 'My Portfolio'
                            ) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- =================================================
                 TEMPLATE CARDS
                 ================================================= -->

            <div class="template-grid">


                <!-- =================================================
                     SIMPLE
                     ================================================= -->

                <article class="template-card">


                    <div class="template-preview simple-preview">

                        <div class="preview-window">

                            <span class="window-dot"></span>
                            <span class="window-dot"></span>
                            <span class="window-dot"></span>

                        </div>


                        <div class="simple-header">

                            <div class="preview-avatar">
                                👤
                            </div>


                            <div>

                                <div class="preview-line large"></div>

                                <div class="preview-line medium"></div>

                                <div class="preview-line short"></div>

                            </div>

                        </div>


                        <div class="simple-content">

                            <div class="preview-line medium"></div>

                            <div class="preview-line"></div>

                            <div class="preview-line"></div>

                            <div class="preview-line short"></div>


                            <div class="mini-section">

                                <div class="mini-grid">

                                    <div class="mini-box"></div>

                                    <div class="mini-box"></div>

                                    <div class="mini-box"></div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="template-info">

                        <div class="template-number">
                            TEMPLATE 01
                        </div>


                        <h2>
                            Simple
                        </h2>


                        <p>
                            Clean, professional, and easy
                            to read. A great choice for students,
                            fresh graduates, and job seekers.
                        </p>


                        <ul class="feature-list">

                            <li>
                                <span>✓</span>
                                Clean professional layout
                            </li>

                            <li>
                                <span>✓</span>
                                Easy-to-read sections
                            </li>

                            <li>
                                <span>✓</span>
                                Perfect for applications
                            </li>

                            <li>
                                <span>✓</span>
                                Mobile-friendly design
                            </li>

                        </ul>


                        <button
                            type="button"
                            class="btn btn-primary"
                            onclick="chooseTemplate('simple')"
                        >
                            Use Simple Template →
                        </button>

                    </div>

                </article>


                <!-- =================================================
                     MODERN
                     ================================================= -->

                <article class="template-card">


                    <div class="recommended-badge">
                        ⭐ RECOMMENDED
                    </div>


                    <div class="template-preview modern-preview">

                        <div class="modern-sidebar">

                            <div class="preview-avatar">
                                👤
                            </div>


                            <div class="preview-line small"></div>

                            <div class="preview-line small"></div>

                            <div class="preview-line small"></div>

                            <div class="preview-line tiny"></div>

                        </div>


                        <div class="modern-main">

                            <div class="preview-line large"></div>

                            <div class="preview-line medium"></div>


                            <div class="modern-box-row">

                                <div class="modern-box"></div>

                                <div class="modern-box"></div>

                            </div>


                            <div class="modern-box wide"></div>

                            <div class="modern-box wide"></div>

                        </div>

                    </div>


                    <div class="template-info">

                        <div class="template-number">
                            TEMPLATE 02
                        </div>


                        <h2>
                            Modern
                        </h2>


                        <p>
                            A polished card-based portfolio
                            designed to make your skills and
                            projects stand out.
                        </p>


                        <ul class="feature-list">

                            <li>
                                <span>✓</span>
                                Modern visual layout
                            </li>

                            <li>
                                <span>✓</span>
                                Organized information cards
                            </li>

                            <li>
                                <span>✓</span>
                                Strong visual hierarchy
                            </li>

                            <li>
                                <span>✓</span>
                                Great for IT portfolios
                            </li>

                        </ul>


                        <button
                            type="button"
                            class="btn btn-primary"
                            onclick="chooseTemplate('modern')"
                        >
                            Use Modern Template →
                        </button>

                    </div>

                </article>


                <!-- =================================================
                     CREATIVE
                     ================================================= -->

                <article class="template-card">


                    <div class="template-preview creative-preview">

                        <div class="creative-avatar">
                            👤
                        </div>


                        <div class="creative-title">

                            <div class="preview-line large"></div>

                            <div class="preview-line medium"></div>

                        </div>


                        <div class="creative-card-row">

                            <div class="creative-mini-card">

                                <div class="preview-line"></div>

                                <div class="preview-line short"></div>

                            </div>


                            <div class="creative-mini-card">

                                <div class="preview-line"></div>

                                <div class="preview-line short"></div>

                            </div>

                        </div>


                        <div class="creative-mini-card"
                             style="margin-top:10px;">

                            <div class="preview-line medium"></div>

                            <div class="preview-line"></div>

                        </div>

                    </div>


                    <div class="template-info">

                        <div class="template-number">
                            TEMPLATE 03
                        </div>


                        <h2>
                            Creative
                        </h2>


                        <p>
                            Bold, expressive, and eye-catching.
                            Designed for users who want their
                            personality to be part of their portfolio.
                        </p>


                        <ul class="feature-list">

                            <li>
                                <span>✓</span>
                                Unique visual style
                            </li>

                            <li>
                                <span>✓</span>
                                Bold colors and sections
                            </li>

                            <li>
                                <span>✓</span>
                                Creative project showcase
                            </li>

                            <li>
                                <span>✓</span>
                                Perfect for designers
                            </li>

                        </ul>


                        <button
                            type="button"
                            class="btn btn-primary"
                            onclick="chooseTemplate('creative')"
                        >
                            Use Creative Template →
                        </button>

                    </div>

                </article>


            </div>


            <!-- =================================================
                 BENEFITS
                 ================================================= -->

            <div class="template-benefits">

                <h2>
                    Built for Your Portfolio
                </h2>


                <p>
                    Every template works with the information
                    you enter in PortfolioGen.
                </p>


                <div class="benefit-grid">


                    <div class="benefit-card">

                        <div class="benefit-icon">
                            📱
                        </div>


                        <h3>
                            Responsive
                        </h3>


                        <p>
                            Your portfolio is designed to
                            work across desktop, tablet,
                            and mobile screens.
                        </p>

                    </div>


                    <div class="benefit-card">

                        <div class="benefit-icon">
                            ⚡
                        </div>


                        <h3>
                            Easy to Customize
                        </h3>


                        <p>
                            Change your information and
                            update your portfolio whenever
                            you need.
                        </p>

                    </div>


                    <div class="benefit-card">

                        <div class="benefit-icon">
                            💼
                        </div>


                        <h3>
                            Professional
                        </h3>


                        <p>
                            Present your education, skills,
                            projects, experience, and links
                            in one organized portfolio.
                        </p>

                    </div>


                </div>

            </div>


        <?php endif; ?>


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


<script>

function chooseTemplate(template) {

    const portfolioSelect =
        document.getElementById(
            "portfolio_id"
        );


    if (!portfolioSelect) {

        return;

    }


    const portfolioId =
        portfolioSelect.value;


    if (!portfolioId) {

        alert(
            "Please select a portfolio first."
        );

        return;

    }


    window.location.href =
        "select_template.php?id="
        + encodeURIComponent(
            portfolioId
        )
        + "&template="
        + encodeURIComponent(
            template
        );

}

</script>


</body>

</html>
