
<?php

session_start();

require_once __DIR__ . "/config/database.php";

$message = "";

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

    } else {

        $url = rtrim($supabaseUrl, '/') .
            "/auth/v1/token?grant_type=password";

        $data = [
            "email" => $email,
            "password" => $password
        ];

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: " . $supabaseKey
            ],
            CURLOPT_TIMEOUT => 20
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $result = json_decode($response ?: '', true);

        if ($response === false) {

            $message = "Unable to connect to the login service. Please try again.";

        } elseif (
            $httpCode >= 200 &&
            $httpCode < 300 &&
            !empty($result['access_token']) &&
            !empty($result['user']['id'])
        ) {

            session_regenerate_id(true);

            $_SESSION['access_token'] = $result['access_token'];
            $_SESSION['refresh_token'] = $result['refresh_token'] ?? '';
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['user_email'] = $result['user']['email'] ?? $email;

            header("Location: dashboard.php");
            exit;

        } else {

            $message = $result['msg']
                ?? $result['error_description']
                ?? $result['message']
                ?? "Invalid email or password.";
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

    <style>
        /* PASSWORD FIELD */

        .password-wrapper {
            position: relative;
            width: 100%;
            isolation: isolate;
        }

        .password-wrapper .form-control {
            width: 100%;
            box-sizing: border-box;
            padding-right: 55px;
            position: relative;
            z-index: 1;
        }

        /* SHOW / HIDE PASSWORD BUTTON */

        .password-wrapper .show-password {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);

            width: 40px;
            height: 40px;
            padding: 0;

            border: none;
            outline: none;
            background: transparent;

            color: #ffffff;
            font-size: 20px;
            line-height: 1;

            display: flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;
            pointer-events: auto;
            touch-action: manipulation;

            border-radius: 8px;
            z-index: 5;

            transition:
                background 0.2s ease,
                color 0.2s ease;
        }

        .password-wrapper .show-password:hover {
            color: #fb7185;
            background: rgba(225, 29, 72, 0.15);
        }

        .password-wrapper .show-password:focus-visible {
            outline: 2px solid #fb7185;
            outline-offset: 2px;
        }

        /* FORGOT PASSWORD */

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
            transition: color 0.2s ease;
        }

        .forgot-password-link:hover {
            color: #a78bfa;
            text-decoration: underline;
        }

        /* LOGIN MESSAGE */

        .message-error {
            overflow-wrap: anywhere;
        }
    </style>

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

            <a href="index.php" class="logo">
                PortfolioGen
            </a>

            <div class="nav-links">

                <a href="index.php">Home</a>
                <a href="register.php">Sign Up</a>

                <button
                    type="button"
                    id="themeToggle"
                    class="theme-toggle"
                    aria-label="Toggle dark mode"
                >
                    🌙
                </button>

            </div>

        </div>

    </nav>

    <!-- SIGN IN SECTION -->

    <section class="section">

        <div class="container">

            <div class="auth-wrapper">

                <div class="auth-card">

                    <!-- HEADER -->

                    <div class="form-header">

                        <span class="hero-badge">
                            🔐 Welcome Back
                        </span>

                        <h1>Sign In</h1>

                        <p>
                            Sign in to manage your
                            professional portfolios.
                        </p>

                    </div>

                    <!-- MESSAGE -->

                    <?php if ($message !== ''): ?>

                        <div
                            class="message-error"
                            role="alert"
                            aria-live="polite"
                        >
                            <?= htmlspecialchars(
                                $message,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </div>

                    <?php endif; ?>

                    <!-- LOGIN FORM -->

                    <form method="POST" action="login.php">

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
                                    $_POST['email'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                autocomplete="email"
                                required
                            >

                        </div>

                        <!-- PASSWORD -->

                        <div class="form-group">

                            <label for="password">
                                🔒 Password
                            </label>

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
                                >
                                    👁️
                                </button>

                            </div>

                            <!-- FORGOT PASSWORD -->

                            <div class="forgot-password-row">

                                <a
                                    href="forgot_password.php"
                                    class="forgot-password-link"
                                >
                                    Forgot Password?
                                </a>

                            </div>

                        </div>

                        <!-- SUBMIT -->

                        <button
                            type="submit"
                            class="btn btn-primary auth-button"
                        >
                            Sign In →
                        </button>

                    </form>

                    <!-- SIGN UP LINK -->

                    <p class="auth-footer">

                        Don't have an account?

                        <a href="register.php">
                            Sign Up
                        </a>

                    </p>

                </div>

            </div>

        </div>

    </section>

    <!-- SHARED FOOTER -->
    <?php include __DIR__ . '/footer.php'; ?>

    <!-- EXISTING SITE JAVASCRIPT -->
    <script src="js/script.js"></script>

</body>
</html>
