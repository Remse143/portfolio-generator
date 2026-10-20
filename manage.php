



<?php
// =====================================================
// PORTFOLIOGEN - MY PORTFOLIOS
// Supabase Database | Responsive Design
// Black | Crimson | Purple
// =====================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/database.php';

if (empty($_SESSION['access_token'])) {
    header('Location: login.php');
    exit;
}

$accessToken = $_SESSION['access_token'];
$userId = $_SESSION['user_id'] ?? '';
$userEmail = $_SESSION['user_email'] ?? '';

$supabaseUrl = rtrim($supabaseUrl ?? '', '/');
$supabaseKey = $supabaseKey ?? '';

$portfolios = [];
$errorMessage = '';
$loadSucceeded = false;

// Check database configuration
if ($supabaseUrl === '' || $supabaseKey === '') {
    $errorMessage = 'Database configuration is missing. Check config/database.php.';
} else {

    // Retrieve the logged-in user if the ID is missing
    if (empty($userId)) {
        $ch = curl_init($supabaseUrl . '/auth/v1/user');

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'apikey: ' . $supabaseKey,
                'Authorization: Bearer ' . $accessToken,
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 20
        ]);

        $userResponse = curl_exec($ch);
        $userStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $userCurlError = curl_error($ch);

        curl_close($ch);

        if ($userResponse !== false && $userStatus >= 200 && $userStatus < 300) {
            $userData = json_decode($userResponse, true);

            if (!empty($userData['id'])) {
                $userId = $userData['id'];
                $_SESSION['user_id'] = $userId;

                if (!empty($userData['email'])) {
                    $userEmail = $userData['email'];
                    $_SESSION['user_email'] = $userEmail;
                }
            }
        } elseif ($userStatus === 401) {
            session_unset();
            session_destroy();
            header('Location: login.php');
            exit;
        } else {
            $errorMessage = 'Unable to verify your account. Please sign in again.';
            if ($userCurlError !== '') {
                error_log('PortfolioGen user verification error: ' . $userCurlError);
            }
        }
    }

    // Load only this user's portfolios
    if ($errorMessage === '' && !empty($userId)) {
        $queryUrl = $supabaseUrl
            . '/rest/v1/portfolios?user_id=eq.'
            . rawurlencode($userId)
            . '&select=*&order=created_at.desc';

        $ch = curl_init($queryUrl);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'apikey: ' . $supabaseKey,
                'Authorization: Bearer ' . $accessToken,
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        curl_close($ch);

        if ($response === false) {
            $errorMessage = 'Could not connect to the database. Please try again.';
        } elseif ($httpStatus >= 200 && $httpStatus < 300) {
            $decoded = json_decode($response, true);

            if (is_array($decoded)) {
                $portfolios = $decoded;
                $loadSucceeded = true;
            } else {
                $errorMessage = 'The database returned an invalid response.';
            }
        } elseif ($httpStatus === 401) {
            $errorMessage = 'Your session has expired. Please sign in again.';
        } elseif ($httpStatus === 403) {
            $errorMessage = 'Access denied. Check your Supabase authentication and RLS policies.';
        } else {
            $errorData = json_decode($response, true);
            $errorMessage = $errorData['message']
                ?? 'Unable to load portfolios. HTTP status: ' . $httpStatus;
        }

        if ($curlError !== '') {
            error_log('PortfolioGen database error: ' . $curlError);
        }
    } elseif ($errorMessage === '') {
        $errorMessage = 'Your account could not be identified. Please sign in again.';
    }
}

