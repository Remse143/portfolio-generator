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


// =====================================================
// GET PORTFOLIO ID
// =====================================================

$id = $_GET['id'] ?? null;


if (!$id) {

    die("Portfolio ID is missing.");

}


$userId = $_SESSION['user_id'];


// =====================================================
// GET PORTFOLIO FROM SUPABASE
// =====================================================

$url = $supabaseUrl
    . "/rest/v1/portfolios"
    . "?id=eq."
    . urlencode($id)
    . "&user_id=eq."
    . urlencode($userId)
    . "&select=*";


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


$results = json_decode(
    $response,
    true
);


// =====================================================
// CHECK PORTFOLIO
// =====================================================

if (
    $httpCode < 200 ||
    $httpCode >= 300
) {

    die("Unable to load portfolio.");

}


if (empty($results)) {

    die("Portfolio not found.");

}


$portfolio = $results[0];


// =====================================================
// UPDATE PORTFOLIO
// =====================================================

if (
    $_SERVER['REQUEST_METHOD']
    === 'POST'
) {


    $data = [

        "full_name" =>
            $_POST['full_name'] ?? '',

        "email" =>
            $_POST['email'] ?? '',

        "contact_number" =>
            $_POST['contact_number'] ?? '',

        "address" =>
            $_POST['address'] ?? '',

        "about_me" =>
            $_POST['about_me'] ?? '',

        "education" =>
            $_POST['education'] ?? '',

        "skills" =>
            $_POST['skills'] ?? '',

        "projects" =>
            $_POST['projects'] ?? '',

        "work_experience" =>
            $_POST['work_experience'] ?? '',

        "social_links" =>
            $_POST['social_links'] ?? ''

    ];


    $updateUrl = $supabaseUrl
        . "/rest/v1/portfolios"
        . "?id=eq."
        . urlencode($id)
        . "&user_id=eq."
        . urlencode($userId);


    $ch = curl_init($updateUrl);


    curl_setopt(
        $ch,
        CURLOPT_RETURNTRANSFER,
        true
    );


    curl_setopt(
        $ch,
        CURLOPT_CUSTOMREQUEST,
        "PATCH"
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

            "apikey: " .
                $supabaseKey,

            "Authorization: Bearer "
                . $_SESSION['access_token'],

            "Prefer: return=representation"

        ]
    );


    $updateResponse =
        curl_exec($ch);


    $updateHttpCode =
        curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );


    curl_close($ch);


    if (
        $updateHttpCode >= 200 &&
        $updateHttpCode < 300
    ) {

        header(
            "Location: preview.php?id="
            . urlencode($id)
        );

        exit;

    }


    $errorMessage =
        $updateResponse;

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
        Edit Portfolio - PortfolioGen
    </title>


    <!-- MAIN WEBSITE DESIGN -->

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
     EDIT PORTFOLIO
     ===================================================== -->

