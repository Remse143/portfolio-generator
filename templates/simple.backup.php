<div class="generated-portfolio simple-template">

    <!-- =====================================================
         HERO / PROFILE HEADER
         ===================================================== -->

    <section class="simple-hero">

        <div class="simple-hero-content">

            <div class="simple-profile">

                <?php if (!empty($portfolio['profile_picture'])): ?>

                    <img
                        src="<?= htmlspecialchars($portfolio['profile_picture']) ?>"
                        alt="Profile Picture"
                        class="generated-profile-picture"
                    >

                <?php else: ?>

                    <div class="generated-profile-placeholder">
                        👤
                    </div>

                <?php endif; ?>

            </div>


            <div class="simple-intro">

                <span class="simple-label">
                    PERSONAL PORTFOLIO
                </span>


                <h1>
                    <?= htmlspecialchars($portfolio['full_name']) ?>
                </h1>


                <p class="simple-subtitle">
                    Professional Portfolio
                </p>


                <div class="simple-contact">

                    <?php if (!empty($portfolio['email'])): ?>

                        <span>
                            📧
                            <?= htmlspecialchars($portfolio['email']) ?>
                        </span>

                    <?php endif; ?>


                    <?php if (!empty($portfolio['contact_number'])): ?>

                        <span>
                            📱
                            <?= htmlspecialchars($portfolio['contact_number']) ?>
                        </span>

                    <?php endif; ?>


                    <?php if (!empty($portfolio['address'])): ?>

                        <span>
                            📍
                            <?= htmlspecialchars($portfolio['address']) ?>
                        </span>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </section>



    <!-- =====================================================
         ABOUT ME
         ===================================================== -->

    <?php if (!empty($portfolio['about_me'])): ?>

        <section class="simple-section">

            <div class="simple-section-heading">

                <span class="simple-number">
                    01
                </span>

                <div>

                    <span class="simple-small-title">
                        INTRODUCTION
                    </span>

                    <h2>
                        About Me
                    </h2>

                </div>

            </div>


            <div class="simple-section-content">

                <p>
                    <?= nl2br(htmlspecialchars($portfolio['about_me'])) ?>
                </p>

            </div>

        </section>

    <?php endif; ?>



    <!-- =====================================================
         EDUCATION
         ===================================================== -->

    <?php if (!empty($portfolio['education'])): ?>

        <section class="simple-section">

            <div class="simple-section-heading">

                <span class="simple-number">
                    02
                </span>

                <div>

                    <span class="simple-small-title">
                        ACADEMIC BACKGROUND
                    </span>

                    <h2>
                        Education
                    </h2>

                </div>

            </div>


            <div class="simple-info-card">

                <div class="simple-card-icon">
                    🎓
                </div>


                <div class="simple-card-content">

                    <p>
                        <?= nl2br(htmlspecialchars($portfolio['education'])) ?>
                    </p>

                </div>

            </div>

        </section>

    <?php endif; ?>



    <!-- =====================================================
         SKILLS
         ===================================================== -->

    <?php if (!empty($portfolio['skills'])): ?>

        <section class="simple-section">

            <div class="simple-section-heading">

                <span class="simple-number">
                    03
                </span>

                <div>

                    <span class="simple-small-title">
                        EXPERTISE
                    </span>

                    <h2>
                        Skills
                    </h2>

                </div>

            </div>


            <div class="simple-skills">

                <?php

                $skillsText =
                    $portfolio['skills'];

                $skills =
                    preg_split(
                        '/[,;\n]+/',
                        $skillsText
                    );

                ?>


                <?php foreach ($skills as $skill): ?>

                    <?php $skill = trim($skill); ?>

                    <?php if ($skill !== ''): ?>

                        <span class="simple-skill">
                            <?= htmlspecialchars($skill) ?>
                        </span>

                    <?php endif; ?>

                <?php endforeach; ?>

            </div>

        </section>

    <?php endif; ?>



    <!-- =====================================================
         PROJECTS
         ===================================================== -->

    <?php if (!empty($portfolio['projects'])): ?>

        <section class="simple-section">

            <div class="simple-section-heading">

                <span class="simple-number">
                    04
                </span>

                <div>

                    <span class="simple-small-title">
                        SELECTED WORK
                    </span>

                    <h2>
                        Projects
                    </h2>

                </div>

            </div>


            <div class="simple-project-card">

                <div class="simple-project-icon">
                    🚀
                </div>


                <div>

                    <h3>
                        Featured Project
                    </h3>


                    <p>
                        <?= nl2br(htmlspecialchars($portfolio['projects'])) ?>
                    </p>

                </div>

            </div>

        </section>

    <?php endif; ?>



    <!-- =====================================================
         WORK EXPERIENCE
         ===================================================== -->

    <?php if (!empty($portfolio['work_experience'])): ?>

        <section class="simple-section">

            <div class="simple-section-heading">

                <span class="simple-number">
                    05
                </span>

                <div>

                    <span class="simple-small-title">
                        PROFESSIONAL JOURNEY
                    </span>

                    <h2>
                        Work Experience
                    </h2>

                </div>

            </div>


            <div class="simple-experience">

                <div class="simple-timeline-line"></div>


                <div class="simple-experience-item">

                    <div class="simple-timeline-dot"></div>


                    <div class="simple-experience-card">

                        <span class="simple-experience-label">
                            EXPERIENCE
                        </span>


                        <p>
                            <?= nl2br(htmlspecialchars($portfolio['work_experience'])) ?>
                        </p>

                    </div>

                </div>

            </div>

        </section>

    <?php endif; ?>



    <!-- =====================================================
         SOCIAL LINKS
         ===================================================== -->

    <?php if (!empty($portfolio['social_links'])): ?>

        <section class="simple-section simple-social-section">

            <div class="simple-section-heading">

                <span class="simple-number">
                    06
                </span>

                <div>

                    <span class="simple-small-title">
                        LET'S CONNECT
                    </span>

                    <h2>
                        Social Media & Links
                    </h2>

                </div>

            </div>


            <div class="simple-social-card">

                <div class="simple-social-icon">
                    🔗
                </div>


                <div>

                    <p>
                        <?= nl2br(htmlspecialchars($portfolio['social_links'])) ?>
                    </p>

                </div>

            </div>

        </section>

    <?php endif; ?>



    <!-- =====================================================
         FOOTER MESSAGE
         ===================================================== -->

    <section class="simple-ending">

        <div class="simple-ending-line"></div>


        <p>
            Thank you for visiting my portfolio.
        </p>


        <span>
            <?= htmlspecialchars($portfolio['full_name']) ?>
        </span>

    </section>

