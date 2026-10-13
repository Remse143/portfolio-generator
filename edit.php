

<?php

session_start();

if (
    !isset($_SESSION['access_token']) ||
    !isset($_SESSION['user_id'])
) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Portfolio ID is missing.");
}

$userId = $_SESSION['user_id'];
$accessToken = $_SESSION['access_token'];

// =====================================================
// GET PORTFOLIO FROM SUPABASE
// =====================================================

$url = $supabaseUrl
    . "/rest/v1/portfolios"
    . "?id=eq."
    . urlencode($id)
    . "&user_id=eq."
    . urlencode($userId)
    . "&select=*";

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "apikey: " . $supabaseKey,
        "Authorization: Bearer " . $accessToken
    ]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

curl_close($ch);

if ($curlError) {
    die("Connection error: " . htmlspecialchars($curlError));
}

$results = json_decode($response, true);

if ($httpCode < 200 || $httpCode >= 300) {
    die("Unable to load portfolio.");
}

if (empty($results) || !isset($results[0])) {
    die("Portfolio not found.");
}

$portfolio = $results[0];
$errorMessage = null;

// =====================================================
// UPDATE PORTFOLIO
// =====================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        "full_name" => trim($_POST['full_name'] ?? ''),
        "email" => trim($_POST['email'] ?? ''),
        "contact_number" => trim($_POST['contact_number'] ?? ''),
        "address" => trim($_POST['address'] ?? ''),
        "professional_title" => trim($_POST['professional_title'] ?? ''),
        "career_objective" => trim($_POST['career_objective'] ?? ''),
        "about_me" => trim($_POST['about_me'] ?? ''),
        "education" => trim($_POST['education'] ?? ''),
        "skills" => trim($_POST['skills'] ?? ''),
        "projects" => trim($_POST['projects'] ?? ''),
        "work_experience" => trim($_POST['work_experience'] ?? ''),
        "social_links" => trim($_POST['social_links'] ?? '')
    ];

    if ($data['full_name'] === '') {
        $errorMessage = "Full name is required.";
    } else {

        $updateUrl = $supabaseUrl
            . "/rest/v1/portfolios"
            . "?id=eq."
            . urlencode($id)
            . "&user_id=eq."
            . urlencode($userId);

        $ch = curl_init($updateUrl);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "PATCH",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: " . $supabaseKey,
                "Authorization: Bearer " . $accessToken,
                "Prefer: return=representation"
            ]
        ]);

        $updateResponse = curl_exec($ch);
        $updateHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $updateCurlError = curl_error($ch);

        curl_close($ch);

        if ($updateCurlError) {
            $errorMessage = "Connection error: " . $updateCurlError;
        } elseif (
            $updateHttpCode >= 200 &&
            $updateHttpCode < 300
        ) {
            header("Location: preview.php?id=" . urlencode($id));
            exit;
        } else {
            $errorMessage = $updateResponse ?: "Unable to update portfolio.";
        }

        // Keep submitted values in the form if saving fails.
        $portfolio = array_merge($portfolio, $data);
    }
}

// =====================================================
// ESCAPE OUTPUT
// =====================================================

