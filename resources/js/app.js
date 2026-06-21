import './bootstrap';

const enhanceNavbar = () => {
    const navbars = document.querySelectorAll('[data-shrink-navbar]');

    if (!navbars.length) {
        return;
    }

    const syncNavbarState = () => {
        navbars.forEach((navbar) => {
            navbar.classList.toggle('is-scrolled', window.scrollY > 16);
        });
    };

    syncNavbarState();
    window.addEventListener('scroll', syncNavbarState, { passive: true });
};

const revealOnScroll = () => {
    const elements = document.querySelectorAll('[data-reveal]');

    if (!elements.length || !('IntersectionObserver' in window)) {
        elements.forEach((element) => element.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12,
    });

    elements.forEach((element) => {
        element.classList.add('reveal-on-scroll');
        observer.observe(element);
    });
};

document.addEventListener('DOMContentLoaded', () => {
    enhanceNavbar();
    revealOnScroll();
});
