
<?php
// =====================================================
// PORTFOLIOGEN - CREATIVE PORTFOLIO TEMPLATE
// Compact Layout + Live Animated Background
// =====================================================

$fullName = $portfolio['full_name'] ?? 'Your Name';
$email = $portfolio['email'] ?? '';
$contactNumber = $portfolio['contact_number'] ?? '';
$address = $portfolio['address'] ?? '';
$aboutMe = $portfolio['about_me'] ?? 'I am a creative individual who loves turning ideas into reality.';
$education = $portfolio['education'] ?? '';
$skills = $portfolio['skills'] ?? '';
$projects = $portfolio['projects'] ?? '';
$experience = $portfolio['work_experience'] ?? '';
$socialLinks = $portfolio['social_links'] ?? '';
$profilePicture = $portfolio['profile_picture'] ?? '';

if (!function_exists('creativeText')) {
    function creativeText($value) {
        if (is_array($value)) {
            return implode(', ', array_map('strval', $value));
        }
        return trim((string)$value);
    }
}

if (!function_exists('creativeItems')) {
    function creativeItems($value) {
        $value = creativeText($value);

        if ($value === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        if (is_array($decoded)) {
            $items = [];

            foreach ($decoded as $item) {
                if (is_array($item)) {
                    $items[] = $item;
                } elseif (is_string($item) || is_numeric($item)) {
                    $items[] = ['title' => (string)$item];
                }
            }

            return $items;
        }

        return array_values(array_filter(
            preg_split('/[\r\n]+/', $value),
            fn($item) => trim($item) !== ''
        ));
    }
}

$skillItems = creativeItems($skills);
$projectItems = creativeItems($projects);
$educationItems = creativeItems($education);
$experienceItems = creativeItems($experience);
$socialItems = creativeItems($socialLinks);

$initials = '';
foreach (preg_split('/\s+/', trim($fullName)) as $word) {
    if ($word !== '') {
        $initials .= strtoupper(substr($word, 0, 1));
    }
}
$initials = substr($initials ?: 'YN', 0, 2);

$esc = function ($value) {
    return htmlspecialchars(creativeText($value), ENT_QUOTES, 'UTF-8');
};

$getField = function ($item, $keys, $fallback = '') {
    if (!is_array($item)) {
        return creativeText($item);
    }

    foreach ($keys as $key) {
        if (isset($item[$key]) && creativeText($item[$key]) !== '') {
            return creativeText($item[$key]);
        }
    }

    return $fallback;
};

$projectColors = ['pink', 'purple', 'orange', 'blue'];
$projectSymbols = ['✳', '◈', '✦', '⬡'];
?>

<div class="creative-portfolio" id="creativePortfolio">

    <!-- ANIMATED BACKGROUND -->
    <div class="creative-background" aria-hidden="true">
        <div class="creative-grid"></div>
        <div class="creative-glow creative-glow-one"></div>
        <div class="creative-glow creative-glow-two"></div>
        <div class="creative-glow creative-glow-three"></div>
        <div class="creative-orbit creative-orbit-one"></div>
        <div class="creative-orbit creative-orbit-two"></div>
        <div class="creative-particles" id="creativeParticles"></div>
    </div>

    <!-- NAVIGATION -->
    <nav class="creative-nav">
        <a href="#creativeHome" class="creative-brand">
            <span class="creative-brand-icon">✳</span>
            <span>CREATIVE<span class="creative-brand-dot">.</span></span>
        </a>

        <div class="creative-nav-links">
            <a href="#creativeAbout">About</a>
            <a href="#creativeSkills">Skills</a>
            <a href="#creativeProjects">Projects</a>
            <a href="#creativeContact" class="creative-nav-contact">Let's Talk ↗</a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="creative-hero" id="creativeHome">
        <div class="creative-hero-content">
            <div class="creative-availability">
                <span class="creative-status-dot"></span>
                PORTFOLIO / PERSONAL SPACE
            </div>

            <p class="creative-hello">HELLO, WORLD! <span>✌</span></p>

            <h1>
                Ideas into<br>
                <span class="creative-gradient-text">something</span><br>
                <span class="creative-outline-text">extraordinary.</span>
            </h1>

            <p class="creative-hero-description">
                I'm <strong><?= $esc($fullName) ?></strong>.
                <?= $esc($aboutMe) ?>
            </p>

            <div class="creative-hero-actions">
                <a href="#creativeProjects" class="creative-button creative-button-primary">
                    Explore My Work <span>↗</span>
                </a>
                <a href="#creativeAbout" class="creative-button creative-button-outline">
                    Discover More
                </a>
            </div>

            <div class="creative-hero-footer">
                <span>DESIGNING MY OWN PATH</span>
                <span>SCROLL TO EXPLORE ↓</span>
            </div>
        </div>

        <div class="creative-hero-art">
            <div class="creative-art-top-label">A LITTLE BIT OF ART</div>

            <div class="creative-art-stage">
                <div class="creative-art-ring creative-art-ring-one"></div>
                <div class="creative-art-ring creative-art-ring-two"></div>
                <div class="creative-art-star">✳</div>
                <div class="creative-art-cross">✕</div>
                <div class="creative-art-small-orb"></div>

                <div class="creative-profile-card">
                    <div class="creative-profile-card-top">
                        <span>PERSONAL IDENTITY</span>
                        <span>✦ 001</span>
                    </div>

                    <?php if ($profilePicture !== ''): ?>
                        <img
                            src="<?= $esc($profilePicture) ?>"
                            alt="<?= $esc($fullName) ?>"
                            class="creative-profile-image"
                        >
                    <?php else: ?>
                        <div class="creative-profile-placeholder">
                            <?= $esc($initials) ?>
                        </div>
                    <?php endif; ?>

                    <div class="creative-profile-card-bottom">
                        <h3><?= $esc($fullName) ?></h3>
                        <p>CREATIVE MIND · BIG DREAMS</p>
                    </div>
                </div>

                <div class="creative-floating-tag creative-tag-top">
                    <span>✦</span> THINK DIFFERENT
                </div>

                <div class="creative-floating-tag creative-tag-bottom">
                    <span>✳</span> CREATE FEARLESSLY
                </div>

                <div class="creative-art-caption">NO. 001 — THE ORIGINAL</div>
            </div>
        </div>
    </section>

    <!-- MARQUEE -->
    <div class="creative-marquee">
        <div class="creative-marquee-track">
            <?php for ($i = 0; $i < 4; $i++): ?>
                <span>CREATIVITY</span><span class="creative-marquee-symbol">✳</span>
                <span>IMAGINATION</span><span class="creative-marquee-symbol">✦</span>
                <span>INNOVATION</span><span class="creative-marquee-symbol">◈</span>
                <span>MY OWN UNIVERSE</span><span class="creative-marquee-symbol">✳</span>
            <?php endfor; ?>
        </div>
    </div>

    <!-- ABOUT -->
    <section class="creative-section creative-about" id="creativeAbout">
        <div class="creative-section-heading">
            <span class="creative-eyebrow">01 / THE PERSON BEHIND THE WORK</span>
            <h2>Not just a name.<br><span>A whole story.</span></h2>
        </div>

        <div class="creative-about-grid">
            <div class="creative-about-art">
                <div class="creative-about-art-inner">
                    <span class="creative-about-big-symbol">✳</span>
                    <span class="creative-about-side-text">STAY CURIOUS. STAY CREATIVE.</span>
                    <span class="creative-about-bottom-text">MADE WITH PURPOSE ©</span>
                </div>
            </div>

            <div class="creative-about-content">
                <span class="creative-small-label">A LITTLE INTRODUCTION</span>
                <h3>Curious by nature.<br>Creative by choice.</h3>
                <p><?= nl2br($esc($aboutMe)) ?></p>

                <?php if ($address !== ''): ?>
                    <div class="creative-detail-row">
                        <span>⌖</span>
                        <div><small>BASED IN</small><p><?= $esc($address) ?></p></div>
                    </div>
                <?php endif; ?>

                <?php if ($email !== ''): ?>
                    <div class="creative-detail-row">
                        <span>✉</span>
                        <div><small>EMAIL ADDRESS</small><p><?= $esc($email) ?></p></div>
                    </div>
                <?php endif; ?>

                <?php if ($contactNumber !== ''): ?>
                    <div class="creative-detail-row">
                        <span>↗</span>
                        <div><small>CONTACT NUMBER</small><p><?= $esc($contactNumber) ?></p></div>
                    </div>
                <?php endif; ?>

                <div class="creative-about-signature">CREATIVE MIND ✳</div>
            </div>
        </div>
    </section>

    <!-- SKILLS -->
    <section class="creative-section creative-skills" id="creativeSkills">
        <div class="creative-section-heading">
            <span class="creative-eyebrow">02 / MY CREATIVE TOOLKIT</span>
            <h2>Things I do.<br><span>Things I love.</span></h2>
        </div>

        <div class="creative-skills-grid">
            <?php if (!empty($skillItems)): ?>
                <?php foreach ($skillItems as $index => $skill): ?>
                    <?php
                    $skillName = $getField($skill, ['name', 'skill', 'title'], 'Skill');
                    $skillDescription = is_array($skill)
                        ? $getField($skill, ['description', 'details'], '')
                        : '';
                    ?>
                    <article class="creative-skill-card">
                        <span class="creative-skill-number">
                            <?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) ?>
                        </span>
                        <div class="creative-skill-icon">
                            <?= ['✳', '◈', '✦', '⬡'][$index % 4] ?>
                        </div>
                        <h3><?= $esc($skillName) ?></h3>
                        <p><?= $esc($skillDescription !== ''
                            ? $skillDescription
                            : 'One of the tools in my growing creative toolkit.') ?></p>
                        <span class="creative-skill-arrow">↗</span>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="creative-empty-state">
                    <span>✳</span>
                    <p>Your skills will appear here once added to your portfolio.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- PROJECTS -->
    <section class="creative-section creative-projects" id="creativeProjects">
        <div class="creative-projects-heading">
            <div class="creative-section-heading">
                <span class="creative-eyebrow">03 / SELECTED CREATIONS</span>
                <h2>Proof of<br><span>imagination.</span></h2>
            </div>
            <p>A collection of projects, ideas, and experiences that reflect my journey.</p>
        </div>

        <div class="creative-project-grid">
            <?php if (!empty($projectItems)): ?>
                <?php foreach ($projectItems as $index => $project): ?>
                    <?php
                    $projectTitle = $getField($project, ['title', 'name', 'project_name'], 'Untitled Project');
                    $projectDescription = $getField(
                        $project,
                        ['description', 'details', 'about'],
                        'A project built through creativity, learning, and experimentation.'
                    );
                    $projectTech = $getField($project, ['technologies', 'tech_stack', 'tools'], '');
                    $projectLink = $getField($project, ['url', 'link', 'project_url'], '');
                    $projectColor = $projectColors[$index % count($projectColors)];
                    $projectSymbol = $projectSymbols[$index % count($projectSymbols)];

                    $validProjectLink = filter_var($projectLink, FILTER_VALIDATE_URL)
                        && in_array(
                            strtolower((string)parse_url($projectLink, PHP_URL_SCHEME)),
                            ['http', 'https'],
                            true
                        );
                    ?>

                    <article class="creative-project-card creative-project-<?= $projectColor ?>">
                        <div class="creative-project-art">
                            <span class="creative-project-index">
                                PROJECT / <?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) ?>
                            </span>
                            <span class="creative-project-symbol"><?= $projectSymbol ?></span>
                            <span class="creative-project-art-bottom">AN ORIGINAL CREATION</span>
                        </div>

                        <div class="creative-project-info">
                            <div class="creative-project-title-row">
                                <h3><?= $esc($projectTitle) ?></h3>
                                <span>↗</span>
                            </div>

                            <p><?= $esc($projectDescription) ?></p>

                            <?php if ($projectTech !== ''): ?>
                                <div class="creative-project-tags">
                                    <span><?= $esc($projectTech) ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($validProjectLink): ?>
                                <a class="creative-project-link"
                                   href="<?= $esc($projectLink) ?>"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    VIEW PROJECT ↗
                                </a>
                            <?php endif; ?>

                            <div class="creative-project-bottom-label">SELECTED WORK ✦</div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="creative-empty-state">
                    <span>◈</span>
                    <p>Your projects will appear here once added to your portfolio.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- EDUCATION AND EXPERIENCE -->
    <section class="creative-section creative-journey" id="creativeJourney">
        <div class="creative-section-heading">
            <span class="creative-eyebrow">04 / THE JOURNEY SO FAR</span>
            <h2>Always learning.<br><span>Always becoming.</span></h2>
        </div>

        <div class="creative-journey-grid">
            <div class="creative-journey-column">
                <div class="creative-journey-heading">
                    <span>✦</span><h3>Education</h3>
                </div>

                <?php if (!empty($educationItems)): ?>
                    <?php foreach ($educationItems as $item): ?>
                        <?php
                        $title = $getField(
                            $item,
                            ['degree', 'title', 'school', 'institution', 'name'],
                            'Education'
                        );
                        $details = is_array($item)
                            ? $getField($item, ['description', 'details', 'course'], '')
                            : '';
                        $year = is_array($item)
                            ? $getField($item, ['year', 'date', 'duration'], '')
                            : '';
                        ?>
                        <div class="creative-timeline-item">
                            <span class="creative-timeline-dot"></span>
                            <h4><?= $esc($title) ?></h4>
                            <?php if ($details !== ''): ?><p><?= $esc($details) ?></p><?php endif; ?>
                            <?php if ($year !== ''): ?><small><?= $esc($year) ?></small><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="creative-muted">Education details will appear here.</p>
                <?php endif; ?>

                <div class="creative-column-footer">LEARN • GROW • EVOLVE</div>
            </div>

            <div class="creative-journey-column">
                <div class="creative-journey-heading">
                    <span>◈</span><h3>Experience</h3>
                </div>

                <?php if (!empty($experienceItems)): ?>
                    <?php foreach ($experienceItems as $item): ?>
                        <?php
                        $title = $getField(
                            $item,
                            ['position', 'title', 'role', 'company', 'name'],
                            'Experience'
                        );
                        $details = is_array($item)
                            ? $getField($item, ['description', 'details', 'company'], '')
                            : '';
                        $year = is_array($item)
                            ? $getField($item, ['year', 'date', 'duration'], '')
                            : '';
                        ?>
                        <div class="creative-timeline-item">
                            <span class="creative-timeline-dot"></span>
                            <h4><?= $esc($title) ?></h4>
                            <?php if ($details !== ''): ?><p><?= $esc($details) ?></p><?php endif; ?>
                            <?php if ($year !== ''): ?><small><?= $esc($year) ?></small><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="creative-muted">Experience details will appear here.</p>
                <?php endif; ?>

                <div class="creative-column-footer">BUILD • CREATE • IMPROVE</div>
            </div>
        </div>
    </section>

    <!-- CONTACT -->
    <section class="creative-contact" id="creativeContact">
        <div class="creative-contact-decoration" aria-hidden="true">✳</div>

        <span class="creative-eyebrow">05 / HAVE AN IDEA?</span>
        <h2>Let's make<br><span>it happen.</span></h2>
        <p>Every great collaboration starts with a simple conversation.</p>

        <?php if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)): ?>
            <a class="creative-button creative-button-primary"
               href="mailto:<?= $esc($email) ?>">
                Get In Touch <span>↗</span>
            </a>
        <?php endif; ?>

        <div class="creative-social-links">
            <?php foreach ($socialItems as $social): ?>
                <?php
                $socialUrl = $getField($social, ['url', 'link', 'href'], '');
                $socialName = $getField($social, ['platform', 'name', 'title'], 'Social Link');

                if (!is_array($social) && is_string($social)) {
                    $socialUrl = trim($social);
                    $socialName = 'Visit Link';
                }

                $validSocialLink = filter_var($socialUrl, FILTER_VALIDATE_URL)
                    && in_array(
                        strtolower((string)parse_url($socialUrl, PHP_URL_SCHEME)),
                        ['http', 'https'],
                        true
                    );

                if (!$validSocialLink) {
                    continue;
                }
                ?>
                <a href="<?= $esc($socialUrl) ?>"
                   target="_blank"
                   rel="noopener noreferrer">
                    <?= $esc($socialName) ?> ↗
                </a>
            <?php endforeach; ?>
        </div>

        <div class="creative-contact-footer">
            <span>CRAFTED WITH CURIOSITY ✳</span>
            <a href="#creativeHome">BACK TO TOP ↑</a>
        </div>
    </section>
