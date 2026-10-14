(function () {
    const nav = document.getElementById('navMenu');
    const header = document.querySelector('.site-header');
    const offset = () => (header ? header.offsetHeight + 8 : 90);

    function scrollToHash(hash, e) {
        if (!hash || hash === '#') return false;
        const target = document.querySelector(hash);
        if (!target) return false;
        if (e) e.preventDefault();
        const top = target.getBoundingClientRect().top + window.pageYOffset - offset();
        window.scrollTo({ top, behavior: 'smooth' });
        if (nav) nav.classList.remove('open');
        history.replaceState(null, '', hash);
        return true;
    }

    document.querySelectorAll('a[href="#services"], a[href$="/#services"]').forEach((link) => {
        link.addEventListener('click', (e) => {
            const hash = '#services';
            if (document.querySelector(hash)) {
                scrollToHash(hash, e);
            }
            // sinon navigation normale vers l'accueil + #services
        });
    });

    // Autres ancres locales (à propos, projets, etc.)
    document.querySelectorAll('a[href^="#"]').forEach((link) => {
        const hash = link.getAttribute('href');
        if (!hash || hash === '#' || hash === '#services') return;
        link.addEventListener('click', (e) => {
            scrollToHash(hash, e);
        });
    });

    if (window.location.hash === '#services') {
        setTimeout(() => scrollToHash('#services'), 100);
    }
})();