</div>



<!-- =========================================================
     SIMPLE TEMPLATE DESIGN
     ========================================================= -->

<style>

    /* =====================================================
       MAIN CONTAINER
       ===================================================== */

    .simple-template {

        max-width: 1050px;

        margin: 0 auto;

        padding: 20px;

        color: #f8fafc;

        font-family:
            Inter,
            Arial,
            sans-serif;

    }


    /* =====================================================
       HERO
       ===================================================== */

    .simple-hero {

        position: relative;

        overflow: hidden;

        padding: 65px 50px;

        margin-bottom: 45px;

        border-radius: 28px;

        background:
            linear-gradient(
                135deg,
                rgba(225, 29, 72, 0.14),
                rgba(124, 58, 237, 0.12),
                rgba(8, 7, 12, 0.92)
            );

        border:
            1px solid
            rgba(225, 29, 72, 0.22);

        box-shadow:
            0 25px 70px
            rgba(0, 0, 0, 0.35);

    }


    .simple-hero::before {

        content: "";

        position: absolute;

        width: 280px;
        height: 280px;

        right: -120px;
        top: -120px;

        border-radius: 50%;

        background:
            rgba(225, 29, 72, 0.12);

        filter: blur(30px);

    }


    .simple-hero::after {

        content: "";

        position: absolute;

        width: 220px;
        height: 220px;

        left: -120px;
        bottom: -120px;

        border-radius: 50%;

        background:
            rgba(124, 58, 237, 0.12);

        filter: blur(35px);

    }


    .simple-hero-content {

        position: relative;

        z-index: 2;

        display: flex;

        align-items: center;

        gap: 45px;

    }


    /* =====================================================
       PROFILE
       ===================================================== */

    .simple-profile {

        width: 170px;
        height: 170px;

        flex-shrink: 0;

        padding: 5px;

        border-radius: 50%;

        background:
            linear-gradient(
                135deg,
                #e11d48,
                #7c3aed
            );

        box-shadow:
            0 0 45px
            rgba(225, 29, 72, 0.20);

    }


    .simple-profile .generated-profile-picture,
    .simple-profile .generated-profile-placeholder {

        width: 100%;
        height: 100%;

        border-radius: 50%;

        object-fit: cover;

        display: flex;

        align-items: center;
        justify-content: center;

        background: #09090b;

    }


    .simple-profile .generated-profile-placeholder {

        font-size: 55px;

    }


    /* =====================================================
       INTRO
       ===================================================== */

    .simple-intro {

        flex: 1;

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

        margin: 0;

        font-size: clamp(38px, 6vw, 64px);

        line-height: 1.05;

        letter-spacing: -2px;

        background:
            linear-gradient(
                90deg,
                #ffffff,
                #fda4af,
                #c4b5fd
            );

        -webkit-background-clip: text;

        -webkit-text-fill-color: transparent;

        background-clip: text;

    }


    .simple-subtitle {

        margin:
            14px 0 22px;

        color: #a1a1aa;

        font-size: 17px;

    }


    /* =====================================================
       CONTACT
       ===================================================== */

    .simple-contact {

        display: flex;

        flex-wrap: wrap;

        gap: 10px;

    }


    .simple-contact span {

        padding:
            9px 13px;

        border-radius: 10px;

        background:
            rgba(255, 255, 255, 0.045);

        border:
            1px solid
            rgba(255, 255, 255, 0.08);

        color: #d4d4d8;

        font-size: 13px;

    }


    /* =====================================================
       SECTIONS
       ===================================================== */

    .simple-section {

        margin-bottom: 48px;

        padding:
            35px;

        border-radius: 22px;

        background:
            rgba(9, 9, 12, 0.72);

        border:
            1px solid
            rgba(255, 255, 255, 0.07);

        box-shadow:
            0 15px 45px
            rgba(0, 0, 0, 0.20);

        transition:
            transform 0.3s ease,
            border-color 0.3s ease,
            box-shadow 0.3s ease;

    }


    .simple-section:hover {

        transform:
            translateY(-3px);

        border-color:
            rgba(225, 29, 72, 0.22);

        box-shadow:
            0 20px 55px
            rgba(0, 0, 0, 0.28);

    }


    /* =====================================================
       SECTION HEADING
       ===================================================== */

    .simple-section-heading {

        display: flex;

        align-items: center;

        gap: 17px;

        margin-bottom: 27px;

    }


    .simple-number {

        display: flex;

        align-items: center;
        justify-content: center;

        width: 45px;
        height: 45px;

        flex-shrink: 0;

        border-radius: 12px;

        background:
            linear-gradient(
                135deg,
                rgba(225, 29, 72, 0.18),
                rgba(124, 58, 237, 0.16)
            );

        border:
            1px solid
            rgba(225, 29, 72, 0.22);

        color: #fb7185;

        font-size: 13px;

        font-weight: 800;

    }


    .simple-small-title {

        display: block;

        margin-bottom: 4px;

        color: #71717a;

        font-size: 10px;

        font-weight: 700;

        letter-spacing: 2px;

    }


    .simple-section h2 {

        margin: 0;

        font-size: 28px;

        color: #ffffff;

    }


    /* =====================================================
       CONTENT
       ===================================================== */

    .simple-section-content {

        padding-left: 62px;

    }


    .simple-section p {

        margin: 0;

        color: #b8b8c2;

        font-size: 15px;

        line-height: 1.85;

    }


    /* =====================================================
       EDUCATION CARD
       ===================================================== */

    .simple-info-card {

        display: flex;

        gap: 20px;

        padding: 25px;

        border-radius: 16px;

        background:
            linear-gradient(
                135deg,
                rgba(225, 29, 72, 0.06),
                rgba(124, 58, 237, 0.05)
            );

        border:
            1px solid
            rgba(255, 255, 255, 0.07);

    }


    .simple-card-icon {

        width: 50px;
        height: 50px;

        display: flex;

        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 13px;

        background:
            rgba(225, 29, 72, 0.10);

        font-size: 22px;

    }


    .simple-card-content {

        flex: 1;

    }


    /* =====================================================
       SKILLS
       ===================================================== */

    .simple-skills {

        display: flex;

        flex-wrap: wrap;

        gap: 12px;

    }


    .simple-skill {

        padding:
            11px 17px;

        border-radius: 30px;

        background:
            linear-gradient(
                135deg,
                rgba(225, 29, 72, 0.10),
                rgba(124, 58, 237, 0.08)
            );

        border:
            1px solid
            rgba(225, 29, 72, 0.20);

        color: #e4e4e7;

        font-size: 14px;

        font-weight: 600;

        transition:
            transform 0.2s ease,
            background 0.2s ease;

    }


    .simple-skill:hover {

        transform:
            translateY(-3px);

        background:
            rgba(225, 29, 72, 0.16);

    }


    /* =====================================================
       PROJECT
       ===================================================== */

    .simple-project-card {

        display: flex;

        gap: 20px;

        padding: 27px;

        border-radius: 17px;

        background:
            linear-gradient(
                135deg,
                rgba(225, 29, 72, 0.07),
                rgba(124, 58, 237, 0.06)
            );

        border:
            1px solid
            rgba(255, 255, 255, 0.07);

    }


    .simple-project-icon {

        width: 52px;
        height: 52px;

        flex-shrink: 0;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background:
            rgba(124, 58, 237, 0.12);

        font-size: 23px;

    }


    .simple-project-card h3 {

        margin:
            2px 0 10px;

        font-size: 18px;

        color: #ffffff;

    }


    /* =====================================================
       EXPERIENCE TIMELINE
       ===================================================== */

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

        background:
            linear-gradient(
                #e11d48,
                #7c3aed
            );

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

        box-shadow:
            0 0 15px
            rgba(225, 29, 72, 0.5);

    }


    .simple-experience-card {

        padding: 23px;

        border-radius: 15px;

        background:
            rgba(255, 255, 255, 0.025);

        border:
            1px solid
            rgba(255, 255, 255, 0.07);

    }


    .simple-experience-label {

        display: block;

        margin-bottom: 10px;

        color: #fb7185;

        font-size: 11px;

        font-weight: 700;

        letter-spacing: 1.5px;

    }


    /* =====================================================
       SOCIAL
       ===================================================== */

    .simple-social-section {

        margin-bottom: 30px;

    }


    .simple-social-card {

        display: flex;

        align-items: center;

        gap: 20px;

        padding: 25px;

        border-radius: 17px;

        background:
            linear-gradient(
                135deg,
                rgba(124, 58, 237, 0.08),
                rgba(225, 29, 72, 0.06)
            );

        border:
            1px solid
            rgba(255, 255, 255, 0.07);

    }


    .simple-social-icon {

        width: 52px;
        height: 52px;

        display: flex;

        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 14px;

        background:
            rgba(124, 58, 237, 0.12);

        font-size: 22px;

    }


    /* =====================================================
       ENDING
       ===================================================== */

    .simple-ending {

        text-align: center;

        padding:
            30px 20px 45px;

    }


    .simple-ending-line {

        width: 70px;
        height: 2px;

        margin:
            0 auto 18px;

        background:
            linear-gradient(
                90deg,
                #e11d48,
                #7c3aed
            );

    }


    .simple-ending p {

        margin: 0 0 7px;

        color: #71717a;

        font-size: 13px;

    }


    .simple-ending span {

        color: #d4d4d8;

        font-size: 14px;

        font-weight: 700;

    }


    /* =====================================================
       RESPONSIVE
       ===================================================== */

    @media (max-width: 700px) {

        .simple-template {

            padding: 10px;

        }


        .simple-hero {

            padding: 40px 25px;

        }


        .simple-hero-content {

            flex-direction: column;

            text-align: center;

        }


        .simple-contact {

            justify-content: center;

        }


        .simple-intro h1 {

            font-size: 40px;

        }


        .simple-section {

            padding: 25px 20px;

        }


        .simple-section-content {

            padding-left: 0;

        }


        .simple-section-heading {

            align-items: flex-start;

        }


        .simple-info-card,
        .simple-project-card,
        .simple-social-card {

            align-items: flex-start;

        }

    }


    @media (max-width: 450px) {

        .simple-profile {

            width: 130px;
            height: 130px;

        }


        .simple-intro h1 {

            font-size: 32px;

        }


        .simple-section h2 {

            font-size: 23px;

        }


        .simple-contact {

            flex-direction: column;

        }


        .simple-contact span {

            width: 100%;

            box-sizing: border-box;

        }

    }

</style>