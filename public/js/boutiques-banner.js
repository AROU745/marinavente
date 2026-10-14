(function () {
  var track = document.getElementById('shopBannerTrack');
  if (!track) return;

  var slides = Array.prototype.slice.call(track.querySelectorAll('.shop-banner-slide'));
  if (slides.length < 2) return;

  var dotsWrap = document.getElementById('bannerDots');
  var dots = dotsWrap ? Array.prototype.slice.call(dotsWrap.querySelectorAll('button')) : [];
  var prev = document.getElementById('bannerPrev');
  var next = document.getElementById('bannerNext');
  var current = 0;
  var timer = null;

  function goTo(i) {
    current = (i + slides.length) % slides.length;
    slides.forEach(function (s, idx) {
      s.classList.toggle('is-active', idx === current);
    });
    dots.forEach(function (d, idx) {
      d.classList.toggle('is-active', idx === current);
    });
  }

  function nextSlide() {
    goTo(current + 1);
  }

  function start() {
    stop();
    timer = setInterval(nextSlide, 4500);
  }

  function stop() {
    if (timer) clearInterval(timer);
  }

  if (prev) prev.addEventListener('click', function () { goTo(current - 1); start(); });
  if (next) next.addEventListener('click', function () { goTo(current + 1); start(); });
  dots.forEach(function (d) {
    d.addEventListener('click', function () {
      goTo(parseInt(d.getAttribute('data-index'), 10));
      start();
    });
  });

  track.addEventListener('mouseenter', stop);
  track.addEventListener('mouseleave', start);

  start();
})();
