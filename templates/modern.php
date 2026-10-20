
<?php
// ======================================================
// PORTFOLIOGEN — MODERN TEMPLATE V6
// Futuristic Developer Portfolio
// Black • Crimson • Purple • Animated Background
// ======================================================

if (!isset($portfolio) || !is_array($portfolio)) {
    $portfolio = [];
}

if (!function_exists('modernV6Escape')) {
    function modernV6Escape($value): string {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('modernV6List')) {
    function modernV6List($value): array {
        if (is_array($value)) {
            return $value;
        }

        if (!is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return preg_split('/[\r\n,]+/', $value, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    }
}

if (!function_exists('modernV6Value')) {
    function modernV6Value(array $item, array $keys, string $fallback = ''): string {
        foreach ($keys as $key) {
            if (isset($item[$key]) && is_scalar($item[$key]) && trim((string) $item[$key]) !== '') {
                return trim((string) $item[$key]);
            }
        }

        return $fallback;
    }
}

$modernName = $portfolio['full_name'] ?? 'Your Name';
$modernEmail = $portfolio['email'] ?? '';
$modernPhone = $portfolio['contact_number'] ?? '';
$modernAddress = $portfolio['address'] ?? '';
$modernAbout = $portfolio['about_me'] ?? 'I am passionate about technology, creativity, and building digital experiences that solve real problems.';
$modernEducation = modernV6List($portfolio['education'] ?? '');
$modernSkills = modernV6List($portfolio['skills'] ?? '');
$modernProjects = modernV6List($portfolio['projects'] ?? '');
$modernExperience = modernV6List($portfolio['work_experience'] ?? '');
$modernSocials = modernV6List($portfolio['social_links'] ?? '');
$modernPicture = trim((string) ($portfolio['profile_picture'] ?? ''));

$modernInitials = '';
$nameParts = preg_split('/\s+/', trim((string) $modernName));
foreach (array_slice($nameParts ?: [], 0, 2) as $part) {
    $modernInitials .= strtoupper(substr($part, 0, 1));
}
$modernInitials = $modernInitials ?: 'YN';

$modernSkillCount = count($modernSkills);
$modernProjectCount = count($modernProjects);
$modernExperienceCount = count($modernExperience);
?>

<div class="modern-v3 modern-v6">

    <!-- LIVE ANIMATED BACKGROUND -->
    <div class="modern-background" aria-hidden="true">
        <div class="modern-bg-grid"></div>
        <div class="modern-bg-glow modern-glow-red"></div>
        <div class="modern-bg-glow modern-glow-purple"></div>
        <div class="modern-bg-glow modern-glow-blue"></div>
        <div class="modern-bg-ring modern-ring-one"></div>
        <div class="modern-bg-ring modern-ring-two"></div>
        <div class="modern-bg-ring modern-ring-three"></div>
        <div class="modern-bg-scanline"></div>
        <div class="modern-bg-stars"></div>
    </div>

    <!-- NAVIGATION -->
    <header class="mv6-header">
        <a class="mv6-logo" href="#mv6-home" aria-label="Go to home">
            <span class="mv6-logo-icon">&lt;/&gt;</span>
            <span>PORTFOLIO<span class="mv6-accent">GEN</span></span>
        </a>

        <nav class="mv6-nav" aria-label="Main navigation">
            <a href="#mv6-home">Home</a>
            <a href="#mv6-about">About</a>
            <a href="#mv6-skills">Skills</a>
            <a href="#mv6-projects">Projects</a>
            <a href="#mv6-journey">Journey</a>
        </nav>

        <a class="mv6-nav-button" href="#mv6-contact">
            Let's Talk <span>↗</span>
        </a>
    </header>

    <main>

        <!-- HERO -->
        <section class="mv6-hero" id="mv6-home">

            <div class="mv6-hero-copy">
                <div class="mv6-eyebrow">
                    <span class="mv6-status-dot"></span>
                    <span>PERSONAL PORTFOLIO / 2026</span>
                </div>

                <p class="mv6-overline">HELLO, WORLD. I'M</p>

                <h1>
                    <?= modernV6Escape($modernName) ?><span class="mv6-name-period">.</span>
                </h1>

                <div class="mv6-role-line">
                    <span class="mv6-role-symbol">✳</span>
                    <span>CREATIVE THINKER</span>
                    <span class="mv6-role-divider">/</span>
                    <span>TECH ENTHUSIAST</span>
                </div>

                <p class="mv6-hero-description">
                    <?= modernV6Escape($modernAbout) ?>
                </p>

                <div class="mv6-hero-actions">
                    <a class="mv6-button-primary" href="#mv6-projects">
                        Explore My Work <span>↗</span>
                    </a>

                    <a class="mv6-button-secondary" href="#mv6-contact">
                        Contact Me <span>→</span>
                    </a>
                </div>

                <div class="mv6-hero-bottom">
                    <div class="mv6-scroll-indicator">
                        <span class="mv6-scroll-line"></span>
                        <span>SCROLL TO EXPLORE</span>
                    </div>

                    <div class="mv6-hero-index">01 <span>/</span> 05</div>
                </div>
            </div>

            <!-- PROFILE VISUAL -->
            <div class="mv6-visual">
                <div class="mv6-visual-label mv6-label-top">
                    <span>FIG. 01</span>
                    <span>IDENTITY MODULE</span>
                </div>

                <div class="mv6-orbit mv6-orbit-outer"></div>
                <div class="mv6-orbit mv6-orbit-middle"></div>
                <div class="mv6-orbit mv6-orbit-inner"></div>

                <div class="mv6-orbit-node mv6-node-one"></div>
                <div class="mv6-orbit-node mv6-node-two"></div>
                <div class="mv6-orbit-node mv6-node-three"></div>

                <div class="mv6-profile-card">
                    <div class="mv6-card-top">
                        <span class="mv6-card-dot"></span>
                        <span class="mv6-card-dot"></span>
                        <span class="mv6-card-dot"></span>
                        <span class="mv6-card-file">profile.module</span>
                        <span class="mv6-card-menu">•••</span>
                    </div>

                    <div class="mv6-card-content">
                        <div class="mv6-profile-image-wrap">
                            <?php if ($modernPicture !== ''): ?>
                                <img
                                    src="<?= modernV6Escape($modernPicture) ?>"
                                    alt="<?= modernV6Escape($modernName) ?>"
                                    class="mv6-profile-image"
                                    onerror="this.style.display='none';this.nextElementSibling.style.display='grid';"
                                >
                                <div class="mv6-profile-initials" style="display:none;">
                                    <?= modernV6Escape($modernInitials) ?>
                                </div>
                            <?php else: ?>
                                <div class="mv6-profile-initials">
                                    <?= modernV6Escape($modernInitials) ?>
                                </div>
                            <?php endif; ?>

                            <span class="mv6-image-corner mv6-corner-tl"></span>
                            <span class="mv6-image-corner mv6-corner-tr"></span>
                            <span class="mv6-image-corner mv6-corner-bl"></span>
                            <span class="mv6-image-corner mv6-corner-br"></span>
                        </div>

                        <div class="mv6-profile-identity">
                            <span class="mv6-mini-label">USER PROFILE</span>
                            <h2><?= modernV6Escape($modernName) ?></h2>
                            <p>Building ideas into reality.</p>
                        </div>

                        <div class="mv6-profile-divider"></div>

                        <div class="mv6-code-line">
                            <span class="mv6-code-number">01</span>
                            <span class="mv6-code-key">const</span>
                            <span class="mv6-code-variable">creativity</span>
                            <span class="mv6-code-symbol">=</span>
                            <span class="mv6-code-value">true;</span>
                        </div>

                        <div class="mv6-code-line">
                            <span class="mv6-code-number">02</span>
                            <span class="mv6-code-key">const</span>
                            <span class="mv6-code-variable">mindset</span>
                            <span class="mv6-code-symbol">=</span>
                            <span class="mv6-code-value">"growth";</span>
                        </div>

                        <div class="mv6-code-line">
                            <span class="mv6-code-number">03</span>
                            <span class="mv6-code-comment">// Always learning...</span>
                        </div>

                        <div class="mv6-card-footer">
                            <span><i></i> OPEN TO OPPORTUNITIES</span>
                            <span>PH / 2026</span>
                        </div>
                    </div>
                </div>

                <div class="mv6-float-badge mv6-badge-code">
                    <span class="mv6-badge-icon">&lt;/&gt;</span>
                    <span><b>CODE</b><small>WITH PURPOSE</small></span>
                </div>

                <div class="mv6-float-badge mv6-badge-creative">
                    <span class="mv6-creative-star">✳</span>
                    <span><b>CREATE</b><small>WITH PASSION</small></span>
                </div>

                <div class="mv6-visual-label mv6-label-bottom">
                    <span>DESIGNED TO STAND OUT</span>
                    <span>◈ 001</span>
                </div>
            </div>
        </section>

        <!-- METRICS -->
        <section class="mv6-metrics" aria-label="Portfolio overview">
            <div class="mv6-metric">
                <span class="mv6-metric-number"><?= str_pad((string) $modernProjectCount, 2, '0', STR_PAD_LEFT) ?></span>
                <span class="mv6-metric-label">PROJECTS</span>
                <span class="mv6-metric-line"></span>
            </div>

            <div class="mv6-metric">
                <span class="mv6-metric-number"><?= str_pad((string) $modernSkillCount, 2, '0', STR_PAD_LEFT) ?></span>
                <span class="mv6-metric-label">SKILLS</span>
                <span class="mv6-metric-line"></span>
            </div>

            <div class="mv6-metric">
                <span class="mv6-metric-number"><?= str_pad((string) $modernExperienceCount, 2, '0', STR_PAD_LEFT) ?></span>
                <span class="mv6-metric-label">EXPERIENCES</span>
                <span class="mv6-metric-line"></span>
            </div>

            <div class="mv6-metric-note">
                <span class="mv6-note-symbol">✳</span>
                <span>Curiosity drives<br>every creation.</span>
            </div>
        </section>

        <!-- ABOUT -->
        <section class="mv6-section mv6-about" id="mv6-about">
            <div class="mv6-section-aside">
                <span class="mv6-section-number">01 / ABOUT</span>
                <span class="mv6-aside-line"></span>
                <span class="mv6-aside-caption">THE PERSON BEHIND THE WORK</span>
            </div>

            <div class="mv6-about-main">
                <div class="mv6-section-heading">
                    <span class="mv6-kicker">A LITTLE ABOUT ME</span>
                    <h2>More than code.<br><span class="mv6-outline-text">A mindset.</span></h2>
                </div>

                <div class="mv6-about-grid">
                    <div class="mv6-about-statement">
                        <span class="mv6-quote-mark">“</span>
                        <p><?= modernV6Escape($modernAbout) ?></p>
                        <div class="mv6-statement-line"></div>
                        <span class="mv6-statement-caption">MY STORY. MY DIRECTION. MY JOURNEY.</span>
                    </div>

                    <div class="mv6-about-details">
                        <div class="mv6-detail-block">
                            <span class="mv6-detail-number">01</span>
                            <div>
                                <h3>Curiosity</h3>
                                <p>Exploring new ideas, tools, and possibilities through continuous learning.</p>
                            </div>
                        </div>

                        <div class="mv6-detail-block">
                            <span class="mv6-detail-number">02</span>
                            <div>
                                <h3>Creativity</h3>
                                <p>Turning inspiration into thoughtful designs and useful digital experiences.</p>
                            </div>
                        </div>

                        <div class="mv6-detail-block">
                            <span class="mv6-detail-number">03</span>
                            <div>
                                <h3>Growth</h3>
                                <p>Improving with every challenge, project, and new experience.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SKILLS -->
        <section class="mv6-section mv6-skills-section" id="mv6-skills">
            <div class="mv6-section-aside">
                <span class="mv6-section-number">02 / EXPERTISE</span>
                <span class="mv6-aside-line"></span>
                <span class="mv6-aside-caption">TOOLS IN MY ARSENAL</span>
            </div>

            <div class="mv6-section-main">
                <div class="mv6-heading-row">
                    <div class="mv6-section-heading">
                        <span class="mv6-kicker">WHAT I BRING</span>
                        <h2>Skills &amp; <span class="mv6-outline-text">abilities.</span></h2>
                    </div>
                    <span class="mv6-heading-count">[ <?= count($modernSkills) ?> ITEMS ]</span>
                </div>

                <?php if (!empty($modernSkills)): ?>
                    <div class="mv6-skills-grid">
                        <?php foreach ($modernSkills as $index => $skill): ?>
                            <?php
                            $skillName = is_array($skill)
                                ? modernV6Value($skill, ['name', 'skill', 'title'], 'Skill')
                                : trim((string) $skill);
                            ?>
                            <?php if ($skillName !== ''): ?>
                                <article class="mv6-skill-card">
                                    <div class="mv6-skill-top">
                                        <span class="mv6-skill-index">
                                            <?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?>
                                        </span>
                                        <span class="mv6-skill-arrow">↗</span>
                                    </div>
                                    <div class="mv6-skill-symbol">
                                        <?= modernV6Escape(strtoupper(substr($skillName, 0, 1))) ?>
                                    </div>
                                    <h3><?= modernV6Escape($skillName) ?></h3>
                                    <div class="mv6-skill-bottom">
                                        <span>TECH / SKILL</span>
                                        <span class="mv6-skill-indicator"></span>
                                    </div>
                                </article>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="mv6-empty-state">
                        <span>✳</span>
                        <p>Add your skills through the portfolio editor to display them here.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- PROJECTS -->
        <section class="mv6-section mv6-projects-section" id="mv6-projects">
            <div class="mv6-section-aside">
                <span class="mv6-section-number">03 / SELECTED WORK</span>
                <span class="mv6-aside-line"></span>
                <span class="mv6-aside-caption">IDEAS MADE REAL</span>
            </div>

            <div class="mv6-section-main">
                <div class="mv6-heading-row">
                    <div class="mv6-section-heading">
                        <span class="mv6-kicker">MY CREATIONS</span>
                        <h2>Selected <span class="mv6-outline-text">projects.</span></h2>
                    </div>
                    <span class="mv6-heading-count">[ <?= count($modernProjects) ?> PROJECTS ]</span>
                </div>

                <?php if (!empty($modernProjects)): ?>
                    <div class="mv6-project-grid">
                        <?php foreach ($modernProjects as $index => $project): ?>
                            <?php
                            if (is_array($project)) {
                                $projectName = modernV6Value($project, ['title', 'name', 'project_name'], 'Untitled Project');
                                $projectDescription = modernV6Value($project, ['description', 'details', 'about'], 'A project focused on creativity, problem-solving, and continuous improvement.');
                                $projectTech = modernV6List($project['technologies'] ?? $project['tech_stack'] ?? $project['tools'] ?? '');
                                $projectUrl = modernV6Value($project, ['url', 'link', 'project_url', 'github'], '');
                            } else {
                                $projectName = trim((string) $project);
                                $projectDescription = '';
                                $projectTech = [];
                                $projectUrl = '';
                            }

                            if ($projectName === '') {
                                continue;
                            }

                            $projectUrlAllowed = $projectUrl !== '' && preg_match('/^https?:\/\//i', $projectUrl);
                            $projectNumber = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                            ?>

                            <article class="mv6-project-card">
                                <div class="mv6-project-art mv6-project-art-<?= $index % 4 ?>">
                                    <div class="mv6-art-grid"></div>
                                    <span class="mv6-art-number"><?= modernV6Escape($projectNumber) ?></span>

                                    <?php if ($index % 4 === 0): ?>
                                        <div class="mv6-art-visual mv6-art-browser">
                                            <div class="mv6-browser-top"><i></i><i></i><i></i></div>
                                            <div class="mv6-browser-content">
                                                <span class="mv6-art-line art-line-wide"></span>
                                                <span class="mv6-art-line art-line-short"></span>
                                                <div class="mv6-art-blocks"><i></i><i></i><i></i></div>
                                            </div>
                                        </div>
                                    <?php elseif ($index % 4 === 1): ?>
                                        <div class="mv6-art-visual mv6-art-orbit">
                                            <span class="mv6-art-orbit-ring"></span>
                                            <span class="mv6-art-orbit-ring ring-small"></span>
                                            <span class="mv6-art-orbit-core">✳</span>
                                        </div>
                                    <?php elseif ($index % 4 === 2): ?>
                                        <div class="mv6-art-visual mv6-art-cube">
                                            <div class="mv6-cube-face cube-front"></div>
                                            <div class="mv6-cube-face cube-side"></div>
                                            <div class="mv6-cube-face cube-top"></div>
                                        </div>
                                    <?php else: ?>
                                        <div class="mv6-art-visual mv6-art-terminal">
                                            <span>&gt; create_future()</span>
                                            <span class="mv6-terminal-line">BUILDING SOMETHING NEW</span>
                                            <span class="mv6-terminal-cursor">_</span>
                                        </div>
                                    <?php endif; ?>

                                    <span class="mv6-art-category">PROJECT / <?= modernV6Escape($projectNumber) ?></span>
                                </div>

                                <div class="mv6-project-content">
                                    <div class="mv6-project-meta">
                                        <span>FEATURED WORK</span>
                                        <span><?= modernV6Escape($projectNumber) ?> — <?= str_pad((string) count($modernProjects), 2, '0', STR_PAD_LEFT) ?></span>
                                    </div>

                                    <h3><?= modernV6Escape($projectName) ?></h3>
                                    <p><?= modernV6Escape($projectDescription) ?></p>

                                    <?php if (!empty($projectTech)): ?>
                                        <div class="mv6-project-tech">
                                            <?php foreach (array_slice($projectTech, 0, 5) as $tech): ?>
                                                <span><?= modernV6Escape(is_array($tech) ? modernV6Value($tech, ['name', 'title'], 'Tool') : $tech) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mv6-project-footer">
                                        <span>DESIGN / DEVELOPMENT</span>
                                        <?php if ($projectUrlAllowed): ?>
                                            <a href="<?= modernV6Escape($projectUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="Open <?= modernV6Escape($projectName) ?>">↗</a>
                                        <?php else: ?>
                                            <span class="mv6-project-arrow">↗</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="mv6-empty-state">
                        <span>↗</span>
                        <p>Your projects will appear here after you add them to your portfolio.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- EDUCATION AND EXPERIENCE -->
        <section class="mv6-section mv6-journey-section" id="mv6-journey">
            <div class="mv6-section-aside">
                <span class="mv6-section-number">04 / MY JOURNEY</span>
                <span class="mv6-aside-line"></span>
                <span class="mv6-aside-caption">LEARNING NEVER STOPS</span>
            </div>

            <div class="mv6-section-main">
                <div class="mv6-section-heading">
                    <span class="mv6-kicker">THE PATH SO FAR</span>
                    <h2>Experience &amp; <span class="mv6-outline-text">education.</span></h2>
                </div>

                <div class="mv6-journey-grid">

                    <div class="mv6-journey-column">
                        <div class="mv6-journey-heading">
                            <span class="mv6-journey-icon">⌘</span>
                            <h3>EXPERIENCE</h3>
                            <span class="mv6-journey-count"><?= count($modernExperience) ?></span>
                        </div>

                        <?php if (!empty($modernExperience)): ?>
                            <div class="mv6-timeline">
                                <?php foreach ($modernExperience as $experience): ?>
                                    <?php
                                    if (is_array($experience)) {
                                        $expTitle = modernV6Value($experience, ['position', 'title', 'role', 'job_title'], 'Experience');
                                        $expCompany = modernV6Value($experience, ['company', 'organization', 'employer'], '');
                                        $expDate = modernV6Value($experience, ['duration', 'date', 'period', 'year'], '');
                                        $expDescription = modernV6Value($experience, ['description', 'details'], '');
                                    } else {
                                        $expTitle = trim((string) $experience);
                                        $expCompany = '';
                                        $expDate = '';
                                        $expDescription = '';
                                    }
                                    ?>
                                    <article class="mv6-timeline-card">
                                        <span class="mv6-timeline-dot"></span>
                                        <?php if ($expDate !== ''): ?>
                                            <span class="mv6-timeline-date"><?= modernV6Escape($expDate) ?></span>
                                        <?php endif; ?>
                                        <h4><?= modernV6Escape($expTitle) ?></h4>
                                        <?php if ($expCompany !== ''): ?>
                                            <p class="mv6-timeline-place"><?= modernV6Escape($expCompany) ?></p>
                                        <?php endif; ?>
                                        <?php if ($expDescription !== ''): ?>
                                            <p class="mv6-timeline-description"><?= modernV6Escape($expDescription) ?></p>
                                        <?php endif; ?>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="mv6-journey-empty">Your professional journey starts here.</div>
                        <?php endif; ?>
                    </div>

                    <div class="mv6-journey-column">
                        <div class="mv6-journey-heading">
                            <span class="mv6-journey-icon">◎</span>
                            <h3>EDUCATION</h3>
                            <span class="mv6-journey-count"><?= count($modernEducation) ?></span>
                        </div>

                        <?php if (!empty($modernEducation)): ?>
                            <div class="mv6-timeline">
                                <?php foreach ($modernEducation as $education): ?>
                                    <?php
                                    if (is_array($education)) {
                                        $eduTitle = modernV6Value($education, ['degree', 'title', 'course', 'program', 'level'], 'Education');
                                        $eduSchool = modernV6Value($education, ['school', 'institution', 'university', 'name'], '');
                                        $eduDate = modernV6Value($education, ['year', 'date', 'duration', 'period'], '');
                                        $eduDescription = modernV6Value($education, ['description', 'details'], '');
                                    } else {
                                        $eduTitle = trim((string) $education);
                                        $eduSchool = '';
                                        $eduDate = '';
                                        $eduDescription = '';
                                    }
                                    ?>
                                    <article class="mv6-timeline-card">
                                        <span class="mv6-timeline-dot"></span>
                                        <?php if ($eduDate !== ''): ?>
                                            <span class="mv6-timeline-date"><?= modernV6Escape($eduDate) ?></span>
                                        <?php endif; ?>
                                        <h4><?= modernV6Escape($eduTitle) ?></h4>
                                        <?php if ($eduSchool !== ''): ?>
                                            <p class="mv6-timeline-place"><?= modernV6Escape($eduSchool) ?></p>
                                        <?php endif; ?>
                                        <?php if ($eduDescription !== ''): ?>
                                            <p class="mv6-timeline-description"><?= modernV6Escape($eduDescription) ?></p>
                                        <?php endif; ?>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="mv6-journey-empty">Every new skill is another step forward.</div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </section>

        <!-- CONTACT -->
        <section class="mv6-contact-section" id="mv6-contact">
            <div class="mv6-contact-glow"></div>
            <div class="mv6-contact-top">
                <span class="mv6-kicker">05 / GET IN TOUCH</span>
                <span class="mv6-contact-availability"><i></i> LET'S CONNECT</span>
            </div>

            <div class="mv6-contact-body">
                <div>
                    <h2>Have an idea?<br><span>Let's make it real.</span></h2>
                    <p>Have a project, an opportunity, or just want to connect? I'd love to hear from you.</p>
                </div>

                <div class="mv6-contact-links">
                    <?php if ($modernEmail !== ''): ?>
                        <a class="mv6-contact-link" href="mailto:<?= modernV6Escape($modernEmail) ?>">
                            <span class="mv6-contact-link-icon">@</span>
                            <span><small>EMAIL ME</small><b><?= modernV6Escape($modernEmail) ?></b></span>
                            <span class="mv6-contact-link-arrow">↗</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($modernPhone !== ''): ?>
                        <a class="mv6-contact-link" href="tel:<?= modernV6Escape(preg_replace('/[^0-9+]/', '', (string) $modernPhone)) ?>">
                            <span class="mv6-contact-link-icon">⌕</span>
                            <span><small>CALL ME</small><b><?= modernV6Escape($modernPhone) ?></b></span>
                            <span class="mv6-contact-link-arrow">↗</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($modernAddress !== ''): ?>
                        <div class="mv6-contact-link">
                            <span class="mv6-contact-link-icon">⌖</span>
                            <span><small>LOCATION</small><b><?= modernV6Escape($modernAddress) ?></b></span>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($modernSocials as $social): ?>
                        <?php
                        if (is_array($social)) {
                            $socialName = modernV6Value($social, ['platform', 'name', 'title', 'label'], 'Social Profile');
                            $socialUrl = modernV6Value($social, ['url', 'link', 'href'], '');
                        } else {
                            $socialName = trim((string) $social);
                            $socialUrl = '';
                        }
                        $socialUrlAllowed = $socialUrl !== '' && preg_match('/^https?:\/\//i', $socialUrl);
                        ?>
                        <?php if ($socialName !== '' && $socialUrlAllowed): ?>
                            <a class="mv6-contact-link" href="<?= modernV6Escape($socialUrl) ?>" target="_blank" rel="noopener noreferrer">
                                <span class="mv6-contact-link-icon">↗</span>
                                <span><small>FOLLOW / CONNECT</small><b><?= modernV6Escape($socialName) ?></b></span>
                                <span class="mv6-contact-link-arrow">↗</span>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mv6-contact-bottom">
                <span>GOOD THINGS START WITH A CONVERSATION.</span>
                <a href="#mv6-home">BACK TO TOP ↑</a>
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="mv6-footer">
        <a class="mv6-logo" href="#mv6-home">
            <span class="mv6-logo-icon">&lt;/&gt;</span>
            <span>PORTFOLIO<span class="mv6-accent">GEN</span></span>
        </a>

        <p>Designed with purpose. Built with passion.</p>

        <span class="mv6-footer-copy">© <?= date('Y') ?> <?= modernV6Escape($modernName) ?></span>
    </footer>

</div>

<style>
/* ======================================================
   MODERN V6 — COMPLETE DISTINCTIVE DESIGN
   Scoped to .modern-v6 to avoid changing other templates
====================================================== */

.modern-v6 {
    --mv-bg: #08070d;
    --mv-panel: rgba(18, 15, 27, .88);
    --mv-panel-light: rgba(255, 255, 255, .035);
    --mv-red: #e11d48;
    --mv-red-light: #fb7185;
    --mv-purple: #a855f7;
    --mv-purple-light: #c084fc;
    --mv-blue: #60a5fa;
    --mv-text: #f4f1fa;
    --mv-muted: #a09aad;
    --mv-line: rgba(255, 255, 255, .12);
    position: relative;
    isolation: isolate;
    overflow: hidden;
    background: var(--mv-bg);
    color: var(--mv-text);
    font-family: Inter, "Segoe UI", Arial, sans-serif;
    min-height: 100vh;
    line-height: 1.6;
}

.modern-v6 *,
.modern-v6 *::before,
.modern-v6 *::after {
    box-sizing: border-box;
}

.modern-v6 a {
    color: inherit;
    text-decoration: none;
}

.modern-v6 .modern-background {
    position: absolute;
    inset: 0;
    z-index: -1;
    overflow: hidden;
    pointer-events: none;
}

.modern-v6 .modern-bg-grid {
    position: absolute;
    inset: 0;
    opacity: .24;
    background-image:
        linear-gradient(rgba(168, 85, 247, .12) 1px, transparent 1px),
        linear-gradient(90deg, rgba(168, 85, 247, .12) 1px, transparent 1px);
    background-size: 55px 55px;
    mask-image: linear-gradient(to bottom, black, transparent 92%);
    animation: mv6GridMove 24s linear infinite;
}

.modern-v6 .modern-bg-glow {
    position: absolute;
    width: 520px;
    height: 520px;
    border-radius: 50%;
    filter: blur(100px);
    opacity: .16;
    animation: mv6GlowFloat 13s ease-in-out infinite alternate;
}

.modern-v6 .modern-glow-red {
    top: 100px;
    left: -250px;
    background: var(--mv-red);
}

.modern-v6 .modern-glow-purple {
    top: 400px;
    right: -280px;
    background: var(--mv-purple);
    animation-delay: -5s;
}

.modern-v6 .modern-glow-blue {
    top: 1300px;
    left: 35%;
    background: #4338ca;
    animation-delay: -8s;
}

.modern-v6 .modern-bg-ring {
    position: absolute;
    border: 1px solid rgba(192, 132, 252, .15);
    border-radius: 50%;
    animation: mv6RingSpin 40s linear infinite;
}

.modern-v6 .modern-ring-one {
    width: 500px;
    height: 500px;
    top: 90px;
    right: -250px;
}

.modern-v6 .modern-ring-two {
    width: 340px;
    height: 340px;
    top: 170px;
    right: -170px;
    border-color: rgba(225, 29, 72, .18);
    animation-direction: reverse;
    animation-duration: 30s;
}

.modern-v6 .modern-ring-three {
    width: 600px;
    height: 600px;
    top: 1000px;
    left: -350px;
    animation-duration: 55s;
}

.modern-v6 .modern-bg-scanline {
    position: absolute;
    left: 0;
    right: 0;
    top: -20%;
    height: 150px;
    background: linear-gradient(transparent, rgba(168, 85, 247, .045), transparent);
    animation: mv6Scan 12s linear infinite;
}

.modern-v6 .modern-bg-stars {
    position: absolute;
    inset: 0;
    opacity: .45;
    background-image:
        radial-gradient(circle, #fff 1px, transparent 1.5px),
        radial-gradient(circle, #c084fc 1px, transparent 1.5px);
    background-size: 130px 130px, 190px 190px;
    background-position: 0 0, 65px 75px;
    animation: mv6Stars 35s linear infinite;
}

@keyframes mv6GridMove {
    to { background-position: 55px 55px; }
}

@keyframes mv6GlowFloat {
    from { transform: translate3d(0, 0, 0) scale(.9); }
    to { transform: translate3d(50px, -35px, 0) scale(1.12); }
}

@keyframes mv6RingSpin {
    to { transform: rotate(360deg); }
}

@keyframes mv6Scan {
    from { transform: translateY(-20%); }
    to { transform: translateY(900%); }
}

@keyframes mv6Stars {
    from { background-position: 0 0, 65px 75px; }
    to { background-position: 0 500px, 65px 575px; }
}

@keyframes mv6Pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(74, 222, 128, .25); }
    50% { box-shadow: 0 0 0 6px rgba(74, 222, 128, 0); }
}

@keyframes mv6Float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes mv6BorderGlow {
    0%, 100% { border-color: rgba(168, 85, 247, .3); }
    50% { border-color: rgba(225, 29, 72, .55); }
}

@keyframes mv6Gradient {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

/* NAVIGATION */

.modern-v6 .mv6-header {
    position: relative;
    z-index: 10;
    width: min(1240px, calc(100% - 64px));
    margin: auto;
    min-height: 86px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;
    border-bottom: 1px solid var(--mv-line);
}

.modern-v6 .mv6-logo {
    display: inline-flex;
    align-items: center;
    gap: 11px;
    font-size: 13px;
    font-weight: 850;
    letter-spacing: 1.5px;
    white-space: nowrap;
}

.modern-v6 .mv6-logo-icon {
    display: grid;
    place-items: center;
    width: 37px;
    height: 37px;
    border: 1px solid rgba(192, 132, 252, .55);
    border-radius: 10px;
    color: var(--mv-purple-light);
    background: rgba(168, 85, 247, .08);
    font-size: 15px;
    box-shadow: 0 0 24px rgba(168, 85, 247, .1);
}

.modern-v6 .mv6-accent {
    color: var(--mv-red-light);
}

.modern-v6 .mv6-nav {
    display: flex;
    align-items: center;
    gap: clamp(16px, 2.6vw, 35px);
}

.modern-v6 .mv6-nav a {
    color: #b9b3c7;
    font-size: 12px;
    transition: color .25s ease;
}

.modern-v6 .mv6-nav a:hover {
    color: white;
}

.modern-v6 .mv6-nav-button {
    display: inline-flex;
    align-items: center;
    gap: 16px;
    padding: 11px 15px;
    border: 1px solid rgba(192, 132, 252, .35);
    border-radius: 7px;
    font-size: 12px;
    transition: background .25s ease, border-color .25s ease;
}

.modern-v6 .mv6-nav-button span {
    color: var(--mv-red-light);
}

.modern-v6 .mv6-nav-button:hover {
    background: rgba(168, 85, 247, .12);
    border-color: var(--mv-purple);
}

/* HERO */

.modern-v6 .mv6-hero {
    position: relative;
    width: min(1240px, calc(100% - 64px));
    margin: auto;
    min-height: 660px;
    display: grid;
    grid-template-columns: 1.05fr .95fr;
    align-items: center;
    gap: 55px;
    padding: 70px 0 60px;
}

.modern-v6 .mv6-hero-copy {
    position: relative;
    z-index: 2;
    min-width: 0;
}

.modern-v6 .mv6-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border: 1px solid rgba(192, 132, 252, .22);
    border-radius: 5px;
    background: rgba(168, 85, 247, .045);
    color: #c7bdd9;
    font-size: 9px;
    letter-spacing: 1.7px;
}

.modern-v6 .mv6-status-dot,
.modern-v6 .mv6-contact-availability i {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #4ade80;
    animation: mv6Pulse 2s infinite;
}

.modern-v6 .mv6-overline {
    margin: 43px 0 6px;
    color: var(--mv-purple-light);
    font-size: 12px;
    font-weight: 750;
    letter-spacing: 4px;
}

.modern-v6 .mv6-hero h1 {
    max-width: 700px;
    margin: 0;
    font-size: clamp(44px, 6.1vw, 82px);
    font-weight: 900;
    line-height: .99;
    letter-spacing: -4px;
    overflow-wrap: anywhere;
}

.modern-v6 .mv6-name-period {
    color: var(--mv-red);
}

.modern-v6 .mv6-role-line {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    margin-top: 24px;
    color: #d7cde7;
    font-size: 10px;
    font-weight: 750;
    letter-spacing: 1.6px;
}

.modern-v6 .mv6-role-symbol {
    color: var(--mv-red-light);
    font-size: 17px;
}

.modern-v6 .mv6-role-divider {
    color: var(--mv-purple);
}

.modern-v6 .mv6-hero-description {
    max-width: 510px;
    margin: 27px 0 0;
    padding-left: 17px;
    border-left: 2px solid rgba(192, 132, 252, .65);
    color: var(--mv-muted);
    font-size: 14px;
    line-height: 1.95;
    overflow-wrap: anywhere;
}

.modern-v6 .mv6-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 13px;
    margin-top: 30px;
}

.modern-v6 .mv6-button-primary,
.modern-v6 .mv6-button-secondary {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    min-height: 47px;
    padding: 0 19px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 750;
    transition: transform .25s ease, box-shadow .25s ease, background .25s ease;
}

.modern-v6 .mv6-button-primary {
    background: linear-gradient(115deg, #e11d48, #a21caf);
    box-shadow: 0 8px 25px rgba(225, 29, 72, .16);
}

.modern-v6 .mv6-button-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(225, 29, 72, .28);
}

.modern-v6 .mv6-button-secondary {
    border: 1px solid var(--mv-line);
    background: rgba(255, 255, 255, .025);
}

.modern-v6 .mv6-button-secondary:hover {
    background: rgba(168, 85, 247, .1);
    transform: translateY(-3px);
}

.modern-v6 .mv6-hero-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    max-width: 510px;
    margin-top: 65px;
    color: #777184;
    font-size: 9px;
    letter-spacing: 1.5px;
}

.modern-v6 .mv6-scroll-indicator {
    display: flex;
    align-items: center;
    gap: 12px;
}

.modern-v6 .mv6-scroll-line {
    width: 34px;
    height: 1px;
    background: var(--mv-red);
}

.modern-v6 .mv6-hero-index {
    color: #d6cce4;
}

.modern-v6 .mv6-hero-index span {
    margin: 0 5px;
    color: var(--mv-purple);
}

/* PROFILE VISUAL */

.modern-v6 .mv6-visual {
    position: relative;
    display: grid;
    place-items: center;
    min-height: 490px;
    isolation: isolate;
}

.modern-v6 .mv6-visual-label {
    position: absolute;
    left: 0;
    right: 0;
    display: flex;
    justify-content: space-between;
    gap: 12px;
    color: #81798e;
    font-size: 8px;
    letter-spacing: 1.7px;
}

.modern-v6 .mv6-label-top {
    top: 0;
}

.modern-v6 .mv6-label-bottom {
    bottom: 0;
}

.modern-v6 .mv6-orbit {
    position: absolute;
    top: 50%;
    left: 50%;
    border: 1px solid rgba(192, 132, 252, .19);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    pointer-events: none;
}

.modern-v6 .mv6-orbit-outer {
    width: min(450px, 95%);
    aspect-ratio: 1;
    animation: mv6RingSpin 45s linear infinite;
}

.modern-v6 .mv6-orbit-middle {
    width: min(375px, 80%);
    aspect-ratio: 1;
    border-style: dashed;
    border-color: rgba(225, 29, 72, .28);
    animation: mv6RingSpin 35s linear infinite reverse;
}

.modern-v6 .mv6-orbit-inner {
    width: min(300px, 66%);
    aspect-ratio: 1;
    border-color: rgba(96, 165, 250, .16);
}

.modern-v6 .mv6-orbit-node {
    position: absolute;
    z-index: 3;
    width: 11px;
    height: 11px;
    border: 2px solid var(--mv-bg);
    border-radius: 50%;
    background: var(--mv-purple-light);
    box-shadow: 0 0 18px rgba(192, 132, 252, .65);
}

.modern-v6 .mv6-node-one {
    top: 18%;
    left: 24%;
}

.modern-v6 .mv6-node-two {
    top: 64%;
    right: 5%;
    background: var(--mv-red-light);
    box-shadow: 0 0 18px rgba(251, 113, 133, .65);
}

.modern-v6 .mv6-node-three {
    bottom: 15%;
    left: 23%;
    width: 7px;
    height: 7px;
    background: var(--mv-blue);
}

.modern-v6 .mv6-profile-card {
    position: relative;
    z-index: 2;
    width: min(335px, 84%);
    overflow: hidden;
    border: 1px solid rgba(192, 132, 252, .35);
    border-radius: 13px;
    background: rgba(13, 10, 21, .94);
    box-shadow: 0 25px 90px rgba(0, 0, 0, .42), 0 0 55px rgba(168, 85, 247, .1);
    animation: mv6BorderGlow 7s ease-in-out infinite;
}

.modern-v6 .mv6-card-top {
    display: flex;
    align-items: center;
    gap: 5px;
    height: 42px;
    padding: 0 14px;
    border-bottom: 1px solid var(--mv-line);
    background: rgba(255, 255, 255, .025);
}

.modern-v6 .mv6-card-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #e11d48;
}

