<?php
session_start();
require_once __DIR__ . '/config/database.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $messageType = 'error';
    } else {
        $redirectUrl = 'http://localhost/portfolio-generator/reset_password.php';

        $url = rtrim($supabaseUrl, '/') . '/auth/v1/recover?redirect_to='
             . urlencode($redirectUrl);

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode(['email' => $email]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'apikey: ' . $supabaseKey
            ],
            CURLOPT_TIMEOUT => 20
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response !== false && $httpCode >= 200 && $httpCode < 300) {
            $message = 'If an account exists for this email, a reset link will be sent. Please check your inbox and spam folder.';
            $messageType = 'success';
        } elseif ($httpCode === 429) {
            $message = 'Too many requests. Please wait before requesting another reset email.';
            $messageType = 'error';
        } else {
            $message = 'Unable to send the reset email. Please try again later.';
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | PortfolioGen</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">

    <style>
        body {
            position: relative;
            min-height: 100vh;
            background: #07060f;
            color: #fff;
            overflow-x: hidden;
        }

        /* Animated crimson and purple background */
        .background-animation {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
            background: radial-gradient(
                ellipse at top,
                #14081f 0%,
                #07060f 70%
            );
        }

        .gradient-orb {
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.4;
            animation: floatOrb 12s ease-in-out infinite alternate;
        }

        .orb-1 {
            top: 5%;
            left: -100px;
            background: #e11d48;
        }

        .orb-2 {
            top: 40%;
            right: -100px;
            background: #7e22ce;
            animation-delay: -4s;
        }

        .orb-3 {
            bottom: -150px;
            left: 30%;
            background: #9f1239;
            animation-delay: -8s;
        }

        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, -30px) scale(1.15); }
            100% { transform: translate(-30px, 40px) scale(0.9); }
        }

        .forgot-wrapper {
            position: relative;
            z-index: 1;
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 20px;
        }

        .forgot-card {
            width: 100%;
            max-width: 430px;
            box-sizing: border-box;
            padding: 35px;
            border: 1px solid rgba(225, 29, 72, 0.45);
            border-radius: 18px;
            background: rgba(12, 8, 20, 0.88);
            backdrop-filter: blur(12px);
            box-shadow: 0 0 35px rgba(225, 29, 72, 0.15);
        }

        .forgot-icon {
            width: 65px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: rgba(225, 29, 72, 0.12);
            border: 1px solid rgba(225, 29, 72, 0.45);
            font-size: 29px;
        }

        .forgot-card h1 {
            text-align: center;
            margin: 0 0 12px;
            color: #fb7185;
            font-size: 28px;
        }

        .forgot-description {
            text-align: center;
            color: #c4b5d0;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .forgot-card label {
            display: block;
            margin-bottom: 9px;
            color: #f3e8ff;
        }

        .forgot-card input {
            width: 100%;
            box-sizing: border-box;
            padding: 13px 14px;
            border: 1px solid #51304f;
            border-radius: 9px;
            background: #100b18;
            color: #fff;
            font-size: 15px;
            outline: none;
        }

        .forgot-card input:focus {
            border-color: #e11d48;
            box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.13);
        }

        .forgot-button {
            width: 100%;
            margin-top: 22px;
            padding: 14px;
            border: none;
            border-radius: 9px;
            background: linear-gradient(135deg, #e11d48, #9f1239);
            color: #fff;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .forgot-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(225, 29, 72, 0.25);
        }

        .forgot-button:disabled {
            opacity: 0.65;
            cursor: wait;
        }

        .forgot-message {
            padding: 13px;
            margin-bottom: 20px;
            border-radius: 9px;
            font-size: 14px;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .forgot-message.success {
            background: rgba(22, 101, 52, 0.25);
            border: 1px solid #166534;
            color: #bbf7d0;
        }

        .forgot-message.error {
            background: rgba(159, 18, 57, 0.2);
            border: 1px solid #9f1239;
            color: #fecdd3;
        }

        .forgot-links {
            margin-top: 24px;
            text-align: center;
            color: #c4b5d0;
            font-size: 14px;
        }

        .forgot-links a {
            color: #fb7185;
            text-decoration: none;
        }

        .forgot-links a:hover {
            color: #fda4af;
            text-decoration: underline;
        }

        .site-footer {
            position: relative;
            z-index: 1;
        }

        @media (max-width: 480px) {
            .forgot-card { padding: 25px 20px; }
            .forgot-card h1 { font-size: 25px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .gradient-orb { animation: none; }
        }
    </style>
</head>
<body>

<div class="background-animation" aria-hidden="true">
    <div class="gradient-orb orb-1"></div>
    <div class="gradient-orb orb-2"></div>
    <div class="gradient-orb orb-3"></div>
</div>

<main class="forgot-wrapper">
    <section class="forgot-card">
        <div class="forgot-icon">🔐</div>

        <h1>Forgot Password?</h1>

        <p class="forgot-description">
            No worries! Enter the email address connected to your
            PortfolioGen account and we'll send you a password reset link.
        </p>

        <?php if ($message !== ''): ?>
            <div class="forgot-message <?= htmlspecialchars($messageType) ?>"
                 role="status">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="forgotForm">
            <label for="email">Email Address</label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your registered email"
                autocomplete="email"
                required
                value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >

            <button type="submit" class="forgot-button" id="submitButton">
                Request Reset Link
            </button>
        </form>

        <div class="forgot-links">
            Remember your password?
            <a href="login.php">Back to Login</a>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

<script>
document.getElementById('forgotForm').addEventListener('submit', function () {
    const button = document.getElementById('submitButton');
    button.disabled = true;
    button.textContent = 'Sending...';
});
</script>

</body>
</html>
