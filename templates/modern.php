<?php
// ========================================
// MODERN PORTFOLIO TEMPLATE
// PortfolioGen - Itachi Inspired Theme
// Black, Crimson and Purple
// ========================================

$fullName = $portfolio['full_name'] ?? 'Your Name';
$email = $portfolio['email'] ?? '';
$contactNumber = $portfolio['contact_number'] ?? '';
$address = $portfolio['address'] ?? '';
$profilePicture = $portfolio['profile_picture'] ?? '';
$aboutMe = $portfolio['about_me'] ?? '';
$skillsText = $portfolio['skills'] ?? '';
$education = $portfolio['education'] ?? '';
$projects = $portfolio['projects'] ?? '';
$workExperience = $portfolio['work_experience'] ?? '';
$socialLinks = $portfolio['social_links'] ?? '';

$skills = array_filter(array_map(
    'trim',
    preg_split('/[,;\r\n]+/', $skillsText)
));

$projectList = array_filter(array_map(
    'trim',
    preg_split('/\r\n|\r|\n/', $projects)
));

$experienceList = array_filter(array_map(
    'trim',
    preg_split('/\r\n|\r|\n/', $workExperience)
));

$educationList = array_filter(array_map(
    'trim',
    preg_split('/\r\n|\r|\n/', $education)
));

$links = array_filter(array_map(
    'trim',
    preg_split('/[\r\n,]+/', $socialLinks)
));

$escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$initials = 'YP';
$nameParts = preg_split('/\s+/', trim($fullName));

if (!empty($nameParts[0])) {
    $initials = strtoupper(substr($nameParts[0], 0, 1));

    if (count($nameParts) > 1) {
        $initials .= strtoupper(substr(end($nameParts), 0, 1));
    }
}

$isWebUrl = static function ($url) {
    return filter_var($url, FILTER_VALIDATE_URL)
        && in_array(
            strtolower((string) parse_url($url, PHP_URL_SCHEME)),
            ['http', 'https'],
            true
        );
};

// Identify social platform from URL
$getSocial = static function ($url) {
    $host = strtolower((string) parse_url($url, PHP_URL_HOST));

    $platforms = [
        'github.com' => ['GitHub', 'fa-github', 'social-github'],
        'linkedin.com' => ['LinkedIn', 'fa-linkedin-in', 'social-linkedin'],
        'facebook.com' => ['Facebook', 'fa-facebook-f', 'social-facebook'],
        'instagram.com' => ['Instagram', 'fa-instagram', 'social-instagram'],
        'youtube.com' => ['YouTube', 'fa-youtube', 'social-youtube'],
        'youtu.be' => ['YouTube', 'fa-youtube', 'social-youtube'],
        'tiktok.com' => ['TikTok', 'fa-tiktok', 'social-tiktok'],
        'x.com' => ['X / Twitter', 'fa-x-twitter', 'social-x'],
        'twitter.com' => ['X / Twitter', 'fa-x-twitter', 'social-x'],
        'discord.com' => ['Discord', 'fa-discord', 'social-discord'],
        'discord.gg' => ['Discord', 'fa-discord', 'social-discord'],
        'behance.net' => ['Behance', 'fa-behance', 'social-behance'],
        'dribbble.com' => ['Dribbble', 'fa-dribbble', 'social-dribbble'],
        'pinterest.com' => ['Pinterest', 'fa-pinterest-p', 'social-pinterest'],
        'telegram.org' => ['Telegram', 'fa-telegram', 'social-telegram'],
        'wa.me' => ['WhatsApp', 'fa-whatsapp', 'social-whatsapp'],
    ];

    foreach ($platforms as $domain => $info) {
        if ($host === $domain || str_ends_with($host, '.' . $domain)) {
            return $info;
        }
    }

    return ['Website', 'fa-globe', 'social-website'];
};
?>