function editEscape($value) {
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Portfolio - PortfolioGen</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">

    <style>
        .edit-wrapper {
            max-width: 1000px;
            margin: 0 auto;
        }

        .edit-form-wrapper {
            background: rgba(8, 7, 12, .82);
            border: 1px solid rgba(225, 29, 72, .2);
            border-radius: 24px;
            padding: 30px;
            backdrop-filter: blur(14px);
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
        }

        .form-section {
            margin-bottom: 25px;
            padding: 25px;
            border-radius: 18px;
            background: linear-gradient(
                135deg,
                rgba(255,255,255,.035),
                rgba(255,255,255,.012)
            );
            border: 1px solid rgba(255,255,255,.08);
        }

        .form-section h2 {
            margin: 0 0 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 9px;
            color: #eeeeF5;
            font-size: 14px;
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea {
            display: block;
            width: 100%;
            box-sizing: border-box;
            padding: 13px 15px;
            border-radius: 11px;
            border: 1px solid rgba(255,255,255,.12);
            background: rgba(10,8,18,.8);
            color: #fff;
            font: inherit;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #e11d48;
            box-shadow: 0 0 0 3px rgba(225,29,72,.12);
        }

        .form-group textarea {
            min-height: 125px;
            resize: vertical;
            line-height: 1.6;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #888493;
        }

        .edit-error {
            margin-bottom: 20px;
            padding: 16px;
            border: 1px solid rgba(225,29,72,.4);
            border-radius: 12px;
            background: rgba(225,29,72,.08);
            color: #ffdbe3;
            overflow-wrap: anywhere;
        }

        .edit-error pre {
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .form-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 25px;
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 6px;
        }

        .nav-links a {
            display: inline-flex;
            align-items: center;
            padding: 9px 12px;
            border-radius: 9px;
            color: #d7d7df;
            text-decoration: none;
            font-size: 14px;
        }

        .nav-links a:hover {
            background: rgba(225,29,72,.12);
            color: #fff;
        }

        @media (max-width: 850px) {
            .navbar-content {
                flex-direction: column;
            }

            .nav-links {
                justify-content: center;
            }
        }

        @media (max-width: 600px) {
            .edit-form-wrapper {
                padding: 16px;
            }

            .form-section {
                padding: 18px;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
                box-sizing: border-box;
                text-align: center;
            }
        }
    </style>
</head>

<body>

<!-- ANIMATED BACKGROUND -->
<div class="animated-background">
    <div class="background-orb orb-one"></div>
    <div class="background-orb orb-two"></div>
    <div class="background-orb orb-three"></div>
    <div class="background-orb orb-four"></div>
    <div class="background-grid"></div>
    <div class="background-particle particle-one"></div>
    <div class="background-particle particle-two"></div>
    <div class="background-particle particle-three"></div>
    <div class="background-particle particle-four"></div>
    <div class="background-particle particle-five"></div>
    <div class="background-particle particle-six"></div>
    <div class="background-particle particle-seven"></div>
    <div class="background-particle particle-eight"></div>
</div>

<!-- NAVIGATION -->
<nav class="navbar">
    <div class="container navbar-content">
        <a href="dashboard.php" class="logo">
            Portfolio<span>Gen</span>
        </a>

        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="create.php">Create Portfolio</a>
            <a href="manage.php">My Portfolios</a>
            <a href="templates.php">Templates</a>
            <a href="logout.php">Logout</a>

            <button type="button" id="themeToggle" class="theme-toggle">
                🌙
            </button>
        </div>
    </div>
</nav>

<!-- EDIT FORM -->
<section class="section">
    <div class="container edit-wrapper">

        <div class="section-header">
            <span class="hero-badge">✏️ Portfolio Editor</span>
            <h1>Edit Your Portfolio</h1>
            <p>Update your information and save your changes.</p>
        </div>

        <?php if ($errorMessage !== null): ?>
            <div class="edit-error">
                <strong>⚠️ Update Error</strong>
                <pre><?= editEscape($errorMessage) ?></pre>
            </div>
        <?php endif; ?>

        <div class="edit-form-wrapper">
            <form method="POST" action="edit.php?id=<?= urlencode($id) ?>">

                <!-- PERSONAL INFORMATION -->
                <div class="form-section">
                    <h2>👤 Personal Information</h2>

                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            value="<?= editEscape($portfolio['full_name'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= editEscape($portfolio['email'] ?? '') ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input
                            type="text"
                            id="contact_number"
                            name="contact_number"
                            value="<?= editEscape($portfolio['contact_number'] ?? '') ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input
                            type="text"
                            id="address"
                            name="address"
                            value="<?= editEscape($portfolio['address'] ?? '') ?>"
                        >
                    </div>
                </div>

                <!-- PROFESSIONAL TITLE -->
                <div class="form-section">
                    <h2>💼 Professional Title</h2>

                    <div class="form-group">
                        <label for="professional_title">
                            Your Professional Title
                        </label>

                        <input
                            type="text"
                            id="professional_title"
                            name="professional_title"
                            placeholder="Example: IT Student, Web Developer, UI/UX Designer"
                            value="<?= editEscape($portfolio['professional_title'] ?? '') ?>"
                        >
                    </div>
                </div>

                <!-- CAREER OBJECTIVE -->
                <div class="form-section">
                    <h2>🎯 Career Objective</h2>

                    <div class="form-group">
                        <label for="career_objective">
                            Your Career Objective
                        </label>

                        <textarea
                            id="career_objective"
                            name="career_objective"
                            rows="5"
                            placeholder="Describe your career goals and professional interests..."
                        ><?= editEscape($portfolio['career_objective'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- ABOUT ME -->
                <div class="form-section">
                    <h2>✨ About Me</h2>

                    <div class="form-group">
                        <label for="about_me">
                            Tell something about yourself
                        </label>

                        <textarea
                            id="about_me"
                            name="about_me"
                            rows="6"
                        ><?= editEscape($portfolio['about_me'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- EDUCATION -->
                <div class="form-section">
                    <h2>🎓 Educational Background</h2>

                    <div class="form-group">
                        <label for="education">Education</label>

                        <textarea
                            id="education"
                            name="education"
                            rows="6"
                        ><?= editEscape($portfolio['education'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- SKILLS -->
                <div class="form-section">
                    <h2>💡 Skills</h2>

                    <div class="form-group">
                        <label for="skills">Your Skills</label>

                        <textarea
                            id="skills"
                            name="skills"
                            rows="6"
                        ><?= editEscape($portfolio['skills'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- PROJECTS -->
                <div class="form-section">
                    <h2>🚀 Projects</h2>

                    <div class="form-group">
                        <label for="projects">Your Projects</label>

                        <textarea
                            id="projects"
                            name="projects"
                            rows="6"
                        ><?= editEscape($portfolio['projects'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- WORK EXPERIENCE -->
                <div class="form-section">
                    <h2>💼 Work Experience</h2>

                    <div class="form-group">
                        <label for="work_experience">Work Experience</label>

                        <textarea
                            id="work_experience"
                            name="work_experience"
                            rows="6"
                        ><?= editEscape($portfolio['work_experience'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- SOCIAL LINKS -->
                <div class="form-section">
                    <h2>🔗 Social Media / Website Links</h2>

                    <div class="form-group">
                        <label for="social_links">Links</label>

                        <textarea
                            id="social_links"
                            name="social_links"
                            rows="6"
                        ><?= editEscape($portfolio['social_links'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        💾 Save Changes
                    </button>

                    <a
                        href="preview.php?id=<?= urlencode($id) ?>"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                    <a href="manage.php" class="btn btn-secondary">
                        My Portfolios
                    </a>
                </div>

            </form>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
