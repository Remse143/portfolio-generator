
<?php
session_start();

require_once __DIR__ . '/config/database.php';

$error = '';

if (isset($_SESSION['user_id'])) {
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
            } elseif (stripos($message, 'already registered') !== false) {
                $error = 'This email is already registered. Please sign in.';
            } elseif ($httpCode >= 200 && $httpCode < 300) {
                $error = 'Supabase accepted the request but returned no account ID. Check Authentication → Users in Supabase.';
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

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: Arial, sans-serif;
            color: #fff;
            background:
                radial-gradient(circle at 15% 20%, rgba(225,29,72,.2), transparent 35%),
                radial-gradient(circle at 85% 80%, rgba(168,85,247,.2), transparent 35%),
                #08070d;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 22px 7%;
        }

        .logo {
            color: #fff;
            font-size: 25px;
            font-weight: 800;
            text-decoration: none;
        }

        .logo span {
            color: #e11d48;
        }

        .nav-link {
            color: #fff;
            text-decoration: none;
            border: 1px solid #443344;
            border-radius: 9px;
            padding: 10px 16px;
        }

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 18px 50px;
        }

        .register-card {
            width: 100%;
            max-width: 450px;
            padding: 35px;
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 20px;
            background: rgba(19,15,28,.94);
            box-shadow: 0 20px 60px rgba(0,0,0,.35);
        }

        .heading {
            text-align: center;
            margin-bottom: 25px;
        }

        .heading h1 {
            font-size: 30px;
            margin-bottom: 10px;
        }

        .heading p {
            color: #b8b2c7;
            font-size: 14px;
            line-height: 1.6;
        }

        .field {
            margin-bottom: 19px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 13px;
            color: #fff;
            background: #100d17;
            border: 1px solid #393243;
            border-radius: 9px;
            outline: none;
            font-size: 15px;
        }

        input:focus {
            border-color: #e11d48;
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap input {
            padding-right: 65px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: none;
            color: #fb7185;
            cursor: pointer;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 9px;
            background: linear-gradient(110deg, #e11d48, #9f1239);
            color: #fff;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        .submit-btn:hover {
            opacity: .9;
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .error {
            color: #ffb8c6;
            background: rgba(225,29,72,.12);
            border: 1px solid rgba(225,29,72,.4);
        }

        .success {
            color: #bbf7d0;
            background: rgba(34,197,94,.12);
            border: 1px solid rgba(34,197,94,.4);
        }

        .bottom-text {
            text-align: center;
            margin-top: 22px;
            color: #b8b2c7;
            font-size: 14px;
            line-height: 1.7;
        }

        .bottom-text a {
            color: #fb7185;
            font-weight: bold;
            text-decoration: none;
        }

        @media (max-width: 480px) {
            .register-card {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>
    <header class="navbar">
        <a href="index.php" class="logo">Portfolio<span>Gen</span></a>
        <a href="login.php" class="nav-link">Sign In</a>
    </header>

    <main>
        <section class="register-card">
            <div class="heading">
                <h1>Create Account</h1>
                <p>Create your account and start building your professional portfolio.</p>
            </div>

            <?php if (isset($_GET['registered'])): ?>
                <div class="message success">
                    Account created successfully. Please sign in.
                </div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <div class="message error" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="field">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="you@example.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="password-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            minlength="6"
                            placeholder="Enter your password"
                            autocomplete="new-password"
                            required
                        >
                        <button type="button" class="toggle-password" data-target="password">Show</button>
                    </div>
                </div>

                <div class="field">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="password-wrap">
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            minlength="6"
                            placeholder="Confirm your password"
                            autocomplete="new-password"
                            required
                        >
                        <button type="button" class="toggle-password" data-target="confirm_password">Show</button>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Create Account</button>
            </form>

            <p class="bottom-text">
                Already have an account?
                <a href="login.php">Sign In</a>
            </p>
        </section>
    </main>

    <?php
    if (file_exists(__DIR__ . '/footer.php')) {
        include __DIR__ . '/footer.php';
    }
    ?>

    <script>
        document.querySelectorAll('.toggle-password').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.target);

                if (!input) return;

                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                this.textContent = show ? 'Hide' : 'Show';
            });
        });
    </script>
</body>
</html>