</div>

<style>
/* =====================================================
   CREATIVE TEMPLATE — COMPLETE RESPONSIVE STYLES
===================================================== */

.creative-portfolio {
    --cp-bg: #0b0913;
    --cp-panel: #15111f;
    --cp-text: #f9f5ff;
    --cp-muted: #aaa2ba;
    --cp-pink: #ff4fa3;
    --cp-purple: #a78bfa;
    --cp-orange: #ffac70;
    --cp-blue: #73d9ff;

    position: relative;
    isolation: isolate;
    overflow: hidden;
    background: var(--cp-bg);
    color: var(--cp-text);
    font-family: Inter, "Segoe UI", Arial, sans-serif;
    line-height: 1.65;
}

.creative-portfolio *,
.creative-portfolio *::before,
.creative-portfolio *::after {
    box-sizing: border-box;
}

.creative-portfolio a {
    color: inherit;
    text-decoration: none;
}

.creative-portfolio p,
.creative-portfolio h1,
.creative-portfolio h2,
.creative-portfolio h3,
.creative-portfolio h4 {
    overflow-wrap: anywhere;
}

.creative-portfolio .creative-background {
    position: absolute;
    inset: 0;
    z-index: -1;
    overflow: hidden;
    pointer-events: none;
}

.creative-portfolio .creative-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,.035) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.035) 1px, transparent 1px);
    background-size: 52px 52px;
    mask-image: linear-gradient(to bottom, black, transparent 88%);
    animation: creativeGridMove 24s linear infinite;
}