.modern-v6 .mv6-card-dot:nth-child(2) {
    background: #a855f7;
}

.modern-v6 .mv6-card-dot:nth-child(3) {
    background: #60a5fa;
}

.modern-v6 .mv6-card-file {
    margin-left: 9px;
    color: #a59bb5;
    font-size: 9px;
}

.modern-v6 .mv6-card-menu {
    margin-left: auto;
    color: #777184;
    letter-spacing: 2px;
}

.modern-v6 .mv6-card-content {
    padding: 27px 25px 18px;
}

.modern-v6 .mv6-profile-image-wrap {
    position: relative;
    display: grid;
    place-items: center;
    width: 112px;
    height: 112px;
    margin: 0 auto 18px;
    padding: 5px;
    border: 1px solid rgba(192, 132, 252, .55);
    border-radius: 50%;
    background: linear-gradient(140deg, rgba(225, 29, 72, .2), rgba(168, 85, 247, .2));
    box-shadow: 0 0 30px rgba(168, 85, 247, .12);
}

.modern-v6 .mv6-profile-image,
.modern-v6 .mv6-profile-initials {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.modern-v6 .mv6-profile-initials {
    display: grid;
    place-items: center;
    background: linear-gradient(145deg, #21112e, #351021);
    color: white;
    font-size: 34px;
    font-weight: 900;
}

.modern-v6 .mv6-image-corner {
    position: absolute;
    width: 12px;
    height: 12px;
    border-color: var(--mv-red-light);
    border-style: solid;
}

.modern-v6 .mv6-corner-tl {
    top: -3px;
    left: 16px;
    border-width: 2px 0 0 2px;
}

.modern-v6 .mv6-corner-tr {
    top: -3px;
    right: 16px;
    border-width: 2px 2px 0 0;
}

.modern-v6 .mv6-corner-bl {
    bottom: -3px;
    left: 16px;
    border-width: 0 0 2px 2px;
}

.modern-v6 .mv6-corner-br {
    bottom: -3px;
    right: 16px;
    border-width: 0 2px 2px 0;
}

.modern-v6 .mv6-profile-identity {
    text-align: center;
}

.modern-v6 .mv6-mini-label {
    color: var(--mv-purple-light);
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 2px;
}

.modern-v6 .mv6-profile-identity h2 {
    margin: 6px 0 2px;
    font-size: 22px;
    line-height: 1.35;
    overflow-wrap: anywhere;
}

.modern-v6 .mv6-profile-identity p {
    margin: 0;
    color: var(--mv-muted);
    font-size: 11px;
}

.modern-v6 .mv6-profile-divider {
    height: 1px;
    margin: 22px 0 17px;
    background: var(--mv-line);
}

.modern-v6 .mv6-code-line {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
    margin: 10px 0;
    font-family: Consolas, monospace;
    font-size: 10px;
}

.modern-v6 .mv6-code-number {
    width: 18px;
    color: #655c72;
}

.modern-v6 .mv6-code-key {
    color: var(--mv-purple-light);
}

.modern-v6 .mv6-code-variable {
    color: #f1e8ff;
}

.modern-v6 .mv6-code-symbol {
    color: #7d748b;
}

.modern-v6 .mv6-code-value {
    color: #86efac;
}

.modern-v6 .mv6-code-comment {
    color: #8b8199;
}

.modern-v6 .mv6-card-footer {
    display: flex;
    justify-content: space-between;
    gap: 8px;
    margin-top: 22px;
    padding-top: 14px;
    border-top: 1px solid var(--mv-line);
    color: #8d859a;
    font-size: 7px;
    letter-spacing: .7px;
}

.modern-v6 .mv6-card-footer span:first-child {
    display: flex;
    align-items: center;
    gap: 7px;
}

.modern-v6 .mv6-card-footer i {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #4ade80;
}

.modern-v6 .mv6-float-badge {
    position: absolute;
    z-index: 4;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 13px;
    border: 1px solid rgba(192, 132, 252, .28);
    border-radius: 8px;
    background: rgba(14, 10, 23, .94);
    box-shadow: 0 10px 35px rgba(0, 0, 0, .28);
    animation: mv6Float 5s ease-in-out infinite;
}

.modern-v6 .mv6-badge-code {
    top: 24%;
    right: -3%;
}

.modern-v6 .mv6-badge-creative {
    bottom: 20%;
    left: -5%;
    animation-delay: -2.5s;
}

.modern-v6 .mv6-badge-icon {
    display: grid;
    place-items: center;
    width: 31px;
    height: 31px;
    border-radius: 6px;
    background: rgba(225, 29, 72, .12);
    color: var(--mv-red-light);
    font-size: 12px;
}

.modern-v6 .mv6-creative-star {
    color: var(--mv-purple-light);
    font-size: 27px;
}

.modern-v6 .mv6-float-badge b,
.modern-v6 .mv6-float-badge small {
    display: block;
}

.modern-v6 .mv6-float-badge b {
    font-size: 9px;
    letter-spacing: 1px;
}

.modern-v6 .mv6-float-badge small {
    margin-top: 2px;
    color: #958ba4;
    font-size: 7px;
    letter-spacing: .8px;
}

/* METRICS */

.modern-v6 .mv6-metrics {
    position: relative;
    display: grid;
    grid-template-columns: repeat(3, 1fr) 1.2fr;
    width: min(1240px, calc(100% - 64px));
    margin: 0 auto;
    padding: 26px 0;
    border-top: 1px solid var(--mv-line);
    border-bottom: 1px solid var(--mv-line);
}

.modern-v6 .mv6-metric {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 0 26px;
    border-right: 1px solid var(--mv-line);
}

.modern-v6 .mv6-metric:first-child {
    padding-left: 0;
}

.modern-v6 .mv6-metric-number {
    color: white;
    font-size: 34px;
    font-weight: 850;
    line-height: 1.2;
    letter-spacing: -1px;
}

.modern-v6 .mv6-metric-label {
    color: #958ba4;
    font-size: 9px;
    letter-spacing: 1.7px;
}

.modern-v6 .mv6-metric-line {
    width: 28px;
    height: 2px;
    margin-top: 8px;
    background: linear-gradient(90deg, var(--mv-red), var(--mv-purple));
}

.modern-v6 .mv6-metric-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    color: #b5aabe;
    font-size: 11px;
    line-height: 1.7;
}

.modern-v6 .mv6-note-symbol {
    color: var(--mv-purple-light);
    font-size: 27px;
}

/* SHARED SECTIONS */

.modern-v6 .mv6-section {
    position: relative;
    display: grid;
    grid-template-columns: 180px minmax(0, 1fr);
    gap: 55px;
    width: min(1240px, calc(100% - 64px));
    margin: auto;
    padding: 110px 0;
    border-bottom: 1px solid var(--mv-line);
    scroll-margin-top: 25px;
}

.modern-v6 .mv6-section-aside {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 18px;
    padding-top: 10px;
}

.modern-v6 .mv6-section-number {
    color: var(--mv-red-light);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.modern-v6 .mv6-aside-line {
    width: 37px;
    height: 1px;
    background: var(--mv-purple);
}

.modern-v6 .mv6-aside-caption {
    max-width: 130px;
    color: #81798e;
    font-size: 8px;
    line-height: 1.9;
    letter-spacing: 1.2px;
}

.modern-v6 .mv6-section-main {
    min-width: 0;
}

.modern-v6 .mv6-section-heading {
    margin-bottom: 38px;
}

.modern-v6 .mv6-kicker {
    color: var(--mv-purple-light);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 2.6px;
}

.modern-v6 .mv6-section-heading h2 {
    margin: 10px 0 0;
    font-size: clamp(32px, 4vw, 51px);
    font-weight: 850;
    line-height: 1.12;
    letter-spacing: -2px;
    overflow-wrap: anywhere;
}

.modern-v6 .mv6-outline-text {
    color: transparent;
    -webkit-text-stroke: 1px rgba(224, 212, 244, .75);
}

.modern-v6 .mv6-heading-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 18px;
}

.modern-v6 .mv6-heading-row .mv6-section-heading {
    margin-bottom: 38px;
}

.modern-v6 .mv6-heading-count {
    flex-shrink: 0;
    margin-bottom: 47px;
    color: #80768e;
    font-size: 8px;
    letter-spacing: 1px;
}

/* ABOUT */

.modern-v6 .mv6-about-grid {
    display: grid;
    grid-template-columns: 1fr .9fr;
    gap: 24px;
}

.modern-v6 .mv6-about-statement {
    position: relative;
    min-height: 300px;
    padding: 32px;
    overflow: hidden;
    border: 1px solid rgba(192, 132, 252, .2);
    border-radius: 9px;
    background:
        radial-gradient(ellipse at 0 0, rgba(168, 85, 247, .13), transparent 55%),
        rgba(255, 255, 255, .018);
}

.modern-v6 .mv6-quote-mark {
    display: block;
    height: 54px;
    color: var(--mv-red-light);
    font-family: Georgia, serif;
    font-size: 75px;
    line-height: 1;
}

.modern-v6 .mv6-about-statement p {
    position: relative;
    z-index: 1;
    margin: 15px 0 30px;
    color: #d1c8dc;
    font-size: 15px;
    line-height: 1.95;
    overflow-wrap: anywhere;
}

.modern-v6 .mv6-statement-line {
    width: 52px;
    height: 2px;
    background: linear-gradient(90deg, var(--mv-red), var(--mv-purple));
}

.modern-v6 .mv6-statement-caption {
    display: block;
    margin-top: 13px;
    color: #81798e;
    font-size: 8px;
    letter-spacing: 1.1px;
}

.modern-v6 .mv6-about-details {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 12px;
}

.modern-v6 .mv6-detail-block {
    display: grid;
    grid-template-columns: 34px minmax(0, 1fr);
    gap: 15px;
    padding: 20px 18px;
    border: 1px solid var(--mv-line);
    border-radius: 8px;
    background: rgba(255, 255, 255, .018);
    transition: border-color .25s ease, transform .25s ease;
}

.modern-v6 .mv6-detail-block:hover {
    transform: translateX(4px);
    border-color: rgba(192, 132, 252, .4);
}

.modern-v6 .mv6-detail-number {
    color: var(--mv-red-light);
    font-size: 11px;
    font-weight: 800;
}

.modern-v6 .mv6-detail-block h3 {
    margin: 0 0 5px;
    font-size: 14px;
}

.modern-v6 .mv6-detail-block p {
    margin: 0;
    color: var(--mv-muted);
    font-size: 11px;
    line-height: 1.8;
}

/* SKILLS */

.modern-v6 .mv6-skills-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
}

