document.addEventListener("DOMContentLoaded", function () {

    // =====================================================
    // PROFILE PICTURE PREVIEW
    // =====================================================

    const profileInput = document.getElementById("profile_picture");
    const profilePreview = document.getElementById("profilePreview");
    const profilePlaceholder = document.getElementById("profilePlaceholder");

    if (profileInput) {

        profileInput.addEventListener("change", function () {

            const file = this.files[0];

            if (!file) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {

                if (profilePreview) {
                    profilePreview.src = event.target.result;
                    profilePreview.style.display = "block";
                }

                if (profilePlaceholder) {
                    profilePlaceholder.style.display = "none";
                }

            };

            reader.readAsDataURL(file);

        });

    }


    // =====================================================
    // DARK MODE
    // =====================================================

    const themeToggle = document.getElementById("themeToggle");
    const savedTheme = localStorage.getItem("theme");

    if (savedTheme === "dark") {

        document.body.classList.add("dark-mode");

        if (themeToggle) {
            themeToggle.textContent = "☀️";
        }

    } else {

        if (themeToggle) {
            themeToggle.textContent = "🌙";
        }

    }


    // =====================================================
    // DARK MODE BUTTON
    // =====================================================

    if (themeToggle) {

        themeToggle.addEventListener("click", function () {

            document.body.classList.toggle("dark-mode");

            const isDark =
                document.body.classList.contains("dark-mode");

            if (isDark) {

                localStorage.setItem("theme", "dark");
                themeToggle.textContent = "☀️";

            } else {

                localStorage.setItem("theme", "light");
                themeToggle.textContent = "🌙";

            }

        });

    }


    // =====================================================
    // SHOW / HIDE PASSWORD
    // =====================================================

    const password = document.getElementById("password");
    const showPassword = document.getElementById("showPassword");

    if (password && showPassword) {

        showPassword.addEventListener("click", function () {

            if (password.type === "password") {

                password.type = "text";
                showPassword.textContent = "🙈";

            } else {

                password.type = "password";
                showPassword.textContent = "👁️";

            }

        });

    }

});

/* Floating background particles */
(function () {
    const portfolios = document.querySelectorAll('.modern-v3');

    portfolios.forEach(function (portfolio) {
        const background = portfolio.querySelector('.modern-background');

        if (!background || background.dataset.particlesReady === 'true') {
            return;
        }

        background.dataset.particlesReady = 'true';

        // Avoid animations for users who prefer reduced motion.
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        const particleCount = 24;
        const fragment = document.createDocumentFragment();

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('span');

            particle.className = 'modern-particle';
            particle.style.left = (Math.random() * 100) + '%';
            particle.style.setProperty(
                '--particle-duration',
                (9 + Math.random() * 12) + 's'
            );
            particle.style.setProperty(
                '--particle-delay',
                (-Math.random() * 20) + 's'
            );
            particle.style.setProperty(
                '--particle-drift',
                (Math.random() * 100 - 50) + 'px'
            );

            if (i % 3 === 0) {
                particle.style.background = '#fb7185';
                particle.style.boxShadow = '0 0 10px #e11d48';
            }

            fragment.appendChild(particle);
        }

        background.appendChild(fragment);
    });
})();
