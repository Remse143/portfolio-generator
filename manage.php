

<?php

session_start();

// =====================================================
// LOAD SUPABASE AUTHENTICATION
// =====================================================

require_once __DIR__ . "/supabase_auth.php";

// =====================================================
// CHECK LOGIN
// =====================================================

if (
    empty($_SESSION['user_id']) ||
    empty($_SESSION['user_email']) ||
    empty($_SESSION['access_token'])
) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$userEmail = $_SESSION['user_email'];

// =====================================================
// FUNCTION TO GET USER PORTFOLIOS
// =====================================================

function getUserPortfolios(string $userId): array
{
    global $supabaseUrl, $supabaseKey;

    $url = rtrim($supabaseUrl, '/')
        . "/rest/v1/portfolios?user_id=eq."
        . urlencode($userId)
        . "&select=*";

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "apikey: " . $supabaseKey,
            "Authorization: Bearer " . $_SESSION['access_token'],
            "Content-Type: application/json",
            "Accept: application/json"
        ],
        CURLOPT_TIMEOUT => 20
    ]);

    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return [
        'response' => $response,
        'curlError' => $curlError,
        'httpCode' => $httpCode
    ];
}

// =====================================================
// FIRST REQUEST
// =====================================================

$request = getUserPortfolios($userId);

$response = $request['response'];
$curlError = $request['curlError'];
$httpCode = $request['httpCode'];

// =====================================================
// REFRESH TOKEN AND RETRY IF HTTP 401
// =====================================================

if ($response !== false && $httpCode === 401) {

    if (refreshSupabaseToken()) {

        // Retry using the new access token.
        $request = getUserPortfolios($userId);

        $response = $request['response'];
        $curlError = $request['curlError'];
        $httpCode = $request['httpCode'];

    } else {

        // Refresh failed. Sign in again.
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header("Location: login.php");
        exit;
    }
}

// =====================================================
// CHECK RESPONSE
// =====================================================

if ($response === false) {
    http_response_code(502);

    die(
        "Connection error: "
        . htmlspecialchars(
            $curlError ?: "Unable to connect to Supabase.",
            ENT_QUOTES,
            'UTF-8'
        )
    );
}

$portfolios = json_decode($response, true);

if ($httpCode < 200 || $httpCode >= 300) {
    http_response_code($httpCode);

    echo "<h1>Portfolio Loading Error</h1>";
    echo "<p>HTTP Code: "
        . htmlspecialchars((string) $httpCode, ENT_QUOTES, 'UTF-8')
        . "</p>";

    echo "<pre>"
        . htmlspecialchars($response, ENT_QUOTES, 'UTF-8')
        . "</pre>";

    exit;
}

if (!is_array($portfolios)) {
    die("Invalid response received from Supabase.");
}

// =====================================================
// GET FIRST PORTFOLIO
// =====================================================

$portfolio = null;

if (count($portfolios) > 0) {
    $portfolio = $portfolios[0];
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

    <title>My Portfolios - PortfolioGen</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">
</head>

<body>

<!-- ANIMATED BACKGROUND -->

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

<!-- NAVBAR -->

<nav class="navbar">

    <div class="navbar-container">

        <a href="dashboard.php" class="logo">
            PortfolioGen
        </a>

        <div class="nav-links">

            <a href="dashboard.php">
                Dashboard
            </a>

            <a href="manage.php">
                My Portfolio
            </a>

            <a href="templates.php">
                Templates
            </a>

            <a href="logout.php">
                Logout
            </a>

        </div>

    </div>

</nav>

<!-- MAIN -->

<main class="container">

    <div class="page-header">

        <h1>My Portfolio</h1>

        <p>
            Manage your portfolio information.
        </p>

    </div>

    <?php if ($portfolio): ?>

        <!-- PORTFOLIO CARD -->

        <div class="portfolio-card">

            <div class="portfolio-card-header">

                <div>

                    <h2>
                        <?= htmlspecialchars(
                            $portfolio['full_name'] ?? 'My Portfolio',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </h2>

                    <p>
                        <?= htmlspecialchars(
                            $portfolio['email'] ?? $userEmail,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </p>

                </div>

                <?php if (!empty($portfolio['template'])): ?>

                    <span class="template-badge">
                        <?= htmlspecialchars(
                            ucfirst($portfolio['template']),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>

                <?php endif; ?>

            </div>

            <!-- PROFILE PICTURE -->

            <?php if (!empty($portfolio['profile_picture'])): ?>

                <div style="text-align:center; margin:25px 0;">

                    <img
                        src="<?= htmlspecialchars(
                            $portfolio['profile_picture'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        alt="Profile Picture"
                        style="
                            width:140px;
                            height:140px;
                            object-fit:cover;
                            border-radius:50%;
                            border:3px solid #e11d48;
                        "
                    >

                </div>

            <?php endif; ?>

            <!-- ABOUT -->

            <?php if (!empty($portfolio['about_me'])): ?>

                <div class="portfolio-section">

                    <h3>About Me</h3>

                    <p>
                        <?= nl2br(
                            htmlspecialchars(
                                $portfolio['about_me'],
                                ENT_QUOTES,
                                'UTF-8'
                            )
                        ) ?>
                    </p>

                </div>

            <?php endif; ?>

            <!-- EDUCATION -->

            <?php if (!empty($portfolio['education'])): ?>

                <div class="portfolio-section">

                    <h3>Education</h3>

                    <p>
                        <?= nl2br(
                            htmlspecialchars(
                                $portfolio['education'],
                                ENT_QUOTES,
                                'UTF-8'
                            )
                        ) ?>
                    </p>

                </div>

            <?php endif; ?>

            <!-- SKILLS -->

            <?php if (!empty($portfolio['skills'])): ?>

                <div class="portfolio-section">

                    <h3>Skills</h3>

                    <p>
                        <?= nl2br(
                            htmlspecialchars(
                                $portfolio['skills'],
                                ENT_QUOTES,
                                'UTF-8'
                            )
                        ) ?>
                    </p>

                </div>

            <?php endif; ?>

            <!-- BUTTONS -->

            <div class="portfolio-actions">

                <a
                    href="preview.php?id=<?= urlencode(
                        (string) $portfolio['id']
                    ) ?>"
                    class="btn"
                >
                    View Portfolio
                </a>

                <a
                    href="edit.php?id=<?= urlencode(
                        (string) $portfolio['id']
                    ) ?>"
                    class="btn"
                >
                    Edit Portfolio
                </a>

                <a href="templates.php" class="btn">
                    Change Template
                </a>

                <a
                    href="delete.php?id=<?= urlencode(
                        (string) $portfolio['id']
                    ) ?>"
                    class="btn"
                    onclick="return confirm('Are you sure you want to delete this portfolio?');"
                >
                    Delete
                </a>

            </div>

        </div>

    <?php else: ?>

        <!-- NO PORTFOLIO -->

        <div class="empty-state">

            <h2>
                You don't have a portfolio yet.
            </h2>

            <p>
                Create your first portfolio to get started.
            </p>

            <a href="create.php" class="btn">
                + Create Portfolio
            </a>

        </div>

    <?php endif; ?>

</main>

<!-- FOOTER -->

<?php include __DIR__ . '/footer.php'; ?>

<!-- JAVASCRIPT -->

<script src="js/script.js"></script>

</body>
</html>