<!-- Font Awesome icons for social media logos -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<style>
.modern-v3 {
    --mv-bg: #08070d;
    --mv-panel: #13101d;
    --mv-red: #e11d48;
    --mv-purple: #7c3aed;
    --mv-lavender: #c4b5fd;
    --mv-text: #f5f3ff;
    --mv-muted: #b6aec7;

    position: relative;
    isolation: isolate;
    min-height: 100vh;
    overflow: hidden;
    background: var(--mv-bg);
    color: var(--mv-text);
    font-family: Inter, "Segoe UI", Arial, sans-serif;
    line-height: 1.7;
}

.modern-v3 *,
.modern-v3 *::before,
.modern-v3 *::after {
    box-sizing: border-box;
}

.modern-v3 a {
    color: inherit;
    text-decoration: none;
}

.modern-v3 .modern-v3-container {
    position: relative;
    z-index: 2;
    width: min(1160px, 92%);
    margin: auto;
    padding: 55px 0;
}

/* Animated background */

.modern-v3 .modern-background {
    position: absolute;
    inset: 0;
    z-index: -1;
    overflow: hidden;
    pointer-events: none;
}

.modern-v3 .modern-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(167,139,250,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(167,139,250,.05) 1px, transparent 1px);
    background-size: 45px 45px;
    mask-image: linear-gradient(to bottom, black, transparent 90%);
    animation: gridMove 20s linear infinite;
}

.modern-v3 .modern-orb {
    position: absolute;
    width: 340px;
    height: 340px;
    border-radius: 50%;
    filter: blur(100px);
    opacity: .23;
    animation: orbFloat 10s ease-in-out infinite alternate;
}

.modern-v3 .modern-orb-purple {
    top: 5%;
    left: -170px;
    background: var(--mv-purple);
}

.modern-v3 .modern-orb-red {
    top: 35%;
    right: -180px;
    background: var(--mv-red);
    animation-delay: -4s;
}

.modern-v3 .modern-orb-bottom {
    bottom: -180px;
    left: 40%;
    background: #5b21b6;
    opacity: .17;
    animation-delay: -7s;
}

@keyframes orbFloat {
    from { transform: translate(0, 0) scale(1); }
    to { transform: translate(35px, -30px) scale(1.12); }
}

@keyframes gridMove {
    from { background-position: 0 0; }
    to { background-position: 45px 45px; }
}

.modern-v3 .modern-particle {
    position: absolute;
    bottom: -20px;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #c4b5fd;
    box-shadow: 0 0 12px #a78bfa;
    animation: particleRise var(--particle-duration, 12s) linear infinite;
    animation-delay: var(--particle-delay, 0s);
    left: var(--particle-left, 50%);
    opacity: 0;
}

@keyframes particleRise {
    0% { transform: translate3d(0, 0, 0); opacity: 0; }
    15% { opacity: .7; }
    85% { opacity: .5; }
    100% { transform: translate3d(var(--particle-drift, 20px), -100vh, 0); opacity: 0; }
}

/* Profile header */

.modern-v3 .modern-profile-header {
    position: relative;
    isolation: isolate;
    overflow: hidden;
    margin-bottom: 28px;
    padding: 55px 35px 40px;
    text-align: center;
    background:
        radial-gradient(circle at 50% 0%, rgba(124,58,237,.2), transparent 55%),
        linear-gradient(140deg, #171020, #0c0a12 70%);
    border: 1px solid rgba(167,139,250,.3);
    border-radius: 26px;
    box-shadow: 0 20px 65px rgba(0,0,0,.25);
}

.modern-v3 .modern-profile-header::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 80%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #c4b5fd, #e11d48, transparent);
    animation: headerLight 6s linear infinite;
}

@keyframes headerLight {
    to { left: 120%; }
}

.modern-v3 .modern-profile-image-wrap {
    display: flex;
    justify-content: center;
    margin-bottom: 24px;
}

.modern-v3 .modern-profile-image-border {
    display: grid;
    place-items: center;
    width: 155px;
    height: 155px;
    padding: 5px;
    border-radius: 50%;
    background: linear-gradient(135deg, #7c3aed, #c084fc, #e11d48, #7c3aed);
    background-size: 300% 300%;
    animation: borderShift 5s ease infinite, imagePulse 3s ease-in-out infinite;
    box-shadow: 0 0 25px rgba(124,58,237,.3), 0 0 45px rgba(225,29,72,.15);
}

@keyframes borderShift {
    0%, 100% { background-position: 0 50%; }
    50% { background-position: 100% 50%; }
}

@keyframes imagePulse {
    0%, 100% { box-shadow: 0 0 20px rgba(124,58,237,.3); }
    50% { box-shadow: 0 0 35px rgba(225,29,72,.4); }
}

.modern-v3 .modern-profile-image,
.modern-v3 .modern-profile-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    border: 4px solid #100c18;
    border-radius: 50%;
    object-fit: cover;
    background: #21152e;
}

