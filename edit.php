
<?php
session_start();

require_once __DIR__ . '/config/database.php';

if (
    !isset($_SESSION['access_token']) ||
    !isset($_SESSION['user_id'])
) {
    header('Location: login.php');
    exit;
}

$userId = (string) $_SESSION['user_id'];
$accessToken = (string) $_SESSION['access_token'];

$supabaseUrl = rtrim($supabaseUrl, '/');

$errorMessage = '';

/* =========================================
   HELPER FUNCTIONS
========================================= */

function editEscape($value): string
{
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

function editLength(string $value): int
{
    return function_exists('mb_strlen')
        ? mb_strlen($value, 'UTF-8')
        : strlen($value);
}

function editFieldLength(
    array $data,
    string $field,
    int $maximum
): bool {
    return editLength((string) ($data[$field] ?? '')) <= $maximum;
}

/* =========================================
   VALIDATE PORTFOLIO ID
========================================= */

$id = $_GET['id'] ?? '';

if (
    !is_string($id) ||
    $id === '' ||
    strlen($id) > 100 ||
    !preg_match('/^[A-Za-z0-9-]+$/', $id)
) {
    header('Location: manage.php');
    exit;
}

/* =========================================
   LOAD PORTFOLIO FROM SUPABASE
========================================= */

$getUrl = $supabaseUrl
    . '/rest/v1/portfolios?id=eq.'
    . rawurlencode($id)
    . '&user_id=eq.'
    . rawurlencode($userId)
    . '&select=*';

$curl = curl_init($getUrl);

curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'apikey: ' . $supabaseKey,
        'Authorization: Bearer ' . $accessToken,
        'Accept: application/json'
    ],
    CURLOPT_TIMEOUT => 20
]);

$portfolioResponse = curl_exec($curl);
$portfolioHttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$portfolioCurlError = curl_error($curl);

curl_close($curl);

$portfolioResults = json_decode(
    (string) $portfolioResponse,
    true
);

