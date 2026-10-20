document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    // =====================================================
    // 1. NAVIGATION BAR
    // =====================================================

    const navbar = document.querySelector("nav.navbar");

    if (navbar) {
        // Let CSS control the navbar position.
        navbar.style.removeProperty("position");
        navbar.style.removeProperty("top");
        navbar.style.removeProperty("z-index");
    }

    // Smooth scrolling for links that point to sections.
    document.addEventListener("click", function (event) {
        const link = event.target.closest("a");

        if (!link) return;

        const href = link.getAttribute("href");

        if (!href || href === "#") {
            if (href === "#") event.preventDefault();
            return;
        }

        if (href.startsWith("#")) {
            const target = document.getElementById(href.slice(1));

            if (target) {
                event.preventDefault();

                const reduceMotion = window.matchMedia(
                    "(prefers-reduced-motion: reduce)"
                ).matches;

                target.scrollIntoView({
                    behavior: reduceMotion ? "auto" : "smooth",
                    block: "start"
                });

                history.replaceState(null, "", href);
            }
        }
    });

    // Highlight the current page in all supported navigation bars.
    const currentPage = window.location.pathname
        .split("/")
        .pop()
        .toLowerCase();

    document.querySelectorAll(
        ".navbar a[href], .mg-nav-links a[href]"
    ).forEach(function (link) {
        try {
            const linkUrl = new URL(link.href, window.location.href);

            const linkPage = linkUrl.pathname
                .split("/")
                .pop()
                .toLowerCase();

            const isCurrentPage =
                currentPage !== "" && linkPage === currentPage;

            link.classList.toggle("active", isCurrentPage);

            if (isCurrentPage) {
                link.setAttribute("aria-current", "page");
            } else {
                link.removeAttribute("aria-current");
            }
        } catch (error) {
            console.warn("PortfolioGen: Could not check navigation link.");
        }
    });

    // =====================================================
    // 2. PROFILE PICTURE PREVIEW
    // =====================================================

    const profileInput = document.getElementById("profile_picture");
    const profilePreview = document.getElementById("profilePreview");
    const profilePlaceholder = document.getElementById("profilePlaceholder");

    if (profileInput) {
        profileInput.addEventListener("change", function () {
            const file = this.files && this.files[0];

            if (!file) return;

            if (!file.type.startsWith("image/")) {
                alert("Please select a valid image file.");
                this.value = "";
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
    // 3. DARK MODE
    // =====================================================

    const themeToggle = document.getElementById("themeToggle");

    let savedTheme = "light";

    try {
        savedTheme = localStorage.getItem("theme") || "light";
    } catch (error) {
        console.warn("PortfolioGen: Could not load theme preference.");
    }

    document.body.classList.toggle(
        "dark-mode",
        savedTheme === "dark"
    );

    function updateThemeButton() {
        if (!themeToggle) return;

        const isDark = document.body.classList.contains("dark-mode");

        themeToggle.textContent = isDark ? "☀️" : "🌙";

        themeToggle.setAttribute(
            "aria-label",
            isDark ? "Switch to light mode" : "Switch to dark mode"
        );

        themeToggle.setAttribute("aria-pressed", String(isDark));
    }

    updateThemeButton();

    if (themeToggle) {
        themeToggle.addEventListener("click", function (event) {
            event.preventDefault();

            document.body.classList.toggle("dark-mode");

            const isDark = document.body.classList.contains("dark-mode");

            try {
                localStorage.setItem(
                    "theme",
                    isDark ? "dark" : "light"
                );
            } catch (error) {
                console.warn("PortfolioGen: Could not save theme preference.");
            }

            updateThemeButton();
        });
    }

    // =====================================================
    // 4. SHOW / HIDE PASSWORD
    // Supports Font Awesome icons and emoji fallback.
    // =====================================================

    const passwordButton = document.getElementById("showPassword");
    const passwordInput = document.getElementById("password");

    if (passwordButton && passwordInput) {
        passwordButton.type = "button";

        passwordButton.addEventListener("click", function (event) {
            event.preventDefault();

            const shouldShow = passwordInput.type === "password";

            passwordInput.type = shouldShow ? "text" : "password";

            const eyeIcon = passwordButton.querySelector("i");

            if (eyeIcon) {
                eyeIcon.classList.toggle("fa-eye", !shouldShow);
                eyeIcon.classList.toggle("fa-eye-slash", shouldShow);
            } else {
                passwordButton.textContent = shouldShow ? "🙈" : "👁️";
            }

            passwordButton.setAttribute(
                "aria-label",
                shouldShow ? "Hide password" : "Show password"
            );

            passwordButton.setAttribute(
                "title",
                shouldShow ? "Hide password" : "Show password"
            );

            passwordButton.setAttribute(
                "aria-pressed",
                String(shouldShow)
            );
        });
    }

    // =====================================================
    // 5. SHARED ANIMATED PARTICLE GENERATOR
    // =====================================================

    function createParticles(container, count, type) {
        if (!container || container.dataset.particlesReady === "true") {
            return;
        }

        container.dataset.particlesReady = "true";

        if (
            window.matchMedia("(prefers-reduced-motion: reduce)").matches
        ) {
            return;
        }

        const fragment = document.createDocumentFragment();

        for (let i = 0; i < count; i++) {
            const particle = document.createElement("span");

            particle.className =
                type === "creative"
                    ? "creative-particle"
                    : "modern-particle";

            particle.style.left = Math.random() * 100 + "%";
            particle.style.top = Math.random() * 100 + "%";

            particle.style.setProperty(
                "--particle-duration",
                (8 + Math.random() * 14) + "s"
            );

            particle.style.setProperty(
                "--particle-delay",
                (-Math.random() * 20) + "s"
            );

            particle.style.setProperty(
                "--particle-drift",
                (Math.random() * 100 - 50) + "px"
            );

            if (type === "modern" && i % 3 === 0) {
                particle.style.background = "#fb7185";
                particle.style.boxShadow = "0 0 10px #e11d48";
            }

            fragment.appendChild(particle);
        }

        container.appendChild(fragment);
    }

    // =====================================================
    // 6. MODERN TEMPLATE PARTICLES
    // =====================================================

    document.querySelectorAll(".modern-v3").forEach(function (portfolio) {
        createParticles(
            portfolio.querySelector(".modern-background"),
            24,
            "modern"
        );
    });

    // =====================================================
    // 7. CREATIVE TEMPLATE PARTICLES
    // =====================================================

    document.querySelectorAll(".creative-portfolio").forEach(function (portfolio) {
        createParticles(
            portfolio.querySelector(".creative-particles"),
            32,
            "creative"
        );
    });

});