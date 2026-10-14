(function () {
  var form = document.getElementById('boutiqueForm');
  if (!form) return;

  var MAX_BANNER = 3;
  var currentStep = 1;

  var panels = form.querySelectorAll('.wizard-panel');
  var steps = document.querySelectorAll('#wizardSteps .boutique-step-pill');
  var activer = document.getElementById('activerBanniere');
  var banBlock = document.getElementById('banniereBlock');
  var banList = document.getElementById('banniereList');
  var banTpl = document.getElementById('banniereRowTemplate');
  var addBanBtn = document.getElementById('addBanniereBtn');
  var banCounter = document.getElementById('bannerCounter');
  var produitsList = document.getElementById('produitsList');
  var prodTpl = document.getElementById('produitRowTemplate');
  var addProdBtn = document.getElementById('addProduitBtn');

  function syncRequired(step) {
    panels.forEach(function (p) {
      var n = parseInt(p.getAttribute('data-panel'), 10);
      var active = n === step;
      p.querySelectorAll('[data-req]').forEach(function (el) {
        if (active) {
          el.setAttribute('required', 'required');
        } else {
          el.removeAttribute('required');
        }
      });
    });
  }

  function goTo(step) {
    currentStep = step;
    panels.forEach(function (p) {
      var n = parseInt(p.getAttribute('data-panel'), 10);
      var active = n === step;
      p.hidden = !active;
      p.classList.toggle('is-active', active);
    });
    steps.forEach(function (s) {
      var n = parseInt(s.getAttribute('data-step'), 10);
      s.classList.toggle('is-active', n <= step);
    });
    syncRequired(step);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function validateStep1() {
    var nom = form.nom_entreprise;
    var tel = form.telephone;
    if (!nom.value.trim()) {
      nom.focus();
      alert('Indiquez le nom de l\'entreprise.');
      return false;
    }
    if (!tel.value.trim()) {
      tel.focus();
      alert('Indiquez le numéro de téléphone.');
      return false;
    }
    return true;
  }

  function banCount() {
    return banList ? banList.querySelectorAll('.banniere-row').length : 0;
  }

  function updateBanUI() {
    var n = banCount();
    if (banCounter) {
      banCounter.innerHTML = 'Slides bannière : <strong>' + n + '</strong> / ' + MAX_BANNER;
    }
    if (addBanBtn) {
      addBanBtn.disabled = n >= MAX_BANNER || !(activer && activer.checked);
      addBanBtn.style.opacity = addBanBtn.disabled ? '0.5' : '1';
    }
    if (banList) {
      banList.querySelectorAll('.ban-num').forEach(function (el, i) {
        el.textContent = String(i + 1);
      });
      banList.querySelectorAll('.banniere-row').forEach(function (row, i) {
        row.querySelectorAll('[name]').forEach(function (el) {
          el.name = el.name.replace(/banniere\[\d+\]/, 'banniere[' + i + ']');
        });
      });
    }
  }

  function toggleBanniere() {
    var on = activer && activer.checked;
    if (banBlock) {
      banBlock.classList.toggle('is-disabled', !on);
    }
    if (!on && banList) {
      banList.innerHTML = '';
    }
    updateBanUI();
  }

  function addBanniereSlide() {
    if (!banTpl || !banList || banCount() >= MAX_BANNER) return;
    if (activer && !activer.checked) {
      activer.checked = true;
      toggleBanniere();
    }
    var idx = banCount();
    var clone = banTpl.content.cloneNode(true);
    clone.querySelectorAll('[name]').forEach(function (el) {
      el.name = el.name.replace(/__INDEX__/g, String(idx));
    });
    banList.appendChild(clone);
    bindBanRemove(banList.lastElementChild);
    updateBanUI();
  }

  function bindBanRemove(row) {
    var btn = row.querySelector('.btn-remove-ban');
    if (!btn) return;
    btn.addEventListener('click', function () {
      row.remove();
      updateBanUI();
    });
  }

  function renumberProduits() {
    if (!produitsList) return;
    produitsList.querySelectorAll('.boutique-produit-row').forEach(function (row, i) {
      var num = row.querySelector('.produit-num');
      if (num) num.textContent = String(i + 1);
      row.querySelectorAll('[name]').forEach(function (el) {
        el.name = el.name.replace(/produits\[\d+\]/, 'produits[' + i + ']');
      });
    });
  }

  function bindProdRemove(row) {
    var btn = row.querySelector('.btn-remove-produit');
    if (!btn) return;
    btn.addEventListener('click', function () {
      row.remove();
      renumberProduits();
    });
  }

  document.getElementById('toStep2') && document.getElementById('toStep2').addEventListener('click', function () {
    if (validateStep1()) goTo(2);
  });
  document.getElementById('backStep1') && document.getElementById('backStep1').addEventListener('click', function () {
    goTo(1);
  });
  document.getElementById('backStep2') && document.getElementById('backStep2').addEventListener('click', function () {
    goTo(2);
  });
  document.getElementById('skipBanniere') && document.getElementById('skipBanniere').addEventListener('click', function () {
    if (activer) activer.checked = false;
    toggleBanniere();
    goTo(3);
  });
  document.getElementById('finBanniere') && document.getElementById('finBanniere').addEventListener('click', function () {
    if (activer && activer.checked && banCount() === 0) {
      alert('Ajoutez au moins 1 slide, ou cliquez sur « Passer sans bannière ».');
      return;
    }
    if (activer && activer.checked) {
      var ok = true;
      banList.querySelectorAll('.banniere-row').forEach(function (row) {
        var nom = row.querySelector('input[name*="[nom]"]');
        var photo = row.querySelector('.ban-photo');
        if (!nom || !nom.value.trim()) ok = false;
        if (!photo || !photo.files || !photo.files.length) ok = false;
      });
      if (!ok) {
        alert('Chaque slide doit avoir un nom et une photo.');
        return;
      }
    }
    goTo(3);
  });

  if (activer) activer.addEventListener('change', toggleBanniere);
  if (addBanBtn) addBanBtn.addEventListener('click', addBanniereSlide);

  if (addProdBtn && prodTpl && produitsList) {
    addProdBtn.addEventListener('click', function () {
      var idx = produitsList.querySelectorAll('.boutique-produit-row').length;
      var clone = prodTpl.content.cloneNode(true);
      clone.querySelectorAll('[name]').forEach(function (el) {
        el.name = el.name.replace(/__INDEX__/g, String(idx));
      });
      produitsList.appendChild(clone);
      bindProdRemove(produitsList.lastElementChild);
      renumberProduits();
    });
    produitsList.querySelectorAll('.boutique-produit-row').forEach(bindProdRemove);
  }

  // Si erreurs de validation : ouvrir l'étape catalogue
  if (document.querySelector('.boutique-form-errors')) {
    goTo(3);
  }

  toggleBanniere();
  updateBanUI();
  syncRequired(currentStep);
})();
