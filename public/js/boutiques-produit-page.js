(function () {
  var main = document.getElementById('articleMainMedia');
  if (!main) return;

  document.querySelectorAll('.article-thumb').forEach(function (thumb) {
    thumb.addEventListener('click', function () {
      document.querySelectorAll('.article-thumb').forEach(function (t) {
        t.classList.remove('is-active');
      });
      thumb.classList.add('is-active');

      var url = thumb.getAttribute('data-url');
      var isVideo = thumb.getAttribute('data-video') === '1';

      if (isVideo) {
        main.innerHTML = '<video src="' + url + '" controls playsinline class="article-main-video" autoplay></video>';
      } else {
        main.innerHTML = '<img src="' + url + '" alt="" id="articleMainImg">';
      }
    });
  });

  document.querySelectorAll('.btn-commander').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (btn.disabled) return;
      alert('Paiement bientôt disponible pour « ' + (btn.getAttribute('data-nom') || 'cet article') + ' ».');
    });
  });
})();