.modern-v3 .modern-profile-fallback {
    color: white;
    font-size: 42px;
    font-weight: 800;
    letter-spacing: 2px;
}

.modern-v3 .modern-status {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 7px 14px;
    border: 1px solid rgba(167,139,250,.28);
    border-radius: 50px;
    background: rgba(124,58,237,.12);
    color: var(--mv-lavender);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1.4px;
    text-transform: uppercase;
}

.modern-v3 .modern-status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #a78bfa;
    box-shadow: 0 0 10px #a78bfa;
    animation: dotPulse 1.8s ease-in-out infinite;
}

@keyframes dotPulse {
    50% { opacity: .35; transform: scale(.75); }
}

.modern-v3 .modern-profile-header h1 {
    margin: 18px 0 10px;
    color: #fff;
    font-size: clamp(32px, 5vw, 55px);
    font-weight: 850;
    line-height: 1.2;
    letter-spacing: -1.5px;
    overflow-wrap: anywhere;
    background: linear-gradient(90deg, #fff, #c4b5fd 55%, #fb7185);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: nameGlow 4s ease-in-out infinite;
}

@keyframes nameGlow {
    0%, 100% { filter: drop-shadow(0 0 3px rgba(124,58,237,.15)); }
    50% { filter: drop-shadow(0 0 14px rgba(225,29,72,.28)); }
}

.modern-v3 .modern-profile-intro {
    max-width: 620px;
    margin: auto;
    color: var(--mv-muted);
    font-size: 15px;
    line-height: 1.9;
    overflow-wrap: anywhere;
}

/* Statistics */

.modern-v3 .modern-stats {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 30px;
}

.modern-v3 .modern-stat {
    min-width: 115px;
    padding: 13px 20px;
    border: 1px solid rgba(167,139,250,.16);
    border-radius: 14px;
    background: rgba(255,255,255,.025);
    transition: transform .3s, border-color .3s, background .3s;
}

.modern-v3 .modern-stat:hover {
    transform: translateY(-5px);
    border-color: #7c3aed;
    background: rgba(124,58,237,.12);
}

.modern-v3 .modern-stat strong {
    display: block;
    color: #e9ddff;
    font-size: 23px;
    line-height: 1.4;
}

.modern-v3 .modern-stat span {
    color: #9288a7;
    font-size: 11px;
    letter-spacing: 1px;
    text-transform: uppercase;
}

/* Contact details */

.modern-v3 .modern-contact-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 28px;
}

.modern-v3 .modern-contact-card {
    min-width: 0;
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 17px;
    border: 1px solid rgba(167,139,250,.17);
    border-radius: 16px;
    background: rgba(20,15,29,.85);
    transition: border-color .3s, transform .3s, box-shadow .3s;
}

.modern-v3 .modern-contact-card:hover {
    transform: translateY(-4px);
    border-color: rgba(225,29,72,.6);
    box-shadow: 0 10px 28px rgba(225,29,72,.1);
}

.modern-v3 .modern-contact-icon {
    display: grid;
    place-items: center;
    flex-shrink: 0;
    width: 42px;
    height: 42px;
    border-radius: 12px;
    color: #d8b4fe;
    background: rgba(124,58,237,.15);
    font-size: 18px;
}

