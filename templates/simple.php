
<?php
// Safely escape text for HTML.
if (!function_exists('simpleEscape')) {
    function simpleEscape($value): string
    {
        return htmlspecialchars(
            (string) ($value ?? ''),
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }
}

$fullName = $portfolio['full_name'] ?? 'My Portfolio';
$socialText = trim((string) ($portfolio['social_links'] ?? ''));

// Find URLs entered by the user, including one URL per line.
preg_match_all(
    '~https?://[^\s<>"\']+~i',
    $socialText,
    $socialMatches
);

$socialUrls = array_unique($socialMatches[0]);

// Supported social media logos.
$socialPlatforms = [
    'github.com'    => ['GitHub', 'fa-github'],
    'facebook.com'  => ['Facebook', 'fa-facebook'],
    'linkedin.com'  => ['LinkedIn', 'fa-linkedin'],
    'instagram.com' => ['Instagram', 'fa-instagram'],
    'youtube.com'   => ['YouTube', 'fa-youtube'],
    'youtu.be'      => ['YouTube', 'fa-youtube'],
    'tiktok.com'    => ['TikTok', 'fa-tiktok'],
    'x.com'         => ['X', 'fa-x-twitter'],
    'twitter.com'   => ['X', 'fa-x-twitter'],
    'discord.com'   => ['Discord', 'fa-discord'],
    'twitch.tv'     => ['Twitch', 'fa-twitch'],
    'medium.com'    => ['Medium', 'fa-medium'],
    'behance.net'   => ['Behance', 'fa-behance'],
    'dribbble.com'  => ['Dribbble', 'fa-dribbble'],
    'pinterest.com' => ['Pinterest', 'fa-pinterest'],
    'reddit.com'    => ['Reddit', 'fa-reddit']
];
?>

<!-- Font Awesome icons for social media logos.
     Requires an internet connection. -->
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
>

<div class="generated-portfolio simple-template">

    <!-- HERO / PROFILE HEADER -->
    <section class="simple-hero">
        <div class="simple-hero-content">

            <div class="simple-profile">
                <?php if (!empty($portfolio['profile_picture'])): ?>
                    <img
                        src="<?= simpleEscape($portfolio['profile_picture']) ?>"
                        alt="Profile picture of <?= simpleEscape($fullName) ?>"
                        class="generated-profile-picture"
                    >
                <?php else: ?>
                    <div class="generated-profile-placeholder">
                        <i class="fa-solid fa-user"></i>
                    </div>
                <?php endif; ?>
            </div>

            <div class="simple-intro">
                <span class="simple-label">PERSONAL PORTFOLIO</span>

                <h1><?= simpleEscape($fullName) ?></h1>

                <p class="simple-subtitle">Professional Portfolio</p>

                <div class="simple-contact">
                    <?php if (!empty($portfolio['email'])): ?>
                        <span>
                            <i class="fa-regular fa-envelope"></i>
                            <?= simpleEscape($portfolio['email']) ?>
                        </span>
                    <?php endif; ?>

                    <?php if (!empty($portfolio['contact_number'])): ?>
                        <span>
                            <i class="fa-solid fa-phone"></i>
                            <?= simpleEscape($portfolio['contact_number']) ?>
                        </span>
                    <?php endif; ?>

                    <?php if (!empty($portfolio['address'])): ?>
                        <span>
                            <i class="fa-solid fa-location-dot"></i>
                            <?= simpleEscape($portfolio['address']) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </section>

    <!-- ABOUT ME -->
    <?php if (!empty($portfolio['about_me'])): ?>
        <section class="simple-section">
            <div class="simple-section-heading">
                <span class="simple-number">01</span>
                <div>
                    <span class="simple-small-title">INTRODUCTION</span>
                    <h2>About Me</h2>
                </div>
            </div>

            <div class="simple-section-content">
                <p><?= nl2br(simpleEscape($portfolio['about_me'])) ?></p>
            </div>
        </section>
    <?php endif; ?>

    <!-- EDUCATION -->
    <?php if (!empty($portfolio['education'])): ?>
        <section class="simple-section">
            <div class="simple-section-heading">
                <span class="simple-number">02</span>
                <div>
                    <span class="simple-small-title">ACADEMIC BACKGROUND</span>
                    <h2>Education</h2>
                </div>
            </div>

            <div class="simple-info-card">
                <div class="simple-card-icon">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div class="simple-card-content">
                    <p><?= nl2br(simpleEscape($portfolio['education'])) ?></p>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- SKILLS -->
    <?php if (!empty($portfolio['skills'])): ?>
        <section class="simple-section">
            <div class="simple-section-heading">
                <span class="simple-number">03</span>
                <div>
                    <span class="simple-small-title">EXPERTISE</span>
                    <h2>Skills</h2>
                </div>
            </div>

            <div class="simple-skills">
                <?php
                $skills = preg_split(
                    '/[,;\n]+/',
                    (string) $portfolio['skills']
                );
                ?>

                <?php foreach ($skills as $skill): ?>
                    <?php $skill = trim($skill); ?>

                    <?php if ($skill !== ''): ?>
                        <span class="simple-skill">
                            <?= simpleEscape($skill) ?>
                        </span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- PROJECTS -->
    <?php if (!empty($portfolio['projects'])): ?>
        <section class="simple-section">
            <div class="simple-section-heading">
                <span class="simple-number">04</span>
                <div>
                    <span class="simple-small-title">SELECTED WORK</span>
                    <h2>Projects</h2>
                </div>
            </div>

            <div class="simple-project-card">
                <div class="simple-project-icon">
                    <i class="fa-solid fa-rocket"></i>
                </div>

                <div class="simple-project-content">
                    <h3>Featured Project</h3>
                    <p><?= nl2br(simpleEscape($portfolio['projects'])) ?></p>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- WORK EXPERIENCE -->
    <?php if (!empty($portfolio['work_experience'])): ?>
        <section class="simple-section">
            <div class="simple-section-heading">
                <span class="simple-number">05</span>
                <div>
                    <span class="simple-small-title">PROFESSIONAL JOURNEY</span>
                    <h2>Work Experience</h2>
                </div>
            </div>

            <div class="simple-experience">
                <div class="simple-timeline-line"></div>

                <div class="simple-experience-item">
                    <div class="simple-timeline-dot"></div>

                    <div class="simple-experience-card">
                        <span class="simple-experience-label">EXPERIENCE</span>
                        <p><?= nl2br(simpleEscape($portfolio['work_experience'])) ?></p>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- SOCIAL MEDIA LINKS WITH LOGOS -->
    <?php if ($socialText !== ''): ?>
        <section class="simple-section simple-social-section">
            <div class="simple-section-heading">
                <span class="simple-number">06</span>
                <div>
                    <span class="simple-small-title">LET'S CONNECT</span>
                    <h2>Social Media &amp; Links</h2>
                </div>
            </div>

            <div class="simple-social-links">
                <?php
                $displayedSocialLinks = 0;

                foreach ($socialUrls as $url):
                    // Remove common punctuation accidentally placed after URLs.
                    $url = rtrim($url, '.,;:!?)');

                    $validUrl = filter_var($url, FILTER_VALIDATE_URL);
                    $scheme = strtolower(
                        (string) parse_url($url, PHP_URL_SCHEME)
                    );

                    if (
                        !$validUrl ||
                        !in_array($scheme, ['http', 'https'], true)
                    ) {
                        continue;
                    }

                    $host = strtolower(
                        (string) parse_url($url, PHP_URL_HOST)
                    );
                    $host = preg_replace('/^www\./', '', $host);

                    $platform = null;

                    foreach ($socialPlatforms as $domain => $details) {
                        if (
                            $host === $domain ||
                            str_ends_with($host, '.' . $domain)
                        ) {
                            $platform = $details;
                            break;
                        }
                    }

                    $platformName = $platform[0] ?? 'Website';
                    $iconClass = $platform[1] ?? 'fa-globe';
                    $displayedSocialLinks++;
                ?>
                    <a
                        class="simple-social-link"
                        href="<?= simpleEscape($url) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="<?= simpleEscape($platformName) ?> (opens in a new tab)"
                    >
                        <i class="fa-brands <?= simpleEscape($iconClass) ?>"></i>
                        <span><?= simpleEscape($platformName) ?></span>
                        <i class="fa-solid fa-arrow-up-right-from-square simple-social-arrow"></i>
                    </a>
                <?php endforeach; ?>

                <?php if ($displayedSocialLinks === 0): ?>
                    <p class="simple-social-empty">
                        Add full social media URLs to display your links here.
                    </p>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- FOOTER -->
    <section class="simple-ending">
        <div class="simple-ending-line"></div>
        <p>Thank you for visiting my portfolio.</p>
        <span><?= simpleEscape($fullName) ?></span>
    </section>

</div>

<style>
.simple-template {
    box-sizing: border-box;
    max-width: 1050px;
    margin: 0 auto;
    padding: 20px;
    color: #f8fafc;
    font-family: Inter, Arial, sans-serif;
}

.simple-template *,
.simple-template *::before,
.simple-template *::after {
    box-sizing: border-box;
}

.simple-hero {
    position: relative;
    overflow: hidden;
    padding: 65px 50px;
    margin-bottom: 35px;
    border-radius: 28px;
    background: linear-gradient(
        135deg,
        rgba(225, 29, 72, .16),
        rgba(124, 58, 237, .14),
        #0b0910
    );
    border: 1px solid rgba(225, 29, 72, .25);
    box-shadow: 0 25px 70px rgba(0, 0, 0, .3);
}

.simple-hero::before {
    content: "";
    position: absolute;
    width: 280px;
    height: 280px;
    right: -120px;
    top: -120px;
    border-radius: 50%;
    background: rgba(225, 29, 72, .15);
    filter: blur(35px);
    pointer-events: none;
}

.simple-hero-content {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 38px;
}

.simple-profile {
    width: 165px;
    height: 165px;
    flex-shrink: 0;
    padding: 5px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e11d48, #7c3aed);
    box-shadow: 0 0 40px rgba(225, 29, 72, .2);
}

.simple-profile .generated-profile-picture,
.simple-profile .generated-profile-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    background: #09090b;
}