<section class="section">

    <div class="container">


        <!-- PAGE HEADER -->

        <div class="section-header">

            <span class="hero-badge">
                ✏️ Portfolio Editor
            </span>


            <h1>
                Edit Your Portfolio
            </h1>


            <p>
                Update your information and save
                your changes.
            </p>

        </div>


        <!-- =================================================
             ERROR MESSAGE
             ================================================= -->

        <?php if (
            isset($errorMessage)
        ): ?>

            <div class="card">

                <h3>
                    ⚠️ Update Error
                </h3>


                <pre><?= htmlspecialchars(
                    $errorMessage
                ) ?></pre>

            </div>

        <?php endif; ?>


        <!-- =================================================
             EDIT FORM
             ================================================= -->

        <div class="form-wrapper">

            <form method="POST">


                <!-- =========================================
                     PERSONAL INFORMATION
                     ========================================= -->

                <div class="form-section">

                    <h2>
                        👤 Personal Information
                    </h2>


                    <div class="form-group">

                        <label for="full_name">
                            Full Name
                        </label>


                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $portfolio['full_name']
                                ?? ''
                            ) ?>"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="email">
                            Email
                        </label>


                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $portfolio['email']
                                ?? ''
                            ) ?>"
                        >

                    </div>


                    <div class="form-group">

                        <label for="contact_number">
                            Contact Number
                        </label>


                        <input
                            type="text"
                            id="contact_number"
                            name="contact_number"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $portfolio[
                                    'contact_number'
                                ] ?? ''
                            ) ?>"
                        >

                    </div>


                    <div class="form-group">

                        <label for="address">
                            Address
                        </label>


                        <input
                            type="text"
                            id="address"
                            name="address"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $portfolio['address']
                                ?? ''
                            ) ?>"
                        >

                    </div>

                </div>


                <!-- =========================================
                     ABOUT ME
                     ========================================= -->

                <div class="form-section">

                    <h2>
                        📝 About Me
                    </h2>


                    <div class="form-group">

                        <label for="about_me">
                            Tell something about yourself
                        </label>


                        <textarea
                            id="about_me"
                            name="about_me"
                            class="form-control"
                            rows="6"
                        ><?= htmlspecialchars(
                            $portfolio['about_me']
                            ?? ''
                        ) ?></textarea>

                    </div>

                </div>


                <!-- =========================================
                     EDUCATION
                     ========================================= -->

                <div class="form-section">

                    <h2>
                        🎓 Educational Background
                    </h2>


                    <div class="form-group">

                        <label for="education">
                            Education
                        </label>


                        <textarea
                            id="education"
                            name="education"
                            class="form-control"
                            rows="6"
                        ><?= htmlspecialchars(
                            $portfolio['education']
                            ?? ''
                        ) ?></textarea>

                    </div>

                </div>


                <!-- =========================================
                     SKILLS
                     ========================================= -->

                <div class="form-section">

                    <h2>
                        💻 Skills
                    </h2>


                    <div class="form-group">

                        <label for="skills">
                            Your Skills
                        </label>


                        <textarea
                            id="skills"
                            name="skills"
                            class="form-control"
                            rows="6"
                        ><?= htmlspecialchars(
                            $portfolio['skills']
                            ?? ''
                        ) ?></textarea>

                    </div>

                </div>


                <!-- =========================================
                     PROJECTS
                     ========================================= -->

                <div class="form-section">

                    <h2>
                        🚀 Projects
                    </h2>


                    <div class="form-group">

                        <label for="projects">
                            Projects
                        </label>


                        <textarea
                            id="projects"
                            name="projects"
                            class="form-control"
                            rows="6"
                        ><?= htmlspecialchars(
                            $portfolio['projects']
                            ?? ''
                        ) ?></textarea>

                    </div>

                </div>


                <!-- =========================================
                     WORK EXPERIENCE
                     ========================================= -->

                <div class="form-section">

                    <h2>
                        💼 Work Experience
                    </h2>


                    <div class="form-group">

                        <label for="work_experience">
                            Work Experience
                        </label>


                        <textarea
                            id="work_experience"
                            name="work_experience"
                            class="form-control"
                            rows="6"
                        ><?= htmlspecialchars(
                            $portfolio[
                                'work_experience'
                            ] ?? ''
                        ) ?></textarea>

                    </div>

                </div>


                <!-- =========================================
                     SOCIAL LINKS
                     ========================================= -->

                <div class="form-section">

                    <h2>
                        🔗 Social Media / Website Links
                    </h2>


                    <div class="form-group">

                        <label for="social_links">
                            Links
                        </label>


                        <textarea
                            id="social_links"
                            name="social_links"
                            class="form-control"
                            rows="6"
                        ><?= htmlspecialchars(
                            $portfolio[
                                'social_links'
                            ] ?? ''
                        ) ?></textarea>

                    </div>

                </div>


                <!-- =========================================
                     FORM BUTTONS
                     ========================================= -->

                <div class="form-actions">


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        💾 Save Changes
                    </button>


                    <a
                        href="preview.php?id=<?= urlencode(
                            $id
                        ) ?>"
                        class="btn btn-secondary"
                    >
                        ❌ Cancel
                    </a>


                    <a
                        href="manage.php"
                        class="btn btn-secondary"
                    >
                        📁 My Portfolios
                    </a>

                </div>


            </form>

        </div>

    </div>

</section>


<!-- =====================================================
     EXISTING ANIMATED FOOTER
     ===================================================== -->

<?php include 'footer.php'; ?>


<!-- =====================================================
     JAVASCRIPT
     ===================================================== -->

<script src="js/script.js"></script>


</body>

</html>
