

<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Portfolio - PortfolioGen</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/footer.css">

    <style>
        .create-wrapper {
            max-width: 1000px;
            margin: 0 auto;
        }

        .form-wrapper {
            background: rgba(8, 7, 12, 0.78);
            border: 1px solid rgba(225, 29, 72, 0.18);
            border-radius: 24px;
            padding: 35px;
            backdrop-filter: blur(14px);
            box-shadow: 0 20px 60px rgba(0,0,0,.35),
                        0 0 35px rgba(124,58,237,.05);
        }

        .form-section {
            position: relative;
            margin-bottom: 28px;
            padding: 28px;
            border-radius: 18px;
            background: linear-gradient(
                135deg,
                rgba(255,255,255,.035),
                rgba(255,255,255,.012)
            );
            border: 1px solid rgba(255,255,255,.07);
            transition: border-color .3s ease,
                        transform .3s ease,
                        box-shadow .3s ease;
        }

        .form-section:hover {
            border-color: rgba(225,29,72,.28);
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0,0,0,.2);
        }

        .form-section h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            font-size: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .form-group {
            width: 100%;
        }

        .form-group label {
            display: block;
            margin-bottom: 9px;
            color: #eeeeF5;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: .2px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,.09);
            background: linear-gradient(
                135deg,
                rgba(255,255,255,.045),
                rgba(0,0,0,.28)
            );
            color: #fff;
            font-family: inherit;
            font-size: 15px;
            outline: none;
            transition: border-color .25s ease,
                        box-shadow .25s ease,
                        background .25s ease,
                        transform .25s ease;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #777783;
        }

        .form-group input:hover,
        .form-group textarea:hover {
            border-color: rgba(225,29,72,.4);
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #e11d48;
            background: linear-gradient(
                135deg,
                rgba(225,29,72,.08),
                rgba(124,58,237,.06)
            );
            box-shadow: 0 0 0 3px rgba(225,29,72,.08),
                        0 0 25px rgba(225,29,72,.08);
            transform: translateY(-1px);
        }

        .form-group textarea {
            min-height: 130px;
            resize: vertical;
            line-height: 1.6;
        }

        .profile-upload {
            display: flex;
            align-items: center;
            gap: 25px;
            padding: 22px;
            border-radius: 16px;
            background: rgba(255,255,255,.025);
            border: 1px dashed rgba(225,29,72,.35);
        }

        .profile-preview {
            width: 110px;
            height: 110px;
            flex-shrink: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(
                135deg,
                rgba(225,29,72,.15),
                rgba(124,58,237,.15)
            );
            border: 2px solid rgba(225,29,72,.35);
            font-size: 36px;
            box-shadow: 0 0 30px rgba(225,29,72,.08);
        }

        .profile-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .form-help {
            margin-top: 9px;
            color: #777783;
            font-size: 13px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 15px;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid rgba(255,255,255,.07);
        }

        .form-actions .btn {
            transition: transform .25s ease,
                        box-shadow .25s ease;
        }

        .form-actions .btn:hover {
            transform: translateY(-3px);
        }

        .form-actions .btn-primary:hover {
            box-shadow: 0 10px 30px rgba(225,29,72,.25);
        }

        .navbar {
            position: relative;
            z-index: 100;
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
            gap: 6px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .nav-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 12px;
            border-radius: 9px;
            color: #d7d7df;
            text-decoration: none;
            font-size: 14px;
            white-space: nowrap;
            transition: background .2s ease, color .2s ease;
        }

        .nav-links a:hover {
            background: rgba(225,29,72,.10);
            color: #fff;
        }

        .theme-toggle {
            flex-shrink: 0;
        }

        @media (max-width: 850px) {
            .navbar-content {
                flex-direction: column;
                align-items: center;
            }

            .nav-links {
                justify-content: center;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .form-wrapper {
                padding: 20px;
                border-radius: 18px;
            }

            .form-section {
                padding: 20px;
            }

            .profile-upload {
                flex-direction: column;
                text-align: center;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .form-actions .btn {
                width: 100%;
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

            <button
                type="button"
                id="themeToggle"
                class="theme-toggle"
            >🌙</button>
        </div>
    </div>
</nav>

<!-- CREATE PORTFOLIO -->
<section class="section">
    <div class="container create-wrapper">

        <div class="form-header">
            <div class="hero-badge">✦ Portfolio Builder</div>
            <h1>Create Your Portfolio</h1>
            <p>
                Enter your information below to create
                your professional portfolio.
            </p>
        </div>

        <div class="form-wrapper">
            <form
                action="save.php"
                method="POST"
                enctype="multipart/form-data"
            >

                <!-- PERSONAL INFORMATION -->
                <div class="form-section">
                    <h2>👤 Personal Information</h2>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input
                                type="text"
                                id="full_name"
                                name="full_name"
                                placeholder="Enter your full name"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="Enter your email"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input
                                type="text"
                                id="contact_number"
                                name="contact_number"
                                placeholder="Enter your contact number"
                            >
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <input
                                type="text"
                                id="address"
                                name="address"
                                placeholder="Enter your address"
                            >
                        </div>
                    </div>
                </div>

                <!-- PROFILE PICTURE -->
                <div class="form-section">
                    <h2>📷 Profile Picture</h2>

                    <div class="profile-upload">
                        <div
                            class="profile-preview"
                            id="profilePreviewContainer"
                        >
                            <span id="profilePlaceholder">👤</span>

                            <img
                                id="profilePreview"
                                src=""
                                alt="Profile Preview"
                                style="display:none;"
                            >
                        </div>

                        <div>
                            <label
                                for="profile_picture"
                                class="btn btn-secondary"
                            >Choose Image</label>

                            <input
                                type="file"
                                id="profile_picture"
                                name="profile_picture"
                                accept="image/jpeg,image/png,image/webp"
                                hidden
                            >

                            <p class="form-help">JPG, PNG or WEBP.</p>
                        </div>
                    </div>
                </div>

                <!-- NEW: PROFESSIONAL TITLE -->
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
                        >
                    </div>
                </div>

                <!-- NEW: CAREER OBJECTIVE -->
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
                            placeholder="Describe your career goals, interests, and what you hope to achieve..."
                        ></textarea>
                    </div>
                </div>

                <!-- ABOUT ME -->
                <div class="form-section">
                    <h2>✨ About Me</h2>

                    <div class="form-group">
                        <label for="about_me">
                            Tell us about yourself
                        </label>

                        <textarea
                            id="about_me"
                            name="about_me"
                            rows="6"
                            placeholder="Write a short introduction about yourself..."
                        ></textarea>
                    </div>
                </div>

                <!-- EDUCATION -->
                <div class="form-section">
                    <h2>🎓 Education</h2>

                    <div class="form-group">
                        <label for="education">Education</label>

                        <textarea
                            id="education"
                            name="education"
                            rows="5"
                            placeholder="Example: BS Information Technology - University Name - 2026"
                        ></textarea>
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
                            rows="5"
                            placeholder="Example: HTML, CSS, PHP, JavaScript, Laravel..."
                        ></textarea>
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
                            placeholder="Describe your projects..."
                        ></textarea>
                    </div>
                </div>

                <!-- WORK EXPERIENCE -->
                <div class="form-section">
                    <h2>💼 Work Experience</h2>

                    <div class="form-group">
                        <label for="work_experience">
                            Work Experience
                        </label>

                        <textarea
                            id="work_experience"
                            name="work_experience"
                            rows="6"
                            placeholder="Describe your work experience..."
                        ></textarea>
                    </div>
                </div>

                <!-- SOCIAL LINKS -->
                <div class="form-section">
                    <h2>🔗 Social Links</h2>

                    <div class="form-group">
                        <label for="social_links">
                            Social Media / Website Links
                        </label>

                        <textarea
                            id="social_links"
                            name="social_links"
                            rows="4"
                            placeholder="Facebook, GitHub, LinkedIn, personal website..."
                        ></textarea>
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="form-actions">
                    <a href="dashboard.php" class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        💾 Save Portfolio
                    </button>
                </div>

            </form>
        </div>
    </div>
</section>

<!-- FOOTER -->
<?php include 'footer.php'; ?>

<!-- JAVASCRIPT -->
<script src="js/script.js"></script>

<script>
const profileInput = document.getElementById('profile_picture');
const profilePreview = document.getElementById('profilePreview');
const profilePlaceholder = document.getElementById('profilePlaceholder');

if (profileInput && profilePreview) {
    profileInput.addEventListener('change', function () {
        const file = this.files && this.files[0];

        if (!file) {
            profilePreview.src = '';
            profilePreview.style.display = 'none';
            profilePlaceholder.style.display = 'block';
            return;
        }

        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            alert('Please choose a JPG, PNG, or WEBP image.');
            this.value = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {
            profilePreview.src = event.target.result;
            profilePreview.style.display = 'block';
            profilePlaceholder.style.display = 'none';
        };

        reader.readAsDataURL(file);
    });
}
</script>

</body>
</html>