.modern-v3 .modern-contact-label {
    display: block;
    margin-bottom: 3px;
    color: #9187a5;
    font-size: 11px;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.modern-v3 .modern-contact-value {
    display: block;
    color: #e7e0f3;
    font-size: 13px;
    overflow-wrap: anywhere;
}

/* Main columns and cards */

.modern-v3 .modern-content-grid {
    display: grid;
    grid-template-columns: minmax(0,1.25fr) minmax(0,.85fr);
    gap: 24px;
    align-items: start;
}

.modern-v3 .modern-column {
    min-width: 0;
}

.modern-v3 .modern-card {
    position: relative;
    isolation: isolate;
    min-width: 0;
    overflow: hidden;
    margin-bottom: 22px;
    padding: 26px;
    border: 1px solid rgba(167,139,250,.18);
    border-radius: 19px;
    background: linear-gradient(145deg, rgba(25,19,38,.96), rgba(12,10,20,.98));
    box-shadow: 0 12px 35px rgba(0,0,0,.18);
    transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease;
}

.modern-v3 .modern-card::before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: -120%;
    width: 70%;
    height: 100%;
    background: linear-gradient(110deg, transparent, rgba(196,181,253,.045), transparent);
    transform: skewX(-15deg);
    transition: left .7s ease;
    pointer-events: none;
}

.modern-v3 .modern-card:hover::before {
    left: 150%;
}

.modern-v3 .modern-card:hover {
    transform: translateY(-4px);
    border-color: rgba(167,139,250,.45);
    box-shadow: 0 15px 40px rgba(68,35,110,.18);
}

.modern-v3 .modern-card-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.modern-v3 .modern-heading-icon {
    display: grid;
    place-items: center;
    flex-shrink: 0;
    width: 43px;
    height: 43px;
    border: 1px solid rgba(167,139,250,.2);
    border-radius: 13px;
    background: rgba(124,58,237,.14);
    color: #c4b5fd;
    font-size: 19px;
}

.modern-v3 .modern-card-heading h2 {
    margin: 0;
    color: #f0eaff;
    font-size: 19px;
    font-weight: 750;
}

.modern-v3 .modern-card-heading p {
    margin: 2px 0 0;
    color: #8f849f;
    font-size: 12px;
}

.modern-v3 .modern-card > p {
    color: #bdb5cb;
    font-size: 14px;
    line-height: 1.9;
    overflow-wrap: anywhere;
}

.modern-v3 .modern-empty {
    color: #857b96;
    font-size: 13px;
    font-style: italic;
}

/* Project, education and experience entries */

.modern-v3 .modern-list {
    display: grid;
    gap: 13px;
}

.modern-v3 .modern-list-item {
    position: relative;
    padding: 16px 17px;
    border: 1px solid rgba(167,139,250,.13);
    border-radius: 13px;
    background: rgba(255,255,255,.025);
    overflow-wrap: anywhere;
}

.modern-v3 .modern-list-item::before {
    content: "";
    position: absolute;
    top: 17px;
    bottom: 17px;
    left: 0;
    width: 3px;
    border-radius: 5px;
    background: linear-gradient(to bottom, var(--mv-purple), var(--mv-red));
}

.modern-v3 .modern-list-item p {
    margin: 0;
    padding-left: 4px;
    color: #bdb5cb;
    white-space: pre-line;
    font-size: 14px;
}

.modern-v3 .modern-list-label {
    display: block;
    margin-bottom: 6px;
    color: #c4b5fd;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
}

/* Skills */

.modern-v3 .modern-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
}

.modern-v3 .modern-skill {
    padding: 8px 12px;
    border: 1px solid rgba(167,139,250,.22);
    border-radius: 9px;
    background: rgba(124,58,237,.1);
    color: #ddd0ff;
    font-size: 12px;
    font-weight: 600;
    overflow-wrap: anywhere;
    transition: border-color .25s, background .25s, transform .25s, box-shadow .25s;
}

.modern-v3 .modern-skill:hover {
    transform: translateY(-3px);
    border-color: rgba(225,29,72,.6);
    background: rgba(225,29,72,.12);
    box-shadow: 0 0 16px rgba(225,29,72,.1);
}

/* CONNECT section */

.modern-v3 .modern-connect-intro {
    margin: -5px 0 20px;
    color: #b9b2c9;
    font-size: 13px;
    line-height: 1.8;
}

.modern-v3 .modern-connect-list {
    display: grid;
    gap: 12px;
}

