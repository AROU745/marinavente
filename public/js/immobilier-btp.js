(function () {
  const modal = document.getElementById('imbDevisModal');
  if (!modal) return;

  const form = document.getElementById('imbDevisForm');
  const nom = document.getElementById('imbNom');
  const sujet = document.getElementById('imbSujet');
  const msg = document.getElementById('imbMsg');
  const feedback = document.getElementById('imbDevisFeedback');

  function openModal() {
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modal.hidden = true;
    document.body.style.overflow = '';
  }

  function openDevis(title) {
    form.reset();
    feedback.hidden = true;
    form.querySelectorAll('.imb-field, #imbDevisSubmit').forEach((el) => {
      el.hidden = false;
    });
    sujet.value = title || 'Devis Immobilier & BTP';
    openModal();
    nom.focus();
  }

  document.querySelectorAll('.imb-devis-btn').forEach((btn) => {
    btn.addEventListener('click', () => openDevis(btn.dataset.sujet || ''));
  });

  document.querySelectorAll('[data-imb-close]').forEach((el) => {
    el.addEventListener('click', closeModal);
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.hidden) closeModal();
  });

  form.addEventListener('submit', () => {
    if (!nom.value.trim()) {
      nom.focus();
      return;
    }
    if (!msg.value.trim()) {
      msg.focus();
      return;
    }
    feedback.hidden = false;
    feedback.textContent =
      'Merci ' +
      nom.value.trim() +
      '. Votre demande « ' +
      sujet.value +
      ' » a été enregistrée. Nous vous recontactons sous 24h.';
    form.querySelectorAll('.imb-field, #imbDevisSubmit').forEach((el) => {
      el.hidden = true;
    });
  });
})();
