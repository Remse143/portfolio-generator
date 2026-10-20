


<?php
session_start();

require_once __DIR__ . "/supabase_auth.php";

// Check if the user is logged in.
if (empty($_SESSION['access_token'])) {
    header("Location: login.php");
    exit;
}

// Get portfolio ID.
$id = $_GET['id'] ?? '';
$userId = $_SESSION['user_id'] ?? '';

if (!is_string($id) || trim($id) === '' || trim((string) $userId) === '') {
    header("Location: manage.php");
    exit;
}

// Get Supabase configuration.
require_once __DIR__ . "/config/database.php";

// Request only the portfolio owned by the logged-in user.
$url = $supabaseUrl
    . "/rest/v1/portfolios?id=eq."
    . urlencode((string) $id)
    . "&user_id=eq."
    . urlencode((string) $userId)
    . "&select=*";

function requestPortfolio(string $url, string $supabaseKey): array
{
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "apikey: " . $supabaseKey,
            "Authorization: Bearer " . $_SESSION['access_token'],
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

$result = requestPortfolio($url, $supabaseKey);

// Refresh the token and retry if it has expired.
if (
    $result['httpCode'] === 401 &&
    function_exists('refreshSupabaseToken') &&
    refreshSupabaseToken()
) {
    $result = requestPortfolio($url, $supabaseKey);
}

// Handle connection errors.
if ($result['curlError'] !== '') {
    http_response_code(500);
    exit("Unable to connect to the portfolio database.");
}

// Handle expired or invalid authentication.
if ($result['httpCode'] === 401) {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax'
        ]);
    }

    session_destroy();

    header("Location: login.php");
    exit;
}

// Handle other database errors.
if ($result['httpCode'] !== 200) {
    http_response_code(500);
    exit("Unable to load the portfolio. Please try again.");
}

// Decode the response.
$results = json_decode($result['response'] ?? '', true);

if (!is_array($results) || empty($results[0])) {
    http_response_code(404);
    exit("Portfolio not found.");
}

$portfolio = $results[0];

// Choose the portfolio template.
$template = strtolower(trim($portfolio['template'] ?? 'simple'));

$allowedTemplates = ['simple', 'modern', 'creative'];

if (!in_array($template, $allowedTemplates, true)) {
    $template = 'simple';
}

$templateFile = __DIR__ . "/templates/" . $template . ".php";

if (!file_exists($templateFile)) {
    http_response_code(500);
    exit("The selected portfolio template could not be found.");
}

$portfolioId = urlencode((string) $portfolio['id']);
$portfolioName = htmlspecialchars(
    $portfolio['full_name'] ?? 'My Portfolio',
    ENT_QUOTES,
    'UTF-8'
);
?>


<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| PortfolioGen Preview Page
|--------------------------------------------------------------------------
| Keep your existing portfolio-loading code above this HTML.
| These variables must already be set by your existing PHP logic:
| $portfolioId, $templateFile
|--------------------------------------------------------------------------
*/

// Validate that the template file exists before including it.
if (
    !isset($templateFile) ||
    !is_string($templateFile) ||
    !is_file($templateFile)
) {
    http_response_code(500);
    exit('Template file not found. Please check your portfolio template selection.');
}

$portfolioId = isset($portfolioId) ? (int) $portfolioId : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Preview Portfolio | PortfolioGen</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">

    <style>
        /* =========================================
           PREVIEW PAGE NAVIGATION
           ========================================= */

        .pg-preview-navbar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            min-height: 65px;
            box-sizing: border-box;
            z-index: 99999 !important;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;

            padding: 12px 5%;
            background: rgba(7, 6, 15, 0.96);
            border-bottom: 1px solid rgba(225, 29, 72, 0.35);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(14px);
        }

        .pg-preview-brand {
            color: #fff;
            text-decoration: none;
            font-size: 21px;
            font-weight: 800;
            white-space: nowrap;
        }

        .pg-preview-brand span {
            color: #e11d48;
        }

        .pg-preview-links {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pg-preview-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 13px;
            border-radius: 8px;
            color: #e4e4e7;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .pg-preview-links a:hover {
            background: rgba(225, 29, 72, 0.16);
            color: #fb7185;
        }

        /* Prevent page content from being hidden behind navbar */
        body {
            padding-top: 85px !important;
        }

        html {
            scroll-padding-top: 90px;
            scroll-behavior: smooth;
        }

        /* =========================================
           PREVIEW CONTENT
           ========================================= */

        .preview-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }

        .preview-heading {
            text-align: center;
            margin: 25px 0;
        }

        .preview-heading h1 {
            margin-bottom: 8px;
        }

        .preview-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin: 25px auto;
            padding: 0 15px;
        }

        .preview-actions .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border: 1px solid rgba(225, 29, 72, 0.25);
            border-radius: 8px;
            background: #18131e;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .preview-actions .btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .preview-actions .download-zip-btn {
            background: linear-gradient(135deg, #e11d48, #9f1239);
            color: #fff;
        }

        .portfolio-preview-container {
            width: 100%;
            min-width: 0;
        }

        /* Keep template content within the available viewport */
        .portfolio-preview-container > .generated-portfolio {
            max-width: 100%;
        }

        /* =========================================
           RESPONSIVE NAVIGATION
           ========================================= */

        @media (max-width: 700px) {
            .pg-preview-navbar {
                align-items: flex-start;
                flex-direction: column;
                gap: 8px;
                padding: 10px 15px;
            }

            .pg-preview-brand {
                font-size: 19px;
            }

            .pg-preview-links {
                width: 100%;
                justify-content: flex-start;
                gap: 5px;
            }

            .pg-preview-links a {
                padding: 7px 9px;
                font-size: 12px;
            }

            body {
                padding-top: 115px !important;
            }

            .preview-container {
                padding: 10px;
            }

            .preview-actions {
                flex-direction: column;
            }

            .preview-actions .btn {
                width: 100%;
                max-width: 320px;
                box-sizing: border-box;
            }
        }

        @media (max-width: 380px) {
            .pg-preview-links a {
                padding: 6px;
                font-size: 11px;
            }
        }
    </style>
</head>

<body>

    <!-- FIXED PREVIEW NAVBAR -->
    <nav class="pg-preview-navbar" aria-label="Preview navigation">

        <a href="index.php" class="pg-preview-brand">
            Portfolio<span>Gen</span>
        </a>

        <div class="pg-preview-links">
            <a href="index.php">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="templates.php">Templates</a>
            <a href="manage.php">My Portfolios</a>
        </div>

    </nav>

    <main class="preview-container">

        <div class="preview-heading">
            <h1>Portfolio Preview</h1>
            <p>Preview your portfolio before sharing or downloading it.</p>
        </div>

        <div class="preview-actions">

            <a href="templates.php" class="btn">
                Change Template
            </a>

            <a href="edit.php?id=<?= $portfolioId ?>" class="btn">
                Edit Portfolio
            </a>

            <a href="download.php?id=<?= $portfolioId ?>"
               class="btn download-zip-btn">
                Download ZIP
            </a>

            <a href="manage.php" class="btn">
                Back to My Portfolios
            </a>

        </div>

        <section class="portfolio-preview-container">
            <?php include $templateFile; ?>
        </section>

        <div class="preview-actions">
            <a href="download.php?id=<?= $portfolioId ?>"
               class="btn download-zip-btn">
                Download Portfolio ZIP
            </a>
        </div>

    </main>

    <?php include __DIR__ . "/footer.php"; ?>

    <script src="js/script.js"></script>

</body>
</html>
