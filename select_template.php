
<?php

session_start();

if (!isset($_SESSION['access_token'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

$id = $_GET['id'] ?? null;
$template = $_GET['template'] ?? null;

if (!$id || !$template) {
    die("Portfolio ID or template is missing.");
}


// ONLY ALLOW THESE TEMPLATES

$allowedTemplates = [
    'simple',
    'modern',
    'creative'
];

if (!in_array($template, $allowedTemplates)) {
    die("Invalid template.");
}


$userId = $_SESSION['user_id'];


// UPDATE TEMPLATE

$url = $supabaseUrl
    . "/rest/v1/portfolios?id=eq."
    . urlencode($id)
    . "&user_id=eq."
    . urlencode($userId);


$data = [
    "template" => $template
];


$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "apikey: " . $supabaseKey,
    "Authorization: Bearer " . $_SESSION['access_token'],
    "Prefer: return=representation"
]);

$response = curl_exec($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);


if ($httpCode >= 200 && $httpCode < 300) {

    header("Location: preview.php?id=" . urlencode($id));

    exit;

}


echo "<h1>Template Selection Error</h1>";

echo "<p>The template could not be saved.</p>";

echo "<p>HTTP Code: " . $httpCode . "</p>";

echo "<pre>";
echo htmlspecialchars($response);
echo "</pre>";

echo '<br><a href="templates.php">Back to Templates</a>';

?>