.creative-portfolio .creative-glow {
    position: absolute;
    width: 380px;
    height: 380px;
    border-radius: 50%;
    filter: blur(100px);
    opacity: .18;
    animation: creativeGlowMove 12s ease-in-out infinite alternate;
}

.creative-portfolio .creative-glow-one {
    top: 100px;
    left: -180px;
    background: #fa369c;
}

.creative-portfolio .creative-glow-two {
    top: 600px;
    right: -200px;
    background: #7546ff;
    animation-delay: -4s;
}

.creative-portfolio .creative-glow-three {
    top: 1500px;
    left: 25%;
    background: #ff9e67;
    opacity: .08;
    animation-delay: -7s;
}

.creative-portfolio .creative-orbit {
    position: absolute;
    width: 340px;
    height: 340px;
    border: 1px solid rgba(196,153,255,.12);
    border-radius: 50%;
}

.creative-portfolio .creative-orbit-one {
    top: 500px;
    left: -240px;
    animation: creativeOrbit 36s linear infinite;
}

.creative-portfolio .creative-orbit-two {
    top: 1400px;
    right: -230px;
    width: 440px;
    height: 440px;
    animation: creativeOrbit 46s linear infinite reverse;
}

.creative-portfolio .creative-particles {
    position: absolute;
    inset: 0;
    overflow: hidden;
}

