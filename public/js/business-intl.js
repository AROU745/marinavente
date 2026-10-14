(function () {
  const devisModal = document.getElementById('bcDevisModal');
  if (!devisModal) return;

  const devisNom = document.getElementById('bcDevisNom');
  const devisEspace = document.getElementById('bcDevisEspace');
  const devisMsg = document.getElementById('bcDevisMsg');
  const devisFeedback = document.getElementById('bcDevisFeedback');
  const devisForm = document.getElementById('bcDevisForm');

  let lastTitle = '';

  function openModal(modal) {
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
  }

  function closeModal(modal) {
    modal.hidden = true;
    document.body.style.overflow = '';
  }

  function openDevis(title) {
    lastTitle = title || '';
    devisForm.reset();
    devisFeedback.hidden = true;
    devisEspace.value = lastTitle;
    openModal(devisModal);
    devisNom.focus();
  }

  document.querySelectorAll('.bci-devis-btn').forEach((btn) => {
    btn.addEventListener('click', () => openDevis(btn.dataset.title));
  });

  document.querySelectorAll('[data-bc-close]').forEach((el) => {
    el.addEventListener('click', () => {
      const which = el.getAttribute('data-bc-close');
      if (which === 'devis') closeModal(devisModal);
      const pay = document.getElementById('bcPayModal');
      if (which === 'pay' && pay) closeModal(pay);
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !devisModal.hidden) closeModal(devisModal);
  });

  devisForm.addEventListener('submit', (e) => {
    e.preventDefault();
    if (!devisNom.value.trim()) {
      devisNom.focus();
      return;
    }
    if (!devisMsg.value.trim()) {
      devisMsg.focus();
      return;
    }
    const nom = devisNom.value.trim();
    const catalogue = devisEspace.value || lastTitle;
    devisFeedback.hidden = false;
    devisFeedback.className = 'bc-modal-feedback is-success';
    devisFeedback.textContent =
      'Merci ' + nom +
      ' ! Votre demande pour « ' + catalogue +
      ' » a bien été envoyée. Nous vous recontacterons rapidement.';
    devisNom.value = '';
    devisMsg.value = '';
    devisEspace.value = catalogue;
  });
})();
