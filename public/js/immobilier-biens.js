(function () {
  const root = document.getElementById('imblApp');
  const dataEl = document.getElementById('imblBiensData');
  const metaEl = document.getElementById('imblMetaData');
  if (!root || !dataEl || !metaEl) return;

  const biens = JSON.parse(dataEl.textContent || '[]');
  const meta = JSON.parse(metaEl.textContent || '{}');
  const imgBase = meta.imgBase || '/images/immobilier';
  const priceSuffix = meta.priceSuffix || 'FCFA';
  const label = meta.label || 'biens';
  const type = meta.type || root.dataset.type || 'vente';

  const PER_PAGE = 9;
  let page = 1;
  let view = 'grid';
  let wish = new Set();
  let filters = { q: '', pays: '', ville: '', budget: '', chambres: '', surface: '' };
  let sort = 'recent';

  const grid = document.getElementById('imblGrid');
  const empty = document.getElementById('imblEmpty');
  const countEl = document.getElementById('imblCount');
  const pagination = document.getElementById('imblPagination');
  const villeSel = document.getElementById('imblVille');
  const detailModal = document.getElementById('imblDetailModal');
  const detailBody = document.getElementById('imblDetailBody');
  const rdvModal = document.getElementById('imblRdvModal');

  const fmt = (n) => Number(n).toLocaleString('fr-FR') + ' ' + priceSuffix;
  const badgeLabel = { nouveau: 'NOUVEAU', une: 'À LA UNE', promo: 'PROMO' };
  const paysSel = document.getElementById('imblPays');

  const villesParPays = {
    Togo: [
      'Lomé',
      'Kara',
      'Sokodé',
      'Kpalimé',
      'Atakpamé',
      'Dapaong',
      'Tsévié',
      'Aného',
      'Vogan',
      'Bassar',
      'Mango',
      'Notsé',
      'Tabligbo',
      'Badou',
      'Blitta',
      'Sotouboua',
      'Pagouda',
      'Kandé',
      'Bafilo',
      'Amlamé',
      'Kévé',
      'Afagnan',
      'Togoville',
      'Davie',
      'Agou',
      'Amou',
    ],
    "Côte d'Ivoire": [
      'Abidjan',
      'Bouaké',
      'Yamoussoukro',
      'San-Pédro',
      'Korhogo',
      'Daloa',
      'Man',
      'Gagnoa',
      'Abengourou',
      'Divo',
      'Anyama',
      'Grand-Bassam',
      'Bingerville',
      'Dabou',
      'Agboville',
      'Bondoukou',
      'Odienné',
      'Séguéla',
      'Ferkessédougou',
      'Bouaflé',
      'Issia',
      'Sinfra',
      'Soubré',
      'Adzopé',
      'Aboisso',
      'Daoukro',
      'Katiola',
      'Boundiali',
      'Guiglo',
      'Duékoué',
      'Jacqueville',
      'Grand-Lahou',
      'Sassandra',
      'Tabou',
    ],
  };

  function fillVilles(pays, keepVille) {
    const previous = keepVille ? villeSel.value : '';
    villeSel.innerHTML = '<option value="">Toutes les villes</option>';

    let villes = [];
    if (pays && villesParPays[pays]) {
      villes = villesParPays[pays].slice();
    } else {
      villes = [...villesParPays.Togo, ...villesParPays["Côte d'Ivoire"]];
    }

    // Inclure aussi les villes présentes dans les biens (quartiers/villes custom)
    biens.forEach((b) => {
      if ((!pays || b.pays === pays) && b.ville && !villes.includes(b.ville)) {
        villes.push(b.ville);
      }
    });

    villes.sort((a, b) => a.localeCompare(b, 'fr'));
    villes.forEach((v) => {
      const opt = document.createElement('option');
      opt.value = v;
      opt.textContent = v;
      villeSel.appendChild(opt);
    });

    if (previous && [...villeSel.options].some((o) => o.value === previous)) {
      villeSel.value = previous;
    } else {
      villeSel.value = '';
    }
  }

  paysSel.addEventListener('change', () => {
    fillVilles(paysSel.value, false);
    readFilters();
    page = 1;
    render();
  });

  function readFilters() {
    filters = {
      q: (document.getElementById('imblQ').value || '').trim().toLowerCase(),
      pays: document.getElementById('imblPays').value,
      ville: document.getElementById('imblVille').value,
      budget: document.getElementById('imblBudget').value,
      chambres: document.getElementById('imblChambres').value,
      surface: document.getElementById('imblSurface').value,
    };
  }

  function filtered() {
    let list = biens.slice();
    if (filters.q) {
      list = list.filter(
        (b) =>
          b.title.toLowerCase().includes(filters.q) ||
          b.ville.toLowerCase().includes(filters.q) ||
          b.quartier.toLowerCase().includes(filters.q) ||
          b.pays.toLowerCase().includes(filters.q)
      );
    }
    if (filters.pays) list = list.filter((b) => b.pays === filters.pays);
    if (filters.ville) list = list.filter((b) => b.ville === filters.ville);
    if (filters.budget) list = list.filter((b) => b.prix <= Number(filters.budget));
    if (filters.chambres) list = list.filter((b) => (b.chambres || 0) >= Number(filters.chambres));
    if (filters.surface) list = list.filter((b) => (b.surface || 0) >= Number(filters.surface));

    if (sort === 'prix-asc') list.sort((a, b) => a.prix - b.prix);
    else if (sort === 'prix-desc') list.sort((a, b) => b.prix - a.prix);
    else if (sort === 'surface') list.sort((a, b) => (b.surface || 0) - (a.surface || 0));

    return list;
  }

  function cardHtml(b) {
    const badge = b.badge
      ? '<span class="imbl-badge imbl-badge-' + b.badge + '">' + (badgeLabel[b.badge] || b.badge) + '</span>'
      : '';
    const wished = wish.has(b.slug) ? ' is-on' : '';
    let specs = '';
    if (type === 'terrains') {
      specs = '<span><i class="fas fa-ruler-combined"></i> ' + b.surface + ' m²</span>';
    } else {
      if (b.chambres) specs += '<span><i class="fas fa-bed"></i> ' + b.chambres + '</span>';
      if (b.sdb) specs += '<span><i class="fas fa-bath"></i> ' + b.sdb + '</span>';
      specs += '<span><i class="fas fa-ruler-combined"></i> ' + b.surface + ' m²</span>';
    }
    const price =
      (b.prixAvant ? '<s>' + Number(b.prixAvant).toLocaleString('fr-FR') + '</s>' : '') + fmt(b.prix);
    const rendement = b.rendement
      ? '<div class="imbl-rendement">Rendement estimé : ' + b.rendement + '</div>'
      : '';

    return (
      '<article class="imbl-card" data-slug="' +
      b.slug +
      '">' +
      '<div class="imbl-card-media">' +
      '<img src="' +
      imgBase +
      '/' +
      b.image +
      '" alt="' +
      b.title +
      '">' +
      '<div class="imbl-badges">' +
      badge +
      '</div>' +
      '<button type="button" class="imbl-wish' +
      wished +
      '" data-wish="' +
      b.slug +
      '" aria-label="Favoris"><i class="fas fa-heart"></i></button>' +
      '</div>' +
      '<div class="imbl-card-body">' +
      '<h3>' +
      b.title +
      '</h3>' +
      '<p class="imbl-loc"><i class="fas fa-map-marker-alt"></i> ' +
      b.ville +
      ', ' +
      b.quartier +
      ' — ' +
      b.pays +
      '</p>' +
      '<div class="imbl-specs">' +
      specs +
      '</div>' +
      '<p class="imbl-price">' +
      price +
      '</p>' +
      rendement +
      '<a href="/services/immobilier-btp/biens/' +
      encodeURIComponent(type) +
      '/' +
      encodeURIComponent(b.slug) +
      '" class="imbl-details">Voir les détails</a>' +
      '</div></article>'
    );
  }

  function render() {
    const list = filtered();
    const total = list.length;
    const pages = Math.max(1, Math.ceil(total / PER_PAGE));
    if (page > pages) page = pages;
    const start = (page - 1) * PER_PAGE;
    const slice = list.slice(start, start + PER_PAGE);

    countEl.textContent =
      total === 0
        ? 'Aucun résultat'
        : 'Affichage ' +
          (start + 1) +
          ' - ' +
          (start + slice.length) +
          ' sur ' +
          total +
          ' ' +
          label;

    empty.hidden = slice.length > 0;
    grid.classList.toggle('is-list', view === 'list');
    grid.innerHTML = slice.map(cardHtml).join('');

    let pagesHtml = '';
    for (let i = 1; i <= pages; i++) {
      if (pages > 7 && i > 3 && i < pages - 1 && Math.abs(i - page) > 1) {
        if (!pagesHtml.endsWith('…')) pagesHtml += '<span>…</span>';
        continue;
      }
      pagesHtml +=
        '<button type="button" data-page="' +
        i +
        '"' +
        (i === page ? ' class="is-active"' : '') +
        '>' +
        i +
        '</button>';
    }

    pagination.innerHTML =
      '<span></span><div class="imbl-pages">' +
      '<button type="button" data-page="' +
      Math.max(1, page - 1) +
      '" aria-label="Précédent"><i class="fas fa-chevron-left"></i></button>' +
      pagesHtml +
      '<button type="button" data-page="' +
      Math.min(pages, page + 1) +
      '" aria-label="Suivant"><i class="fas fa-chevron-right"></i></button>' +
      '</div>';
  }

  function openModal(m) {
    m.hidden = false;
    document.body.style.overflow = 'hidden';
  }
  function closeModal(m) {
    m.hidden = true;
    if (detailModal.hidden && rdvModal.hidden) document.body.style.overflow = '';
  }

  document.getElementById('imblSearch').addEventListener('click', () => {
    readFilters();
    page = 1;
    render();
  });

  document.getElementById('imblReset').addEventListener('click', () => {
    document.getElementById('imblFilters').reset();
    fillVilles('', false);
    readFilters();
    page = 1;
    render();
  });

  document.getElementById('imblSort').addEventListener('change', (e) => {
    sort = e.target.value;
    page = 1;
    render();
  });

  document.querySelectorAll('[data-view]').forEach((btn) => {
    btn.addEventListener('click', () => {
      view = btn.dataset.view;
      document.querySelectorAll('[data-view]').forEach((b) => b.classList.toggle('is-active', b === btn));
      render();
    });
  });

  pagination.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-page]');
    if (!btn) return;
    page = Number(btn.dataset.page);
    render();
    root.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });

  grid.addEventListener('click', (e) => {
    const wishBtn = e.target.closest('[data-wish]');
    if (wishBtn) {
      e.preventDefault();
      const s = wishBtn.dataset.wish;
      if (wish.has(s)) wish.delete(s);
      else wish.add(s);
      render();
    }
  });

  document.querySelectorAll('[data-imbl-close]').forEach((el) => {
    el.addEventListener('click', () => closeModal(detailModal));
  });
  document.querySelectorAll('[data-imbl-close-rdv]').forEach((el) => {
    el.addEventListener('click', () => closeModal(rdvModal));
  });

  document.querySelectorAll('.imbl-rdv-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      closeModal(detailModal);
      document.getElementById('imblRdvForm').reset();
      document.getElementById('imblRdvFeedback').hidden = true;
      openModal(rdvModal);
    });
  });

  document.getElementById('imblRdvForm').addEventListener('submit', () => {
    const nom = document.getElementById('imblRdvNom');
    if (!nom.value.trim()) {
      nom.focus();
      return;
    }
    const fb = document.getElementById('imblRdvFeedback');
    fb.hidden = false;
    fb.textContent = 'Merci ' + nom.value.trim() + '. Votre demande de rendez-vous a été enregistrée.';
  });

  fillVilles(paysSel.value || '', false);
  // Adjust budget options for location (monthly)
  if (type === 'location') {
    const budget = document.getElementById('imblBudget');
    budget.innerHTML =
      '<option value="">Sans limite</option>' +
      '<option value="200000">200 000</option>' +
      '<option value="350000">350 000</option>' +
      '<option value="500000">500 000</option>' +
      '<option value="800000">800 000</option>' +
      '<option value="1500000">1 500 000</option>';
  }

  render();
})();
