
<?php
session_start();

require_once __DIR__ . '/config/database.php';

$message = '';
$messageType = '';
$success = false;

// Handle the new password submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accessToken = trim($_POST['access_token'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($accessToken)) {
        $message = 'Your reset link is invalid or expired. Please request another one.';
    } elseif (strlen($password) < 8) {
        $message = 'Your password must contain at least 8 characters.';
    } elseif ($password !== $confirmPassword) {
        $message = 'Your passwords do not match.';
    } else {
        $url = rtrim($supabaseUrl, '/') . '/auth/v1/user';

        $payload = json_encode([
            'password' => $password
        ]);

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'apikey: ' . $supabaseKey,
                'Authorization: Bearer ' . $accessToken
            ],
            CURLOPT_TIMEOUT => 20
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        curl_close($ch);

        if ($response !== false && $httpCode >= 200 && $httpCode < 300) {
            $success = true;
            $message = 'Your password has been changed successfully!';
        } elseif ($response === false) {
            $message = 'Connection error. Please try again.';
        } else {
            $data = json_decode($response, true);
            $message = $data['msg']
                ?? $data['message']
                ?? 'Unable to reset your password. Your link may have expired.';
        }
    }

    $messageType = $success ? 'success' : 'error';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | PortfolioGen</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">

    <style>
        .reset-wrapper {
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .reset-card {
            width: 100%;
            max-width: 430px;
            padding: 35px;
            border: 1px solid rgba(225, 29, 72, 0.45);
            border-radius: 16px;
            background: rgba(12, 8, 20, 0.94);
            box-shadow: 0 0 30px rgba(225, 29, 72, 0.12);
            color: #fff;
        }

        .reset-card h1 {
            text-align: center;
            margin-bottom: 10px;
            color: #f43f5e;
        }

        .reset-description {
            text-align: center;
            color: #c4b5d0;
            margin-bottom: 25px;
        }

        .reset-card label {
            display: block;
            margin: 15px 0 8px;
            color: #f3e8ff;
        }

        .reset-card input {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            border: 1px solid #51304f;
            border-radius: 8px;
            background: #100b18;
            color: white;
            outline: none;
        }

        .reset-card input:focus {
            border-color: #e11d48;
        }

        .reset-button {
            width: 100%;
            margin-top: 22px;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #e11d48;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .reset-button:hover {
            background: #be123c;
        }

        .reset-message {
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
            overflow-wrap: anywhere;
        }

        .reset-message.success {
            background: #123a2a;
            color: #bbf7d0;
        }

        .reset-message.error {
            background: #451a24;
            color: #fecdd3;
        }

        .reset-links {
            margin-top: 20px;
            text-align: center;
        }

        .reset-links a {
            color: #fb7185;
            text-decoration: none;
        }

        .reset-links a:hover {
            text-decoration: underline;
        }

        .reset-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

<?php
// Reuse your existing navigation if your project has a navbar include.
// For example: require_once __DIR__ . '/navbar.php';
?>

<main class="reset-wrapper">
    <section class="reset-card">
        <h1>Reset Password</h1>

        <p class="reset-description">
            Create a new password for your PortfolioGen account.
        </p>

        <?php if ($message !== ''): ?>
            <div class="reset-message <?= htmlspecialchars($messageType) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
            <form method="POST" id="resetForm">
                <input
                    type="hidden"
                    name="access_token"
                    id="access_token"
                >

                <label for="password">New Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    minlength="8"
                    required
                    autocomplete="new-password"
                    placeholder="Enter your new password"
                >

                <label for="confirm_password">Confirm New Password</label>
                <input
                    type="password"
                    name="confirm_password"
                    id="confirm_password"
                    minlength="8"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm your new password"
                >

                <button
                    type="submit"
                    class="reset-button"
                    id="resetButton"
                >
                    Update Password
                </button>
            </form>

            <p id="linkError" class="reset-message error" hidden>
                Your reset link is missing or invalid. Please request
                a new password reset email.
            </p>
        <?php endif; ?>

        <div class="reset-links">
            <?php if ($success): ?>
                <a href="login.php">Return to Login</a>
            <?php else: ?>
                <a href="forgot_password.php">Request another reset link</a>
                <br><br>
                <a href="login.php">Back to Login</a>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('resetForm');

    if (!form) return;

    const tokenInput = document.getElementById('access_token');
    const linkError = document.getElementById('linkError');
    const resetButton = document.getElementById('resetButton');

    // Supabase's implicit-flow recovery links return tokens
    // in the URL fragment, after the # symbol.
    const fragment = new URLSearchParams(
        window.location.hash.substring(1)
    );

    const accessToken = fragment.get('access_token');
    const recoveryType = fragment.get('type');

    // Do not accept a normal sign-in token as a recovery session.
    if (!accessToken || recoveryType !== 'recovery') {
        form.hidden = true;
        linkError.hidden = false;
        return;
    }

    tokenInput.value = accessToken;

    // Remove the token from the visible URL after reading it.
    history.replaceState(
        null,
        document.title,
        window.location.pathname + window.location.search
    );

    form.addEventListener('submit', function (event) {
        const password = document.getElementById('password').value;
        const confirmation =
            document.getElementById('confirm_password').value;

        if (password !== confirmation) {
            event.preventDefault();
            alert('Your passwords do not match.');
            return;
        }

        if (!tokenInput.value) {
            event.preventDefault();
            linkError.hidden = false;
            return;
        }

        resetButton.disabled = true;
        resetButton.textContent = 'Updating Password...';
    });
});
</script>

</body>
</html>
                