.modern-v6 .mv6-skill-card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-height: 190px;
    padding: 19px;
    overflow: hidden;
    border: 1px solid var(--mv-line);
    border-radius: 9px;
    background: linear-gradient(145deg, rgba(255, 255, 255, .045), rgba(255, 255, 255, .008));
    transition: transform .25s ease, border-color .25s ease, background .25s ease;
}

.modern-v6 .mv6-skill-card::before {
    content: "";
    position: absolute;
    top: -40px;
    right: -40px;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: var(--mv-purple);
    filter: blur(55px);
    opacity: .15;
    transition: opacity .25s ease;
}

.modern-v6 .mv6-skill-card:hover {
    transform: translateY(-5px);
    border-color: rgba(192, 132, 252, .5);
    background: rgba(168, 85, 247, .045);
}

.modern-v6 .mv6-skill-card:hover::before {
    opacity: .38;
}

.modern-v6 .mv6-skill-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #8b8199;
    font-size: 9px;
}

.modern-v6 .mv6-skill-arrow {
    color: var(--mv-purple-light);
    font-size: 14px;
}

.modern-v6 .mv6-skill-symbol {
    display: grid;
    place-items: center;
    width: 38px;
    height: 38px;
    margin-top: 23px;
    border: 1px solid rgba(192, 132, 252, .25);
    border-radius: 9px;
    background: rgba(168, 85, 247, .08);
    color: #e3caff;
    font-size: 15px;
    font-weight: 850;
}