.creative-portfolio .creative-particle {
    position: absolute;
    width: 3px;
    height: 3px;
    border-radius: 50%;
    background: #f6b4ff;
    box-shadow: 0 0 12px rgba(255,120,230,.8);
    animation: creativeParticleFloat var(--particle-duration, 12s) linear infinite;
    animation-delay: var(--particle-delay, 0s);
}

.creative-portfolio .creative-nav,
.creative-portfolio .creative-hero,
.creative-portfolio .creative-section {
    width: min(1160px, calc(100% - 48px));
    margin-inline: auto;
}

/* NAVIGATION */
.creative-portfolio .creative-nav {
    min-height: 72px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    border-bottom: 1px solid rgba(255,255,255,.09);
}

.creative-portfolio .creative-brand {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 900;
    letter-spacing: 2px;
}

.creative-portfolio .creative-brand-icon {
    display: grid;
    place-items: center;
    width: 34px;
    height: 34px;
    border-radius: 11px;
    background: linear-gradient(135deg, var(--cp-pink), var(--cp-purple));
    font-size: 20px;
}

.creative-portfolio .creative-brand-dot {
    color: var(--cp-pink);
}

.creative-portfolio .creative-nav-links {
    display: flex;
    align-items: center;
    gap: 25px;
    color: var(--cp-muted);
    font-size: 12px;
}

.creative-portfolio .creative-nav-links a {
    transition: color .25s ease;
}

.creative-portfolio .creative-nav-links a:hover {
    color: white;
}

.creative-portfolio .creative-nav-contact {
    padding: 9px 15px;
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 30px;
    color: white !important;
}

/* HERO */
.creative-portfolio .creative-hero {
    position: relative;
    display: grid;
    grid-template-columns: 1.08fr .92fr;
    align-items: center;
    gap: 35px;
    padding-block: 50px 55px;
}