.simple-profile .generated-profile-placeholder {
    color: #fda4af;
    font-size: 45px;
}

.simple-intro {
    flex: 1;
    min-width: 0;
}

.simple-label {
    display: inline-block;
    margin-bottom: 12px;
    color: #fb7185;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 3px;
}

.simple-intro h1 {
    overflow-wrap: anywhere;
    margin: 0;
    font-size: clamp(34px, 6vw, 62px);
    line-height: 1.12;
    letter-spacing: -1.5px;
    background: linear-gradient(90deg, #fff, #fda4af, #c4b5fd);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.simple-subtitle {
    margin: 14px 0 22px;
    color: #a1a1aa;
    font-size: 17px;
}

.simple-contact {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.simple-contact span {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    overflow-wrap: anywhere;
    padding: 9px 12px;
    border-radius: 10px;
    background: rgba(255, 255, 255, .045);
    border: 1px solid rgba(255, 255, 255, .08);
    color: #d4d4d8;
    font-size: 13px;
}

.simple-contact i {
    color: #fb7185;
}

.simple-section {
    margin-bottom: 28px;
    padding: 35px;
    border-radius: 22px;
    background: rgba(9, 9, 12, .85);
    border: 1px solid rgba(255, 255, 255, .08);
    box-shadow: 0 15px 45px rgba(0, 0, 0, .2);
    transition: transform .25s ease, border-color .25s ease;
}

.simple-section:hover {
    transform: translateY(-2px);
    border-color: rgba(225, 29, 72, .28);
}

.simple-section-heading {
    display: flex;
    align-items: center;
    gap: 17px;
    margin-bottom: 26px;
}

.simple-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    flex-shrink: 0;
    border-radius: 12px;
    background: linear-gradient(
        135deg,
        rgba(225, 29, 72, .18),
        rgba(124, 58, 237, .16)
    );
    border: 1px solid rgba(225, 29, 72, .25);
    color: #fb7185;
    font-size: 13px;
    font-weight: 800;
}

.simple-small-title {
    display: block;
    margin-bottom: 4px;
    color: #858592;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
}

.simple-section h2 {
    margin: 0;
    color: #fff;
    font-size: 28px;
}

.simple-section p {
    margin: 0;
    color: #c4c4ce;
    font-size: 15px;
    line-height: 1.85;
    overflow-wrap: anywhere;
}

.simple-section-content {
    padding-left: 62px;
}

.simple-info-card,
.simple-project-card,
.simple-experience-card {
    padding: 24px;
    border-radius: 16px;
    background: linear-gradient(
        135deg,
        rgba(225, 29, 72, .06),
        rgba(124, 58, 237, .06)
    );
    border: 1px solid rgba(255, 255, 255, .08);
}

.simple-info-card,
.simple-project-card {
    display: flex;
    align-items: flex-start;
    gap: 18px;
}

.simple-card-icon,
.simple-project-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    flex-shrink: 0;
    border-radius: 14px;
    background: rgba(225, 29, 72, .12);
    color: #fb7185;
    font-size: 22px;
}

