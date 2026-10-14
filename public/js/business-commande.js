(function () {
  const root = document.getElementById('bcoApp');
  if (!root) return;

  const prix = Number(root.dataset.prix || 0);
  const title = root.dataset.title || 'Produit';
  const catalogue = root.dataset.catalogue || '/services/business-center/catalogue/tous';

  const qtyInput = document.getElementById('bcoQty');
  const specs = document.getElementById('bcoSpecs');
  const specsCount = document.getElementById('bcoSpecsCount');
  const photoInput = document.getElementById('bcoPhotoInput');
  const photosWrap = document.getElementById('bcoPhotos');
  const photoAdd = document.getElementById('bcoPhotoAdd');
  const payBlock = document.getElementById('bcoPayBlock');
  const cardFields = document.getElementById('bcoCardFields');
  const mobileFields = document.getElementById('bcoMobileFields');
  const submitLabel = document.getElementById('bcoSubmitLabel');
  const form = document.getElementById('bcoForm');
  const success = document.getElementById('bcoSuccess');

  let mode = 'devis';
  let photos = [];
  const MAX_PHOTOS = 5;

  const fmt = (n) => Number(n).toLocaleString('fr-FR') + ' FCFA';
  const onlyDigits = (v) => String(v || '').replace(/\D/g, '');

  function getQty() {
    let q = parseInt(qtyInput.value, 10);
    if (isNaN(q) || q < 1) q = 1;
    if (q > 99) q = 99;
    qtyInput.value = String(q);
    return q;
  }

  function syncSummary() {
    const q = getQty();
    const total = prix * q;
    document.getElementById('bcoSumQty').textContent = String(q);
    document.getElementById('bcoSumSub').textContent = fmt(total);
    document.getElementById('bcoSumTotal').textContent = fmt(total);
  }

  function setMode(m) {
    mode = m;
    document.querySelectorAll('.bco-mode').forEach((btn) => {
      btn.classList.toggle('is-active', btn.dataset.mode === m);
    });
    payBlock.hidden = m !== 'achat';
    submitLabel.textContent = m === 'achat' ? 'Valider ma commande' : 'Valider ma demande';
  }

  function syncPayMethod() {
    const method = (document.querySelector('input[name="bcoPayMethod"]:checked') || {}).value || 'mobile';
    document.querySelectorAll('.bco-pay-opt').forEach((opt) => {
      const input = opt.querySelector('input');
      opt.classList.toggle('is-active', input && input.checked);
    });
    cardFields.hidden = method !== 'carte';
    mobileFields.hidden = method !== 'mobile';
  }

  function renderPhotos() {
    photosWrap.querySelectorAll('.bco-photo-thumb').forEach((el) => el.remove());
    photos.forEach((p, idx) => {
      const wrap = document.createElement('div');
      wrap.className = 'bco-photo-thumb';
      wrap.innerHTML =
        '<img src="' +
        p.url +
        '" alt="Photo ' +
        (idx + 1) +
        '"><button type="button" data-rm-photo="' +
        idx +
        '" aria-label="Supprimer"><i class="fas fa-times"></i></button>';
      photosWrap.insertBefore(wrap, photoAdd);
    });
    photoAdd.style.display = photos.length >= MAX_PHOTOS ? 'none' : '';
  }

  document.getElementById('bcoQtyMinus').addEventListener('click', () => {
    qtyInput.value = String(Math.max(1, getQty() - 1));
    syncSummary();
  });
  document.getElementById('bcoQtyPlus').addEventListener('click', () => {
    qtyInput.value = String(Math.min(99, getQty() + 1));
    syncSummary();
  });
  qtyInput.addEventListener('change', syncSummary);
  qtyInput.addEventListener('input', syncSummary);

  specs.addEventListener('input', () => {
    specsCount.textContent = String(specs.value.length);
  });

  photoInput.addEventListener('change', () => {
    const files = Array.from(photoInput.files || []);
    files.forEach((file) => {
      if (photos.length >= MAX_PHOTOS) return;
      if (!file.type.startsWith('image/')) return;
      const url = URL.createObjectURL(file);
      photos.push({ url: url, name: file.name });
    });
    photoInput.value = '';
    renderPhotos();
  });

  photosWrap.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-rm-photo]');
    if (!btn) return;
    const idx = Number(btn.getAttribute('data-rm-photo'));
    const removed = photos.splice(idx, 1)[0];
    if (removed) URL.revokeObjectURL(removed.url);
    renderPhotos();
  });

  document.querySelectorAll('.bco-mode').forEach((btn) => {
    btn.addEventListener('click', () => setMode(btn.dataset.mode));
  });

  document.querySelectorAll('input[name="bcoPayMethod"]').forEach((r) => {
    r.addEventListener('change', syncPayMethod);
  });

  const card = document.getElementById('bcoCard');
  const exp = document.getElementById('bcoExp');
  const cvc = document.getElementById('bcoCvc');
  card.addEventListener('input', () => {
    card.value = onlyDigits(card.value).slice(0, 16).replace(/(\d{4})(?=\d)/g, '$1 ').trim();
  });
  exp.addEventListener('input', () => {
    const d = onlyDigits(exp.value).slice(0, 4);
    exp.value = d.length <= 2 ? d : d.slice(0, 2) + '/' + d.slice(2);
  });
  cvc.addEventListener('input', () => {
    cvc.value = onlyDigits(cvc.value).slice(0, 4);
  });

  document.getElementById('bcoReset').addEventListener('click', () => {
    form.reset();
    qtyInput.value = '1';
    specs.value = '';
    specsCount.textContent = '0';
    photos.forEach((p) => URL.revokeObjectURL(p.url));
    photos = [];
    renderPhotos();
    setMode('devis');
    syncPayMethod();
    syncSummary();
    success.hidden = true;
    form.querySelectorAll('.bco-card, .bco-actions').forEach((el) => {
      el.hidden = false;
    });
  });

  document.getElementById('bcoSubmit').addEventListener('click', () => {
    const nom = document.getElementById('bcoNom');
    if (!nom.value.trim()) {
      nom.focus();
      nom.reportValidity?.();
      return;
    }

    if (mode === 'achat') {
      const method = (document.querySelector('input[name="bcoPayMethod"]:checked') || {}).value;
      if (method === 'carte') {
        if (onlyDigits(card.value).length < 16) {
          card.focus();
          return;
        }
        if (onlyDigits(exp.value).length < 4) {
          exp.focus();
          return;
        }
        if (onlyDigits(cvc.value).length < 3) {
          cvc.focus();
          return;
        }
      }
      if (method === 'mobile') {
        const mob = document.getElementById('bcoMobileNum');
        if (onlyDigits(mob.value).length < 8) {
          mob.focus();
          return;
        }
      }
    }

    const q = getQty();
    const total = fmt(prix * q);
    const n = nom.value.trim();

    form.querySelectorAll('.bco-card, .bco-actions').forEach((el) => {
      el.hidden = true;
    });
    success.hidden = false;

    if (mode === 'devis') {
      document.getElementById('bcoSuccessTitle').textContent = 'Demande de devis envoyée';
      document.getElementById('bcoSuccessMsg').textContent =
        'Merci ' +
        n +
        '. Votre demande pour « ' +
        title +
        ' » (×' +
        q +
        ') a été enregistrée. Un devis personnalisé vous sera communiqué sous 24h. Total estimé : ' +
        total +
        '.';
    } else {
      document.getElementById('bcoSuccessTitle').textContent = 'Commande confirmée';
      document.getElementById('bcoSuccessMsg').textContent =
        'Merci ' +
        n +
        '. Votre commande de « ' +
        title +
        ' » (×' +
        q +
        ') pour ' +
        total +
        ' a été confirmée. Livraison prévue selon l’adresse indiquée.';
    }
  });

  setMode('devis');
  syncPayMethod();
  syncSummary();
})();