.creative-portfolio .creative-availability {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 8px 12px;
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 30px;
    background: rgba(255,255,255,.035);
    color: var(--cp-muted);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.creative-portfolio .creative-status-dot {
    width: 7px;
    height: 7px;
    flex-shrink: 0;
    border-radius: 50%;
    background: #78f5b2;
    box-shadow: 0 0 10px #78f5b2;
}

.creative-portfolio .creative-hello {
    margin: 24px 0 10px;
    color: var(--cp-pink);
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 3px;
}

.creative-portfolio .creative-hello span {
    display: inline-block;
    animation: creativeWave 2.5s ease-in-out infinite;
}

.creative-portfolio .creative-hero h1 {
    margin: 0;
    font-size: clamp(42px, 5.4vw, 72px);
    font-weight: 900;
    line-height: 1.04;
    letter-spacing: -2.8px;
}

.creative-portfolio .creative-gradient-text {
    background: linear-gradient(100deg, #ff72b6, #bb9bff 60%, #78dcff);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.creative-portfolio .creative-outline-text {
    color: transparent;
    -webkit-text-stroke: 1.2px rgba(255,255,255,.75);
}

.creative-portfolio .creative-hero-description {
    max-width: 490px;
    margin: 18px 0;
    color: var(--cp-muted);
    font-size: 13px;
    line-height: 1.8;
}

.creative-portfolio .creative-hero-description strong {
    color: white;
}

.creative-portfolio .creative-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.creative-portfolio .creative-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    min-height: 44px;
    padding: 11px 17px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 800;
    transition: transform .25s ease, box-shadow .25s ease;
}

.creative-portfolio .creative-button:hover {
    transform: translateY(-3px);
}

.creative-portfolio .creative-button-primary {
    color: white;
    background: linear-gradient(110deg, #f34e9f, #8d63f6);
    box-shadow: 0 8px 25px rgba(200,68,191,.2);
}

.creative-portfolio .creative-button-primary:hover {
    box-shadow: 0 12px 32px rgba(200,68,191,.35);
}

.creative-portfolio .creative-button-outline {
    border: 1px solid rgba(255,255,255,.2);
    background: rgba(255,255,255,.025);
}

.creative-portfolio .creative-hero-footer {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 12px;
    margin-top: 30px;
    padding-top: 15px;
    border-top: 1px solid rgba(255,255,255,.1);
    color: #9389a2;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

/* PROFILE ART */
.creative-portfolio .creative-hero-art {
    position: relative;
    min-width: 0;
}

.creative-portfolio .creative-art-top-label {
    margin-bottom: 10px;
    text-align: right;
    color: var(--cp-muted);
    font-size: 8px;
    letter-spacing: 3px;
}

.creative-portfolio .creative-art-stage {
    position: relative;
    display: grid;
    place-items: center;
    min-height: 390px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,.11);
    border-radius: 150px 150px 16px 16px;
    background:
        radial-gradient(ellipse at 50% 45%, rgba(183,94,255,.15), transparent 52%),
        linear-gradient(150deg, rgba(255,255,255,.045), rgba(255,255,255,.008));
}

.creative-portfolio .creative-art-stage::before {
    content: "";
    position: absolute;
    inset: 13px;
    border: 1px dashed rgba(255,255,255,.13);
    border-radius: inherit;
}

.creative-portfolio .creative-art-ring {
    position: absolute;
    border: 1px solid rgba(255,112,192,.35);
    border-radius: 50%;
    animation: creativeOrbit 18s linear infinite;
}

.creative-portfolio .creative-art-ring-one {
    width: 270px;
    height: 270px;
}

.creative-portfolio .creative-art-ring-two {
    width: 330px;
    height: 330px;
    border-color: rgba(173,136,255,.2);
    animation-duration: 26s;
    animation-direction: reverse;
}

.creative-portfolio .creative-art-star {
    position: absolute;
    top: 25px;
    right: 30px;
    color: #ff72b6;
    font-size: 40px;
    animation: creativeSpin 15s linear infinite;
}

.creative-portfolio .creative-art-cross {
    position: absolute;
    bottom: 60px;
    left: 25px;
    color: #a78bfa;
    font-size: 30px;
    animation: creativeSpin 12s linear infinite reverse;
}

.creative-portfolio .creative-art-small-orb {
    position: absolute;
    top: 85px;
    left: 35px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff78ba, #9b8aff);
    box-shadow: 0 0 25px #dc65ed;
}

.creative-portfolio .creative-profile-card {
    position: relative;
    z-index: 2;
    width: min(260px, 75%);
    padding: 12px;
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 12px;
    background: linear-gradient(145deg, rgba(35,25,49,.97), rgba(14,12,24,.96));
    box-shadow: 0 25px 60px rgba(0,0,0,.4);
    transform: rotate(-3deg);
    transition: transform .4s ease;
}

.creative-portfolio .creative-profile-card:hover {
    transform: rotate(0deg) translateY(-4px);
}

.creative-portfolio .creative-profile-card-top {
    display: flex;
    justify-content: space-between;
    gap: 8px;
    padding: 8px 3px;
    color: #b8aecb;
    font-size: 7px;
    letter-spacing: 1px;
}

.creative-portfolio .creative-profile-image,
.creative-portfolio .creative-profile-placeholder {
    display: block;
    width: 100%;
    height: 205px;
    object-fit: cover;
    border-radius: 7px;
    background: linear-gradient(145deg, #513365, #171323);
}

.creative-portfolio .creative-profile-placeholder {
    display: grid;
    place-items: center;
    color: white;
    font-size: 60px;
    font-weight: 900;
    background: linear-gradient(145deg, #f34e9f, #795df3);
}

.creative-portfolio .creative-profile-card-bottom {
    padding: 8px 3px 5px;
}

.creative-portfolio .creative-profile-card-bottom h3 {
    margin: 4px 0 0;
    font-size: 19px;
    line-height: 1.35;
}

.creative-portfolio .creative-profile-card-bottom p {
    margin: 4px 0 0;
    color: var(--cp-muted);
    font-size: 7px;
    letter-spacing: 1px;
}

.creative-portfolio .creative-floating-tag {
    position: absolute;
    z-index: 3;
    padding: 8px 10px;
    border: 1px solid rgba(255,255,255,.17);
    border-radius: 5px;
    background: rgba(19,14,29,.95);
    box-shadow: 0 10px 30px rgba(0,0,0,.2);
    font-size: 8px;
    font-weight: 900;
    letter-spacing: 1px;
    animation: creativeFloat 4s ease-in-out infinite;
}

.creative-portfolio .creative-floating-tag span {
    margin-right: 4px;
    color: var(--cp-pink);
}

.creative-portfolio .creative-tag-top {
    top: 80px;
    left: 0;
}

.creative-portfolio .creative-tag-bottom {
    right: 0;
    bottom: 65px;
    animation-delay: -2s;
}

.creative-portfolio .creative-art-caption {
    position: absolute;
    bottom: 13px;
    color: #a196b0;
    font-size: 7px;
    letter-spacing: 2px;
}

/* MARQUEE */
.creative-portfolio .creative-marquee {
    overflow: hidden;
    margin-block: 8px;
    padding: 13px 0;
    border-block: 1px solid rgba(255,79,163,.25);
    background: linear-gradient(90deg, rgba(255,79,163,.08), rgba(167,139,250,.08));
    transform: rotate(-1deg) scale(1.02);
}

.creative-portfolio .creative-marquee-track {
    display: flex;
    align-items: center;
    width: max-content;
    animation: creativeMarquee 32s linear infinite;
}

.creative-portfolio .creative-marquee-track span {
    margin-inline: 20px;
    color: #e4dceb;
    font-size: 12px;
    font-weight: 900;
    letter-spacing: 3px;
    white-space: nowrap;
}

.creative-portfolio .creative-marquee-track .creative-marquee-symbol {
    color: var(--cp-pink);
    font-size: 19px;
}

/* GENERAL SECTIONS */
.creative-portfolio .creative-section {
    position: relative;
    isolation: isolate;
    overflow: hidden;
    padding-block: 65px;
    scroll-margin-top: 25px;
}

.creative-portfolio .creative-section > * {
    position: relative;
    z-index: 1;
}

.creative-portfolio .creative-section::after {
    content: "";
    position: absolute;
    z-index: 0;
    top: 80px;
    right: -100px;
    width: 220px;
    height: 220px;
    border: 1px solid rgba(167,139,250,.15);
    border-radius: 50%;
    box-shadow:
        0 0 0 20px rgba(167,139,250,.025),
        0 0 0 40px rgba(255,79,163,.02);
    pointer-events: none;
}

.creative-portfolio .creative-section-heading {
    position: relative;
    margin-bottom: 28px;
    padding: 6px 0 6px 17px;
    border-left: 3px solid var(--cp-pink);
}

.creative-portfolio .creative-eyebrow,
.creative-portfolio .creative-small-label {
    color: var(--cp-muted);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.8px;
}

.creative-portfolio .creative-section-heading h2 {
    margin: 10px 0 0;
    font-size: clamp(36px, 4.5vw, 56px);
    font-weight: 900;
    line-height: 1.08;
    letter-spacing: -1.8px;
}

.creative-portfolio .creative-section-heading h2 span,
.creative-portfolio .creative-contact h2 span {
    color: var(--cp-purple);
}

.creative-portfolio .creative-section-heading::after {
    content: "✦  CREATE / INSPIRE / REPEAT";
    display: block;
    margin-top: 10px;
    color: #9b8cac;
    font-size: 7px;
    letter-spacing: 2px;
}

/* ABOUT */
.creative-portfolio .creative-about-grid {
    display: grid;
    grid-template-columns: .9fr 1.1fr;
    align-items: stretch;
    gap: 25px;
}

.creative-portfolio .creative-about-art {
    min-width: 0;
    padding: 12px;
    border: 1px solid rgba(255,255,255,.12);
    background: linear-gradient(145deg, rgba(244,74,165,.11), rgba(127,88,244,.08));
}

.creative-portfolio .creative-about-art-inner {
    position: relative;
    display: grid;
    min-height: 270px;
    place-items: center;
    overflow: hidden;
    background:
        radial-gradient(circle at center, rgba(255,86,169,.23), transparent 55%),
        repeating-linear-gradient(135deg, transparent 0 22px, rgba(255,255,255,.025) 22px 23px);
}

.creative-portfolio .creative-about-art-inner::before {
    content: "";
    position: absolute;
    width: 72%;
    aspect-ratio: 1;
    border: 1px solid rgba(255,255,255,.13);
    border-radius: 50%;
    box-shadow:
        0 0 0 16px rgba(255,255,255,.025),
        0 0 0 32px rgba(255,255,255,.02);
}

.creative-portfolio .creative-about-big-symbol {
    position: relative;
    z-index: 1;
    color: transparent;
    background: linear-gradient(140deg, #ff5fae, #b4a0ff, #6edfff);
    -webkit-background-clip: text;
    background-clip: text;
    font-size: clamp(120px, 17vw, 210px);
    line-height: 1;
    animation: creativeSpin 35s linear infinite;
}

.creative-portfolio .creative-about-side-text {
    position: absolute;
    top: 50%;
    left: 8px;
    writing-mode: vertical-rl;
    transform: translateY(-50%);
    color: #b5a7ca;
    font-size: 7px;
    letter-spacing: 2px;
}

.creative-portfolio .creative-about-bottom-text {
    position: absolute;
    right: 10px;
    bottom: 10px;
    color: #b5a7ca;
    font-size: 7px;
    letter-spacing: 1px;
}

.creative-portfolio .creative-about-content {
    align-self: center;
    min-width: 0;
    padding: 22px;
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 10px;
    background:
        radial-gradient(circle at 100% 0%, rgba(167,139,250,.12), transparent 45%),
        linear-gradient(145deg, rgba(255,255,255,.04), rgba(255,255,255,.01));
}

.creative-portfolio .creative-about-content h3 {
    margin: 10px 0 12px;
    font-size: clamp(25px, 3vw, 36px);
    line-height: 1.2;
    letter-spacing: -1px;
}

.creative-portfolio .creative-about-content > p {
    margin: 0 0 16px;
    color: var(--cp-muted);
    font-size: 12px;
    line-height: 1.85;
}

.creative-portfolio .creative-detail-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-top: 1px solid rgba(255,255,255,.1);
}

.creative-portfolio .creative-detail-row > span {
    display: grid;
    place-items: center;
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 8px;
    color: var(--cp-pink);
}

.creative-portfolio .creative-detail-row small {
    color: #a69bb8;
    font-size: 7px;
    letter-spacing: 1.3px;
}

.creative-portfolio .creative-detail-row p {
    margin: 2px 0 0;
    font-size: 11px;
}

.creative-portfolio .creative-about-signature {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid rgba(255,255,255,.1);
    color: #b8a4d1;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 3px;
}

/* SKILLS */
.creative-portfolio .creative-skills,
.creative-portfolio .creative-projects,
.creative-portfolio .creative-journey {
    padding-top: 40px;
}

.creative-portfolio .creative-skills-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 13px;
}

.creative-portfolio .creative-skill-card {
    position: relative;
    min-width: 0;
    min-height: 160px;
    padding: 18px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,.11);
    border-radius: 9px;
    background:
        radial-gradient(circle at 100% 0%, rgba(167,139,250,.12), transparent 48%),
        linear-gradient(145deg, #1a1425, #100d18);
    transition: transform .3s ease, border-color .3s ease;
}

.creative-portfolio .creative-skill-card:hover {
    transform: translateY(-4px);
    border-color: rgba(255,105,188,.45);
}

.creative-portfolio .creative-skill-number {
    position: absolute;
    top: 14px;
    right: 15px;
    color: #8e829f;
    font-size: 9px;
}

.creative-portfolio .creative-skill-icon {
    position: relative;
    z-index: 1;
    display: grid;
    place-items: center;
    width: 42px;
    height: 42px;
    margin-bottom: 15px;
    border: 1px solid rgba(255,79,163,.25);
    border-radius: 11px;
    background: rgba(227,103,195,.12);
    color: var(--cp-pink);
    font-size: 22px;
}

.creative-portfolio .creative-skill-card h3 {
    position: relative;
    z-index: 1;
    margin: 0 0 6px;
    font-size: 15px;
}

.creative-portfolio .creative-skill-card p {
    position: relative;
    z-index: 1;
    margin: 0;
    padding-right: 8px;
    color: var(--cp-muted);
    font-size: 10px;
    line-height: 1.7;
}

.creative-portfolio .creative-skill-arrow {
    position: absolute;
    right: 13px;
    bottom: 12px;
    color: #a89ab9;
}

.creative-portfolio .creative-skill-card::after {
    content: "✳";
    position: absolute;
    right: 12px;
    bottom: 0;
    color: rgba(255,255,255,.06);
    font-size: 58px;
    pointer-events: none;
}

.creative-portfolio .creative-empty-state {
    display: grid;
    place-items: center;
    grid-column: 1 / -1;
    min-height: 150px;
    padding: 20px;
    border: 1px dashed rgba(255,255,255,.2);
    border-radius: 10px;
    background: radial-gradient(circle at center, rgba(167,139,250,.09), transparent 60%);
    text-align: center;
    color: var(--cp-muted);
    font-size: 11px;
}

.creative-portfolio .creative-empty-state > span {
    display: grid;
    place-items: center;
    width: 48px;
    height: 48px;
    border: 1px solid rgba(255,79,163,.3);
    border-radius: 14px;
    color: var(--cp-pink);
    font-size: 28px;
}

/* PROJECTS */
.creative-portfolio .creative-projects-heading {
    display: flex;
    align-items: end;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 25px;
}

.creative-portfolio .creative-projects-heading .creative-section-heading {
    margin-bottom: 0;
}

.creative-portfolio .creative-projects-heading > p {
    max-width: 290px;
    margin: 0 0 8px;
    color: var(--cp-muted);
    font-size: 11px;
    line-height: 1.8;
}

.creative-portfolio .creative-project-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.creative-portfolio .creative-project-card {
    min-width: 0;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 10px;
    background: #120f1c;
    transition: transform .3s ease, border-color .3s ease;
}

.creative-portfolio .creative-project-card:hover {
    transform: translateY(-4px);
    border-color: rgba(255,255,255,.28);
}

.creative-portfolio .creative-project-art {
    position: relative;
    display: grid;
    place-items: center;
    min-height: 185px;
    overflow: hidden;
    background-image:
        linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px),
        radial-gradient(circle at 50% 50%, var(--project-glow), transparent 60%),
        linear-gradient(135deg, #21162d, #100d19);
    background-size: 25px 25px, 25px 25px, auto, auto;
}

.creative-portfolio .creative-project-pink {
    --project-glow: rgba(255,70,162,.27);
    --project-color: #ff75b8;
}

.creative-portfolio .creative-project-purple {
    --project-glow: rgba(157,104,255,.3);
    --project-color: #b99aff;
}

.creative-portfolio .creative-project-orange {
    --project-glow: rgba(255,164,104,.23);
    --project-color: #ffb77c;
}

.creative-portfolio .creative-project-blue {
    --project-glow: rgba(95,206,255,.24);
    --project-color: #7edcff;
}

.creative-portfolio .creative-project-art::before,
.creative-portfolio .creative-project-art::after {
    content: "";
    position: absolute;
    width: 155px;
    height: 155px;
    border: 1px dashed color-mix(in srgb, var(--project-color) 40%, transparent);
    border-radius: 50%;
    animation: creativeOrbit 24s linear infinite;
}

.creative-portfolio .creative-project-art::after {
    width: 205px;
    height: 205px;
    border-style: dotted;
    animation-direction: reverse;
}

.creative-portfolio .creative-project-index,
.creative-portfolio .creative-project-art-bottom {
    position: absolute;
    left: 15px;
    color: #c6b9d8;
    font-size: 7px;
    font-weight: 800;
    letter-spacing: 1.7px;
}

.creative-portfolio .creative-project-index {
    top: 13px;
}

.creative-portfolio .creative-project-art-bottom {
    bottom: 13px;
}

.creative-portfolio .creative-project-symbol {
    position: relative;
    z-index: 1;
    color: var(--project-color);
    font-size: 90px;
    line-height: 1;
    text-shadow: 0 0 35px var(--project-glow);
    transition: transform .4s ease;
}

.creative-portfolio .creative-project-card:hover .creative-project-symbol {
    transform: rotate(15deg) scale(1.08);
}

.creative-portfolio .creative-project-info {
    padding: 18px;
}

.creative-portfolio .creative-project-title-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
}

.creative-portfolio .creative-project-title-row h3 {
    margin: 0;
    font-size: 18px;
    line-height: 1.4;
}

.creative-portfolio .creative-project-title-row > span {
    color: var(--project-color);
}

.creative-portfolio .creative-project-info > p {
    margin: 9px 0;
    color: var(--cp-muted);
    font-size: 11px;
    line-height: 1.75;
}

.creative-portfolio .creative-project-tags {
    margin-top: 12px;
}

.creative-portfolio .creative-project-tags span {
    display: inline-block;
    padding: 5px 9px;
    border: 1px solid rgba(255,255,255,.13);
    border-radius: 4px;
    color: #d4c7e6;
    font-size: 9px;
}

.creative-portfolio .creative-project-link {
    display: inline-block;
    margin-top: 13px;
    color: var(--project-color) !important;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: 1px;
}

.creative-portfolio .creative-project-bottom-label {
    margin-top: 13px;
    padding-top: 10px;
    border-top: 1px solid rgba(255,255,255,.09);
    color: #9586aa;
    font-size: 7px;
    font-weight: 800;
    letter-spacing: 2px;
}

/* JOURNEY */
.creative-portfolio .creative-journey-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.creative-portfolio .creative-journey-column {
    min-width: 0;
    padding: 18px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,.13);
    border-radius: 10px;
    background:
        radial-gradient(circle at 100% 0%, rgba(167,139,250,.1), transparent 40%),
        linear-gradient(145deg, rgba(255,255,255,.04), rgba(255,255,255,.01));
}

.creative-portfolio .creative-journey-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 0;
    padding: 12px;
    border: 1px solid rgba(255,255,255,.09);
    border-radius: 7px;
    background: rgba(255,255,255,.025);
}