.modern-v6 .mv6-skill-card:nth-child(3n + 2) .mv6-skill-symbol {
    border-color: rgba(251, 113, 133, .25);
    background: rgba(225, 29, 72, .08);
    color: var(--mv-red-light);
}

.modern-v6 .mv6-skill-card h3 {
    position: relative;
    margin: 12px 0 18px;
    font-size: 16px;
    font-weight: 750;
    overflow-wrap: anywhere;
}

.modern-v6 .mv6-skill-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    margin-top: auto;
    padding-top: 12px;
    border-top: 1px solid rgba(255, 255, 255, .07);
    color: #82798e;
    font-size: 7px;
    letter-spacing: 1px;
}

.modern-v6 .mv6-skill-indicator {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--mv-purple-light);
}

/* PROJECTS */

.modern-v6 .mv6-project-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.modern-v6 .mv6-project-card {
    min-width: 0;
    overflow: hidden;
    border: 1px solid var(--mv-line);
    border-radius: 10px;
    background: rgba(15, 12, 23, .8);
    transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease;
}

.modern-v6 .mv6-project-card:hover {
    transform: translateY(-5px);
    border-color: rgba(192, 132, 252, .42);
    box-shadow: 0 18px 40px rgba(0, 0, 0, .2);
}

.modern-v6 .mv6-project-art {
    position: relative;
    display: grid;
    place-items: center;
    min-height: 235px;
    overflow: hidden;
    border-bottom: 1px solid var(--mv-line);
    background: radial-gradient(ellipse at center, #2c1538 0%, #130d20 55%, #0d0a14 100%);
}

.modern-v6 .mv6-project-art-1 {
    background: radial-gradient(ellipse at center, #30132a 0%, #180d20 60%, #0d0a14 100%);
}

.modern-v6 .mv6-project-art-2 {
    background: radial-gradient(ellipse at center, #19294a 0%, #130e25 58%, #0d0a14 100%);
}

.modern-v6 .mv6-project-art-3 {
    background: radial-gradient(ellipse at center, #342018 0%, #1b1022 58%, #0d0a14 100%);
}

.modern-v6 .mv6-art-grid {
    position: absolute;
    inset: 0;
    opacity: .38;
    background-image:
        linear-gradient(rgba(192, 132, 252, .15) 1px, transparent 1px),
        linear-gradient(90deg, rgba(192, 132, 252, .15) 1px, transparent 1px);
    background-size: 28px 28px;
    mask-image: radial-gradient(ellipse at center, black, transparent 85%);
}

.modern-v6 .mv6-art-number {
    position: absolute;
    top: 15px;
    left: 17px;
    color: rgba(255, 255, 255, .55);
    font-family: Consolas, monospace;
    font-size: 10px;
}

.modern-v6 .mv6-art-category {
    position: absolute;
    right: 14px;
    bottom: 13px;
    color: rgba(255, 255, 255, .65);
    font-size: 7px;
    letter-spacing: 1.4px;
}

.modern-v6 .mv6-art-visual {
    position: relative;
    z-index: 1;
}

.modern-v6 .mv6-art-browser {
    width: 190px;
    overflow: hidden;
    border: 1px solid rgba(192, 132, 252, .5);
    border-radius: 7px;
    background: rgba(12, 9, 20, .92);
    box-shadow: 0 15px 45px rgba(0, 0, 0, .35);
    transform: rotate(-4deg);
    transition: transform .3s ease;
}

.modern-v6 .mv6-project-card:hover .mv6-art-browser {
    transform: rotate(0) translateY(-3px);
}

.modern-v6 .mv6-browser-top {
    display: flex;
    gap: 5px;
    padding: 9px;
    border-bottom: 1px solid var(--mv-line);
}

.modern-v6 .mv6-browser-top i {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: var(--mv-red-light);
}

.modern-v6 .mv6-browser-top i:nth-child(2) {
    background: var(--mv-purple-light);
}

.modern-v6 .mv6-browser-top i:nth-child(3) {
    background: var(--mv-blue);
}

.modern-v6 .mv6-browser-content {
    padding: 18px;
}

.modern-v6 .mv6-art-line {
    display: block;
    height: 6px;
    margin-bottom: 8px;
    border-radius: 5px;
    background: linear-gradient(90deg, var(--mv-purple), rgba(168, 85, 247, .1));
}

.modern-v6 .mv6-art-line.art-line-wide {
    width: 85%;
}

.modern-v6 .mv6-art-line.art-line-short {
    width: 50%;
    opacity: .5;
}

.modern-v6 .mv6-art-blocks {
    display: flex;
    gap: 7px;
    margin-top: 17px;
}

.modern-v6 .mv6-art-blocks i {
    flex: 1;
    height: 35px;
    border: 1px solid rgba(192, 132, 252, .28);
    border-radius: 4px;
    background: rgba(168, 85, 247, .08);
}

.modern-v6 .mv6-art-orbit {
    display: grid;
    place-items: center;
    width: 145px;
    height: 145px;
}

.modern-v6 .mv6-art-orbit-ring {
    position: absolute;
    inset: 0;
    border: 1px solid rgba(251, 113, 133, .7);
    border-radius: 50%;
    transform: rotateX(60deg) rotate(-35deg);
    animation: mv6RingSpin 12s linear infinite;
}

.modern-v6 .mv6-art-orbit-ring.ring-small {
    inset: 20px;
    border-color: rgba(192, 132, 252, .7);
    transform: rotateX(60deg) rotate(35deg);
    animation-direction: reverse;
}

.modern-v6 .mv6-art-orbit-core {
    display: grid;
    place-items: center;
    width: 50px;
    height: 50px;
    border: 1px solid rgba(251, 113, 133, .5);
    border-radius: 50%;
    background: rgba(225, 29, 72, .15);
    color: #fecdd3;
    font-size: 24px;
    box-shadow: 0 0 30px rgba(225, 29, 72, .18);
}

.modern-v6 .mv6-art-cube {
    position: relative;
    width: 100px;
    height: 100px;
    transform: rotate(-28deg) skew(5deg, 5deg);
    transition: transform .35s ease;
}

.modern-v6 .mv6-project-card:hover .mv6-art-cube {
    transform: rotate(-18deg) skew(0, 0) translateY(-5px);
}

.modern-v6 .mv6-cube-face {
    position: absolute;
    width: 64px;
    height: 64px;
    border: 1px solid rgba(147, 197, 253, .7);
    background: rgba(96, 165, 250, .12);
}

.modern-v6 .mv6-cube-face.cube-front {
    left: 18px;
    top: 25px;
}

.modern-v6 .mv6-cube-face.cube-side {
    left: 43px;
    top: 0;
    transform: skewY(-35deg);
    background: rgba(168, 85, 247, .12);
}

.modern-v6 .mv6-cube-face.cube-top {
    left: -7px;
    top: 0;
    transform: skewY(35deg);
    background: rgba(96, 165, 250, .16);
}

.modern-v6 .mv6-art-terminal {
    display: flex;
    flex-direction: column;
    gap: 14px;
    max-width: 100%;
    padding: 23px;
    border-left: 2px solid var(--mv-red-light);
    background: rgba(8, 7, 13, .8);
    color: #86efac;
    font-family: Consolas, monospace;
    font-size: 12px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, .3);
}

.modern-v6 .mv6-art-terminal .mv6-terminal-line {
    color: #a99eb8;
    font-size: 8px;
    letter-spacing: 1px;
}

.modern-v6 .mv6-art-terminal .mv6-terminal-cursor {
    color: var(--mv-red-light);
    animation: mv6Pulse 1.5s infinite;
}

.modern-v6 .mv6-project-content {
    padding: 23px;
}

.modern-v6 .mv6-project-meta {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    color: #93899f;
    font-size: 8px;
    letter-spacing: 1px;
}

.modern-v6 .mv6-project-meta span:first-child {
    color: var(--mv-purple-light);
}

.modern-v6 .mv6-project-content h3 {
    margin: 17px 0 8px;
    font-size: 21px;
    line-height: 1.35;
    letter-spacing: -.5px;
    overflow-wrap: anywhere;
}

.modern-v6 .mv6-project-content > p {
    min-height: 44px;
    margin: 0;
    color: var(--mv-muted);
    font-size: 11px;
    line-height: 1.9;
    overflow-wrap: anywhere;
}

.modern-v6 .mv6-project-tech {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 17px;
}

.modern-v6 .mv6-project-tech span {
    padding: 5px 9px;
    border: 1px solid rgba(192, 132, 252, .18);
    border-radius: 4px;
    background: rgba(168, 85, 247, .06);
    color: #c6b6da;
    font-size: 9px;
}

.modern-v6 .mv6-project-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 20px;
    padding-top: 14px;
    border-top: 1px solid var(--mv-line);
    color: #8a8197;
    font-size: 8px;
    letter-spacing: .8px;
}

.modern-v6 .mv6-project-footer a,
.modern-v6 .mv6-project-arrow {
    display: grid;
    place-items: center;
    width: 30px;
    height: 30px;
    border: 1px solid rgba(192, 132, 252, .3);
    border-radius: 50%;
    color: var(--mv-purple-light);
    font-size: 15px;
}

.modern-v6 .mv6-project-footer a {
    transition: background .25s ease, color .25s ease;
}

.modern-v6 .mv6-project-footer a:hover {
    color: white;
    background: var(--mv-red);
}

/* JOURNEY */

.modern-v6 .mv6-journey-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.modern-v6 .mv6-journey-column {
    min-width: 0;
    padding: 22px;
    border: 1px solid var(--mv-line);
    border-radius: 9px;
    background: rgba(255, 255, 255, .018);
}

.modern-v6 .mv6-journey-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--mv-line);
}

