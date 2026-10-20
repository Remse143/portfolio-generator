
<?php
session_start();

require_once __DIR__ . "/config/database.php";

// =====================================================
// HELPER: SHOW A SAFE ERROR MESSAGE
// =====================================================

function showError($title, $message)
{
    http_response_code(400);

    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $safeMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>' . $safeTitle . ' - PortfolioGen</title>
        <link rel="stylesheet" href="css/style.css">
        <style>
            body {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
                box-sizing: border-box;
                background: #08070d;
                color: #fff;
                font-family: Arial, sans-serif;
            }
            .error-card {
                width: 100%;
                max-width: 520px;
                padding: 32px;
                border-radius: 20px;
                background: #15121d;
                border: 1px solid rgba(225,29,72,.4);
                box-shadow: 0 20px 60px rgba(0,0,0,.4);
            }
            .error-card h1 { color: #fb456b; }
            .error-card p { line-height: 1.8; color: #ddd; }
            .error-card a {
                display: inline-block;
                padding: 12px 20px;
                border-radius: 10px;
                background: #e11d48;
                color: white;
                text-decoration: none;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <main class="error-card">
            <h1>' . $safeTitle . '</h1>
            <p>' . $safeMessage . '</p>
            <a href="create.php">Go Back to Form</a>
        </main>
    </body>
    </html>';

    exit;
}

// =====================================================
// CHECK LOGIN
// =====================================================

if (
    empty($_SESSION['access_token']) ||
    empty($_SESSION['user_id']) ||
    empty($_SESSION['user_email'])
) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$userEmail = $_SESSION['user_email'];
$accessToken = $_SESSION['access_token'];

// =====================================================
// ONLY ACCEPT POST REQUESTS
// =====================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: create.php");
    exit;
}

// =====================================================
// READ AND TRIM FORM DATA
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
// VALIDATE REQUIRED FIELDS
// =====================================================

$requiredFields = [
    'Full Name' => $fullName,
    'Email' => $email,
    'Contact Number' => $contactNumber,
    'Address' => $address,
    'Professional Title' => $professionalTitle,
    'Career Objective' => $careerObjective,
    'About Me' => $aboutMe,
    'Education' => $education,
    'Skills' => $skills
];

foreach ($requiredFields as $fieldName => $value) {
    if ($value === '') {
        showError(
            'Required Field Missing',
            $fieldName . ' is required. Please complete all required fields.'
        );
    }
}

// =====================================================
// VALIDATE FULL NAME
// Letters from different languages, spaces, apostrophes,
// and hyphens are allowed. Numbers are not allowed.
// =====================================================

$namePattern = "/^\p{L}+(?:[ '\x{2019}-]\p{L}+)*$/u";

if (
    mb_strlen($fullName, 'UTF-8') < 2 ||
    mb_strlen($fullName, 'UTF-8') > 100 ||
    !preg_match($namePattern, $fullName)
) {
    showError(
        'Invalid Full Name',
        'Enter a valid name using letters only. Spaces, apostrophes, and hyphens are allowed. Numbers and other symbols are not accepted.'
    );
}

// =====================================================
// VALIDATE EMAIL
// =====================================================

if (
    strlen($email) > 254 ||
    filter_var($email, FILTER_VALIDATE_EMAIL) === false
) {
    showError(
        'Invalid Email',
        'Please enter a valid email address, such as name@example.com.'
    );
}

// =====================================================
// VALIDATE CONTACT NUMBER
// =====================================================

$phonePattern = '/^[0-9+\s().-]+$/';
$phoneDigits = preg_replace('/\D/', '', $contactNumber);

if (
    strlen($contactNumber) > 20 ||
    !preg_match($phonePattern, $contactNumber) ||
    strlen($phoneDigits) < 7 ||
    strlen($phoneDigits) > 15
) {
    showError(
        'Invalid Contact Number',
        'Enter a valid phone number containing 7 to 15 digits. Letters are not allowed.'
    );
}

// =====================================================
// VALIDATE FIELD LENGTHS
// =====================================================

$lengthRules = [
    'Email' => [$email, 254],
    'Address' => [$address, 255],
    'Professional Title' => [$professionalTitle, 150],
    'Career Objective' => [$careerObjective, 3000],
    'About Me' => [$aboutMe, 5000],
    'Education' => [$education, 5000],
    'Skills' => [$skills, 5000],
    'Projects' => [$projects, 10000],
    'Work Experience' => [$workExperience, 10000],
    'Social Links' => [$socialLinks, 5000]
];

foreach ($lengthRules as $fieldName => $rule) {
    if (mb_strlen($rule[0], 'UTF-8') > $rule[1]) {
        showError(
            'Input Too Long',
            $fieldName . ' must not exceed ' . $rule[1] . ' characters.'
        );
    }
}

if (mb_strlen($professionalTitle, 'UTF-8') < 2) {
    showError(
        'Invalid Professional Title',
        'Your professional title must contain at least 2 characters.'
    );
}

if (mb_strlen($careerObjective, 'UTF-8') < 10) {
    showError(
        'Invalid Career Objective',
        'Your career objective must contain at least 10 characters.'
    );
}

if (mb_strlen($aboutMe, 'UTF-8') < 10) {
    showError(
        'Invalid About Me',
        'Your About Me section must contain at least 10 characters.'
    );
}

if (mb_strlen($education, 'UTF-8') < 2) {
    showError('Invalid Education', 'Please provide your education details.');
}

if (mb_strlen($skills, 'UTF-8') < 2) {
    showError('Invalid Skills', 'Please provide your skills.');
}

// =====================================================
// CHECK SUPABASE CONFIGURATION
// =====================================================

if (
    empty($supabaseUrl) ||
    empty($supabaseKey) ||
    !filter_var($supabaseUrl, FILTER_VALIDATE_URL)
) {
    showError(
        'Configuration Error',
        'The database configuration is incomplete. Please check config/database.php.'
    );
}

// =====================================================
// PROFILE PICTURE UPLOAD
// =====================================================

$profilePictureUrl = null;

if (
    isset($_FILES['profile_picture']) &&
    $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE
) {
    $file = $_FILES['profile_picture'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        showError(
            'Profile Picture Error',
            'The profile picture could not be uploaded. Please try again.'
        );
    }

    if (
        !isset($file['tmp_name']) ||
        !is_uploaded_file($file['tmp_name'])
    ) {
        showError(
            'Invalid Upload',
            'The profile picture upload could not be verified.'
        );
    }

    // Maximum image size: 5 MB
    if ($file['size'] > 5 * 1024 * 1024) {
        showError(
            'Image Too Large',
            'Please upload an image that is 5 MB or smaller.'
        );
    }

    // Verify actual file type rather than trusting its filename.
    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    $fileType = (new finfo(FILEINFO_MIME_TYPE))
        ->file($file['tmp_name']);

    if (!isset($allowedTypes[$fileType])) {
        showError(
            'Invalid Image',
            'Only JPG, PNG, and WEBP images are allowed.'
        );
    }

    // Check that PHP can read the uploaded file.
    $fileData = file_get_contents($file['tmp_name']);

    if ($fileData === false) {
        showError(
            'Image Error',
            'Unable to read the uploaded image.'
        );
    }

    // Create a unique filename.
    $extension = $allowedTypes[$fileType];

    $fileName = $userId . '_' . bin2hex(random_bytes(16))
        . '.' . $extension;

    // Upload to the existing Supabase Storage bucket.
    $storageUrl = rtrim($supabaseUrl, '/') .
        '/storage/v1/object/profile-pictures/' .
        $fileName;

    $ch = curl_init($storageUrl);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $fileData,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $accessToken,
            'apikey: ' . $supabaseKey,
            'Content-Type: ' . $fileType,
            'x-upsert: false'
        ],
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30
    ]);

    $uploadResponse = curl_exec($ch);
    $uploadHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $uploadCurlError = curl_error($ch);

    curl_close($ch);

    if (
        $uploadResponse === false ||
        $uploadCurlError !== '' ||
        $uploadHttpCode < 200 ||
        $uploadHttpCode >= 300
    ) {
        error_log(
            'PortfolioGen storage upload failed. HTTP: ' .
            $uploadHttpCode . '; Error: ' . $uploadCurlError .
            '; Response: ' . (string)$uploadResponse
        );

        showError(
            'Profile Picture Upload Failed',
            'Supabase could not upload your picture. Please check your Storage bucket permissions and try again.'
        );
    }

    // Public URL for the existing public bucket.
    $profilePictureUrl = rtrim($supabaseUrl, '/') .
        '/storage/v1/object/public/profile-pictures/' .
        $fileName;
}