.creative-portfolio .creative-journey-heading > span {
    color: var(--cp-pink);
    font-size: 21px;
}

.creative-portfolio .creative-journey-heading h3 {
    margin: 0;
    font-size: 17px;
}

.creative-portfolio .creative-timeline-item {
    position: relative;
    margin-left: 7px;
    padding: 17px 0 17px 21px;
    border-left: 1px solid rgba(255,255,255,.13);
}

.creative-portfolio .creative-timeline-dot {
    position: absolute;
    top: 24px;
    left: -5px;
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: var(--cp-pink);
    box-shadow: 0 0 12px rgba(255,79,163,.5);
}

.creative-portfolio .creative-timeline-item h4 {
    margin: 0 0 6px;
    font-size: 14px;
}

.creative-portfolio .creative-timeline-item p {
    margin: 0;
    color: var(--cp-muted);
    font-size: 11px;
    line-height: 1.7;
}

.creative-portfolio .creative-timeline-item small {
    display: inline-block;
    margin-top: 7px;
    color: var(--cp-purple);
    font-size: 9px;
}

.creative-portfolio .creative-muted {
    margin: 15px 0 0;
    padding: 15px;
    border: 1px dashed rgba(255,255,255,.15);
    border-radius: 7px;
    color: var(--cp-muted);
    font-size: 11px;
}