if (
    $portfolioResponse === false ||
    $portfolioHttpCode < 200 ||
    $portfolioHttpCode >= 300 ||
    !is_array($portfolioResults) ||
    !isset($portfolioResults[0])
) {
    if ($portfolioCurlError !== '') {
        error_log(
            'PortfolioGen edit load error: ' . $portfolioCurlError
        );
    }

    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Portfolio Not Found | PortfolioGen</title>
        <style>
            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                display: grid;
                place-items: center;
                padding: 20px;
                background: #08070d;
                color: #fff;
                font-family: Arial, sans-serif;
            }

            .error-card {
                max-width: 480px;
                padding: 35px;
                text-align: center;
                border: 1px solid #7f1d3c;
                border-radius: 20px;
                background: #140d19;
            }

            h1 {
                color: #fb7185;
            }

            a {
                display: inline-block;
                margin-top: 15px;
                padding: 12px 20px;
                border-radius: 9px;
                background: #e11d48;
                color: white;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="error-card">
            <h1>Portfolio Not Found</h1>
            <p>
                We could not find this portfolio, or you do not have
                permission to edit it.
            </p>
            <a href="manage.php">Back to My Portfolios</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$portfolio = $portfolioResults[0];

/* =========================================
   PROCESS EDIT FORM
========================================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'full_name' => trim((string) ($_POST['full_name'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'contact_number' => trim((string) ($_POST['contact_number'] ?? '')),
        'address' => trim((string) ($_POST['address'] ?? '')),
        'professional_title' => trim((string) ($_POST['professional_title'] ?? '')),
        'career_objective' => trim((string) ($_POST['career_objective'] ?? '')),
        'about_me' => trim((string) ($_POST['about_me'] ?? '')),
        'education' => trim((string) ($_POST['education'] ?? '')),
        'skills' => trim((string) ($_POST['skills'] ?? '')),
        'projects' => trim((string) ($_POST['projects'] ?? '')),
        'work_experience' => trim((string) ($_POST['work_experience'] ?? '')),
        'social_links' => trim((string) ($_POST['social_links'] ?? ''))
    ];

    /* Validate full name. */
    if ($data['full_name'] === '') {
        $errorMessage = 'Please enter your full name.';
    } elseif (
        !preg_match(
            '/^\p{L}+(?:[ \'\x{2019}-]\p{L}+)*$/u',
            $data['full_name']
        )
    ) {
        $errorMessage =
            'Your name may contain letters, spaces, apostrophes, and hyphens only. Numbers and other special characters are not allowed.';
    } elseif (editLength($data['full_name']) > 100) {
        $errorMessage = 'Your full name must not exceed 100 characters.';
    }

    /* Validate email. */
    if ($errorMessage === '') {
        if ($data['email'] === '') {
            $errorMessage = 'Please enter your email address.';
        } elseif (
            editLength($data['email']) > 254 ||
            !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
        ) {
            $errorMessage = 'Please enter a valid email address.';
        }
    }

    /* Validate phone number. */
    if ($errorMessage === '') {
        $phone = $data['contact_number'];
        $digitCount = preg_match_all('/[0-9]/', $phone);

        if ($phone === '') {
            $errorMessage = 'Please enter your contact number.';
        } elseif (
            !preg_match('/^[0-9+\s().-]+$/', $phone) ||
            $digitCount < 7 ||
            $digitCount > 15
        ) {
            $errorMessage =
                'Enter a valid contact number containing 7 to 15 digits. Letters are not allowed.';
        } elseif (editLength($phone) > 20) {
            $errorMessage = 'Your contact number is too long.';
        }
    }

    /* Validate required fields. */
    if ($errorMessage === '') {
        $requiredFields = [
            'address' => 'Address',
            'professional_title' => 'Professional title',
            'career_objective' => 'Career objective',
            'about_me' => 'About Me',
            'education' => 'Educational background',
            'skills' => 'Skills'
        ];

        foreach ($requiredFields as $field => $label) {
            if ($data[$field] === '') {
                $errorMessage = 'Please fill out the ' . $label . ' field.';
                break;
            }
        }
    }

    /* Validate maximum lengths. */
    if ($errorMessage === '') {
        $lengthRules = [
            'address' => 255,
            'professional_title' => 150,
            'career_objective' => 3000,
            'about_me' => 5000,
            'education' => 5000,
            'skills' => 5000,
            'projects' => 10000,
            'work_experience' => 10000,
            'social_links' => 5000
        ];

        $labels = [
            'address' => 'Address',
            'professional_title' => 'Professional title',
            'career_objective' => 'Career objective',
            'about_me' => 'About Me',
            'education' => 'Educational background',
            'skills' => 'Skills',
            'projects' => 'Projects',
            'work_experience' => 'Work experience',
            'social_links' => 'Social links'
        ];

        foreach ($lengthRules as $field => $maximum) {
            if (!editFieldLength($data, $field, $maximum)) {
                $errorMessage = $labels[$field]
                    . ' must not exceed '
                    . $maximum
                    . ' characters.';
                break;
            }
        }
    }

    /* Validate minimum lengths. */
    if ($errorMessage === '') {
        if (editLength($data['professional_title']) < 2) {
            $errorMessage =
                'Professional title must contain at least 2 characters.';
        } elseif (editLength($data['career_objective']) < 10) {
            $errorMessage =
                'Career objective must contain at least 10 characters.';
        } elseif (editLength($data['about_me']) < 10) {
            $errorMessage =
                'About Me must contain at least 10 characters.';
        } elseif (editLength($data['education']) < 2) {
            $errorMessage =
                'Please provide a more complete educational background.';
        } elseif (editLength($data['skills']) < 2) {
            $errorMessage = 'Please provide at least one skill.';
        }
    }

    /* =========================================
       UPDATE PORTFOLIO
    ========================================= */

    if ($errorMessage === '') {

        $updateUrl = $supabaseUrl
            . '/rest/v1/portfolios?id=eq.'
            . rawurlencode($id)
            . '&user_id=eq.'
            . rawurlencode($userId);

        $updateBody = json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
        );

        $curl = curl_init($updateUrl);

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => $updateBody,
            CURLOPT_HTTPHEADER => [
                'apikey: ' . $supabaseKey,
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
                'Accept: application/json',
                'Prefer: return=representation'
            ],
            CURLOPT_TIMEOUT => 20
        ]);

        $updateResponse = curl_exec($curl);
        $updateHttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $updateCurlError = curl_error($curl);

        curl_close($curl);

        $updatedPortfolio = json_decode(
            (string) $updateResponse,
            true
        );

        if (
            $updateResponse !== false &&
            $updateHttpCode >= 200 &&
            $updateHttpCode < 300 &&
            is_array($updatedPortfolio) &&
            !empty($updatedPortfolio)
        ) {
            header('Location: preview.php?id=' . rawurlencode($id));
            exit;
        }

        if ($updateCurlError !== '') {
            error_log(
                'PortfolioGen edit update cURL error: '
                . $updateCurlError
            );
        }

        error_log(
            'PortfolioGen edit update failed. HTTP status: '
            . $updateHttpCode
            . '. Response: '
            . (string) $updateResponse
        );

        $errorMessage =
            'Your portfolio could not be updated. Please try again. If the problem continues, check your Supabase database columns and server configuration.';

        $portfolio = array_merge($portfolio, $data);
    }
}

