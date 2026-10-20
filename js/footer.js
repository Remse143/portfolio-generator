
/* =====================================================
   PORTFOLIOGEN - FOOTER ANIMATIONS
   Preserves the existing footer design.
   ===================================================== */

document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    const footer = document.querySelector(".site-footer");

    if (!footer) {
        return;
    }

    // -------------------------------------------------
    // 1. CREATE ANIMATED BACKGROUND PARTICLES
    // -------------------------------------------------

    const particleContainer = footer.querySelector("#footerParticles");

    if (particleContainer && !particleContainer.dataset.initialized) {
        particleContainer.dataset.initialized = "true";

        const particleCount = 35;
        const fragment = document.createDocumentFragment();

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement("span");

            particle.className = "footer-particle";

            particle.style.left = Math.random() * 100 + "%";
            particle.style.top = Math.random() * 100 + "%";
            particle.style.animationDelay = Math.random() * 6 + "s";
            particle.style.animationDuration =
                (4 + Math.random() * 6) + "s";

            fragment.appendChild(particle);
        }

        particleContainer.appendChild(fragment);
    }

    // -------------------------------------------------
    // 2. MAKE SURE FOOTER ICONS REMAIN VISIBLE
    // -------------------------------------------------

    const icons = footer.querySelectorAll(
        ".footer-links i, " +
        ".footer-icon i, " +
        ".footer-socials i, " +
        ".creator-avatar i, " +
        ".creator-name i, " +
        ".creator-link-icon"
    );

    icons.forEach(function (icon) {
        icon.style.visibility = "visible";
        icon.style.opacity = "1";
    });

    // -------------------------------------------------
    // 3. ACCESSIBLE HOVER EFFECTS
    // -------------------------------------------------

    const footerLinks = footer.querySelectorAll(
        ".footer-links a, " +
        ".footer-socials a, " +
        ".creator-card, " +
        ".creator-name"
    );

    footerLinks.forEach(function (link) {
        link.addEventListener("focus", function () {
            link.classList.add("footer-link-focused");
        });

        link.addEventListener("blur", function () {
            link.classList.remove("footer-link-focused");
        });
    });

    // -------------------------------------------------
    // 4. RESPECT REDUCED-MOTION PREFERENCES
    // -------------------------------------------------

    const reducedMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)"
    );

    function updateMotion() {
        if (reducedMotion.matches) {
            footer.classList.add("footer-reduced-motion");
        } else {
            footer.classList.remove("footer-reduced-motion");
        }
    }

    updateMotion();

    if (reducedMotion.addEventListener) {
        reducedMotion.addEventListener("change", updateMotion);
    }

});
