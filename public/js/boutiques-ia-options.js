(function () {
  var MAX_PHOTOS = 5;

  function panelFor(cb) {
    if (!cb) return null;
    if (cb.classList.contains('ia-photos-cb')) {
      var row = cb.closest('.boutique-produit-row');
      return row ? row.querySelector('.ia-photos-panel') : null;
    }
    if (cb.classList.contains('ia-video-cb')) {
      var rowV = cb.closest('.boutique-produit-row');
      return rowV ? rowV.querySelector('.ia-video-panel') : null;
    }
    return null;
  }

  function syncRow(row) {
    if (!row) return;
    var photos = row.querySelector('.ia-photos-cb');
    var video = row.querySelector('.ia-video-cb');
    var photosPanel = row.querySelector('.ia-photos-panel');
    var videoPanel = row.querySelector('.ia-video-panel');

    if (photosPanel) {
      photosPanel.classList.toggle('is-hidden', !(photos && photos.checked));
      if (!(photos && photos.checked)) resetPanel(photosPanel);
    }
    if (videoPanel) {
      videoPanel.classList.toggle('is-hidden', !(video && video.checked));
      if (!(video && video.checked)) resetPanel(videoPanel);
    }
  }

  function updateCounter(panel) {
    if (!panel) return;
    var input = panel.querySelector('.ia-sources-input');
    var counter = panel.querySelector('.ia-sources-count');
    if (!input || !counter) return;

    var n = input.files ? input.files.length : 0;
    var isVideo = panel.classList.contains('ia-video-panel');
    var unit = isVideo ? 'fichier' : 'photo';
    counter.textContent = n + '/' + MAX_PHOTOS + ' ' + unit + (n > 1 ? 's' : '');
    counter.classList.toggle('is-full', n >= MAX_PHOTOS);
    counter.classList.toggle('is-ok', n > 0 && n <= MAX_PHOTOS);

    if (n === 0) resetPanel(panel, true);
  }

  function resetPanel(panel, keepFiles) {
    if (!panel) return;
    var hidden = panel.querySelector('.ia-envoyer-flag');
    var status = panel.querySelector('.ia-envoyer-status');
    var btn = panel.querySelector('.ia-envoyer-btn');
    if (hidden) hidden.value = '0';
    if (status) {
      status.textContent = '';
      status.className = 'ia-envoyer-status';
    }
    if (btn) {
      btn.classList.remove('is-sent');
      btn.innerHTML = '<i class="fas fa-paper-plane"></i> Envoyer';
    }
    if (!keepFiles) {
      // nothing — files stay if only status reset
    }
  }

  function limitFiles(input) {
    if (!input.files || input.files.length <= MAX_PHOTOS) return;
    var dt = new DataTransfer();
    for (var i = 0; i < MAX_PHOTOS; i++) {
      dt.items.add(input.files[i]);
    }
    input.files = dt.files;
    alert('Maximum ' + MAX_PHOTOS + ' fichiers autorisés.');
  }

  function envoyerPanel(panel) {
    if (!panel) return;
    var row = panel.closest('.boutique-produit-row');
    var isVideo = panel.classList.contains('ia-video-panel');
    var cb = row.querySelector(isVideo ? '.ia-video-cb' : '.ia-photos-cb');
    var input = panel.querySelector('.ia-sources-input');
    var hidden = panel.querySelector('.ia-envoyer-flag');
    var status = panel.querySelector('.ia-envoyer-status');
    var btn = panel.querySelector('.ia-envoyer-btn');

    if (!(cb && cb.checked)) {
      alert(isVideo ? 'Cochez d’abord « 1 vidéo IA ».' : 'Cochez d’abord « 5 photos IA ».');
      return;
    }

    var n = input && input.files ? input.files.length : 0;
    var clip = panel.querySelector('.ia-video-clip-input');
    var hasClip = clip && clip.files && clip.files.length > 0;

    if (n < 1 && !hasClip) {
      alert(isVideo
        ? 'Ajoutez au moins 1 image source ou 1 vidéo source (max 5 images).'
        : 'Ajoutez au moins 1 photo (vous pouvez en envoyer moins de 5).');
      return;
    }
    if (n > MAX_PHOTOS) {
      alert('Maximum ' + MAX_PHOTOS + ' fichiers.');
      return;
    }

    if (hidden) hidden.value = '1';
    if (status) {
      var parts = [];
      if (n > 0) parts.push(n + (isVideo ? ' image' : ' photo') + (n > 1 ? 's' : ''));
      if (hasClip) parts.push('1 vidéo source');
      status.textContent = 'Commande ' + (isVideo ? 'vidéo' : 'photos') + ' prête (' + parts.join(' + ') + ') — envoi à l’admin à l’enregistrement.';
      status.className = 'ia-envoyer-status is-ready';
    }
    if (btn) {
      btn.classList.add('is-sent');
      btn.innerHTML = '<i class="fas fa-check"></i> Commande prête';
    }
  }

  function bindPanel(panel) {
    if (!panel || panel.dataset.iaBound) return;
    panel.dataset.iaBound = '1';

    var input = panel.querySelector('.ia-sources-input');
    if (input) {
      input.setAttribute('multiple', 'multiple');
      input.addEventListener('change', function () {
        limitFiles(input);
        updateCounter(panel);
        resetPanel(panel, true);
      });
    }

    var clip = panel.querySelector('.ia-video-clip-input');
    if (clip) {
      clip.addEventListener('change', function () {
        resetPanel(panel, true);
        updateCounter(panel);
      });
    }

    var btn = panel.querySelector('.ia-envoyer-btn');
    if (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        envoyerPanel(panel);
      });
    }

    updateCounter(panel);
  }

  function bindRow(row) {
    row.querySelectorAll('.ia-photos-cb, .ia-video-cb').forEach(function (cb) {
      cb.addEventListener('change', function () {
        syncRow(row);
      });
    });
    row.querySelectorAll('.ia-photos-panel, .ia-video-panel').forEach(bindPanel);
    syncRow(row);
  }

  document.querySelectorAll('.boutique-produit-row').forEach(bindRow);

  var lists = [
    document.getElementById('produitsList'),
    document.getElementById('adminProduitsList'),
  ];
  lists.forEach(function (list) {
    if (!list) return;
    list.addEventListener('change', function (e) {
      var row = e.target.closest('.boutique-produit-row');
      if (!row) return;
      if (e.target.classList.contains('ia-photos-cb') || e.target.classList.contains('ia-video-cb')) {
        syncRow(row);
      }
      if (e.target.classList.contains('ia-sources-input')) {
        limitFiles(e.target);
        updateCounter(e.target.closest('.ia-photos-panel, .ia-video-panel'));
      }
    });
    list.addEventListener('click', function (e) {
      var btn = e.target.closest('.ia-envoyer-btn');
      if (!btn) return;
      e.preventDefault();
      envoyerPanel(btn.closest('.ia-photos-panel, .ia-video-panel'));
    });
    var obs = new MutationObserver(function (mutations) {
      mutations.forEach(function (m) {
        m.addedNodes.forEach(function (node) {
          if (node.nodeType === 1 && node.classList.contains('boutique-produit-row')) {
            bindRow(node);
          }
        });
      });
    });
    obs.observe(list, { childList: true });
  });
})();
