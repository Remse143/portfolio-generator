
<?php

require_once __DIR__ . '/config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Refresh an expired Supabase access token.
 * Returns true if successful, false otherwise.
 */
function refreshSupabaseToken(): bool
{
    if (empty($_SESSION['refresh_token'])) {
        return false;
    }

    global $supabaseUrl, $supabaseKey;

    $url = rtrim($supabaseUrl, '/') . '/auth/v1/token?grant_type=refresh_token';

    $payload = json_encode([
        'refresh_token' => $_SESSION['refresh_token']
    ]);

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'apikey: ' . $supabaseKey
        ],
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_TIMEOUT => 15
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($response === false || $httpCode < 200 || $httpCode >= 300) {
        return false;
    }

    $result = json_decode($response, true);

    if (
        empty($result['access_token']) ||
        empty($result['refresh_token'])
    ) {
        return false;
    }

    // Save the new tokens in the session.
    $_SESSION['access_token'] = $result['access_token'];
    $_SESSION['refresh_token'] = $result['refresh_token'];

    if (!empty($result['user']['id'])) {
        $_SESSION['user_id'] = $result['user']['id'];
    }

    return true;
}