.modern-v3 .modern-connect-item {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
    padding: 14px;
    border: 1px solid rgba(167,139,250,.17);
    border-radius: 14px;
    background: rgba(255,255,255,.025);
    color: #fff;
    transition: transform .3s, border-color .3s, background .3s, box-shadow .3s;
}

.modern-v3 .modern-connect-item:hover {
    transform: translateY(-3px);
    border-color: rgba(225,29,72,.65);
    background: rgba(60,12,45,.4);
    box-shadow: 0 8px 25px rgba(225,29,72,.1);
}

.modern-v3 .modern-connect-logo {
    display: grid;
    place-items: center;
    flex: 0 0 45px;
    width: 45px;
    height: 45px;
    border-radius: 13px;
    background: rgba(124,58,237,.15);
    color: #c4b5fd;
    font-size: 21px;
    transition: transform .3s, box-shadow .3s;
}

.modern-v3 .modern-connect-item:hover .modern-connect-logo {
    transform: rotate(-5deg) scale(1.08);
    box-shadow: 0 0 17px rgba(124,58,237,.25);
}

.modern-v3 .modern-connect-text {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-width: 0;
    gap: 3px;
}

.modern-v3 .modern-connect-text strong {
    color: #f5f3ff;
    font-size: 13px;
}

.modern-v3 .modern-connect-text small {
    color: #a99fba;
    font-size: 11px;
    overflow-wrap: anywhere;
    word-break: break-word;
}

.modern-v3 .modern-connect-arrow {
    flex-shrink: 0;
    color: #a78bfa;
    font-size: 12px;
    transition: transform .25s, color .25s;
}

.modern-v3 .modern-connect-item:hover .modern-connect-arrow {
    transform: translate(3px,-3px);
    color: #fb7185;
}

/* Social logo colors */