.modern-v6 .mv6-journey-icon {
    display: grid;
    place-items: center;
    width: 36px;
    height: 36px;
    border: 1px solid rgba(192, 132, 252, .3);
    border-radius: 7px;
    background: rgba(168, 85, 247, .08);
    color: var(--mv-purple-light);
    font-size: 18px;
}

.modern-v6 .mv6-journey-heading h3 {
    margin: 0;
    font-size: 11px;
    letter-spacing: 1.3px;
}

.modern-v6 .mv6-journey-count {
    display: grid;
    place-items: center;
    min-width: 25px;
    height: 25px;
    margin-left: auto;
    border-radius: 5px;
    background: rgba(225, 29, 72, .1);
    color: var(--mv-red-light);
    font-size: 10px;
}

.modern-v6 .mv6-timeline {
    position: relative;
    margin-top: 23px;
    padding-left: 21px;
}

.modern-v6 .mv6-timeline::before {
    content: "";
    position: absolute;
    top: 7px;
    bottom: 8px;
    left: 4px;
    width: 1px;
    background: linear-gradient(var(--mv-purple), rgba(168, 85, 247, .05));
}

.modern-v6 .mv6-timeline-card {
    position: relative;
    padding: 0 0 25px 12px;
    overflow-wrap: anywhere;
}

