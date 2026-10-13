<?php

session_start();

if (!isset($_SESSION['access_token'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Portfolio ID is missing.");
}

$userId = $_SESSION['user_id'];

$url = $supabaseUrl
    . "/rest/v1/portfolios"
    . "?id=eq." . urlencode($id)
    . "&user_id=eq." . urlencode($userId);

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

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

    header("Location: manage.php");
    exit;

}

echo "<h1>Delete Error</h1>";

echo "<p>The portfolio could not be deleted.</p>";

echo "<p>HTTP Code: " . htmlspecialchars($httpCode) . "</p>";

echo "<pre>";
echo htmlspecialchars($response);
echo "</pre>";

echo '<br>';

echo '<a href="manage.php">Back to My Portfolios</a>';

?>