// Safe HTML output
function manageEscape($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function manageImageUrl($value): string
{
    $value = trim((string)($value ?? ''));

    if ($value === '') {
        return '';
    }

    if (filter_var($value, FILTER_VALIDATE_URL)) {
        $scheme = strtolower((string)parse_url($value, PHP_URL_SCHEME));
        return $scheme === 'https' ? $value : '';
    }

    if (
        strpos($value, '..') !== false ||
        preg_match('/[\x00-\x1F\\\\]/', $value) ||
        !preg_match('/^[a-zA-Z0-9_\/.-]+\.(jpg|jpeg|png|gif|webp)$/i', $value)
    ) {
        return '';
    }

    return $value;
}

$pageTitle = 'My Portfolios';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= manageEscape($pageTitle) ?> | PortfolioGen</title>

    <!-- Font Awesome icons -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >

    <style>
        :root {
            --mg-bg: #08070d;
            --mg-panel: #12101b;
            --mg-panel-light: #191522;
            --mg-border: rgba(168, 85, 247, 0.20);
            --mg-red: #e11d48;
            --mg-red-dark: #9f1239;
            --mg-purple: #a855f7;
            --mg-text: #f8fafc;
            --mg-muted: #a1a1aa;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--mg-bg);
            color: var(--mg-text);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
        }

        a {
            -webkit-tap-highlight-color: transparent;
        }

        button,
        a {
            font-family: inherit;
        }

        .mg-background {
            position: fixed;
            inset: 0;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
            background:
                radial-gradient(ellipse at 15% 15%, rgba(225,29,72,.10), transparent 42%),
                radial-gradient(ellipse at 85% 65%, rgba(124,58,237,.12), transparent 42%),
                #08070d;
        }

        .mg-background::before {
            content: "";
            position: absolute;
            inset: -50%;
            background: conic-gradient(
                from 0deg,
                transparent,
                rgba(225,29,72,.06),
                transparent,
                rgba(168,85,247,.08),
                transparent
            );
            animation: mgRotate 35s linear infinite;
        }

        .mg-background::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.018) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.018) 1px, transparent 1px);
            background-size: 55px 55px;
            mask-image: linear-gradient(to bottom, black, transparent 90%);
        }

        @keyframes mgRotate {
            to { transform: rotate(360deg); }
        }

        /* Navigation */
        .mg-navbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(8,7,13,.90);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .mg-nav-inner {
            width: min(1250px, 92%);
            min-height: 76px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .mg-logo {
            flex-shrink: 0;
            color: #fff;
            text-decoration: none;
            font-size: 25px;
            font-weight: 900;
            letter-spacing: -1px;
        }

        .mg-logo span {
            color: var(--mg-red);
        }

        .mg-nav-links {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .mg-nav-links a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 13px;
            color: #a1a1aa;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 9px;
            transition: .25s ease;
        }

        .mg-nav-links a:hover,
        .mg-nav-links a.active {
            color: #fff;
            background: rgba(225,29,72,.11);
        }

        .mg-nav-links a.active {
            box-shadow: inset 0 -2px var(--mg-red);
        }

        .mg-nav-links .mg-logout {
            color: #fda4af;
            border: 1px solid rgba(225,29,72,.25);
        }

        .mg-nav-links .mg-logout:hover {
            background: var(--mg-red-dark);
            color: white;
        }

        /* Main content */
        .mg-main {
            width: min(1150px, 92%);
            min-height: 65vh;
            margin: 0 auto;
            padding: 55px 0 85px;
        }

        .mg-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 25px;
            margin-bottom: 32px;
        }

        .mg-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 13px;
            color: #fda4af;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 3px;
        }

        .mg-eyebrow::before {
            content: "";
            width: 22px;
            height: 2px;
            background: var(--mg-red);
        }

        .mg-heading h1 {
            margin: 0 0 12px;
            font-size: clamp(30px, 4vw, 43px);
            font-weight: 850;
            letter-spacing: -1.6px;
        }

        .mg-heading p {
            margin: 0;
            color: var(--mg-muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .mg-create-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 46px;
            padding: 0 18px;
            color: white;
            background: linear-gradient(110deg, var(--mg-red), var(--mg-red-dark));
            border: 1px solid rgba(251,113,133,.35);
            border-radius: 11px;
            font-size: 13px;
            font-weight: 750;
            text-decoration: none;
            white-space: nowrap;
            box-shadow: 0 8px 24px rgba(225,29,72,.17);
            transition: .25s ease;
        }

        .mg-create-btn:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(225,29,72,.28);
        }

        /* Empty state */
        .mg-empty-card {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 65px 25px 35px;
            text-align: center;
            background: linear-gradient(135deg, rgba(18,15,30,.97), rgba(10,10,20,.97));
            border: 1px solid var(--mg-border);
            border-radius: 24px;
            box-shadow: 0 25px 80px rgba(0,0,0,.24);
        }

        .mg-empty-card::before {
            content: "";
            position: absolute;
            z-index: -1;
            top: 0;
            left: 15%;
            width: 70%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--mg-red), var(--mg-purple), transparent);
        }

        .mg-empty-glow {
            position: absolute;
            z-index: -1;
            top: 15%;
            left: 50%;
            width: 300px;
            height: 200px;
            background: rgba(124,58,237,.09);
            filter: blur(75px);
            transform: translateX(-50%);
            pointer-events: none;
        }

        .mg-empty-icon-wrap {
            position: relative;
            display: grid;
            place-items: center;
            width: 105px;
            height: 105px;
            margin-bottom: 25px;
        }

        .mg-empty-icon-glow {
            position: absolute;
            inset: 0;
            background: rgba(168,85,247,.20);
            filter: blur(22px);
            border-radius: 50%;
            animation: mgGlow 3s ease-in-out infinite alternate;
        }

        .mg-empty-icon {
            position: relative;
            display: grid;
            place-items: center;
            width: 85px;
            height: 85px;
            color: #fda4af;
            font-size: 34px;
            background: linear-gradient(135deg, rgba(225,29,72,.17), rgba(124,58,237,.20));
            border: 1px solid rgba(168,85,247,.35);
            border-radius: 24px;
            transform: rotate(-5deg);
            transition: transform .3s ease;
        }

        .mg-empty-card:hover .mg-empty-icon {
            transform: rotate(0) translateY(-4px);
        }

        @keyframes mgGlow {
            from { opacity: .5; transform: scale(.9); }
            to { opacity: 1; transform: scale(1.1); }
        }

        .mg-empty-status {
            margin-bottom: 14px;
            color: #c084fc;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2.5px;
        }

        .mg-empty-card h2 {
            margin: 0 0 13px;
            color: white;
            font-size: clamp(22px, 3vw, 29px);
            font-weight: 800;
            letter-spacing: -.6px;
        }

        .mg-empty-description {
            max-width: 550px;
            margin: 0 0 27px;
            color: var(--mg-muted);
            font-size: 14px;
            line-height: 1.9;
        }

        .mg-empty-features {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 25px;
            width: 100%;
            margin-top: 42px;
            padding-top: 25px;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .mg-empty-feature {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #a1a1aa;
            font-size: 12px;
        }

        .mg-empty-feature i {
            color: #c084fc;
        }

        /* Error state */
        .mg-error-card {
            padding: 35px 25px;
            text-align: center;
            background: rgba(225,29,72,.07);
            border: 1px solid rgba(225,29,72,.25);
            border-radius: 16px;
        }

        .mg-error-card > i {
            color: #fb7185;
            font-size: 32px;
        }

        .mg-error-card h2 {
            margin: 15px 0 10px;
            font-size: 22px;
        }

        .mg-error-card p {
            color: var(--mg-muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .mg-retry-btn {
            display: inline-flex;
            gap: 9px;
            align-items: center;
            padding: 12px 18px;
            color: white;
            background: var(--mg-red-dark);
            border-radius: 9px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
        }

        /* Portfolio cards */
        .mg-count {
            margin-bottom: 20px;
            color: var(--mg-muted);
            font-size: 13px;
        }

        .mg-count strong {
            color: white;
        }

        .mg-portfolio-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .mg-portfolio-card {
            position: relative;
            overflow: hidden;
            padding: 25px;
            background: linear-gradient(145deg, rgba(25,21,34,.97), rgba(13,12,20,.98));
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 18px;
            transition: transform .25s ease, border-color .25s ease;
        }

        .mg-portfolio-card:hover {
            transform: translateY(-4px);
            border-color: rgba(168,85,247,.4);
        }

        .mg-card-top {
            display: flex;
            align-items: center;
            gap: 17px;
            margin-bottom: 22px;
        }

        .mg-profile-picture {
            flex-shrink: 0;
            width: 76px;
            height: 76px;
            display: grid;
            place-items: center;
            overflow: hidden;
            color: #fda4af;
            font-size: 27px;
            background: linear-gradient(135deg, rgba(225,29,72,.18), rgba(124,58,237,.25));
            border: 1px solid rgba(168,85,247,.32);
            border-radius: 18px;
        }

        .mg-profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .mg-card-name {
            min-width: 0;
        }

        .mg-card-name h2 {
            margin: 0 0 8px;
            overflow-wrap: anywhere;
            color: white;
            font-size: 19px;
            font-weight: 800;
        }

        .mg-card-name p {
            margin: 0;
            overflow-wrap: anywhere;
            color: var(--mg-muted);
            font-size: 12px;
        }

        .mg-template-tag {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 20px;
            padding: 7px 10px;
            color: #d8b4fe;
            background: rgba(168,85,247,.09);
            border: 1px solid rgba(168,85,247,.20);
            border-radius: 7px;
            font-size: 11px;
            font-weight: 700;
        }

        .mg-card-description {
            display: -webkit-box;
            overflow: hidden;
            min-height: 44px;
            margin: 0 0 22px;
            color: var(--mg-muted);
            font-size: 13px;
            line-height: 1.8;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .mg-card-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            padding-top: 18px;
            border-top: 1px solid rgba(255,255,255,.08);
        }

        .mg-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 37px;
            padding: 0 12px;
            color: #e4e4e7;
            background: rgba(255,255,255,.045);
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s ease;
        }

        .mg-action:hover {
            color: white;
            background: rgba(168,85,247,.15);
            border-color: rgba(168,85,247,.4);
        }

        .mg-action.mg-action-view {
            background: rgba(225,29,72,.12);
            border-color: rgba(225,29,72,.28);
        }

        .mg-action.mg-action-delete {
            color: #fda4af;
        }

        /* Responsive */
        @media (max-width: 850px) {
            .mg-nav-inner {
                flex-wrap: wrap;
                justify-content: center;
                padding: 15px 0;
                gap: 12px;
            }

            .mg-logo {
                width: 100%;
                text-align: center;
            }

            .mg-nav-links {
                justify-content: center;
                flex-wrap: wrap;
            }

            .mg-nav-links a {
                padding: 9px 10px;
                font-size: 12px;
            }

            .mg-portfolio-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .mg-main {
                padding-top: 35px;
            }

            .mg-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .mg-heading .mg-create-btn {
                width: 100%;
            }

            .mg-empty-card {
                padding: 45px 18px 25px;
                border-radius: 18px;
            }

            .mg-empty-features {
                flex-direction: column;
                align-items: center;
                gap: 17px;
                margin-top: 30px;
            }

            .mg-portfolio-card {
                padding: 19px;
            }

            .mg-card-top {
                align-items: flex-start;
                gap: 12px;
            }

            .mg-profile-picture {
                width: 62px;
                height: 62px;
                border-radius: 14px;
            }

            .mg-card-name h2 {
                font-size: 16px;
            }

            .mg-card-actions > a {
                flex: 1 1 auto;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .mg-background::before,
            .mg-empty-icon-glow {
                animation: none;
            }

            .mg-main *,
            .mg-main *::before,
            .mg-main *::after {
                transition-duration: .01ms !important;
            }
        }
    </style>

    <!-- IMPORTANT: Load your original footer CSS after the page styles -->
    <link rel="stylesheet" href="css/footer.css">
</head>

<body>

<div class="mg-background" aria-hidden="true"></div>

<header class="mg-navbar">
    <div class="mg-nav-inner">

        <a href="index.php" class="mg-logo">
            Portfolio<span>Gen</span>
        </a>

        <nav class="mg-nav-links" aria-label="Main navigation">
            <a href="dashboard.php">
                <i class="fa-solid fa-house"></i>
                Dashboard
            </a>

            <a href="manage.php" class="active" aria-current="page">
                <i class="fa-solid fa-folder-open"></i>
                My Portfolio
            </a>

            <a href="templates.php">
                <i class="fa-solid fa-layer-group"></i>
                Templates
            </a>

            <a href="logout.php" class="mg-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>
        </nav>

    </div>
</header>

<main class="mg-main">

    <section class="mg-heading">
        <div>
            <span class="mg-eyebrow">YOUR CREATIVE SPACE</span>
            <h1>My Portfolio</h1>
            <p>Manage, build, and publish your personal developer portfolio.</p>
        </div>

        <a href="create.php" class="mg-create-btn">
            <i class="fa-solid fa-plus"></i>
            Create Portfolio
        </a>
    </section>

    <?php if ($errorMessage !== ''): ?>

        <section class="mg-error-card">
            <i class="fa-solid fa-circle-exclamation"></i>
            <h2>Unable to Load Your Portfolios</h2>
            <p><?= manageEscape($errorMessage) ?></p>

            <?php if (strpos($errorMessage, 'session has expired') !== false): ?>
                <a href="login.php" class="mg-retry-btn">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Sign In Again
                </a>
            <?php else: ?>
                <a href="manage.php" class="mg-retry-btn">
                    <i class="fa-solid fa-rotate-right"></i>
                    Try Again
                </a>
            <?php endif; ?>
        </section>

    <?php elseif ($loadSucceeded && empty($portfolios)): ?>

        <section class="mg-empty-card">

            <div class="mg-empty-glow"></div>

            <div class="mg-empty-icon-wrap">
                <div class="mg-empty-icon-glow"></div>
                <div class="mg-empty-icon">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
            </div>

            <span class="mg-empty-status">YOUR JOURNEY STARTS HERE</span>

            <h2>You don't have a portfolio yet</h2>

            <p class="mg-empty-description">
                Create your first portfolio to showcase your projects,
                skills, and experience to recruiters. Bring your ideas
                to life and build your professional online presence.
            </p>

            <a href="create.php" class="mg-create-btn">
                <i class="fa-solid fa-plus"></i>
                Create Portfolio
                <i class="fa-solid fa-arrow-right"></i>
            </a>

            <div class="mg-empty-features">
                <div class="mg-empty-feature">
                    <i class="fa-solid fa-palette"></i>
                    <span>Choose a Template</span>
                </div>

                <div class="mg-empty-feature">
                    <i class="fa-solid fa-code"></i>
                    <span>Showcase Your Skills</span>
                </div>

                <div class="mg-empty-feature">
                    <i class="fa-solid fa-globe"></i>
                    <span>Share Your Portfolio</span>
                </div>
            </div>

        </section>

    <?php elseif ($loadSucceeded): ?>

        <div class="mg-count">
            You have
            <strong><?= count($portfolios) ?></strong>
            portfolio<?= count($portfolios) === 1 ? '' : 's' ?>.
        </div>

        <section class="mg-portfolio-grid">

            <?php foreach ($portfolios as $portfolio): ?>

                <?php
                $portfolioId = $portfolio['id'] ?? '';

                $portfolioName = trim(
                    (string)($portfolio['full_name'] ?? '')
                );

                if ($portfolioName === '') {
                    $portfolioName = 'Untitled Portfolio';
                }

                $portfolioEmail = $portfolio['email'] ?? '';

                $portfolioAbout = trim(
                    (string)($portfolio['about_me'] ?? '')
                );

                if ($portfolioAbout === '') {
                    $portfolioAbout = 'No About Me description has been added yet.';
                }

                $templateName = $portfolio['template_name']
                    ?? $portfolio['template']
                    ?? 'Simple';

                $templateName = ucfirst(strtolower((string)$templateName));

                $profileImage = manageImageUrl(
                    $portfolio['profile_picture'] ?? ''
                );
                ?>

                <article class="mg-portfolio-card">

                    <div class="mg-card-top">

                        <div class="mg-profile-picture">

                            <?php if ($profileImage !== ''): ?>

                                <img
                                    src="<?= manageEscape($profileImage) ?>"
                                    alt="Profile picture of <?= manageEscape($portfolioName) ?>"
                                    loading="lazy"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';"
                                >

                                <i
                                    class="fa-solid fa-user"
                                    style="display:none;"
                                    aria-hidden="true"
                                ></i>

                            <?php else: ?>

                                <i class="fa-solid fa-user" aria-hidden="true"></i>

                            <?php endif; ?>

                        </div>

                        <div class="mg-card-name">
                            <h2><?= manageEscape($portfolioName) ?></h2>

                            <p>
                                <i class="fa-regular fa-envelope"></i>
                                <?= manageEscape($portfolioEmail) ?>
                            </p>
                        </div>

                    </div>

                    <div class="mg-template-tag">
                        <i class="fa-solid fa-palette"></i>
                        <?= manageEscape($templateName) ?> Template
                    </div>

                    <p class="mg-card-description">
                        <?= manageEscape($portfolioAbout) ?>
                    </p>

                    <div class="mg-card-actions">

                        <a
                            class="mg-action mg-action-view"
                            href="preview.php?id=<?= rawurlencode((string)$portfolioId) ?>"
                        >
                            <i class="fa-solid fa-eye"></i>
                            View
                        </a>

                        <a
                            class="mg-action"
                            href="edit.php?id=<?= rawurlencode((string)$portfolioId) ?>"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                            Edit
                        </a>

                        <a
                            class="mg-action mg-action-delete"
                            href="delete.php?id=<?= rawurlencode((string)$portfolioId) ?>"
                            onclick="return confirm('Are you sure you want to delete this portfolio?');"
                        >
                            <i class="fa-solid fa-trash"></i>
                            Delete
                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </section>

    <?php endif; ?>

</main>

<?php
// Keep the original shared footer.
// footer.php contains the footer HTML, footer animation script,
// and the closing body/html tags.
require_once __DIR__ . '/footer.php';
?>
