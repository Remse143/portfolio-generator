
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


$userId =
    $_SESSION['user_id'];

$userEmail =
    $_SESSION['user_email'];

$accessToken =
    $_SESSION['access_token'];


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

$fullName =
    trim($_POST['full_name'] ?? '');

$email =
    trim($_POST['email'] ?? '');

$contactNumber =
    trim($_POST['contact_number'] ?? '');

$address =
    trim($_POST['address'] ?? '');

$aboutMe =
    trim($_POST['about_me'] ?? '');

$education =
    trim($_POST['education'] ?? '');

$skills =
    trim($_POST['skills'] ?? '');

$projects =
    trim($_POST['projects'] ?? '');

$workExperience =
    trim($_POST['work_experience'] ?? '');

$socialLinks =
    trim($_POST['social_links'] ?? '');


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
// PROFILE PICTURE
// =====================================================

$profilePictureUrl = null;


if (
    isset($_FILES['profile_picture']) &&
    $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE
) {

    if (
        $_FILES['profile_picture']['error']
        !== UPLOAD_ERR_OK
    ) {

        die("
            <h2>Profile Picture Error</h2>
            <p>The profile picture could not be uploaded.</p>
            <a href='create.php'>Go Back</a>
        ");

    }


    $file =
        $_FILES['profile_picture'];


    // Allowed file types

    $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];


    // Check MIME type

    $fileType =
        mime_content_type(
            $file['tmp_name']
        );


    if (
        !in_array(
            $fileType,
            $allowedTypes,
            true
        )
    ) {

        die("
            <h2>Invalid Image</h2>
            <p>Please upload JPG, PNG, or WEBP.</p>
            <a href='create.php'>Go Back</a>
        ");

    }


    // Maximum size: 5 MB

    if (
        $file['size'] > 5 * 1024 * 1024
    ) {

        die("
            <h2>Image Too Large</h2>
            <p>Please upload an image smaller than 5 MB.</p>
            <a href='create.php'>Go Back</a>
        ");

    }


    // File extension

    $extension =
        strtolower(
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            )
        );


    // Unique filename

    $fileName =
        $userId .
        '_' .
        time() .
        '_' .
        bin2hex(
            random_bytes(4)
        ) .
        '.' .
        $extension;


    // Read file

    $fileData =
        file_get_contents(
            $file['tmp_name']
        );


    if ($fileData === false) {

        die("
            <h2>Image Error</h2>
            <p>Unable to read the uploaded image.</p>
            <a href='create.php'>Go Back</a>
        ");

    }


    // =================================================
    // SUPABASE STORAGE UPLOAD
    // =================================================

    $storageUrl =
        $supabaseUrl .
        "/storage/v1/object/profile-pictures/" .
        $fileName;


    $ch =
        curl_init(
            $storageUrl
        );


    curl_setopt(
        $ch,
        CURLOPT_RETURNTRANSFER,
        true
    );


    curl_setopt(
        $ch,
        CURLOPT_POST,
        true
    );


    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        $fileData
    );


    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        [

            "Authorization: Bearer " .
                $accessToken,

            "apikey: " .
                $supabaseKey,

            "Content-Type: " .
                $fileType

        ]
    );


    $uploadResponse =
        curl_exec($ch);


    $uploadHttpCode =
        curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );


    curl_close($ch);


    if (
        $uploadHttpCode < 200 ||
        $uploadHttpCode >= 300
    ) {

        echo "<h2>Profile Picture Upload Failed</h2>";

        echo "<p>HTTP Code: " .
            htmlspecialchars(
                (string)$uploadHttpCode
            ) .
            "</p>";

        echo "<pre>";

        echo htmlspecialchars(
            $uploadResponse
        );

        echo "</pre>";

        echo "<a href='create.php'>Go Back</a>";

        exit;
    }


    // Public image URL

    $profilePictureUrl =
        $supabaseUrl .
        "/storage/v1/object/public/profile-pictures/" .
        $fileName;
}


// =====================================================
// PORTFOLIO DATA
// =====================================================

$data = [

    "user_id" =>
        $userId,

    "full_name" =>
        $fullName,

    "profile_picture" =>
        $profilePictureUrl,

    "email" =>
        $email !== ''
            ? $email
            : $userEmail,

    "contact_number" =>
        $contactNumber,

    "address" =>
        $address,

    "about_me" =>
        $aboutMe,

    "education" =>
        $education,

    "skills" =>
        $skills,

    "projects" =>
        $projects,

    "work_experience" =>
        $workExperience,

    "social_links" =>
        $socialLinks,

    // Default template
    "template" =>
        "simple"
];


// =====================================================
// INSERT INTO SUPABASE
// =====================================================

$url =
    $supabaseUrl .
    "/rest/v1/portfolios";


$ch =
    curl_init($url);


curl_setopt(
    $ch,
    CURLOPT_RETURNTRANSFER,
    true
);


curl_setopt(
    $ch,
    CURLOPT_POST,
    true
);


curl_setopt(
    $ch,
    CURLOPT_POSTFIELDS,
    json_encode(
        $data
    )
);


curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    [

        "Content-Type: application/json",

        "apikey: " .
            $supabaseKey,

        "Authorization: Bearer " .
            $accessToken,

        "Prefer: return=representation"

    ]
);


$response =
    curl_exec($ch);


$httpCode =
    curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );


$curlError =
    curl_error($ch);


curl_close($ch);


// =====================================================
// CHECK CURL ERROR
// =====================================================

if ($curlError) {

    echo "<h2>Connection Error</h2>";

    echo "<p>" .
        htmlspecialchars(
            $curlError
        ) .
        "</p>";

    echo "<a href='create.php'>Go Back</a>";

    exit;
}


// =====================================================
// SUCCESS
// =====================================================

if (
    $httpCode >= 200 &&
    $httpCode < 300
) {

    header(
        "Location: manage.php"
    );

    exit;
}


// =====================================================
// DATABASE ERROR
// =====================================================

echo "<h2>Portfolio Could Not Be Saved</h2>";

echo "<p>Supabase returned HTTP Code: ";

echo htmlspecialchars(
    (string)$httpCode
);

echo "</p>";

echo "<p>Supabase response:</p>";

echo "<pre>";

echo htmlspecialchars(
    $response
);

echo "</pre>";

echo "<br>";

echo "<a href='create.php'>← Go Back</a>";

?>
