<div class="generated-portfolio creative-template">

    <div class="creative-header">

        <div class="creative-profile">

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


        <h1>
            <?= htmlspecialchars($portfolio['full_name']) ?>
        </h1>


        <?php if (!empty($portfolio['email'])): ?>

            <span>
                <?= htmlspecialchars($portfolio['email']) ?>
            </span>

        <?php endif; ?>


        <?php if (!empty($portfolio['contact_number'])): ?>

            <span>
                <?= htmlspecialchars($portfolio['contact_number']) ?>
            </span>

        <?php endif; ?>

    </div>


    <div class="creative-grid">


        <?php if (!empty($portfolio['about_me'])): ?>

            <div class="creative-card creative-wide">

                <h2>✨ About Me</h2>

                <p>
                    <?= nl2br(htmlspecialchars($portfolio['about_me'])) ?>
                </p>

            </div>

        <?php endif; ?>


        <?php if (!empty($portfolio['skills'])): ?>

            <div class="creative-card">

                <h2>💡 Skills</h2>

                <p>
                    <?= nl2br(htmlspecialchars($portfolio['skills'])) ?>
                </p>

            </div>

        <?php endif; ?>


        <?php if (!empty($portfolio['education'])): ?>

            <div class="creative-card">

                <h2>🎓 Education</h2>

                <p>
                    <?= nl2br(htmlspecialchars($portfolio['education'])) ?>
                </p>

            </div>

        <?php endif; ?>


        <?php if (!empty($portfolio['projects'])): ?>

            <div class="creative-card creative-wide">

                <h2>🚀 Projects</h2>

                <p>
                    <?= nl2br(htmlspecialchars($portfolio['projects'])) ?>
                </p>

            </div>

        <?php endif; ?>


        <?php if (!empty($portfolio['work_experience'])): ?>

            <div class="creative-card">

                <h2>💼 Experience</h2>

                <p>
                    <?= nl2br(htmlspecialchars($portfolio['work_experience'])) ?>
                </p>

            </div>

        <?php endif; ?>


        <?php if (!empty($portfolio['social_links'])): ?>

            <div class="creative-card">

                <h2>🌐 Links</h2>

                <p>
                    <?= nl2br(htmlspecialchars($portfolio['social_links'])) ?>
                </p>

            </div>

        <?php endif; ?>


    </div>

</div>