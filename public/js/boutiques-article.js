(function () {
  var modal = document.getElementById('articleModal');
  if (!modal) return;

  var img = document.getElementById('articleModalImg');
  var noPhoto = document.getElementById('articleModalNoPhoto');
  var title = document.getElementById('articleModalTitle');
  var price = document.getElementById('articleModalPrice');
  var num = document.getElementById('articleModalNum');
  var meta = document.getElementById('articleModalMeta');

  function openModal(btn) {
    var photo = btn.getAttribute('data-photo') || '';
    var tailles = btn.getAttribute('data-tailles') || '';
    var couleurs = btn.getAttribute('data-couleurs') || '';
    var stock = btn.getAttribute('data-stock') || '0';

    title.textContent = btn.getAttribute('data-nom') || '';
    price.textContent = btn.getAttribute('data-prix') || '';
    num.textContent = 'Article N° ' + (btn.getAttribute('data-numero') || '');

    var lines = [];
    if (tailles) lines.push('Tailles : ' + tailles);
    if (couleurs) lines.push('Couleurs : ' + couleurs);
    lines.push(parseInt(stock, 10) > 0 ? stock + ' en stock' : 'Rupture de stock');
    meta.textContent = lines.join(' · ');

    if (photo) {
      img.src = photo;
      img.alt = title.textContent;
      img.hidden = false;
      noPhoto.hidden = true;
    } else {
      img.removeAttribute('src');
      img.hidden = true;
      noPhoto.hidden = false;
    }

    modal.hidden = false;
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modal.hidden = true;
    document.body.style.overflow = '';
  }

  document.querySelectorAll('.btn-voir-article').forEach(function (btn) {
    btn.addEventListener('click', function () {
      openModal(btn);
    });
  });

  modal.querySelectorAll('[data-close-modal]').forEach(function (el) {
    el.addEventListener('click', closeModal);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.hidden) closeModal();
  });

  // Commander : placeholder paiement (API à brancher plus tard)
  document.querySelectorAll('.btn-commander').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (btn.disabled) return;
      // TODO: intégrer l'API de paiement ici
      alert('Paiement bientôt disponible pour « ' + (btn.getAttribute('data-nom') || 'cet article') + ' ».');
    });
  });
})();