// =====================================================
// PREPARE PORTFOLIO DATA
// =====================================================

$data = [
    'user_id' => $userId,
    'full_name' => $fullName,
    'profile_picture' => $profilePictureUrl,
    'email' => $email,
    'contact_number' => $contactNumber,
    'address' => $address,
    'professional_title' => $professionalTitle,
    'career_objective' => $careerObjective,
    'about_me' => $aboutMe,
    'education' => $education,
    'skills' => $skills,
    'projects' => $projects,
    'work_experience' => $workExperience,
    'social_links' => $socialLinks,
    'template' => 'simple'
];

// =====================================================
// SAVE TO SUPABASE
// =====================================================

$url = rtrim($supabaseUrl, '/') . '/rest/v1/portfolios';

$jsonData = json_encode($data);

if ($jsonData === false) {
    showError(
        'Data Error',
        'The portfolio information could not be prepared. Please try again.'
    );
}

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $jsonData,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'apikey: ' . $supabaseKey,
        'Authorization: Bearer ' . $accessToken,
        'Prefer: return=representation'
    ],
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 30
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

curl_close($ch);

// =====================================================
// HANDLE CONNECTION ERRORS
// =====================================================

if ($response === false || $curlError !== '') {
    error_log('PortfolioGen database connection error: ' . $curlError);

    showError(
        'Connection Error',
        'Unable to connect to the database. Please try again later.'
    );
}

// =====================================================
// HANDLE SUCCESS
// =====================================================

if ($httpCode >= 200 && $httpCode < 300) {
    header('Location: manage.php?success=created');
    exit;
}

// Log details for the site administrator without showing
// raw database errors or configuration details to visitors.
error_log(
    'PortfolioGen insert failed. HTTP: ' .
    $httpCode . '; Response: ' . (string)$response
);

showError(
    'Portfolio Could Not Be Saved',
    'Your portfolio was not saved. Please check your information and try again. If the problem continues, contact the website administrator.'
);

