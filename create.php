
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
        /* =========================================
           GENERAL LAYOUT
           ========================================= */

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 100px;
        }

        body {
            overflow-x: clip;
        }

        .create-wrapper {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* =========================================
           STICKY NAVIGATION BAR
           ========================================= */

        nav.navbar {
            position: sticky !important;
            top: 0 !important;
            left: 0;
            width: 100%;
            z-index: 99999 !important;
            pointer-events: auto;
            background: rgba(8, 7, 12, 0.97);
            border-bottom: 1px solid rgba(225, 29, 72, 0.3);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        nav.navbar .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            min-height: 76px;
        }

        nav.navbar .logo {
            flex-shrink: 0;
            text-decoration: none;
        }

        nav.navbar .nav-links {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 6px;
        }

        nav.navbar .nav-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 12px;
            border-radius: 9px;
            color: #d7d7df;
            text-decoration: none;
            font-size: 14px;
            white-space: nowrap;
            transition: background 0.2s ease, color 0.2s ease;
        }

        nav.navbar .nav-links a:hover {
            background: rgba(225, 29, 72, 0.15);
            color: #ffffff;
        }

        nav.navbar .nav-links a:focus-visible,
        nav.navbar button:focus-visible {
            outline: 2px solid #e11d48;
            outline-offset: 3px;
        }

        nav.navbar .theme-toggle {
            flex-shrink: 0;
            cursor: pointer;
            pointer-events: auto;
        }

        /* =========================================
           FORM DESIGN
           ========================================= */

        .form-wrapper {
            background: rgba(8,7,12,.86);
            border: 1px solid rgba(225,29,72,.22);
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
            background: linear-gradient(135deg,
                rgba(255,255,255,.035),
                rgba(255,255,255,.012));
            border: 1px solid rgba(255,255,255,.07);
            transition: border-color .3s, transform .3s, box-shadow .3s;
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
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: 22px;
        }

        .form-group {
            width: 100%;
            min-width: 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 9px;
            color: #eeeef5;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: .2px;
        }

        .required-star {
            color: #fb456b;
            font-weight: 800;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,.12);
            background: linear-gradient(135deg,
                rgba(255,255,255,.045), rgba(0,0,0,.28));
            color: #fff;
            font-family: inherit;
            font-size: 15px;
            outline: none;
            transition: border-color .25s, box-shadow .25s,
                        background .25s, transform .25s;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #92909e;
        }

        .form-group input:hover,
        .form-group textarea:hover {
            border-color: rgba(225,29,72,.4);
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #e11d48;
            background: linear-gradient(135deg,
                rgba(225,29,72,.08), rgba(124,58,237,.06));
            box-shadow: 0 0 0 3px rgba(225,29,72,.10),
                        0 0 25px rgba(225,29,72,.08);
            transform: translateY(-1px);
        }

        .form-group input.invalid,
        .form-group textarea.invalid {
            border-color: #ff4569;
            box-shadow: 0 0 0 3px rgba(255,69,105,.09);
        }

        .form-group input.valid,
        .form-group textarea.valid {
            border-color: #22c55e;
        }

        .form-group textarea {
            min-height: 130px;
            resize: vertical;
            line-height: 1.6;
        }

        .form-help {
            margin-top: 9px;
            color: #aaa6b5;
            font-size: 13px;
        }

        .field-error {
            display: block;
            color: #ff718b;
            font-size: 12px;
            margin-top: 7px;
            line-height: 1.5;
        }

        .field-error:empty {
            display: none;
        }

        .required-note {
            color: #c9c5d4;
            font-size: 13px;
            margin: 0 0 22px;
            line-height: 1.6;
        }

        .validation-summary {
            display: none;
            margin-bottom: 24px;
            padding: 16px 18px;
            border: 1px solid rgba(255,69,105,.45);
            background: rgba(225,29,72,.10);
            color: #ffd1da;
            border-radius: 12px;
            line-height: 1.6;
        }

        .validation-summary.show {
            display: block;
        }

        /* =========================================
           PROFILE PICTURE
           ========================================= */

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
            background: linear-gradient(135deg,
                rgba(225,29,72,.15), rgba(124,58,237,.15));
            border: 2px solid rgba(225,29,72,.35);
            font-size: 36px;
            box-shadow: 0 0 30px rgba(225,29,72,.08);
        }

        .profile-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* =========================================
           FORM ACTIONS
           ========================================= */

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
            transition: transform .25s, box-shadow .25s;
        }

        .form-actions .btn:hover {
            transform: translateY(-3px);
        }

        .form-actions .btn-primary:hover {
            box-shadow: 0 10px 30px rgba(225,29,72,.25);
        }

        .form-actions .btn:disabled {
            opacity: .65;
            cursor: wait;
            transform: none;
        }

        /* =========================================
           RESPONSIVE DESIGN
           ========================================= */

        @media (max-width: 850px) {
            nav.navbar .navbar-content {
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 10px;
                padding-top: 12px;
                padding-bottom: 12px;
            }

            nav.navbar .nav-links {
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

        @media (max-width: 480px) {
            nav.navbar .nav-links {
                gap: 4px;
            }

            nav.navbar .nav-links a {
                padding: 8px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

<div class="animated-background" aria-hidden="true">
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

<nav class="navbar">
    <div class="container navbar-content">
        <a href="dashboard.php" class="logo">Portfolio<span>Gen</span></a>

        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="create.php">Create Portfolio</a>
            <a href="manage.php">My Portfolios</a>
            <a href="templates.php">Templates</a>
            <a href="logout.php">Logout</a>
            <button type="button" id="themeToggle" class="theme-toggle"
                    aria-label="Toggle dark mode">🌙</button>
        </div>
    </div>
</nav>

<section class="section">
    <div class="container create-wrapper">

        <div class="form-header">
            <div class="hero-badge">✦ Portfolio Builder</div>
            <h1>Create Your Portfolio</h1>
            <p>Enter your information below to create your professional portfolio.</p>
        </div>

        <div class="form-wrapper">

            <p class="required-note">
                Fields marked with <span class="required-star">*</span>
                are required. Please enter accurate information before saving.
            </p>

            <div id="validationSummary" class="validation-summary"
                 role="alert" aria-live="polite"></div>

            <form id="portfolioForm" action="save.php" method="POST"
                  enctype="multipart/form-data">

                <div class="form-section">
                    <h2>👤 Personal Information</h2>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="full_name">Full Name <span class="required-star">*</span></label>
                            <input type="text" id="full_name" name="full_name"
                                   placeholder="Enter your full name"
                                   autocomplete="name" required minlength="2" maxlength="100"
                                   aria-describedby="full_name_error">
                            <small class="field-error" id="full_name_error"></small>
                        </div>

                        <div class="form-group">
                            <label for="email">Email <span class="required-star">*</span></label>
                            <input type="email" id="email" name="email"
                                   placeholder="example@gmail.com"
                                   autocomplete="email" required maxlength="254"
                                   aria-describedby="email_error">
                            <small class="field-error" id="email_error"></small>
                        </div>

                        <div class="form-group">
                            <label for="contact_number">Contact Number <span class="required-star">*</span></label>
                            <input type="tel" id="contact_number" name="contact_number"
                                   placeholder="e.g. 09123456789"
                                   autocomplete="tel" required maxlength="20"
                                   aria-describedby="contact_number_error">
                            <small class="field-error" id="contact_number_error"></small>
                        </div>

                        <div class="form-group">
                            <label for="address">Address <span class="required-star">*</span></label>
                            <input type="text" id="address" name="address"
                                   placeholder="Enter your city or address"
                                   autocomplete="street-address" required maxlength="255"
                                   aria-describedby="address_error">
                            <small class="field-error" id="address_error"></small>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2>📷 Profile Picture</h2>

                    <div class="profile-upload">
                        <div class="profile-preview" id="profilePreviewContainer">
                            <span id="profilePlaceholder">👤</span>
                            <img id="profilePreview" src="" alt="Profile Preview" style="display:none;">
                        </div>

                        <div>
                            <label for="profile_picture" class="btn btn-secondary">Choose Image</label>
                            <input type="file" id="profile_picture" name="profile_picture"
                                   accept="image/jpeg,image/png,image/webp" hidden>
                            <p class="form-help">JPG, PNG or WEBP. Maximum 5 MB. Optional.</p>
                            <small class="field-error" id="profile_picture_error"></small>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2>💼 Professional Title</h2>
                    <div class="form-group">
                        <label for="professional_title">Your Professional Title <span class="required-star">*</span></label>
                        <input type="text" id="professional_title" name="professional_title"
                               placeholder="e.g. IT Student, Web Developer, UI/UX Designer"
                               required minlength="2" maxlength="150"
                               aria-describedby="professional_title_error">
                        <small class="field-error" id="professional_title_error"></small>
                    </div>
                </div>

                <div class="form-section">
                    <h2>🎯 Career Objective</h2>
                    <div class="form-group">
                        <label for="career_objective">Your Career Objective <span class="required-star">*</span></label>
                        <textarea id="career_objective" name="career_objective" rows="5"
                                  placeholder="Describe your career goals and what you hope to achieve..."
                                  required minlength="10" maxlength="3000"
                                  aria-describedby="career_objective_error"></textarea>
                        <small class="field-error" id="career_objective_error"></small>
                    </div>
                </div>

                <div class="form-section">
                    <h2>✨ About Me</h2>
                    <div class="form-group">
                        <label for="about_me">Tell us about yourself <span class="required-star">*</span></label>
                        <textarea id="about_me" name="about_me" rows="6"
                                  placeholder="Write a short introduction about yourself..."
                                  required minlength="10" maxlength="5000"
                                  aria-describedby="about_me_error"></textarea>
                        <small class="field-error" id="about_me_error"></small>
                    </div>
                </div>

                <div class="form-section">
                    <h2>🎓 Education</h2>
                    <div class="form-group">
                        <label for="education">Education <span class="required-star">*</span></label>
                        <textarea id="education" name="education" rows="5"
                                  placeholder="School, course, degree and graduation year..."
                                  required minlength="2" maxlength="5000"
                                  aria-describedby="education_error"></textarea>
                        <small class="field-error" id="education_error"></small>
                    </div>
                </div>

                <div class="form-section">
                    <h2>💡 Skills</h2>
                    <div class="form-group">
                        <label for="skills">Your Skills <span class="required-star">*</span></label>
                        <textarea id="skills" name="skills" rows="5"
                                  placeholder="e.g. HTML, CSS, PHP, JavaScript..."
                                  required minlength="2" maxlength="5000"
                                  aria-describedby="skills_error"></textarea>
                        <small class="field-error" id="skills_error"></small>
                    </div>
                </div>

                <div class="form-section">
                    <h2>🚀 Projects</h2>
                    <div class="form-group">
                        <label for="projects">Your Projects</label>
                        <textarea id="projects" name="projects" rows="6"
                                  placeholder="Describe your projects (optional)..."
                                  maxlength="10000"></textarea>
                        <small class="form-help">Optional. You can add projects later.</small>
                    </div>
                </div>

                <div class="form-section">
                    <h2>💼 Work Experience</h2>
                    <div class="form-group">
                        <label for="work_experience">Work Experience</label>
                        <textarea id="work_experience" name="work_experience" rows="6"
                                  placeholder="Describe your work experience (optional)..."
                                  maxlength="10000"></textarea>
                        <small class="form-help">Optional if you have no work experience yet.</small>
                    </div>
                </div>

                <div class="form-section">
                    <h2>🔗 Social Links</h2>
                    <div class="form-group">
                        <label for="social_links">Social Media / Website Links</label>
                        <textarea id="social_links" name="social_links" rows="4"
                                  placeholder="GitHub, LinkedIn, Facebook or personal website (optional)..."
                                  maxlength="5000"></textarea>
                        <small class="form-help">Optional. Only share links you want displayed publicly.</small>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" id="submitButton" class="btn btn-primary">
                        💾 Save Portfolio
                    </button>
                </div>

            </form>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

<script>
(function () {
    'use strict';

    const form = document.getElementById('portfolioForm');
    const summary = document.getElementById('validationSummary');
    const submitButton = document.getElementById('submitButton');

    const requiredFields = [
        'full_name',
        'email',
        'contact_number',
        'address',
        'professional_title',
        'career_objective',
        'about_me',
        'education',
        'skills'
    ];

    function showError(input, message) {
        const error = document.getElementById(input.id + '_error');

        input.classList.remove('valid');
        input.classList.add('invalid');
        input.setAttribute('aria-invalid', 'true');

        if (error) error.textContent = message;
    }

    function clearError(input) {
        const error = document.getElementById(input.id + '_error');

        input.classList.remove('invalid');
        input.removeAttribute('aria-invalid');

        if (error) error.textContent = '';

        if (input.value.trim() !== '') {
            input.classList.add('valid');
        } else {
            input.classList.remove('valid');
        }
    }

    function validateField(input) {
        const value = input.value.trim();

        if (input.required && value === '') {
            showError(input, 'This field is required.');
            return false;
        }

        if (value === '') {
            clearError(input);
            return true;
        }

        if (input.id === 'full_name') {
            const namePattern = /^\p{L}+(?:[ '\u2019-]\p{L}+)*$/u;

            if (value.length < 2 || !namePattern.test(value)) {
                showError(input, 'Enter a valid name using letters only. Spaces, apostrophes and hyphens are allowed.');
                return false;
            }
        }

        if (input.id === 'email') {
            if (!input.validity.valid || /\s/.test(value)) {
                showError(input, 'Enter a valid email address, such as name@example.com.');
                return false;
            }
        }

        if (input.id === 'contact_number') {
            const phonePattern = /^[0-9+\s().-]+$/;
            const digits = value.replace(/\D/g, '');

            if (!phonePattern.test(value) || digits.length < 7 || digits.length > 15) {
                showError(input, 'Enter a valid phone number containing 7–15 digits. Letters are not allowed.');
                return false;
            }
        }

        if (input.id === 'professional_title' && value.length < 2) {
            showError(input, 'Please enter a professional title with at least 2 characters.');
            return false;
        }

        if (['career_objective', 'about_me'].includes(input.id) && value.length < 10) {
            showError(input, 'Please enter at least 10 characters.');
            return false;
        }

        if (['education', 'skills'].includes(input.id) && value.length < 2) {
            showError(input, 'Please provide more information.');
            return false;
        }

        if (input.maxLength > 0 && value.length > input.maxLength) {
            showError(input, 'This field contains too much text.');
            return false;
        }

        clearError(input);
        return true;
    }

    requiredFields.forEach(function (id) {
        const input = document.getElementById(id);

        input.addEventListener('input', function () {
            validateField(input);
            summary.classList.remove('show');
        });

        input.addEventListener('blur', function () {
            validateField(input);
        });
    });

    /* =========================================
       PROFILE PICTURE PREVIEW
       ========================================= */

    const profileInput = document.getElementById('profile_picture');
    const profilePreview = document.getElementById('profilePreview');
    const profilePlaceholder = document.getElementById('profilePlaceholder');
    const profileError = document.getElementById('profile_picture_error');

    let currentPreviewUrl = null;

    profileInput.addEventListener('change', function () {
        const file = this.files && this.files[0];

        if (currentPreviewUrl) {
            URL.revokeObjectURL(currentPreviewUrl);
            currentPreviewUrl = null;
        }

        profilePreview.removeAttribute('src');
        profilePreview.style.display = 'none';
        profilePlaceholder.style.display = 'block';
        profileError.textContent = '';

        if (!file) return;

        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!allowedTypes.includes(file.type)) {
            profileError.textContent = 'Choose a JPG, PNG or WEBP image.';
            this.value = '';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            profileError.textContent = 'The image must be 5 MB or smaller.';
            this.value = '';
            return;
        }

        currentPreviewUrl = URL.createObjectURL(file);
        profilePreview.src = currentPreviewUrl;
        profilePreview.style.display = 'block';
        profilePlaceholder.style.display = 'none';
    });

    /* =========================================
       FORM VALIDATION AND SUBMISSION
       ========================================= */

    form.addEventListener('submit', function (event) {
        let isValid = true;
        let firstInvalid = null;

        requiredFields.forEach(function (id) {
            const input = document.getElementById(id);

            if (!validateField(input)) {
                isValid = false;
                if (!firstInvalid) firstInvalid = input;
            }
        });

        if (!form.checkValidity()) {
            isValid = false;

            if (!firstInvalid) {
                firstInvalid = form.querySelector(':invalid');
            }
        }

        if (profileInput.files.length > 0) {
            const file = profileInput.files[0];
            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

            if (!allowedTypes.includes(file.type) || file.size > 5 * 1024 * 1024) {
                isValid = false;
                profileError.textContent =
                    'Choose a JPG, PNG or WEBP image no larger than 5 MB.';
            }
        }

        if (!isValid) {
            event.preventDefault();

            summary.textContent =
                'Please correct the highlighted fields before saving your portfolio.';
            summary.classList.add('show');

            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            return;
        }

        submitButton.disabled = true;
        submitButton.textContent = 'Saving...';
    });

})();
</script>

</body>
</html>