.modern-v6 .mv6-timeline-card:last-child {
    padding-bottom: 0;
}

.modern-v6 .mv6-timeline-dot {
    position: absolute;
    top: 4px;
    left: -21px;
    width: 9px;
    height: 9px;
    border: 2px solid var(--mv-purple-light);
    border-radius: 50%;
    background: var(--mv-bg);
    box-shadow: 0 0 12px rgba(192, 132, 252, .3);
}

.modern-v6 .mv6-timeline-date {
    display: inline-block;
    margin-bottom: 7px;
    color: var(--mv-red-light);
    font-size: 9px;
    letter-spacing: .7px;
}

.modern-v6 .mv6-timeline-card h4 {
    margin: 0;
    font-size: 14px;
    line-height: 1.6;
}

.modern-v6 .mv6-timeline-place {
    margin: 4px 0 0;
    color: var(--mv-purple-light);
    font-size: 11px;
}

.modern-v6 .mv6-timeline-description {
    margin: 8px 0 0;
    color: var(--mv-muted);
    font-size: 10px;
    line-height: 1.8;
}

.modern-v6 .mv6-journey-empty {
    margin-top: 20px;
    padding: 18px;
    border: 1px dashed rgba(192, 132, 252, .2);
    border-radius: 6px;
    color: var(--mv-muted);
    font-size: 11px;
}

