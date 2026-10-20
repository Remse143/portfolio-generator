
<?php
session_start();

require_once __DIR__ . "/config/database.php";

$message = "";

// =====================================================
// REDIRECT ALREADY-LOGGED-IN USERS
// =====================================================

if (!empty($_SESSION['access_token']) && !empty($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

// =====================================================
// REGISTERED MESSAGE
// =====================================================

if (isset($_GET['registered'])) {
    $message = "Account created successfully. Please sign in.";
}

// =====================================================
// LOGIN
// =====================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {

        $message = "Please enter your email and password.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } elseif (
        empty($supabaseUrl) ||
        empty($supabaseKey) ||
        !filter_var($supabaseUrl, FILTER_VALIDATE_URL)
    ) {

        error_log("PortfolioGen: Invalid Supabase configuration.");
        $message = "The login service is temporarily unavailable.";

    } elseif (!function_exists('curl_init')) {

        error_log("PortfolioGen: PHP cURL extension is unavailable.");
        $message = "The login service is temporarily unavailable.";

    } else {

        $url = rtrim($supabaseUrl, '/') .
            "/auth/v1/token?grant_type=password";

        $payload = json_encode([
            'email' => $email,
            'password' => $password
        ]);

        if ($payload === false) {

            $message = "Unable to process your login. Please try again.";

        } else {

            $ch = curl_init($url);

            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'apikey: ' . $supabaseKey
                ],
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 30
            ]);

            $response = curl_exec($ch);
            $curlError = curl_error($ch);
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if ($response === false) {

                error_log(
                    "PortfolioGen login connection error: " . $curlError
                );

                $message = "Unable to connect to the login service. Please try again.";

            } else {

                $result = json_decode($response, true);

                if (
                    $httpCode >= 200 &&
                    $httpCode < 300 &&
                    is_array($result) &&
                    !empty($result['access_token']) &&
                    !empty($result['user']['id'])
                ) {

                    // LOGIN SUCCESS

                    session_regenerate_id(true);

                    $_SESSION['access_token'] = $result['access_token'];
                    $_SESSION['refresh_token'] = $result['refresh_token'] ?? '';
                    $_SESSION['user_id'] = $result['user']['id'];
                    $_SESSION['user_email'] = $result['user']['email'] ?? $email;

                    header("Location: dashboard.php");
                    exit;

                } else {

                    error_log(
                        "PortfolioGen login failed. HTTP "
                        . $httpCode
                        . "; Response: "
                        . substr($response, 0, 1000)
                    );

                    $errorCode = is_array($result)
                        ? (string) ($result['error_code'] ?? '')
                        : '';

                    $errorText = is_array($result)
                        ? (string) (
                            $result['msg']
                            ?? $result['error_description']
                            ?? $result['message']
                            ?? $result['error']
                            ?? ''
                        )
                        : '';

                    $lowerError = strtolower($errorText . ' ' . $errorCode);

                    if (
                        strpos($lowerError, 'email_not_confirmed') !== false ||
                        strpos($lowerError, 'email not confirmed') !== false
                    ) {

                        $message = "Please confirm your email address before signing in.";

                    } elseif (
                        strpos($lowerError, 'invalid login credentials') !== false ||
                        strpos($lowerError, 'invalid_credentials') !== false
                    ) {

                        $message = "Invalid email or password. If you just registered, check whether your email is confirmed.";

                    } elseif ($httpCode === 429) {

                        $message = "Too many login attempts. Please wait and try again.";

                    } else {

                        $message = "Unable to sign in. Please check your details and try again.";
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign In - PortfolioGen</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">

    <!-- Font Awesome icons -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        referrerpolicy="no-referrer"
    >

    <style>
        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-wrapper .form-control {
            width: 100%;
            box-sizing: border-box;
            padding-right: 65px;
        }

        .password-wrapper .show-password {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            min-width: 42px;
            min-height: 40px;
            padding: 5px;
            border: none;
            background: transparent;
            color: inherit;
            font-size: 20px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 8px;
            z-index: 5;
            pointer-events: auto;
        }

        .password-wrapper .show-password:hover {
            color: #fb7185;
            background: rgba(225, 29, 72, 0.15);
        }

        .password-wrapper .show-password:focus-visible {
            outline: 2px solid #fb7185;
            outline-offset: 2px;
        }

        .forgot-password-row {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .forgot-password-link {
            color: #fb7185;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .forgot-password-link:hover {
            color: #a78bfa;
            text-decoration: underline;
        }

        .message-error {
            overflow-wrap: anywhere;
            margin-bottom: 18px;
        }
    </style>

    <!-- Load the existing site JavaScript once -->
    <script src="js/script.js" defer></script>

</head>

<body>

<!-- ANIMATED BACKGROUND -->

<div class="animated-background" aria-hidden="true">

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
    <div class="container navbar-content">

        <a href="index.php" class="logo">PortfolioGen</a>

        <div class="nav-links">

            <a href="index.php">Home</a>
            <a href="register.php">Sign Up</a>

            <button
                type="button"
                id="themeToggle"
                class="theme-toggle"
                aria-label="Toggle dark mode"
            >🌙</button>

        </div>
    </div>
</nav>

<!-- LOGIN SECTION -->

<section class="section">
    <div class="container">

        <div class="auth-wrapper">
            <div class="auth-card">

                <div class="form-header">

                    <span class="hero-badge">🔐 Welcome Back</span>

                    <h1>Sign In</h1>

                    <p>
                        Sign in to manage your professional portfolios.
                    </p>

                </div>

                <?php if ($message !== ''): ?>
                    <div class="message-error" role="alert" aria-live="polite">
                        <?= htmlspecialchars(
                            $message,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>
                <?php endif; ?>

                <!-- LOGIN FORM -->

                <form method="POST" action="login.php">

                    <div class="form-group">

                        <label for="email">📧 Email</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            placeholder="Enter your email"
                            value="<?= htmlspecialchars(
                                $_POST['email'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            autocomplete="email"
                            maxlength="254"
                            required
                        >

                    </div>

                    <div class="form-group">

                        <label for="password">🔒 Password</label>

                        <div class="password-wrapper">

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                id="showPassword"
                                class="show-password"
                                aria-label="Show password"
                                aria-controls="password"
                                aria-pressed="false"
                                title="Show password"
                            >
                                <i class="fa-solid fa-eye" aria-hidden="true"></i>
                            </button>

                        </div>

                        <div class="forgot-password-row">

                            <a
                                href="forgot_password.php"
                                class="forgot-password-link"
                            >
                                Forgot Password?
                            </a>

                        </div>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary auth-button"
                    >
                        Sign In →
                    </button>

                </form>

                <p class="auth-footer">
                    Don't have an account?
                    <a href="register.php">Sign Up</a>
                </p>

            </div>
        </div>

    </div>
</section>

<!-- SHARED FOOTER -->
<?php include __DIR__ . '/footer.php'; ?>