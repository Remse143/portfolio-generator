
<?php
session_start();

require_once __DIR__ . '/config/database.php';

$error = '';

if (isset($_SESSION['access_token']) || isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($email === '' || $password === '' || $confirmPassword === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (empty($supabaseUrl) || empty($supabaseKey)) {
        $error = 'Supabase configuration is missing.';
    } elseif (!function_exists('curl_init')) {
        $error = 'PHP cURL is not enabled.';
    } else {
        $url = rtrim($supabaseUrl, '/') . '/auth/v1/signup';

        $payload = json_encode([
            'email' => $email,
            'password' => $password
        ]);

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'apikey: ' . $supabaseKey
            ],
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        curl_close($ch);

        if ($response === false || $curlError !== '') {
            error_log('PortfolioGen signup connection error: ' . $curlError);
            $error = 'Unable to connect. Please try again.';
        } else {
            $result = json_decode($response, true);

            $userId = $result['id'] ?? $result['user']['id'] ?? null;

            if ($httpCode >= 200 && $httpCode < 300 && $userId) {
                header('Location: login.php?registered=1');
                exit;
            }

            $message = '';

            if (is_array($result)) {
                $message = $result['msg']
                    ?? $result['message']
                    ?? $result['error_description']
                    ?? $result['error']
                    ?? '';
            }

            error_log(
                'PortfolioGen signup failed. HTTP ' .
                $httpCode . ': ' . substr($response, 0, 1000)
            );

            if ($httpCode === 429) {
                $error = 'Too many attempts. Please wait and try again.';
            } elseif (
                stripos($message, 'already registered') !== false ||
                stripos($message, 'already exists') !== false
            ) {
                $error = 'This email is already registered. Please sign in.';
            } elseif ($httpCode >= 200 && $httpCode < 300) {
                $error = 'Registration response was incomplete. Check Authentication → Users in Supabase.';
            } else {
                $error = $message !== ''
                    ? $message
                    : 'Registration failed. Please check your details.';
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

    <title>Sign Up | PortfolioGen</title>

    <!-- Shared PortfolioGen styles -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">

    <!-- Footer icons -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <style>
        /*
         * Keep the registration styles scoped so they don't
         * overwrite the shared footer's layout and spacing.
         */

        .register-page,
        .register-page *,
        .register-page *::before,
        .register-page *::after {
            box-sizing: border-box;
        }

        .register-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: Arial, Helvetica, sans-serif;
            color: #fff;

            background:
                radial-gradient(
                    circle at 15% 20%,
                    rgba(225, 29, 72, .20),
                    transparent 35%
                ),
                radial-gradient(
                    circle at 85% 80%,
                    rgba(168, 85, 247, .20),
                    transparent 35%
                ),
                #08070d;
        }

        .register-page .register-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 22px 7%;
        }

        .register-page .register-logo {
            color: #fff;
            font-size: 25px;
            font-weight: 800;
            text-decoration: none;
        }

        .register-page .register-logo span {
            color: #e11d48;
        }

        .register-page .register-nav-link {
            display: inline-block;
            color: #fff;
            text-decoration: none;
            border: 1px solid #443344;
            border-radius: 9px;
            padding: 10px 16px;
            transition: background .2s ease, border-color .2s ease;
        }

        .register-page .register-nav-link:hover {
            background: rgba(225, 29, 72, .12);
            border-color: #e11d48;
        }

        .register-page .register-main {
            flex: 1;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 18px 50px;
        }

        .register-page .register-card {
            width: 100%;
            max-width: 450px;
            padding: 35px;

            color: #fff;
            border: 1px solid rgba(255, 255, 255, .13);
            border-radius: 20px;

            background: rgba(19, 15, 28, .94);

            box-shadow:
                0 20px 60px rgba(0, 0, 0, .35),
                0 0 30px rgba(168, 85, 247, .06);
        }

        .register-page .register-heading {
            text-align: center;
            margin-bottom: 25px;
        }

        .register-page .register-heading h1 {
            color: #fff;
            font-size: 30px;
            line-height: 1.3;
            margin: 0 0 10px;
        }

        .register-page .register-heading p {
            color: #b8b2c7;
            font-size: 14px;
            line-height: 1.7;
            margin: 0;
        }

        .register-page .register-field {
            margin-bottom: 19px;
        }

        .register-page .register-field label {
            display: block;
            color: #fff;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .register-page .register-field input {
            display: block;
            width: 100%;
            min-width: 0;
            padding: 13px;

            color: #fff;
            background: #100d17;

            border: 1px solid #393243;
            border-radius: 9px;
            outline: none;

            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;

            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .register-page .register-field input::placeholder {
            color: #82798f;
        }

        .register-page .register-field input:focus {
            border-color: #e11d48;
            box-shadow: 0 0 0 3px rgba(225, 29, 72, .12);
        }

        .register-page .register-password-wrap {
            position: relative;
            width: 100%;
        }

        .register-page .register-password-wrap input {
            padding-right: 70px;
        }

        .register-page .register-toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);

            padding: 5px 0;
            border: 0;
            background: transparent;

            color: #fb7185;
            font-family: inherit;
            font-size: 13px;

            cursor: pointer;
        }

        .register-page .register-toggle-password:hover {
            color: #fff;
        }

        .register-page .register-submit {
            display: block;
            width: 100%;
            padding: 14px;

            border: none;
            border-radius: 9px;

            background: linear-gradient(110deg, #e11d48, #9f1239);
            color: #fff;

            font-family: inherit;
            font-size: 15px;
            font-weight: bold;

            cursor: pointer;

            box-shadow: 0 8px 20px rgba(225, 29, 72, .15);

            transition: transform .2s ease, opacity .2s ease;
        }

        .register-page .register-submit:hover {
            opacity: .92;
            transform: translateY(-1px);
        }

        .register-page .register-message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .register-page .register-error {
            color: #ffb8c6;
            background: rgba(225, 29, 72, .12);
            border: 1px solid rgba(225, 29, 72, .4);
        }

        .register-page .register-success {
            color: #bbf7d0;
            background: rgba(34, 197, 94, .12);
            border: 1px solid rgba(34, 197, 94, .4);
        }

        .register-page .register-bottom-text {
            text-align: center;
            margin: 22px 0 0;
            color: #b8b2c7;
            font-size: 14px;
            line-height: 1.7;
        }

        .register-page .register-bottom-text a {
            color: #fb7185;
            font-weight: bold;
            text-decoration: none;
        }

        .register-page .register-bottom-text a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .register-page .register-navbar {
                padding: 18px 5%;
            }

            .register-page .register-logo {
                font-size: 22px;
            }

            .register-page .register-main {
                padding: 25px 14px 35px;
            }

            .register-page .register-card {
                padding: 25px 20px;
            }

            .register-page .register-heading h1 {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

    <div class="register-page">

        <header class="register-navbar">
            <a href="index.php" class="register-logo">
                Portfolio<span>Gen</span>
            </a>

            <a href="login.php" class="register-nav-link">
                Sign In
            </a>
        </header>

        <main class="register-main">
            <section class="register-card">

                <div class="register-heading">
                    <h1>Create Account</h1>
                    <p>
                        Create your account and start building your
                        professional portfolio.
                    </p>
                </div>

                <?php if (isset($_GET['registered'])): ?>
                    <div class="register-message register-success" role="status">
                        Account created successfully. Please sign in.
                    </div>
                <?php endif; ?>

                <?php if ($error !== ''): ?>
                    <div class="register-message register-error" role="alert">
                        <?= htmlspecialchars(
                            $error,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>
                <?php endif; ?>

                <form action="register.php" method="POST">

                    <div class="register-field">
                        <label for="email">Email Address</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="you@example.com"
                            value="<?= htmlspecialchars(
                                $_POST['email'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <div class="register-field">
                        <label for="password">Password</label>

                        <div class="register-password-wrap">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                minlength="6"
                                placeholder="Enter your password"
                                autocomplete="new-password"
                                required
                            >

                            <button
                                type="button"
                                class="register-toggle-password"
                                data-target="password"
                                aria-controls="password"
                                aria-label="Show password"
                            >Show</button>
                        </div>
                    </div>

                    <div class="register-field">
                        <label for="confirm_password">
                            Confirm Password
                        </label>

                        <div class="register-password-wrap">
                            <input
                                type="password"
                                id="confirm_password"
                                name="confirm_password"
                                minlength="6"
                                placeholder="Confirm your password"
                                autocomplete="new-password"
                                required
                            >

                            <button
                                type="button"
                                class="register-toggle-password"
                                data-target="confirm_password"
                                aria-controls="confirm_password"
                                aria-label="Show confirm password"
                            >Show</button>
                        </div>
                    </div>

                    <button type="submit" class="register-submit">
                        Create Account
                    </button>

                </form>

                <p class="register-bottom-text">
                    Already have an account?
                    <a href="login.php">Sign In</a>
                </p>

            </section>
        </main>

    </div>

    <!-- Keep the original shared footer outside the page wrapper -->
    <?php include __DIR__ . '/footer.php'; ?>

    <script>
        document.querySelectorAll(
            '.register-toggle-password'
        ).forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(
                    this.dataset.target
                );

                if (!input) {
                    return;
                }

                const showPassword = input.type === 'password';

                input.type = showPassword ? 'text' : 'password';
                this.textContent = showPassword ? 'Hide' : 'Show';

                this.setAttribute(
                    'aria-label',
                    showPassword ? 'Hide password' : 'Show password'
                );
            });
        });
    </script>

</body>
</html>