/* CONTACT */

.modern-v6 .mv6-contact-section {
    position: relative;
    width: min(1240px, calc(100% - 64px));
    margin: 80px auto 0;
    padding: 40px;
    overflow: hidden;
    border: 1px solid rgba(192, 132, 252, .25);
    border-radius: 11px;
    background: linear-gradient(125deg, rgba(225, 29, 72, .07), rgba(168, 85, 247, .08) 48%, rgba(255, 255, 255, .018));
    scroll-margin-top: 25px;
}

.modern-v6 .mv6-contact-glow {
    position: absolute;
    top: -150px;
    left: 20%;
    width: 350px;
    height: 250px;
    border-radius: 50%;
    background: var(--mv-purple);
    filter: blur(130px);
    opacity: .15;
    pointer-events: none;
}

.modern-v6 .mv6-contact-top {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--mv-line);
}

.modern-v6 .mv6-contact-availability {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    color: #b6aabe;
    font-size: 8px;
    letter-spacing: 1px;
}

.modern-v6 .mv6-contact-availability i {
    display: inline-block;
}

.modern-v6 .mv6-contact-body {
    position: relative;
    display: grid;
    grid-template-columns: 1.1fr .9fr;
    gap: 45px;
    padding: 40px 0;
}

.modern-v6 .mv6-contact-body h2 {
    margin: 0;
    font-size: clamp(33px, 4.5vw, 56px);
    line-height: 1.1;
    letter-spacing: -2px;
    overflow-wrap: anywhere;
}

.modern-v6 .mv6-contact-body h2 span {
    color: transparent;
    background: linear-gradient(90deg, var(--mv-red-light), var(--mv-purple-light));
    background-size: 200% 200%;
    -webkit-background-clip: text;
    background-clip: text;
    animation: mv6Gradient 7s ease infinite;
}

.modern-v6 .mv6-contact-body > div:first-child > p {
    max-width: 380px;
    margin-top: 20px;
    color: var(--mv-muted);
    font-size: 12px;
    line-height: 1.9;
}

.modern-v6 .mv6-contact-links {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.modern-v6 .mv6-contact-link {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
    padding: 13px;
    border: 1px solid var(--mv-line);
    border-radius: 6px;
    background: rgba(8, 7, 13, .25);
    transition: border-color .25s ease, background .25s ease;
}

.modern-v6 .mv6-contact-link:hover {
    border-color: rgba(192, 132, 252, .4);
    background: rgba(168, 85, 247, .055);
}

.modern-v6 .mv6-contact-link-icon {
    display: grid;
    place-items: center;
    flex-shrink: 0;
    width: 35px;
    height: 35px;
    border: 1px solid rgba(192, 132, 252, .25);
    border-radius: 6px;
    color: var(--mv-purple-light);
    font-size: 16px;
}

.modern-v6 .mv6-contact-link > span:nth-child(2) {
    display: flex;
    flex-direction: column;
    min-width: 0;
    gap: 4px;
}

.modern-v6 .mv6-contact-link small {
    color: #958ba4;
    font-size: 7px;
    letter-spacing: 1.2px;
}

.modern-v6 .mv6-contact-link b {
    color: #e6dfef;
    font-size: 10px;
    font-weight: 600;
    overflow-wrap: anywhere;
}

.modern-v6 .mv6-contact-link-arrow {
    margin-left: auto;
    color: var(--mv-red-light);
}

.modern-v6 .mv6-contact-bottom {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    padding-top: 20px;
    border-top: 1px solid var(--mv-line);
    color: #8e849c;
    font-size: 8px;
    letter-spacing: 1px;
}

.modern-v6 .mv6-contact-bottom a {
    color: var(--mv-purple-light);
}

/* FOOTER */

.modern-v6 .mv6-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 18px;
    width: min(1240px, calc(100% - 64px));
    margin: auto;
    padding: 35px 0;
}

.modern-v6 .mv6-footer p {
    margin: 0;
    color: #8e849c;
    font-size: 10px;
}

.modern-v6 .mv6-footer-copy {
    color: #8e849c;
    font-size: 9px;
}

/* EMPTY STATES */

.modern-v6 .mv6-empty-state {
    display: flex;
    align-items: center;
    gap: 17px;
    padding: 25px;
    border: 1px dashed rgba(192, 132, 252, .25);
    border-radius: 8px;
    background: rgba(168, 85, 247, .025);
}

.modern-v6 .mv6-empty-state > span {
    color: var(--mv-purple-light);
    font-size: 25px;
}

.modern-v6 .mv6-empty-state p {
    margin: 0;
    color: var(--mv-muted);
    font-size: 12px;
}

/* RESPONSIVE */

@media (max-width: 1050px) {
    .modern-v6 .mv6-hero {
        gap: 25px;
    }

    .modern-v6 .mv6-section {
        grid-template-columns: 130px minmax(0, 1fr);
        gap: 30px;
    }

    .modern-v6 .mv6-skills-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .modern-v6 .mv6-visual {
        transform: scale(.92);
    }
}

@media (max-width: 760px) {
    .modern-v6 .mv6-header {
        width: calc(100% - 36px);
        min-height: 75px;
        flex-wrap: wrap;
        padding: 17px 0;
    }

    .modern-v6 .mv6-nav {
        order: 3;
        width: 100%;
        justify-content: flex-start;
        gap: 20px;
        overflow-x: auto;
        padding: 6px 0 2px;
    }

    .modern-v6 .mv6-nav a {
        flex-shrink: 0;
    }

    .modern-v6 .mv6-hero {
        width: calc(100% - 36px);
        grid-template-columns: 1fr;
        gap: 35px;
        padding: 60px 0 45px;
    }

    .modern-v6 .mv6-hero h1 {
        font-size: clamp(42px, 10vw, 68px);
        letter-spacing: -2.5px;
    }

    .modern-v6 .mv6-hero-bottom {
        margin-top: 38px;
    }

    .modern-v6 .mv6-visual {
        width: 100%;
        min-height: 470px;
        transform: none;
    }

    .modern-v6 .mv6-metrics {
        width: calc(100% - 36px);
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 22px 0;
    }

    .modern-v6 .mv6-metric {
        padding: 0 12px;
    }

    .modern-v6 .mv6-metric-number {
        font-size: 27px;
    }

    .modern-v6 .mv6-metric-label {
        font-size: 8px;
    }

    .modern-v6 .mv6-metric-note {
        grid-column: 1 / -1;
        justify-content: flex-start;
        padding: 16px 0 0;
        border-top: 1px solid var(--mv-line);
    }

    .modern-v6 .mv6-section {
        width: calc(100% - 36px);
        grid-template-columns: 1fr;
        gap: 25px;
        padding: 75px 0;
    }

    .modern-v6 .mv6-section-aside {
        flex-direction: row;
        align-items: center;
        gap: 13px;
    }

    .modern-v6 .mv6-aside-line {
        width: 25px;
    }

    .modern-v6 .mv6-aside-caption {
        max-width: none;
    }

    .modern-v6 .mv6-about-grid {
        grid-template-columns: 1fr;
    }

    .modern-v6 .mv6-journey-grid {
        grid-template-columns: 1fr;
    }

    .modern-v6 .mv6-contact-section {
        width: calc(100% - 36px);
        margin-top: 55px;
        padding: 25px;
    }

    .modern-v6 .mv6-contact-body {
        grid-template-columns: 1fr;
        gap: 28px;
        padding: 30px 0;
    }

    .modern-v6 .mv6-footer {
        width: calc(100% - 36px);
    }
}

@media (max-width: 480px) {
    .modern-v6 .mv6-nav-button {
        padding: 9px 10px;
        gap: 8px;
        font-size: 10px;
    }

    .modern-v6 .mv6-logo {
        font-size: 11px;
        gap: 7px;
    }

    .modern-v6 .mv6-logo-icon {
        width: 31px;
        height: 31px;
    }

    .modern-v6 .mv6-overline {
        margin-top: 32px;
    }

    .modern-v6 .mv6-role-line {
        font-size: 8px;
        letter-spacing: 1px;
    }

    .modern-v6 .mv6-hero-description {
        font-size: 12px;
    }

    .modern-v6 .mv6-visual {
        min-height: 405px;
    }

    .modern-v6 .mv6-profile-card {
        width: 82%;
    }

    .modern-v6 .mv6-card-content {
        padding: 20px 17px 15px;
    }

    .modern-v6 .mv6-profile-image-wrap {
        width: 90px;
        height: 90px;
    }

    .modern-v6 .mv6-profile-identity h2 {
        font-size: 18px;
    }

    .modern-v6 .mv6-badge-code {
        right: -2%;
    }

    .modern-v6 .mv6-badge-creative {
        left: -2%;
    }

    .modern-v6 .mv6-float-badge {
        padding: 8px;
        gap: 7px;
    }

    .modern-v6 .mv6-float-badge b {
        font-size: 8px;
    }

    .modern-v6 .mv6-float-badge small {
        font-size: 6px;
    }

    .modern-v6 .mv6-metric {
        padding: 0 8px;
    }

    .modern-v6 .mv6-metric-number {
        font-size: 24px;
    }

    .modern-v6 .mv6-heading-row {
        align-items: flex-start;
        flex-direction: column;
        gap: 0;
    }

    .modern-v6 .mv6-heading-row .mv6-section-heading {
        margin-bottom: 12px;
    }

    .modern-v6 .mv6-heading-count {
        margin-bottom: 25px;
    }

    .modern-v6 .mv6-skills-grid,
    .modern-v6 .mv6-project-grid {
        grid-template-columns: 1fr;
    }

    .modern-v6 .mv6-skill-card {
        min-height: 165px;
    }

    .modern-v6 .mv6-project-art {
        min-height: 215px;
    }

    .modern-v6 .mv6-about-statement {
        min-height: auto;
        padding: 24px;
    }

    .modern-v6 .mv6-contact-section {
        padding: 20px;
    }

    .modern-v6 .mv6-contact-body h2 {
        font-size: 36px;
        letter-spacing: -1.5px;
    }

    .modern-v6 .mv6-contact-top {
        align-items: flex-start;
        flex-direction: column;
    }

    .modern-v6 .mv6-footer {
        align-items: flex-start;
        flex-direction: column;
    }
}