.simple-card-content,
.simple-project-content {
    flex: 1;
    min-width: 0;
}

.simple-project-card h3 {
    margin: 2px 0 10px;
    color: #fff;
    font-size: 18px;
}

.simple-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.simple-skill {
    padding: 11px 17px;
    border-radius: 30px;
    background: linear-gradient(
        135deg,
        rgba(225, 29, 72, .10),
        rgba(124, 58, 237, .08)
    );
    border: 1px solid rgba(225, 29, 72, .22);
    color: #e4e4e7;
    font-size: 14px;
    font-weight: 600;
    overflow-wrap: anywhere;
}

.simple-experience {
    position: relative;
    padding-left: 30px;
}

.simple-timeline-line {
    position: absolute;
    left: 8px;
    top: 8px;
    bottom: 8px;
    width: 2px;
    background: linear-gradient(#e11d48, #7c3aed);
}

.simple-experience-item {
    position: relative;
}

.simple-timeline-dot {
    position: absolute;
    left: -28px;
    top: 5px;
    width: 13px;
    height: 13px;
    border-radius: 50%;
    background: #e11d48;
    box-shadow: 0 0 15px rgba(225, 29, 72, .5);
}

.simple-experience-label {
    display: block;
    margin-bottom: 10px;
    color: #fb7185;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.5px;
}

/* SOCIAL MEDIA LOGO BUTTONS */
.simple-social-links {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
}

.simple-social-link {
    display: inline-flex;
    align-items: center;
    gap: 11px;
    max-width: 100%;
    padding: 15px 17px;
    border-radius: 14px;
    color: #f4f4f5;
    text-decoration: none;
    background: linear-gradient(
        135deg,
        rgba(225, 29, 72, .12),
        rgba(124, 58, 237, .12)
    );
    border: 1px solid rgba(225, 29, 72, .25);
    transition: transform .25s ease, border-color .25s ease,
                box-shadow .25s ease;
}

.simple-social-link:hover {
    transform: translateY(-4px);
    border-color: #e11d48;
    box-shadow: 0 8px 25px rgba(225, 29, 72, .18);
}

.simple-social-link > .fa-brands {
    font-size: 25px;
    color: #fb7185;
}

.simple-social-link span {
    font-size: 14px;
    font-weight: 600;
}

.simple-social-arrow {
    color: #a78bfa;
    font-size: 11px;
}

.simple-social-empty {
    width: 100%;
}

.simple-ending {
    padding: 30px 20px 45px;
    text-align: center;
}

.simple-ending-line {
    width: 70px;
    height: 2px;
    margin: 0 auto 18px;
    background: linear-gradient(90deg, #e11d48, #7c3aed);
}

.simple-ending p {
    margin: 0 0 7px;
    color: #858592;
    font-size: 13px;
}

.simple-ending span {
    color: #d4d4d8;
    font-size: 14px;
    font-weight: 700;
}

@media (max-width: 700px) {
    .simple-template {
        padding: 10px;
    }

    .simple-hero {
        padding: 38px 22px;
    }

    .simple-hero-content {
        flex-direction: column;
        text-align: center;
        gap: 24px;
    }

    .simple-profile {
        width: 135px;
        height: 135px;
    }

    .simple-contact {
        justify-content: center;
    }

    .simple-section {
        padding: 25px 20px;
    }

    .simple-section-content {
        padding-left: 0;
    }

    .simple-section h2 {
        font-size: 24px;
    }

    .simple-social-link {
        flex: 1 1 140px;
    }
}

@media (max-width: 450px) {
    .simple-intro h1 {
        font-size: 32px;
    }

    .simple-contact {
        flex-direction: column;
    }

    .simple-contact span {
        justify-content: center;
        max-width: 100%;
    }

    .simple-section-heading {
        gap: 12px;
    }

    .simple-number {
        width: 39px;
        height: 39px;
    }

    .simple-info-card,
    .simple-project-card {
        padding: 18px;
    }

    .simple-social-link {
        flex: 1 1 100%;
    }
}

@media print {
    .simple-template {
        max-width: none;
        padding: 0;
        color: #222;
    }

    .simple-section,
    .simple-hero {
        break-inside: avoid;
        box-shadow: none;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .simple-section:hover {
        transform: none;
    }

    .simple-social-link {
        break-inside: avoid;
    }
}
</style>
