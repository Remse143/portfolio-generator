
<?php

session_start();

if (!isset($_SESSION['access_token']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

$id = $_GET['id'] ?? '';
$template = $_GET['template'] ?? '';

$allowedTemplates = [
    'simple',
    'modern',
    'creative'
];

if ($id === '' || !in_array($template, $allowedTemplates, true)) {
    exit("Invalid portfolio ID or template.");
}

$userId = $_SESSION['user_id'];

$url = $supabaseUrl
    . "/rest/v1/portfolios?id=eq."
    . urlencode($id)
    . "&user_id=eq."
    . urlencode($userId)
    . "&select=id";

$data = [
    "template" => $template
];

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "PATCH",
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "apikey: " . $supabaseKey,
        "Authorization: Bearer " . $_SESSION['access_token'],
        "Prefer: return=representation"
    ]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

curl_close($ch);

if ($response === false) {
    exit("Database connection failed: " . htmlspecialchars($curlError));
}

if ($httpCode < 200 || $httpCode >= 300) {
    exit(
        "Could not save the template. HTTP "
        . $httpCode
        . "<pre>"
        . htmlspecialchars($response)
        . "</pre><a href='templates.php'>Back to Templates</a>"
    );
}

$savedPortfolios = json_decode($response, true);

if (!is_array($savedPortfolios) || count($savedPortfolios) === 0) {
    exit("Portfolio not found, or you do not have permission to update it.");
}

// Open the existing preview page with the selected portfolio.
header("Location: preview.php?id=" . urlencode($id));
exit;
?>