.creative-portfolio .creative-column-footer {
    margin-top: 8px;
    padding-top: 11px;
    border-top: 1px solid rgba(255,255,255,.1);
    color: #9383a8;
    font-size: 7px;
    letter-spacing: 2px;
}

/* CONTACT */
.creative-portfolio .creative-contact {
    position: relative;
    width: 100%;
    max-width: none;
    padding: 65px 20px 20px;
    overflow: hidden;
    border-top: 1px solid rgba(255,255,255,.12);
    background:
        radial-gradient(circle at 10% 30%, rgba(255,79,163,.12), transparent 25%),
        radial-gradient(circle at 90% 65%, rgba(167,139,250,.14), transparent 28%),
        #0b0913;
    text-align: center;
    scroll-margin-top: 25px;
}

.creative-portfolio .creative-contact-decoration {
    position: absolute;
    top: 25px;
    right: 9%;
    color: rgba(255,111,185,.2);
    font-size: 75px;
    animation: creativeSpin 30s linear infinite;
    pointer-events: none;
}

.creative-portfolio .creative-contact h2 {
    position: relative;
    margin: 12px 0;
    font-size: clamp(50px, 7vw, 82px);
    font-weight: 900;
    line-height: 1.02;
    letter-spacing: -2px;
}

.creative-portfolio .creative-contact > p {
    position: relative;
    margin: 0 auto 22px;
    color: var(--cp-muted);
    font-size: 12px;
}

.creative-portfolio .creative-social-links {
    position: relative;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px 22px;
    margin-top: 24px;
}

.creative-portfolio .creative-social-links a {
    color: #c2b4d4;
    font-size: 10px;
    transition: color .2s ease;
}

.creative-portfolio .creative-social-links a:hover {
    color: var(--cp-pink);
}

.creative-portfolio .creative-contact-footer {
    position: relative;
    display: flex;
    justify-content: space-between;
    gap: 15px;
    width: min(1160px, 100%);
    margin: 50px auto 0;
    padding-top: 16px;
    border-top: 1px solid rgba(255,255,255,.12);
    color: #9389a2;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 1px;
}

.creative-portfolio .creative-contact-footer a:hover {
    color: white;
}

/* ANIMATIONS */
@keyframes creativeGridMove {
    from { background-position: 0 0; }
    to { background-position: 52px 52px; }
}

@keyframes creativeGlowMove {
    from { transform: translate3d(-15px, 0, 0) scale(.9); }
    to { transform: translate3d(35px, 45px, 0) scale(1.15); }
}

@keyframes creativeOrbit {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes creativeSpin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes creativeFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-9px); }
}

