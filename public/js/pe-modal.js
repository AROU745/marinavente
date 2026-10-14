(function () {
    const modal = document.getElementById('peModal');
    if (!modal) return;

    const titleEl = document.getElementById('peModalTitle');
    const videoEl = document.getElementById('peModalVideo');
    const imageEl = document.getElementById('peModalImage');
    const fallbackEl = document.getElementById('peModalFallback');

    function resetMedia() {
        videoEl.oncanplay = null;
        videoEl.onerror = null;
        videoEl.onloadeddata = null;
        videoEl.pause();
        videoEl.removeAttribute('src');
        videoEl.load();
        videoEl.style.display = 'none';
        imageEl.removeAttribute('src');
        imageEl.style.display = 'none';
        fallbackEl.hidden = true;
    }

    function showImage(src, title, asFallback) {
        videoEl.pause();
        videoEl.removeAttribute('src');
        videoEl.load();
        videoEl.style.display = 'none';
        imageEl.src = src;
        imageEl.alt = title || '';
        imageEl.style.display = 'block';
        fallbackEl.hidden = !asFallback;
    }

    function tryPlay() {
        videoEl.muted = true;
        const p = videoEl.play();
        if (p && typeof p.then === 'function') {
            p.catch(function () {
                // Contrôles visibles si autoplay bloqué
            });
        }
    }

    function showVideo(videoSrc, imageSrc, title) {
        let failed = false;
        fallbackEl.hidden = true;
        imageEl.style.display = 'none';
        videoEl.style.display = 'block';
        videoEl.muted = true;
        videoEl.controls = true;
        videoEl.playsInline = true;
        videoEl.setAttribute('playsinline', '');
        videoEl.setAttribute('webkit-playsinline', '');

        const fail = function () {
            if (failed) return;
            failed = true;
            showImage(imageSrc, title, true);
        };

        videoEl.onerror = fail;
        videoEl.onloadeddata = function () {
            if (failed) return;
            fallbackEl.hidden = true;
            try {
                if (videoEl.duration && !isNaN(videoEl.duration)) {
                    videoEl.currentTime = 0.05;
                }
            } catch (e) {}
            tryPlay();
        };
        videoEl.oncanplay = function () {
            if (failed) return;
            tryPlay();
        };

        videoEl.src = videoSrc;
        videoEl.load();
    }

    function openModal(btn) {
        const title = btn.dataset.title || '';
        const video = (btn.dataset.video || '').trim();
        const image = btn.dataset.image || '';
        titleEl.textContent = title;
        resetMedia();
        modal.hidden = false;
        document.body.style.overflow = 'hidden';

        if (video) {
            showVideo(video, image, title);
        } else {
            showImage(image, title, false);
        }
    }

    function closeModal() {
        modal.hidden = true;
        document.body.style.overflow = '';
        resetMedia();
    }

    document.querySelectorAll('.pe-play').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            openModal(el);
        });
        el.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openModal(el);
            }
        });
    });

    modal.querySelectorAll('[data-close]').forEach(function (el) {
        el.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.hidden) closeModal();
    });
})();
