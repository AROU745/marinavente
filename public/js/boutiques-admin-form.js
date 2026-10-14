(function () {
  var banList = document.getElementById('adminBanniereList');
  var prodList = document.getElementById('adminProduitsList');
  var banTpl = document.getElementById('adminBanTpl');
  var prodTpl = document.getElementById('adminProdTpl');
  var addBan = document.getElementById('adminAddBanBtn');
  var addProd = document.getElementById('adminAddProdBtn');
  var banCounter = document.getElementById('adminBanCounter');
  var MAX_BAN = 3;

  function renumberBan() {
    if (!banList) return;
    var rows = banList.querySelectorAll('.banniere-row');
    rows.forEach(function (row, i) {
      var num = row.querySelector('.ban-num');
      if (num) num.textContent = String(i + 1);
      row.querySelectorAll('[name]').forEach(function (el) {
        el.name = el.name.replace(/banniere\[\d+\]/, 'banniere[' + i + ']');
      });
    });
    if (banCounter) {
      banCounter.innerHTML = 'Slides : <strong>' + rows.length + '</strong> / ' + MAX_BAN;
    }
    if (addBan) {
      addBan.disabled = rows.length >= MAX_BAN;
      addBan.style.opacity = addBan.disabled ? '0.5' : '1';
    }
  }

  function renumberProd() {
    if (!prodList) return;
    prodList.querySelectorAll('.boutique-produit-row').forEach(function (row, i) {
      var num = row.querySelector('.produit-num');
      if (num) num.textContent = String(i + 1);
      row.querySelectorAll('[name]').forEach(function (el) {
        el.name = el.name.replace(/produits\[\d+\]/, 'produits[' + i + ']');
      });
    });
  }

  function bindBanRemove(row) {
    var btn = row.querySelector('.btn-remove-ban');
    if (!btn) return;
    btn.addEventListener('click', function () {
      row.remove();
      renumberBan();
    });
  }

  function bindProdRemove(row) {
    var btn = row.querySelector('.btn-remove-produit');
    if (!btn) return;
    btn.addEventListener('click', function () {
      row.remove();
      renumberProd();
    });
  }

  if (addBan && banTpl && banList) {
    addBan.addEventListener('click', function () {
      if (banList.querySelectorAll('.banniere-row').length >= MAX_BAN) return;
      var idx = banList.querySelectorAll('.banniere-row').length;
      var clone = banTpl.content.cloneNode(true);
      clone.querySelectorAll('[name]').forEach(function (el) {
        el.name = el.name.replace(/__INDEX__/g, String(idx));
      });
      banList.appendChild(clone);
      bindBanRemove(banList.lastElementChild);
      renumberBan();
    });
    banList.querySelectorAll('.banniere-row').forEach(bindBanRemove);
  }

  if (addProd && prodTpl && prodList) {
    addProd.addEventListener('click', function () {
      var idx = prodList.querySelectorAll('.boutique-produit-row').length;
      var clone = prodTpl.content.cloneNode(true);
      clone.querySelectorAll('[name]').forEach(function (el) {
        el.name = el.name.replace(/__INDEX__/g, String(idx));
      });
      prodList.appendChild(clone);
      bindProdRemove(prodList.lastElementChild);
      renumberProd();
    });
    prodList.querySelectorAll('.boutique-produit-row').forEach(bindProdRemove);
  }

  renumberBan();
  renumberProd();
})();