/* =========================================
   PREPARE FORM VALUES
========================================= */

function editValue(array $portfolio, string $field): string
{
    return editEscape($portfolio[$field] ?? '');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Portfolio | PortfolioGen</title>

    <style>
        :root {
            --edit-bg: #08070d;
            --edit-panel: rgba(20, 14, 28, 0.94);
            --edit-input: rgba(8, 7, 13, 0.85);
            --edit-border: rgba(168, 85, 247, 0.3);
            --edit-red: #e11d48;
            --edit-purple: #a855f7;
            --edit-text: #f5f3ff;
            --edit-muted: #b8afc8;
        }

        * {
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--edit-text);
            background:
                radial-gradient(
                    circle at 15% 10%,
                    rgba(225, 29, 72, 0.16),
                    transparent 32%
                ),
                radial-gradient(
                    circle at 85% 40%,
                    rgba(168, 85, 247, 0.15),
                    transparent 35%
                ),
                var(--edit-bg);
            background-attachment: fixed;
        }

        /* Background decoration */
        .edit-background {
            position: fixed;
            inset: 0;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }

        .edit-orb {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            filter: blur(85px);
            opacity: 0.2;
            animation: editFloat 12s ease-in-out infinite alternate;
        }

        .edit-orb-red {
            top: 8%;
            left: -100px;
            background: #e11d48;
        }

        .edit-orb-purple {
            right: -100px;
            bottom: 8%;
            background: #9333ea;
            animation-delay: -5s;
        }

        .edit-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(
                    rgba(168, 85, 247, 0.045) 1px,
                    transparent 1px
                ),
                linear-gradient(
                    90deg,
                    rgba(168, 85, 247, 0.045) 1px,
                    transparent 1px
                );
            background-size: 42px 42px;
            mask-image: linear-gradient(to bottom, black, transparent 90%);
        }

        @keyframes editFloat {
            from {
                transform: translate(0, 0) scale(1);
            }

            to {
                transform: translate(50px, -35px) scale(1.2);
            }
        }

        /* Navigation */
        .edit-navbar {
            position: relative;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            flex-shrink: 0;
            padding: 20px clamp(18px, 5vw, 70px);
            background: rgba(8, 7, 13, 0.83);
            border-bottom: 1px solid rgba(168, 85, 247, 0.2);
            backdrop-filter: blur(14px);
        }

        .edit-logo {
            color: white;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.7px;
            text-decoration: none;
        }

        .edit-logo span {
            color: var(--edit-red);
        }

        .edit-nav-links {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
        }

        .edit-nav-links a {
            color: #d7d0e4;
            font-size: 14px;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .edit-nav-links a:hover {
            color: #fb7185;
        }

        /* Main page */
        .edit-page {
            flex: 1 0 auto;
            width: min(100% - 30px, 950px);
            margin: 45px auto 65px;
        }

        .edit-heading {
            margin-bottom: 28px;
            text-align: center;
        }

        .edit-eyebrow {
            color: #fda4af;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .edit-heading h1 {
            margin: 12px 0;
            font-size: clamp(30px, 5vw, 43px);
            letter-spacing: -1.3px;
        }

        .edit-heading h1 span {
            color: #fb7185;
        }

        .edit-heading p {
            max-width: 600px;
            margin: 0 auto;
            color: var(--edit-muted);
            line-height: 1.7;
        }

        /* Form card */
        .edit-card {
            padding: clamp(20px, 4vw, 38px);
            background: var(--edit-panel);
            border: 1px solid var(--edit-border);
            border-radius: 22px;
            box-shadow:
                0 25px 80px rgba(0, 0, 0, 0.35),
                0 0 35px rgba(168, 85, 247, 0.06);
            backdrop-filter: blur(15px);
        }

        .edit-alert {
            padding: 15px 17px;
            margin-bottom: 24px;
            border-radius: 12px;
            font-size: 14px;
            line-height: 1.6;
        }

        .edit-alert-error {
            color: #fecdd3;
            background: rgba(190, 18, 60, 0.15);
            border: 1px solid rgba(251, 113, 133, 0.45);
        }

        /* Form sections */
        .edit-section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 30px 0 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(168, 85, 247, 0.2);
            font-size: 18px;
        }

        .edit-section-title:first-of-type {
            margin-top: 0;
        }

        .edit-section-number {
            display: grid;
            place-items: center;
            width: 32px;
            height: 32px;
            flex-shrink: 0;
            border-radius: 10px;
            background: rgba(225, 29, 72, 0.16);
            border: 1px solid rgba(225, 29, 72, 0.35);
            color: #fda4af;
            font-size: 13px;
            font-weight: 700;
        }

        .edit-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 21px;
        }

        .edit-field {
            min-width: 0;
        }

        .edit-field-full {
            grid-column: 1 / -1;
        }

        .edit-field label {
            display: block;
            margin-bottom: 9px;
            color: #eee7f7;
            font-size: 14px;
            font-weight: 600;
        }

        .edit-required {
            margin-left: 3px;
            color: #fb7185;
        }

        .edit-optional {
            color: #a99bbd;
            font-size: 12px;
            font-weight: 400;
        }

        .edit-field input,
        .edit-field textarea {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid rgba(168, 85, 247, 0.28);
            border-radius: 11px;
            outline: none;
            background: var(--edit-input);
            color: white;
            font: inherit;
            font-size: 14px;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .edit-field textarea {
            min-height: 125px;
            resize: vertical;
            line-height: 1.65;
        }

        .edit-field input:focus,
        .edit-field textarea:focus {
            border-color: #c084fc;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.12);
        }

        .edit-field input::placeholder,
        .edit-field textarea::placeholder {
            color: #82758f;
        }

        .edit-help {
            margin: 8px 0 0;
            color: #a99bbd;
            font-size: 12px;
            line-height: 1.6;
        }

        /* Buttons */
        .edit-form-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid rgba(168, 85, 247, 0.2);
        }

        .edit-button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            min-height: 46px;
            padding: 12px 22px;
            border: 1px solid transparent;
            border-radius: 11px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .edit-button:hover {
            transform: translateY(-2px);
        }

        .edit-button-primary {
            color: white;
            background: linear-gradient(135deg, #e11d48, #9f1239);
            box-shadow: 0 8px 22px rgba(225, 29, 72, 0.2);
        }

        .edit-button-primary:hover {
            box-shadow: 0 10px 28px rgba(225, 29, 72, 0.35);
        }

        .edit-button-secondary {
            color: #e9ddf7;
            background: rgba(168, 85, 247, 0.08);
            border-color: rgba(168, 85, 247, 0.28);
        }

        /* Footer layout fix */
        .site-footer {
            position: relative;
            isolation: isolate;
            flex-shrink: 0;
            width: 100%;
            max-width: none;
            margin-top: auto;
            overflow: hidden;
        }

        .site-footer .footer-live-bg {
            position: absolute;
            inset: 0;
            z-index: -1;
            pointer-events: none;
        }

        .site-footer > *:not(.footer-live-bg) {
            position: relative;
            z-index: 1;
        }

        /* Responsive layout */
        @media (max-width: 650px) {
            .edit-navbar {
                justify-content: center;
            }

            .edit-nav-links {
                justify-content: center;
                gap: 13px;
            }

            .edit-form-grid {
                grid-template-columns: 1fr;
            }

            .edit-field-full {
                grid-column: auto;
            }

            .edit-form-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .edit-button {
                width: 100%;
            }

            .edit-page {
                width: calc(100% - 24px);
                margin: 28px auto 40px;
            }

            .site-footer {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .edit-orb,
            .site-footer *,
            .edit-button,
            .edit-nav-links a {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>

    <!-- Load the shared footer design after the page styles -->
    <link rel="stylesheet" href="css/footer.css?v=3">
</head>

<body>

<div class="edit-background" aria-hidden="true">
    <div class="edit-grid"></div>
    <div class="edit-orb edit-orb-red"></div>
    <div class="edit-orb edit-orb-purple"></div>
</div>

<header class="edit-navbar">
    <a href="index.php" class="edit-logo">
        Portfolio<span>Gen</span>
    </a>

    <nav class="edit-nav-links" aria-label="Main navigation">
        <a href="index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="create.php">Create Portfolio</a>
        <a href="manage.php">My Portfolios</a>
        <a href="templates.php">Templates</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="edit-page">

    <div class="edit-heading">
        <div class="edit-eyebrow">Make it yours</div>

        <h1>Edit Your <span>Portfolio</span></h1>

        <p>
            Update your information and keep your professional portfolio
            complete, accurate, and ready to share.
        </p>
    </div>

    <section class="edit-card">

        <?php if ($errorMessage !== ''): ?>
            <div class="edit-alert edit-alert-error" role="alert">
                <strong>Please check your information.</strong><br>
                <?= editEscape($errorMessage) ?>
            </div>
        <?php endif; ?>

        <form
            id="editPortfolioForm"
            action="edit.php?id=<?= rawurlencode($id) ?>"
            method="POST"
            novalidate
        >

            <h2 class="edit-section-title">
                <span class="edit-section-number">01</span>
                Personal Information
            </h2>

            <div class="edit-form-grid">

                <div class="edit-field">
                    <label for="full_name">
                        Full Name <span class="edit-required">*</span>
                    </label>

                    <input
                        type="text"
                        id="full_name"
                        name="full_name"
                        value="<?= editValue($portfolio, 'full_name') ?>"
                        placeholder="e.g. Remsert C. Sibay"
                        maxlength="100"
                        autocomplete="name"
                        required
                    >

                    <p class="edit-help">
                        Letters, spaces, apostrophes, and hyphens only.
                        Do not enter numbers.
                    </p>
                </div>

                <div class="edit-field">
                    <label for="email">
                        Email Address <span class="edit-required">*</span>
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= editValue($portfolio, 'email') ?>"
                        placeholder="you@example.com"
                        maxlength="254"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="edit-field">
                    <label for="contact_number">
                        Contact Number <span class="edit-required">*</span>
                    </label>

                    <input
                        type="tel"
                        id="contact_number"
                        name="contact_number"
                        value="<?= editValue($portfolio, 'contact_number') ?>"
                        placeholder="e.g. +63 912 345 6789"
                        maxlength="20"
                        autocomplete="tel"
                        required
                    >

                    <p class="edit-help">
                        Use 7–15 digits. You may use +, spaces, parentheses,
                        dots, and hyphens.
                    </p>
                </div>

                <div class="edit-field">
                    <label for="address">
                        Address <span class="edit-required">*</span>
                    </label>

                    <input
                        type="text"
                        id="address"
                        name="address"
                        value="<?= editValue($portfolio, 'address') ?>"
                        placeholder="City, province, or address"
                        maxlength="255"
                        autocomplete="street-address"
                        required
                    >
                </div>

                <div class="edit-field edit-field-full">
                    <label for="professional_title">
                        Professional Title
                        <span class="edit-required">*</span>
                    </label>

                    <input
                        type="text"
                        id="professional_title"
                        name="professional_title"
                        value="<?= editValue($portfolio, 'professional_title') ?>"
                        placeholder="e.g. Web Developer, IT Student, UI Designer"
                        maxlength="150"
                        required
                    >
                </div>

            </div>

            <h2 class="edit-section-title">
                <span class="edit-section-number">02</span>
                About You
            </h2>

            <div class="edit-form-grid">

                <div class="edit-field edit-field-full">
                    <label for="career_objective">
                        Career Objective
                        <span class="edit-required">*</span>
                    </label>

                    <textarea
                        id="career_objective"
                        name="career_objective"
                        maxlength="3000"
                        minlength="10"
                        placeholder="Describe your professional goals and what you hope to contribute..."
                        required
                    ><?= editValue($portfolio, 'career_objective') ?></textarea>

                    <p class="edit-help">
                        Enter at least 10 characters.
                    </p>
                </div>

                <div class="edit-field edit-field-full">
                    <label for="about_me">
                        About Me <span class="edit-required">*</span>
                    </label>

                    <textarea
                        id="about_me"
                        name="about_me"
                        maxlength="5000"
                        minlength="10"
                        placeholder="Introduce yourself, your interests, and your strengths..."
                        required
                    ><?= editValue($portfolio, 'about_me') ?></textarea>

                    <p class="edit-help">
                        Enter at least 10 characters.
                    </p>
                </div>

                <div class="edit-field edit-field-full">
                    <label for="education">
                        Educational Background
                        <span class="edit-required">*</span>
                    </label>

                    <textarea
                        id="education"
                        name="education"
                        maxlength="5000"
                        minlength="2"
                        placeholder="List your school, course, and relevant educational details..."
                        required
                    ><?= editValue($portfolio, 'education') ?></textarea>
                </div>

                <div class="edit-field edit-field-full">
                    <label for="skills">
                        Skills <span class="edit-required">*</span>
                    </label>

                    <textarea
                        id="skills"
                        name="skills"
                        maxlength="5000"
                        minlength="2"
                        placeholder="e.g. HTML, CSS, PHP, teamwork, communication..."
                        required
                    ><?= editValue($portfolio, 'skills') ?></textarea>
                </div>

            </div>

            <h2 class="edit-section-title">
                <span class="edit-section-number">03</span>
                Professional Details
            </h2>

            <div class="edit-form-grid">

                <div class="edit-field edit-field-full">
                    <label for="projects">
                        Projects
                        <span class="edit-optional">(Optional)</span>
                    </label>

                    <textarea
                        id="projects"
                        name="projects"
                        maxlength="10000"
                        placeholder="Describe your projects and your role in each one..."
                    ><?= editValue($portfolio, 'projects') ?></textarea>
                </div>

                <div class="edit-field edit-field-full">
                    <label for="work_experience">
                        Work Experience
                        <span class="edit-optional">(Optional)</span>
                    </label>

                    <textarea
                        id="work_experience"
                        name="work_experience"
                        maxlength="10000"
                        placeholder="Add your work experience, internship, or relevant activities..."
                    ><?= editValue($portfolio, 'work_experience') ?></textarea>
                </div>

                <div class="edit-field edit-field-full">
                    <label for="social_links">
                        Social Media / Website Links
                        <span class="edit-optional">(Optional)</span>
                    </label>

                    <textarea
                        id="social_links"
                        name="social_links"
                        maxlength="5000"
                        placeholder="Add your LinkedIn, GitHub, personal website, or other links..."
                    ><?= editValue($portfolio, 'social_links') ?></textarea>

                    <p class="edit-help">
                        Use full links such as https://github.com/yourusername.
                    </p>
                </div>

            </div>

            <p class="edit-help">
                Fields marked with
                <span class="edit-required">*</span>
                are required. Make sure your information is correct before saving.
            </p>

            <div class="edit-form-actions">
                <a href="manage.php" class="edit-button edit-button-secondary">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="edit-button edit-button-primary"
                >
                    Save Changes
                </button>
            </div>

        </form>

    </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editPortfolioForm');
    const nameInput = document.getElementById('full_name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('contact_number');

    if (!form || !nameInput || !emailInput || !phoneInput) {
        return;
    }

    function validateName() {
        const name = nameInput.value.trim();
        const namePattern = /^\p{L}+(?:[ '\u2019-]\p{L}+)*$/u;

        if (name === '') {
            nameInput.setCustomValidity('Please enter your full name.');
        } else if (!namePattern.test(name)) {
            nameInput.setCustomValidity(
                'Use letters, spaces, apostrophes, and hyphens only. Numbers are not allowed.'
            );
        } else {
            nameInput.setCustomValidity('');
        }
    }

    function validatePhone() {
        const phone = phoneInput.value.trim();
        const digits = phone.replace(/\D/g, '');

        if (phone === '') {
            phoneInput.setCustomValidity(
                'Please enter your contact number.'
            );
        } else if (!/^[0-9+\s().-]+$/.test(phone)) {
            phoneInput.setCustomValidity(
                'The contact number contains invalid characters.'
            );
        } else if (digits.length < 7 || digits.length > 15) {
            phoneInput.setCustomValidity(
                'Your contact number must contain 7 to 15 digits.'
            );
        } else {
            phoneInput.setCustomValidity('');
        }
    }

    function validateEmail() {
        const email = emailInput.value.trim();

        if (email === '') {
            emailInput.setCustomValidity(
                'Please enter your email address.'
            );
        } else if (!emailInput.validity.valid) {
            emailInput.setCustomValidity(
                'Please enter a valid email address.'
            );
        } else {
            emailInput.setCustomValidity('');
        }
    }

    nameInput.addEventListener('input', validateName);
    phoneInput.addEventListener('input', validatePhone);
    emailInput.addEventListener('input', validateEmail);

    form.addEventListener('submit', function (event) {
        validateName();
        validatePhone();
        validateEmail();

        if (!form.checkValidity()) {
            event.preventDefault();
            form.reportValidity();
        }
    });
});
</script>

<?php include __DIR__ . '/footer.php'; ?>

</body>
</html>
