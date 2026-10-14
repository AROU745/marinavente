(function () {
  const root = document.getElementById('bccApp');
  const dataEl = document.getElementById('bccProduitsData');
  if (!root || !dataEl) return;

  const produits = JSON.parse(dataEl.textContent || '[]');
  let flagBase = '/images/business-intl/flags/';
  const sampleFlag = document.querySelector('.bcc-pays-filters img');
  if (sampleFlag) {
    const src = sampleFlag.getAttribute('src') || '';
    flagBase = src.replace(/[^/]+$/, '');
  }
  let paysActif = root.dataset.paysActif || 'tous';
  let catActif = 'all';
  let query = '';
  let sort = 'popularite';
  let cart = [];
  let wish = new Set();

  const grid = document.getElementById('bccGrid');
  const empty = document.getElementById('bccEmpty');
  const countEl = document.getElementById('bccCount');
  const paysLabel = document.getElementById('bccPaysLabel');
  const cartBadge = document.getElementById('bccCartBadge');
  const cartModal = document.getElementById('bccCartModal');
  const payModal = document.getElementById('bccPayModal');
  const cartList = document.getElementById('bccCartList');
  const cartTotal = document.getElementById('bccCartTotal');
  const checkoutBtn = document.getElementById('bccCheckout');

  const fmt = (n) => Number(n).toLocaleString('fr-FR') + ' FCFA';
  const onlyDigits = (v) => String(v || '').replace(/\D/g, '');

  const paysNames = {
    togo: 'Togo',
    chine: 'Chine',
    turquie: 'Turquie',
    nigeria: 'Nigeria',
    tous: 'Tous les pays',
  };

  function openModal(m) {
    m.hidden = false;
    document.body.style.overflow = 'hidden';
  }
  function closeModal(m) {
    m.hidden = true;
    if (cartModal.hidden && payModal.hidden) document.body.style.overflow = '';
  }

  function baseList() {
    let list = produits.slice();
    if (paysActif !== 'tous') {
      list = list.filter((p) => p.paysSlug === paysActif);
    }
    if (query) {
      const q = query.toLowerCase();
      list = list.filter(
        (p) =>
          p.title.toLowerCase().includes(q) ||
          p.categorie.toLowerCase().includes(q) ||
          p.pays.toLowerCase().includes(q)
      );
    }
    return list;
  }

  function filtered() {
    let list = baseList();
    if (catActif !== 'all') {
      list = list.filter((p) => p.categorie === catActif);
    }
    if (sort === 'prix-asc') list.sort((a, b) => a.prix - b.prix);
    else if (sort === 'prix-desc') list.sort((a, b) => b.prix - a.prix);
    else if (sort === 'nom') list.sort((a, b) => a.title.localeCompare(b.title, 'fr'));
    else list.sort((a, b) => (b.popularite || 0) - (a.popularite || 0));
    return list;
  }

  function syncCatCounts() {
    const list = baseList();
    const counts = {};
    list.forEach((p) => {
      counts[p.categorie] = (counts[p.categorie] || 0) + 1;
    });
    document.querySelectorAll('#bccCats button').forEach((btn) => {
      const cat = btn.dataset.cat;
      const em = btn.querySelector('em');
      if (!em) return;
      const n = cat === 'all' ? list.length : counts[cat] || 0;
      em.textContent = String(n);
      btn.closest('li').hidden = cat !== 'all' && n === 0;
    });
    if (catActif !== 'all' && !(counts[catActif] > 0)) {
      catActif = 'all';
      document.querySelectorAll('#bccCats button').forEach((b) => {
        b.classList.toggle('is-active', b.dataset.cat === 'all');
      });
    }
  }

  function syncPaysCounts() {
    const counts = { tous: produits.length };
    produits.forEach((p) => {
      counts[p.paysSlug] = (counts[p.paysSlug] || 0) + 1;
    });
    document.querySelectorAll('#bccPaysFilters em').forEach((em) => {
      const input = em.closest('label')?.querySelector('input[name="bccPays"]');
      if (!input) return;
      em.textContent = String(counts[input.value] || 0);
    });
  }

  function render() {
    syncCatCounts();
    syncPaysCounts();
    const list = filtered();
    paysLabel.textContent = paysNames[paysActif] || paysActif;
    countEl.textContent = list.length + ' produit' + (list.length > 1 ? 's' : '') + ' trouvé' + (list.length > 1 ? 's' : '');
    empty.hidden = list.length > 0;
    grid.innerHTML = list
      .map((p) => {
        const wished = wish.has(p.slug) ? ' is-on' : '';
        return (
          '<article class="bcc-card" data-slug="' + p.slug + '">' +
          '<div class="bcc-card-media"><i class="' + p.icon + '"></i></div>' +
          '<div class="bcc-card-body">' +
          '<h3>' + p.title + '</h3>' +
          '<p class="bcc-origin">Origine : ' + p.pays +
          ' <img src="' + flagBase + p.flagCode + '.svg" alt=""></p>' +
          '<p class="bcc-price">' + fmt(p.prix) + '</p>' +
          '<div class="bcc-card-actions">' +
          '<button type="button" class="bcc-wish' + wished + '" data-wish="' + p.slug + '" aria-label="Favoris"><i class="fas fa-heart"></i></button>' +
          '<a class="bcc-add" href="/services/business-center/commande/' + encodeURIComponent(p.slug) + '"><i class="fas fa-cart-plus"></i> Ajouter au panier</a>' +
          '</div></div></article>'
        );
      })
      .join('');
  }

  function cartQty() {
    return cart.reduce((s, i) => s + i.qty, 0);
  }
  function cartSum() {
    return cart.reduce((s, i) => s + i.prix * i.qty, 0);
  }

  function syncCart() {
    cartBadge.textContent = String(cartQty());
    if (!cart.length) {
      cartList.innerHTML = '<p style="color:#5F6B64;margin:0">Votre panier est vide.</p>';
      cartTotal.textContent = fmt(0);
      checkoutBtn.disabled = true;
      return;
    }
    cartList.innerHTML = cart
      .map(
        (i) =>
          '<div class="bcc-cart-item"><div><strong>' +
          i.title +
          '</strong><br><small>' +
          i.qty +
          ' × ' +
          fmt(i.prix) +
          '</small></div><button type="button" data-rm="' +
          i.slug +
          '"><i class="fas fa-trash"></i></button></div>'
      )
      .join('');
    cartTotal.textContent = fmt(cartSum());
    checkoutBtn.disabled = false;
  }

  function addToCart(slug) {
    const p = produits.find((x) => x.slug === slug);
    if (!p) return;
    const existing = cart.find((x) => x.slug === slug);
    if (existing) existing.qty += 1;
    else cart.push({ slug: p.slug, title: p.title, prix: p.prix, qty: 1 });
    syncCart();
  }

  function setPays(slug, pushUrl) {
    paysActif = slug;
    document.querySelectorAll('input[name="bccPays"]').forEach((r) => {
      r.checked = r.value === slug;
    });
    const sel = document.getElementById('bccPaysSelect');
    if (sel) sel.value = slug;
    if (pushUrl) {
      const url = '/services/business-center/catalogue/' + slug;
      history.replaceState(null, '', url);
    }
    render();
  }

  document.getElementById('bccCats').addEventListener('click', (e) => {
    const btn = e.target.closest('[data-cat]');
    if (!btn) return;
    catActif = btn.dataset.cat;
    document.querySelectorAll('#bccCats button').forEach((b) => b.classList.toggle('is-active', b === btn));
    render();
  });

  document.getElementById('bccPaysFilters').addEventListener('change', (e) => {
    if (e.target.name === 'bccPays') setPays(e.target.value, true);
  });

  document.getElementById('bccPaysSelect').addEventListener('change', (e) => {
    setPays(e.target.value, true);
  });

  document.getElementById('bccSearch').addEventListener('input', (e) => {
    query = e.target.value.trim();
    render();
  });

  document.getElementById('bccSort').addEventListener('change', (e) => {
    sort = e.target.value;
    render();
  });

  grid.addEventListener('click', (e) => {
    const w = e.target.closest('[data-wish]');
    if (w) {
      e.preventDefault();
      const s = w.dataset.wish;
      if (wish.has(s)) wish.delete(s);
      else wish.add(s);
      render();
    }
  });

  document.getElementById('bccCartOpen').addEventListener('click', () => {
    syncCart();
    openModal(cartModal);
  });

  cartList.addEventListener('click', (e) => {
    const rm = e.target.closest('[data-rm]');
    if (!rm) return;
    cart = cart.filter((i) => i.slug !== rm.dataset.rm);
    syncCart();
  });

  checkoutBtn.addEventListener('click', () => {
    if (!cart.length) return;
    window.location.href = '/services/business-center/commande/' + encodeURIComponent(cart[0].slug);
  });

  document.querySelectorAll('[data-bcc-close]').forEach((el) => {
    el.addEventListener('click', () => {
      const which = el.getAttribute('data-bcc-close');
      closeModal(which === 'pay' ? payModal : cartModal);
    });
  });

  const payCard = document.getElementById('bccPayCard');
  const payExp = document.getElementById('bccPayExp');
  const payCvc = document.getElementById('bccPayCvc');
  payCard.addEventListener('input', () => {
    payCard.value = onlyDigits(payCard.value).slice(0, 16).replace(/(\d{4})(?=\d)/g, '$1 ').trim();
  });
  payExp.addEventListener('input', () => {
    const d = onlyDigits(payExp.value).slice(0, 4);
    payExp.value = d.length <= 2 ? d : d.slice(0, 2) + '/' + d.slice(2);
  });
  payCvc.addEventListener('input', () => {
    payCvc.value = onlyDigits(payCvc.value).slice(0, 4);
  });

  document.getElementById('bccPaySubmit').addEventListener('click', () => {
    const nom = document.getElementById('bccPayNom');
    if (!nom.value.trim()) {
      nom.focus();
      return;
    }
    if (onlyDigits(payCard.value).length < 16) {
      payCard.focus();
      return;
    }
    if (onlyDigits(payExp.value).length < 4) {
      payExp.focus();
      return;
    }
    if (onlyDigits(payCvc.value).length < 3) {
      payCvc.focus();
      return;
    }
    const total = cartSum();
    const n = nom.value.trim();
    document.getElementById('bccPayFields').hidden = true;
    document.getElementById('bccPaySuccess').hidden = false;
    document.getElementById('bccPaySuccessMsg').textContent =
      'Merci ' + n + '. Votre commande de ' + fmt(total) + ' a été confirmée. Livraison prévue au Togo.';
    cart = [];
    syncCart();
  });

  // flag path already resolved above

  render();
  syncCart();
})();