.modern-v3 .social-github { color: #fff; }
.modern-v3 .social-linkedin { color: #60a5fa; }
.modern-v3 .social-facebook { color: #60a5fa; }
.modern-v3 .social-instagram { color: #f0abfc; }
.modern-v3 .social-youtube { color: #fb7185; }
.modern-v3 .social-tiktok { color: #67e8f9; }
.modern-v3 .social-x { color: #fff; }
.modern-v3 .social-discord { color: #a5b4fc; }
.modern-v3 .social-behance { color: #60a5fa; }
.modern-v3 .social-dribbble { color: #f9a8d4; }
.modern-v3 .social-pinterest { color: #fb7185; }
.modern-v3 .social-telegram { color: #7dd3fc; }
.modern-v3 .social-whatsapp { color: #86efac; }
.modern-v3 .social-website { color: #c4b5fd; }

/* Overview */

.modern-v3 .modern-overview-row {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid rgba(167,139,250,.1);
    font-size: 13px;
}

.modern-v3 .modern-overview-row:last-child {
    border-bottom: none;
}

.modern-v3 .modern-overview-row span {
    color: #968ba8;
}

.modern-v3 .modern-overview-row strong {
    color: #e7def5;
    text-align: right;
    overflow-wrap: anywhere;
}

/* Footer */

.modern-v3 .modern-footer {
    margin-top: 12px;
    padding: 30px 15px 0;
    border-top: 1px solid rgba(167,139,250,.14);
    text-align: center;
}

.modern-v3 .modern-footer-title {
    margin: 0 0 5px;
    color: #d9ccff;
    font-size: 15px;
    font-weight: 700;
}

.modern-v3 .modern-footer p {
    margin: 0;
    color: #827891;
    font-size: 12px;
}

/* Responsive */

@media (max-width: 800px) {
    .modern-v3 .modern-v3-container {
        width: 92%;
        padding: 35px 0;
    }

    .modern-v3 .modern-content-grid {
        grid-template-columns: minmax(0,1fr);
        gap: 5px;
    }

    .modern-v3 .modern-contact-grid {
        grid-template-columns: minmax(0,1fr);
    }
}

@media (max-width: 480px) {
    .modern-v3 .modern-profile-header {
        padding: 35px 18px;
        border-radius: 20px;
    }

    .modern-v3 .modern-profile-image-border {
        width: 125px;
        height: 125px;
    }

    .modern-v3 .modern-profile-header h1 {
        font-size: 32px;
        letter-spacing: -.7px;
    }

    .modern-v3 .modern-card {
        padding: 20px;
        border-radius: 16px;
    }

    .modern-v3 .modern-stat {
        min-width: 95px;
        padding: 10px 13px;
    }

    .modern-v3 .modern-connect-item {
        gap: 10px;
        padding: 11px;
    }

    .modern-v3 .modern-connect-logo {
        flex-basis: 39px;
        width: 39px;
        height: 39px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .modern-v3 *,
    .modern-v3 *::before,
    .modern-v3 *::after {
        animation: none !important;
        transition: none !important;
        scroll-behavior: auto !important;
    }

    .modern-v3 .modern-card:hover,
    .modern-v3 .modern-contact-card:hover,
    .modern-v3 .modern-connect-item:hover {
        transform: none;
    }
}
</style>

<div class="modern-v3">

    <div class="modern-background" aria-hidden="true">
        <div class="modern-grid"></div>
        <div class="modern-orb modern-orb-purple"></div>
        <div class="modern-orb modern-orb-red"></div>
        <div class="modern-orb modern-orb-bottom"></div>
    </div>

    <main class="modern-v3-container">

        <!-- PROFILE HEADER -->
        <header class="modern-profile-header">

            <div class="modern-profile-image-wrap">
                <div class="modern-profile-image-border">
                    <?php if (!empty($profilePicture)): ?>
                        <img
                            class="modern-profile-image"
                            src="<?= $escape($profilePicture) ?>"
                            alt="<?= $escape($fullName) ?> profile picture"
                        >
                    <?php else: ?>
                        <div class="modern-profile-fallback">
                            <?= $escape($initials) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="modern-status">
                <span class="modern-status-dot"></span>
                Personal Portfolio
            </div>

            <h1><?= $escape($fullName) ?></h1>

            <p class="modern-profile-intro">
                <?php if (!empty(trim($aboutMe))): ?>
                    <?= nl2br($escape($aboutMe)) ?>
                <?php else: ?>
                    Welcome to my portfolio. Explore my skills,
                    projects, education, and experience.
                <?php endif; ?>
            </p>

            <div class="modern-stats">
                <div class="modern-stat">
                    <strong><?= count($skills) ?></strong>
                    <span>Skills</span>
                </div>
                <div class="modern-stat">
                    <strong><?= count($projectList) ?></strong>
                    <span>Projects</span>
                </div>
                <div class="modern-stat">
                    <strong><?= count($educationList) ?></strong>
                    <span>Education</span>
                </div>
            </div>

        </header>

        <!-- CONTACT DETAILS -->
        <?php if ($email || $contactNumber || $address): ?>
            <section class="modern-contact-grid">

                <?php if ($email): ?>
                    <a class="modern-contact-card"
                       href="mailto:<?= $escape($email) ?>">
                        <span class="modern-contact-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <span>
                            <span class="modern-contact-label">Email</span>
                            <span class="modern-contact-value">
                                <?= $escape($email) ?>
                            </span>
                        </span>
                    </a>
                <?php endif; ?>

                <?php if ($contactNumber): ?>
                    <a class="modern-contact-card"
                       href="tel:<?= $escape($contactNumber) ?>">
                        <span class="modern-contact-icon">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <span>
                            <span class="modern-contact-label">Phone</span>
                            <span class="modern-contact-value">
                                <?= $escape($contactNumber) ?>
                            </span>
                        </span>
                    </a>
                <?php endif; ?>

                <?php if ($address): ?>
                    <div class="modern-contact-card">
                        <span class="modern-contact-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </span>
                        <span>
                            <span class="modern-contact-label">Location</span>
                            <span class="modern-contact-value">
                                <?= $escape($address) ?>
                            </span>
                        </span>
                    </div>
                <?php endif; ?>

            </section>
        <?php endif; ?>

        <!-- MAIN CONTENT -->
        <div class="modern-content-grid">

            <!-- LEFT COLUMN -->
            <div class="modern-column">

                <!-- ABOUT -->
                <section class="modern-card">
                    <div class="modern-card-heading">
                        <span class="modern-heading-icon">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <div>
                            <h2>About Me</h2>
                            <p>A little introduction</p>
                        </div>
                    </div>

                    <?php if (!empty(trim($aboutMe))): ?>
                        <p><?= nl2br($escape($aboutMe)) ?></p>
                    <?php else: ?>
                        <p class="modern-empty">
                            No introduction has been added yet.
                        </p>
                    <?php endif; ?>
                </section>

                <!-- PROJECTS -->
                <section class="modern-card">
                    <div class="modern-card-heading">
                        <span class="modern-heading-icon">
                            <i class="fa-solid fa-code"></i>
                        </span>
                        <div>
                            <h2>Projects</h2>
                            <p>Things I have worked on</p>
                        </div>
                    </div>

                    <?php if (!empty($projectList)): ?>
                        <div class="modern-list">
                            <?php foreach ($projectList as $project): ?>
                                <div class="modern-list-item">
                                    <span class="modern-list-label">Project</span>
                                    <p><?= $escape($project) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="modern-empty">
                            No projects have been added yet.
                        </p>
                    <?php endif; ?>
                </section>

                <!-- WORK EXPERIENCE -->
                <section class="modern-card">
                    <div class="modern-card-heading">
                        <span class="modern-heading-icon">
                            <i class="fa-solid fa-briefcase"></i>
                        </span>
                        <div>
                            <h2>Work Experience</h2>
                            <p>My experience and activities</p>
                        </div>
                    </div>

                    <?php if (!empty($experienceList)): ?>
                        <div class="modern-list">
                            <?php foreach ($experienceList as $experience): ?>
                                <div class="modern-list-item">
                                    <p><?= $escape($experience) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="modern-empty">
                            No work experience has been added yet.
                        </p>
                    <?php endif; ?>
                </section>

            </div>

            <!-- RIGHT COLUMN -->
            <aside class="modern-column">

                <!-- SKILLS -->
                <section class="modern-card">
                    <div class="modern-card-heading">
                        <span class="modern-heading-icon">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </span>
                        <div>
                            <h2>My Skills</h2>
                            <p>What I can do</p>
                        </div>
                    </div>

                    <?php if (!empty($skills)): ?>
                        <div class="modern-skills">
                            <?php foreach ($skills as $skill): ?>
                                <span class="modern-skill">
                                    <?= $escape($skill) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="modern-empty">
                            No skills have been added yet.
                        </p>
                    <?php endif; ?>
                </section>

                <!-- EDUCATION -->
                <section class="modern-card">
                    <div class="modern-card-heading">
                        <span class="modern-heading-icon">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </span>
                        <div>
                            <h2>Education</h2>
                            <p>My learning journey</p>
                        </div>
                    </div>

                    <?php if (!empty($educationList)): ?>
                        <div class="modern-list">
                            <?php foreach ($educationList as $school): ?>
                                <div class="modern-list-item">
                                    <span class="modern-list-label">Education</span>
                                    <p><?= $escape($school) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="modern-empty">
                            No education details have been added yet.
                        </p>
                    <?php endif; ?>
                </section>

                <!-- CONNECT -->
                <?php if ($email || $contactNumber || $address || !empty($links)): ?>
                    <section class="modern-card modern-connect-card">

                        <div class="modern-card-heading">
                            <span class="modern-heading-icon">
                                <i class="fa-solid fa-paper-plane"></i>
                            </span>
                            <div>
                                <h2>Let's Connect</h2>
                                <p>Find me online</p>
                            </div>
                        </div>

                        <p class="modern-connect-intro">
                            Have a question or an opportunity?
                            Feel free to reach out!
                        </p>

                        <div class="modern-connect-list">

                            <?php if ($email): ?>
                                <a class="modern-connect-item"
                                   href="mailto:<?= $escape($email) ?>">
                                    <span class="modern-connect-logo">
                                        <i class="fa-solid fa-envelope"></i>
                                    </span>
                                    <span class="modern-connect-text">
                                        <strong>Email Address</strong>
                                        <small><?= $escape($email) ?></small>
                                    </span>
                                    <i class="fa-solid fa-arrow-up-right-from-square modern-connect-arrow"></i>
                                </a>
                            <?php endif; ?>

                            <?php if ($contactNumber): ?>
                                <a class="modern-connect-item"
                                   href="tel:<?= $escape($contactNumber) ?>">
                                    <span class="modern-connect-logo">
                                        <i class="fa-solid fa-phone"></i>
                                    </span>
                                    <span class="modern-connect-text">
                                        <strong>Phone Number</strong>
                                        <small><?= $escape($contactNumber) ?></small>
                                    </span>
                                    <i class="fa-solid fa-arrow-up-right-from-square modern-connect-arrow"></i>
                                </a>
                            <?php endif; ?>

                            <?php if ($address): ?>
                                <div class="modern-connect-item">
                                    <span class="modern-connect-logo">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </span>
                                    <span class="modern-connect-text">
                                        <strong>Location</strong>
                                        <small><?= $escape($address) ?></small>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <?php foreach ($links as $link): ?>
                                <?php if ($isWebUrl($link)): ?>
                                    <?php
                                    [$platformName, $platformIcon, $platformClass]
                                        = $getSocial($link);
                                    ?>

                                    <a class="modern-connect-item"
                                       href="<?= $escape($link) ?>"
                                       target="_blank"
                                       rel="noopener noreferrer">
                                        <span class="modern-connect-logo <?= $escape($platformClass) ?>">
                                            <i class="fa-brands <?= $escape($platformIcon) ?>"></i>
                                        </span>
                                        <span class="modern-connect-text">
                                            <strong><?= $escape($platformName) ?></strong>
                                            <small><?= $escape($link) ?></small>
                                        </span>
                                        <i class="fa-solid fa-arrow-up-right-from-square modern-connect-arrow"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>

                        </div>
                    </section>
                <?php endif; ?>

                <!-- PORTFOLIO OVERVIEW -->
                <section class="modern-card">
                    <div class="modern-card-heading">
                        <span class="modern-heading-icon">
                            <i class="fa-solid fa-chart-pie"></i>
                        </span>
                        <div>
                            <h2>Portfolio Overview</h2>
                            <p>At a glance</p>
                        </div>
                    </div>

                    <div class="modern-overview-row">
                        <span>Skills listed</span>
                        <strong><?= count($skills) ?></strong>
                    </div>
                    <div class="modern-overview-row">
                        <span>Projects listed</span>
                        <strong><?= count($projectList) ?></strong>
                    </div>
                    <div class="modern-overview-row">
                        <span>Education entries</span>
                        <strong><?= count($educationList) ?></strong>
                    </div>
                    <div class="modern-overview-row">
                        <span>Experience entries</span>
                        <strong><?= count($experienceList) ?></strong>
                    </div>
                </section>

            </aside>
        </div>

        <!-- FOOTER -->
        <footer class="modern-footer">
            <p class="modern-footer-title">
                Thanks for visiting my portfolio.
            </p>
            <p>
                Designed with PortfolioGen
                <span style="color:#e11d48;">♥</span>
            </p>
        </footer>

    </main>
</div>

<script>
(function () {
    const root = document.querySelector('.modern-v3');
    if (!root) return;

    const background = root.querySelector('.modern-background');
    if (!background) return;

    // Respect the visitor's reduced-motion setting.
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    // Create floating purple and crimson particles.
    for (let i = 0; i < 24; i++) {
        const particle = document.createElement('span');
        particle.className = 'modern-particle';

        particle.style.setProperty(
            '--particle-left',
            Math.random() * 100 + '%'
        );

        particle.style.setProperty(
            '--particle-duration',
            (9 + Math.random() * 12) + 's'
        );

        particle.style.setProperty(
            '--particle-delay',
            (-Math.random() * 18) + 's'
        );

        particle.style.setProperty(
            '--particle-drift',
            (Math.random() * 100 - 50) + 'px'
        );

        if (Math.random() > 0.5) {
            particle.style.background = '#fb7185';
            particle.style.boxShadow = '0 0 12px #e11d48';
        }

        background.appendChild(particle);
    }
})();
</script>
