(function () {
  var color = document.getElementById('couleur_fond');
  var hex = document.getElementById('couleur_fond_hex');
  var preview = document.getElementById('couleurFondPreview');
  if (!color || !hex) return;

  function normalize(v) {
    v = String(v || '').trim();
    if (!v) return '#ffffff';
    if (v[0] !== '#') v = '#' + v;
    if (/^#[A-Fa-f0-9]{3}$/.test(v) || /^#[A-Fa-f0-9]{6}$/.test(v)) return v.toLowerCase();
    return null;
  }

  function syncFromColor() {
    hex.value = color.value;
    if (preview) preview.style.background = color.value;
  }

  function syncFromHex() {
    var v = normalize(hex.value);
    if (!v) return;
    hex.value = v;
    try {
      color.value = v.length === 4
        ? '#' + v[1] + v[1] + v[2] + v[2] + v[3] + v[3]
        : v;
    } catch (e) {}
    if (preview) preview.style.background = color.value;
  }

  color.addEventListener('input', syncFromColor);
  hex.addEventListener('change', syncFromHex);
  hex.addEventListener('blur', syncFromHex);
})();
