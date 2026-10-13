<?php
session_start();

require_once __DIR__ . "/supabase_auth.php";
require_once __DIR__ . "/config/database.php";

// Check if the user is logged in.
if (
    empty($_SESSION['access_token']) ||
    empty($_SESSION['user_id'])
) {
    header("Location: login.php");
    exit;
}

// Get portfolio ID.
$id = $_GET['id'] ?? '';

if (!is_string($id) || trim($id) === '') {
    http_response_code(400);
    exit("Invalid portfolio ID.");
}

$id = trim($id);
$userId = (string) $_SESSION['user_id'];

// Prepare Supabase request.
$url = rtrim($supabaseUrl, '/')
    . "/rest/v1/portfolios?id=eq."
    . urlencode($id)
    . "&user_id=eq."
    . urlencode($userId)
    . "&select=*";

// Function to retrieve portfolio data.
function fetchPortfolioForDownload(
    string $url,
    string $supabaseKey
): array {
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "apikey: " . $supabaseKey,
            "Authorization: Bearer " . $_SESSION['access_token'],
            "Accept: application/json"
        ],
        CURLOPT_TIMEOUT => 20
    ]);

    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return [
        'response' => $response,
        'curlError' => $curlError,
        'httpCode' => $httpCode
    ];
}

// Request portfolio data.
$result = fetchPortfolioForDownload($url, $supabaseKey);

// Retry if the access token expired.
if (
    $result['httpCode'] === 401 &&
    function_exists('refreshSupabaseToken') &&
    refreshSupabaseToken()
) {
    $result = fetchPortfolioForDownload($url, $supabaseKey);
}

// Handle connection problems.
if ($result['curlError'] !== '') {
    http_response_code(500);
    exit("Unable to connect to the database.");
}

// Handle invalid login.
if ($result['httpCode'] === 401) {
    header("Location: login.php");
    exit;
}

// Handle other database errors.
if ($result['httpCode'] !== 200) {
    http_response_code(500);
    exit("Unable to retrieve your portfolio.");
}

// Decode the response.
$portfolios = json_decode($result['response'] ?? '', true);

if (!is_array($portfolios) || empty($portfolios[0])) {
    http_response_code(404);
    exit("Portfolio not found or access denied.");
}

$portfolio = $portfolios[0];

// Select the portfolio template.
$template = strtolower(
    trim($portfolio['template'] ?? 'simple')
);

if (!in_array($template, ['simple', 'modern', 'creative'], true)) {
    $template = 'simple';
}

$templateFile = __DIR__ . "/templates/" . $template . ".php";
$cssFile = __DIR__ . "/css/style.css";

// Check required files.
if (!is_file($templateFile)) {
    http_response_code(500);
    exit("The selected portfolio template was not found.");
}

// Render the template.
ob_start();

try {
    include $templateFile;
    $templateHtml = ob_get_clean();
} catch (Throwable $e) {
    ob_end_clean();
    http_response_code(500);
    exit("Unable to render the portfolio template.");
}

// Load the main stylesheet if it exists.
$css = '';

if (is_file($cssFile)) {
    $loadedCss = file_get_contents($cssFile);

    if ($loadedCss === false) {
        http_response_code(500);
        exit("Unable to load the stylesheet.");
    }

    $css = $loadedCss;
}

// Escape the page title.
$title = htmlspecialchars(
    $portfolio['full_name'] ?? 'My Portfolio',
    ENT_QUOTES | ENT_SUBSTITUTE,
    'UTF-8'
);

// Build a complete HTML document.
// Template-specific HTML and inline styles are preserved.
$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio created with PortfolioGen">
    <title>' . $title . ' - Portfolio</title>
    <style>
' . $css . '
    </style>
</head>
<body>
' . $templateHtml . '
</body>
</html>';

// Check ZIP extension.
if (!class_exists('ZipArchive')) {
    http_response_code(500);
    exit("PHP ZIP extension is not enabled. Enable the zip extension in php.ini.");
}

// Create temporary ZIP file.
$tempFile = tempnam(sys_get_temp_dir(), 'portfolio_');

if ($tempFile === false) {
    http_response_code(500);
    exit("Unable to create a temporary file.");
}

$zip = new ZipArchive();

$openResult = $zip->open(
    $tempFile,
    ZipArchive::OVERWRITE
);

if ($openResult !== true) {
    @unlink($tempFile);
    http_response_code(500);
    exit("Unable to open the ZIP archive for writing.");
}

// Add the generated portfolio.
if (!$zip->addFromString('index.html', $html)) {
    $zip->close();
    @unlink($tempFile);
    http_response_code(500);
    exit("Unable to add index.html to the ZIP.");
}

// Add instructions.
$readme = "PortfolioGen Export\n\n"
    . "1. Extract this ZIP file.\n"
    . "2. Open index.html in your browser.\n\n"
    . "Note: External image links require an internet connection.\n";

if (!$zip->addFromString('README.txt', $readme)) {
    $zip->close();
    @unlink($tempFile);
    http_response_code(500);
    exit("Unable to add README.txt to the ZIP.");
}

// Finish writing the ZIP archive.
if (!$zip->close()) {
    @unlink($tempFile);
    http_response_code(500);
    exit("Unable to finish creating the ZIP archive.");
}

// Verify the ZIP file.
if (
    !is_file($tempFile) ||
    filesize($tempFile) === 0
) {
    @unlink($tempFile);
    http_response_code(500);
    exit("The generated ZIP file is empty.");
}

// Create a safe download filename.
$filename = preg_replace(
    '/[^a-zA-Z0-9_-]/',
    '_',
    $portfolio['full_name'] ?? 'My_Portfolio'
);

if (!is_string($filename) || $filename === '') {
    $filename = 'My_Portfolio';
}

// Clear buffered output so it cannot corrupt the ZIP.
while (ob_get_level() > 0) {
    ob_end_clean();
}

// Send the ZIP file.
header('Content-Type: application/zip');
header(
    'Content-Disposition: attachment; filename="' . $filename . '.zip"'
);
header('Content-Length: ' . filesize($tempFile));
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

readfile($tempFile);

@unlink($tempFile);
exit;