@media (prefers-reduced-motion: reduce) {
    .modern-v6 *,
    .modern-v6 *::before,
    .modern-v6 *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
    }
}
</style>

<script>
(function () {
    const root = document.querySelector('.modern-v6');

    if (!root || root.dataset.mv6Initialized === 'true') {
        return;
    }

    root.dataset.mv6Initialized = 'true';

    // Smooth scrolling for this template only.
    root.querySelectorAll('a[href^="#mv6-"]').forEach(function (link) {
        link.addEventListener('click', function (event) {
            const targetId = link.getAttribute('href');
            const target = root.querySelector(targetId);

            if (target) {
                event.preventDefault();
                target.scrollIntoView({
                    behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches
                        ? 'auto'
                        : 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Decorative animated particles. Existing background layers stay in place.
    const background = root.querySelector('.modern-background');

    if (background && !background.querySelector('.mv6-particle-layer')) {
        const particleLayer = document.createElement('div');
        particleLayer.className = 'mv6-particle-layer';
        particleLayer.setAttribute('aria-hidden', 'true');

        Object.assign(particleLayer.style, {
            position: 'absolute',
            inset: '0',
            overflow: 'hidden',
            pointerEvents: 'none'
        });

        for (let i = 0; i < 28; i++) {
            const particle = document.createElement('span');

            Object.assign(particle.style, {
                position: 'absolute',
                left: (Math.random() * 100) + '%',
                top: (Math.random() * 100) + '%',
                width: (Math.random() > 0.75 ? '3px' : '2px'),
                height: (Math.random() > 0.75 ? '3px' : '2px'),
                borderRadius: '50%',
                background: i % 3 === 0 ? '#fb7185' : '#c084fc',
                opacity: String(0.2 + Math.random() * 0.45),
                boxShadow: '0 0 9px currentColor',
                animation: 'mv6ParticleFloat ' + (5 + Math.random() * 10) + 's ease-in-out ' + (-Math.random() * 10) + 's infinite alternate'
            });

            particleLayer.appendChild(particle);
        }

        background.appendChild(particleLayer);
    }

    if (!document.getElementById('mv6-particle-keyframes')) {
        const style = document.createElement('style');
        style.id = 'mv6-particle-keyframes';
        style.textContent = `
            @keyframes mv6ParticleFloat {
                from { transform: translate3d(0, 0, 0); opacity: .15; }
                to { transform: translate3d(12px, -25px, 0); opacity: .75; }
            }
        `;
        document.head.appendChild(style);
    }
})();

/* ======================================================
   MODERN V6 — COMPACT LAYOUT UPDATE
   Reduces excessive empty space without changing the
   existing colors, animations, content, or functionality.
====================================================== */

/* Keep the template content comfortably centered */
.modern-v6 .mv6-header,
.modern-v6 .mv6-hero,
.modern-v6 .mv6-metrics,
.modern-v6 .mv6-section,
.modern-v6 .mv6-contact-section,
.modern-v6 .mv6-footer {
    max-width: 1180px;
}

/* Compact navigation */
.modern-v6 .mv6-header {
    min-height: 70px;
}

/* Reduce the oversized hero area */
.modern-v6 .mv6-hero {
    min-height: auto;
    padding: 45px 0 35px;
    gap: 30px;
}

.modern-v6 .mv6-overline {
    margin-top: 25px;
}

.modern-v6 .mv6-role-line {
    margin-top: 16px;
}

.modern-v6 .mv6-hero-description {
    margin-top: 18px;
    line-height: 1.75;
}

.modern-v6 .mv6-hero-actions {
    margin-top: 22px;
}

.modern-v6 .mv6-hero-bottom {
    margin-top: 35px;
}

/* Make the profile visual more compact */
.modern-v6 .mv6-visual {
    min-height: 420px;
}

.modern-v6 .mv6-profile-card {
    width: min(320px, 82%);
}

.modern-v6 .mv6-card-content {
    padding: 20px 22px 15px;
}

.modern-v6 .mv6-profile-image-wrap {
    width: 95px;
    height: 95px;
    margin-bottom: 13px;
}

.modern-v6 .mv6-profile-divider {
    margin: 15px 0 12px;
}

.modern-v6 .mv6-card-footer {
    margin-top: 15px;
}

/* Tighten the statistics row */
.modern-v6 .mv6-metrics {
    padding: 20px 0;
}

/* Main improvement: reduce the large gaps between sections */
.modern-v6 .mv6-section {
    padding: 65px 0;
    gap: 35px;
    grid-template-columns: 155px minmax(0, 1fr);
}

.modern-v6 .mv6-section-heading {
    margin-bottom: 25px;
}

.modern-v6 .mv6-heading-row .mv6-section-heading {
    margin-bottom: 25px;
}

/* About section */
.modern-v6 .mv6-about-grid {
    gap: 18px;
}

.modern-v6 .mv6-about-statement {
    min-height: 0;
    padding: 24px;
}

.modern-v6 .mv6-about-statement p {
    margin: 10px 0 20px;
    line-height: 1.8;
}

.modern-v6 .mv6-about-details {
    justify-content: flex-start;
    gap: 10px;
}

.modern-v6 .mv6-detail-block {
    padding: 15px;
}

/* Skills: less empty space inside each card */
.modern-v6 .mv6-skills-grid {
    gap: 12px;
}

.modern-v6 .mv6-skill-card {
    min-height: 155px;
    padding: 15px;
}

.modern-v6 .mv6-skill-symbol {
    width: 33px;
    height: 33px;
    margin-top: 14px;
}

.modern-v6 .mv6-skill-card h3 {
    margin: 9px 0 12px;
}

/* Projects: reduce oversized artwork and card padding */
.modern-v6 .mv6-project-grid {
    gap: 16px;
}

.modern-v6 .mv6-project-art {
    min-height: 185px;
}

.modern-v6 .mv6-project-content {
    padding: 17px;
}

.modern-v6 .mv6-project-content h3 {
    margin-top: 12px;
}

.modern-v6 .mv6-project-content > p {
    min-height: 0;
    line-height: 1.75;
}

.modern-v6 .mv6-project-tech {
    margin-top: 12px;
}

.modern-v6 .mv6-project-footer {
    margin-top: 14px;
    padding-top: 11px;
}

/* Education and experience */
.modern-v6 .mv6-journey-grid {
    gap: 16px;
}

.modern-v6 .mv6-journey-column {
    padding: 18px;
}

.modern-v6 .mv6-journey-heading {
    padding-bottom: 14px;
}

.modern-v6 .mv6-timeline {
    margin-top: 18px;
}

.modern-v6 .mv6-timeline-card {
    padding-bottom: 18px;
}

/* Contact section */
.modern-v6 .mv6-contact-section {
    margin-top: 45px;
    padding: 30px;
}

.modern-v6 .mv6-contact-body {
    gap: 30px;
    padding: 28px 0;
}

.modern-v6 .mv6-contact-link {
    padding: 10px;
}

/* Footer */
.modern-v6 .mv6-footer {
    padding: 25px 0;
}

/* Tablet adjustments */
@media (max-width: 1050px) {
    .modern-v6 .mv6-section {
        grid-template-columns: 125px minmax(0, 1fr);
        gap: 24px;
        padding: 55px 0;
    }

    .modern-v6 .mv6-hero {
        gap: 20px;
        padding: 35px 0;
    }

    .modern-v6 .mv6-visual {
        min-height: 390px;
    }
}

/* Mobile adjustments */
@media (max-width: 760px) {
    .modern-v6 .mv6-header {
        min-height: 65px;
    }

    .modern-v6 .mv6-hero {
        padding: 40px 0 25px;
        gap: 20px;
    }

    .modern-v6 .mv6-hero-bottom {
        margin-top: 25px;
    }

    .modern-v6 .mv6-visual {
        min-height: 390px;
    }

    .modern-v6 .mv6-metrics {
        padding: 18px 0;
    }

    .modern-v6 .mv6-section {
        padding: 45px 0;
        gap: 18px;
        grid-template-columns: 1fr;
    }

    .modern-v6 .mv6-section-heading {
        margin-bottom: 20px;
    }

    .modern-v6 .mv6-heading-row .mv6-section-heading {
        margin-bottom: 10px;
    }

    .modern-v6 .mv6-about-grid {
        gap: 14px;
    }

    .modern-v6 .mv6-journey-grid {
        gap: 14px;
    }

    .modern-v6 .mv6-contact-section {
        margin-top: 30px;
        padding: 22px;
    }

    .modern-v6 .mv6-contact-body {
        gap: 20px;
        padding: 22px 0;
    }

    .modern-v6 .mv6-footer {
        padding: 22px 0;
    }
}

/* Small mobile screens */
@media (max-width: 480px) {
    .modern-v6 .mv6-hero {
        padding-top: 30px;
    }

    .modern-v6 .mv6-visual {
        min-height: 355px;
    }

    .modern-v6 .mv6-section {
        padding: 35px 0;
    }

    .modern-v6 .mv6-skill-card {
        min-height: 140px;
    }

    .modern-v6 .mv6-project-art {
        min-height: 170px;
    }

    .modern-v6 .mv6-project-content {
        padding: 15px;
    }

    .modern-v6 .mv6-journey-column {
        padding: 15px;
    }

    .modern-v6 .mv6-contact-section {
        margin-top: 25px;
        padding: 17px;
    }
}
 
 
</script>