@keyframes creativeWave {
    0%, 100% { transform: rotate(0deg); }
    15% { transform: rotate(18deg); }
    30% { transform: rotate(-10deg); }
    45% { transform: rotate(15deg); }
    60% { transform: rotate(0deg); }
}

@keyframes creativeMarquee {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); }
}

@keyframes creativeParticleFloat {
    0% { transform: translate3d(0, 30px, 0); opacity: 0; }
    15% { opacity: .75; }
    80% { opacity: .5; }
    100% { transform: translate3d(35px, -180px, 0); opacity: 0; }
}

/* TABLET */
@media (max-width: 850px) {
    .creative-portfolio .creative-hero {
        grid-template-columns: 1fr;
        gap: 28px;
        padding-block: 40px;
    }

    .creative-portfolio .creative-hero-content {
        max-width: 650px;
    }

    .creative-portfolio .creative-hero-art {
        width: min(460px, 100%);
        margin-inline: auto;
    }

    .creative-portfolio .creative-about-grid {
        grid-template-columns: 1fr;
        gap: 18px;
    }

    .creative-portfolio .creative-about-art {
        max-width: 500px;
        width: 100%;
    }

    .creative-portfolio .creative-nav-links {
        gap: 14px;
    }

    .creative-portfolio .creative-skills-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .creative-portfolio .creative-journey-grid {
        gap: 12px;
    }
}

/* MOBILE */
@media (max-width: 560px) {
    .creative-portfolio .creative-nav,
    .creative-portfolio .creative-hero,
    .creative-portfolio .creative-section {
        width: calc(100% - 32px);
    }

    .creative-portfolio .creative-nav {
        min-height: auto;
        align-items: flex-start;
        flex-direction: column;
        justify-content: center;
        gap: 10px;
        padding-block: 14px;
    }

    .creative-portfolio .creative-nav-links {
        width: 100%;
        flex-wrap: wrap;
        gap: 10px 15px;
        font-size: 10px;
    }

    .creative-portfolio .creative-nav-contact {
        padding: 5px 9px;
    }

    .creative-portfolio .creative-hero {
        padding-block: 32px 38px;
        gap: 22px;
    }

    .creative-portfolio .creative-hero h1 {
        font-size: clamp(39px, 11vw, 58px);
        letter-spacing: -2px;
    }

    .creative-portfolio .creative-hero-description {
        font-size: 12px;
    }

    .creative-portfolio .creative-hero-footer {
        margin-top: 23px;
    }

    .creative-portfolio .creative-art-stage {
        min-height: 335px;
    }

    .creative-portfolio .creative-art-ring-one {
        width: 230px;
        height: 230px;
    }

    .creative-portfolio .creative-art-ring-two {
        width: 280px;
        height: 280px;
    }

    .creative-portfolio .creative-profile-card {
        width: 76%;
    }

    .creative-portfolio .creative-profile-image,
    .creative-portfolio .creative-profile-placeholder {
        height: 170px;
    }

    .creative-portfolio .creative-profile-placeholder {
        font-size: 48px;
    }

    .creative-portfolio .creative-floating-tag {
        padding: 7px;
        font-size: 7px;
    }

    .creative-portfolio .creative-tag-top {
        left: 0;
    }

    .creative-portfolio .creative-tag-bottom {
        right: 0;
        bottom: 50px;
    }

    .creative-portfolio .creative-section {
        padding-block: 38px;
    }

    .creative-portfolio .creative-section-heading {
        margin-bottom: 20px;
    }

    .creative-portfolio .creative-section-heading h2 {
        font-size: 39px;
        letter-spacing: -1.4px;
    }

    .creative-portfolio .creative-about-art {
        min-height: 225px;
    }

    .creative-portfolio .creative-about-art-inner {
        min-height: 200px;
    }

    .creative-portfolio .creative-about-content {
        padding: 15px;
    }

    .creative-portfolio .creative-skills,
    .creative-portfolio .creative-projects,
    .creative-portfolio .creative-journey {
        padding-top: 30px;
    }

    .creative-portfolio .creative-skills-grid,
    .creative-portfolio .creative-project-grid,
    .creative-portfolio .creative-journey-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .creative-portfolio .creative-skill-card {
        min-height: 140px;
        padding: 16px;
    }

    .creative-portfolio .creative-projects-heading {
        align-items: flex-start;
        flex-direction: column;
        gap: 8px;
    }

    .creative-portfolio .creative-project-art {
        min-height: 165px;
    }

    .creative-portfolio .creative-project-info {
        padding: 15px;
    }

    .creative-portfolio .creative-journey-column {
        padding: 14px;
    }

    .creative-portfolio .creative-contact {
        padding: 50px 15px 18px;
    }

    .creative-portfolio .creative-contact h2 {
        font-size: 54px;
        letter-spacing: -1.5px;
    }

    .creative-portfolio .creative-contact-decoration {
        right: 3%;
        font-size: 55px;
    }

    .creative-portfolio .creative-contact-footer {
        margin-top: 35px;
        font-size: 7px;
    }
}

/* ACCESSIBILITY */
@media (prefers-reduced-motion: reduce) {
    .creative-portfolio *,
    .creative-portfolio *::before,
    .creative-portfolio *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
    }
}
</style>

<script>
(function () {
    const root = document.getElementById('creativePortfolio');

    if (!root || root.dataset.particlesInitialized === 'true') {
        return;
    }

    root.dataset.particlesInitialized = 'true';

    // Smooth scrolling for this portfolio only.
    root.querySelectorAll('a[href^="#creative"]').forEach(function (link) {
        link.addEventListener('click', function (event) {
            const targetId = link.getAttribute('href');
            const target = root.querySelector(targetId);

            if (target) {
                event.preventDefault();

                target.scrollIntoView({
                    behavior: window.matchMedia(
                        '(prefers-reduced-motion: reduce)'
                    ).matches ? 'auto' : 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Create the live background particles.
    const particleContainer = root.querySelector('#creativeParticles');

    if (particleContainer && !particleContainer.children.length) {
        const particleCount = window.matchMedia('(max-width: 600px)').matches
            ? 16
            : 30;

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('span');

            particle.className = 'creative-particle';
            particle.style.left = (Math.random() * 100) + '%';
            particle.style.top = (Math.random() * 100) + '%';

            particle.style.setProperty(
                '--particle-duration',
                (8 + Math.random() * 14) + 's'
            );

            particle.style.setProperty(
                '--particle-delay',
                (-Math.random() * 15) + 's'
            );

            particleContainer.appendChild(particle);
        }
    }
})();
</script>
