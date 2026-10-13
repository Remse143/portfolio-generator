

<?php

session_start();

require_once "config/database.php";

// =====================================================
// CHECK LOGIN
// =====================================================

if (
    !isset($_SESSION['access_token']) ||
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['user_email'])
) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$userEmail = $_SESSION['user_email'];
$accessToken = $_SESSION['access_token'];

// =====================================================
// ONLY ACCEPT POST
// =====================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: create.php");
    exit;
}

// =====================================================
// GET FORM DATA
// =====================================================

$fullName = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$contactNumber = trim($_POST['contact_number'] ?? '');
$address = trim($_POST['address'] ?? '');
$professionalTitle = trim($_POST['professional_title'] ?? '');
$careerObjective = trim($_POST['career_objective'] ?? '');
$aboutMe = trim($_POST['about_me'] ?? '');
$education = trim($_POST['education'] ?? '');
$skills = trim($_POST['skills'] ?? '');
$projects = trim($_POST['projects'] ?? '');
$workExperience = trim($_POST['work_experience'] ?? '');
$socialLinks = trim($_POST['social_links'] ?? '');

// =====================================================
// BASIC VALIDATION
// =====================================================

if ($fullName === '') {
    die("
        <h2>Portfolio Error</h2>
        <p>Full name is required.</p>
        <a href='create.php'>Go Back</a>
    ");
}

// =====================================================
// PROFILE PICTURE UPLOAD
// =====================================================

$profilePictureUrl = null;

if (
    isset($_FILES['profile_picture']) &&
    $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE
) {
    if ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
        die("
            <h2>Profile Picture Error</h2>
            <p>The profile picture could not be uploaded.</p>
            <a href='create.php'>Go Back</a>
        ");
    }

    $file = $_FILES['profile_picture'];

    // Check actual image type
    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    $fileType = mime_content_type($file['tmp_name']);

    if (!isset($allowedTypes[$fileType])) {
        die("
            <h2>Invalid Image</h2>
            <p>Please upload a JPG, PNG, or WEBP image.</p>
            <a href='create.php'>Go Back</a>
        ");
    }

    // Maximum size: 5 MB
    if ($file['size'] > 5 * 1024 * 1024) {
        die("
            <h2>Image Too Large</h2>
            <p>Please upload an image smaller than 5 MB.</p>
            <a href='create.php'>Go Back</a>
        ");
    }

    // Generate a unique filename using a verified extension
    $extension = $allowedTypes[$fileType];

    $fileName = $userId . '_' . time() . '_' .
        bin2hex(random_bytes(4)) . '.' . $extension;

    $fileData = file_get_contents($file['tmp_name']);

    if ($fileData === false) {
        die("
            <h2>Image Error</h2>
            <p>Unable to read the uploaded image.</p>
            <a href='create.php'>Go Back</a>
        ");
    }

    // Upload to Supabase Storage
    $storageUrl = $supabaseUrl .
        "/storage/v1/object/profile-pictures/" .
        $fileName;

    $ch = curl_init($storageUrl);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $fileData,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $accessToken,
            "apikey: " . $supabaseKey,
            "Content-Type: " . $fileType
        ]
    ]);

    $uploadResponse = curl_exec($ch);
    $uploadHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $uploadCurlError = curl_error($ch);

    curl_close($ch);

    if (
        $uploadCurlError ||
        $uploadHttpCode < 200 ||
        $uploadHttpCode >= 300
    ) {
        echo "<h2>Profile Picture Upload Failed</h2>";

        if ($uploadCurlError) {
            echo "<p>" . htmlspecialchars($uploadCurlError) . "</p>";
        } else {
            echo "<p>HTTP Code: " .
                htmlspecialchars((string)$uploadHttpCode) .
                "</p><pre>" .
                htmlspecialchars((string)$uploadResponse) .
                "</pre>";
        }

        echo "<a href='create.php'>Go Back</a>";
        exit;
    }

    // Public URL for the uploaded image
    $profilePictureUrl = $supabaseUrl .
        "/storage/v1/object/public/profile-pictures/" .
        $fileName;
}

// =====================================================
// PORTFOLIO DATA
// =====================================================

$data = [
    "user_id" => $userId,
    "full_name" => $fullName,
    "profile_picture" => $profilePictureUrl,
    "email" => $email !== '' ? $email : $userEmail,
    "contact_number" => $contactNumber,
    "address" => $address,
    "professional_title" => $professionalTitle,
    "career_objective" => $careerObjective,
    "about_me" => $aboutMe,
    "education" => $education,
    "skills" => $skills,
    "projects" => $projects,
    "work_experience" => $workExperience,
    "social_links" => $socialLinks,
    "template" => "simple"
];

// =====================================================
// INSERT INTO SUPABASE
// =====================================================

$url = $supabaseUrl . "/rest/v1/portfolios";

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "apikey: " . $supabaseKey,
        "Authorization: Bearer " . $accessToken,
        "Prefer: return=representation"
    ]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

curl_close($ch);

// =====================================================
// CHECK CONNECTION ERROR
// =====================================================

if ($curlError) {
    echo "<h2>Connection Error</h2>";
    echo "<p>" . htmlspecialchars($curlError) . "</p>";
    echo "<a href='create.php'>Go Back</a>";
    exit;
}

// =====================================================
// SUCCESS
// =====================================================

if ($httpCode >= 200 && $httpCode < 300) {
    header("Location: manage.php");
    exit;
}

// =====================================================
// DATABASE ERROR
// =====================================================

echo "<h2>Portfolio Could Not Be Saved</h2>";

echo "<p>Supabase returned HTTP Code: " .
    htmlspecialchars((string)$httpCode) .
    "</p>";

echo "<p>Supabase response:</p>";

echo "<pre>" .
    htmlspecialchars((string)$response) .
    "</pre>";

echo "<a href='create.php'>Go Back</a>";

?>
