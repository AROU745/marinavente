(function () {
  const choiceModal = document.getElementById('bcChoiceModal');
  const devisModal = document.getElementById('bcDevisModal');
  const payModal = document.getElementById('bcPayModal');
  if (!choiceModal || !devisModal || !payModal) return;

  const choiceSpace = document.getElementById('bcChoiceSpace');
  const choicePay = document.getElementById('bcChoicePay');
  const choiceDevis = document.getElementById('bcChoiceDevis');

  const devisNom = document.getElementById('bcDevisNom');
  const devisEspace = document.getElementById('bcDevisEspace');
  const devisMsg = document.getElementById('bcDevisMsg');
  const devisFeedback = document.getElementById('bcDevisFeedback');
  const devisForm = document.getElementById('bcDevisForm');

  const payFields = document.getElementById('bcPayFields');
  const paySuccess = document.getElementById('bcPaySuccess');
  const payItem = document.getElementById('bcPayItem');
  const payAmount = document.getElementById('bcPayAmount');
  const payNom = document.getElementById('bcPayNom');
  const payCard = document.getElementById('bcPayCard');
  const payExp = document.getElementById('bcPayExp');
  const payCvc = document.getElementById('bcPayCvc');
  const paySubmit = document.getElementById('bcPaySubmit');
  const paySuccessMsg = document.getElementById('bcPaySuccessMsg');

  let current = { title: '', prix: 0 };
  let lastDevisTitle = '';

  const fmt = (n) => Number(n).toLocaleString('fr-FR') + ' FCFA';
  const onlyDigits = (v) => String(v || '').replace(/\D/g, '');

  function anyOpen() {
    return !choiceModal.hidden || !devisModal.hidden || !payModal.hidden;
  }

  function openModal(modal) {
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
  }

  function closeModal(modal) {
    modal.hidden = true;
    if (!anyOpen()) document.body.style.overflow = '';
  }

  function openChoice(title, prix) {
    current = { title: title || '', prix: Number(prix || 0) };
    choiceSpace.textContent = current.title;
    openModal(choiceModal);
  }

  function openDevis(title) {
    lastDevisTitle = title || current.title || '';
    devisForm.reset();
    devisFeedback.hidden = true;
    devisEspace.value = lastDevisTitle;
    openModal(devisModal);
    devisNom.focus();
  }

  function openPay(title, prix) {
    current = { title: title || current.title || '', prix: Number(prix != null ? prix : current.prix) };
    document.getElementById('bcPayForm').reset();
    payFields.hidden = false;
    paySuccess.hidden = true;
    paySubmit.disabled = false;
    payItem.textContent = current.title;
    payAmount.textContent = fmt(current.prix);
    paySubmit.innerHTML = '<i class="fas fa-lock"></i> Payer maintenant — ' + fmt(current.prix);
    openModal(payModal);
    payNom.focus();
  }

  document.querySelectorAll('.bc-reserve-btn').forEach((btn) => {
    btn.addEventListener('click', () => openChoice(btn.dataset.title, btn.dataset.prix));
  });

  document.querySelectorAll('.bc-country-btn').forEach((btn) => {
    btn.addEventListener('click', () => openDevis(btn.dataset.title));
  });

  choicePay.addEventListener('click', () => {
    closeModal(choiceModal);
    openPay(current.title, current.prix);
  });

  choiceDevis.addEventListener('click', () => {
    closeModal(choiceModal);
    openDevis(current.title);
  });

  document.querySelectorAll('[data-bc-close]').forEach((el) => {
    el.addEventListener('click', () => {
      const which = el.getAttribute('data-bc-close');
      if (which === 'pay') closeModal(payModal);
      else if (which === 'devis') closeModal(devisModal);
      else closeModal(choiceModal);
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    if (!choiceModal.hidden) closeModal(choiceModal);
    if (!devisModal.hidden) closeModal(devisModal);
    if (!payModal.hidden) closeModal(payModal);
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
    const espace = devisEspace.value || lastDevisTitle;
    devisFeedback.hidden = false;
    devisFeedback.className = 'bc-modal-feedback is-success';
    devisFeedback.textContent =
      'Merci ' + nom +
      ' ! Votre demande pour « ' + espace +
      ' » a bien été envoyée. Nous vous recontacterons rapidement.';
    devisNom.value = '';
    devisMsg.value = '';
    devisEspace.value = espace;
  });

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

  function validatePay() {
    if (!payNom.value.trim()) {
      payNom.focus();
      return false;
    }
    if (onlyDigits(payCard.value).length < 16) {
      payCard.focus();
      return false;
    }
    if (onlyDigits(payExp.value).length < 4) {
      payExp.focus();
      return false;
    }
    if (onlyDigits(payCvc.value).length < 3) {
      payCvc.focus();
      return false;
    }
    return true;
  }

  paySubmit.addEventListener('click', () => {
    if (!validatePay()) return;
    paySubmit.disabled = true;
    const nom = payNom.value.trim();
    payFields.hidden = true;
    paySuccess.hidden = false;
    paySuccessMsg.textContent =
      'Merci ' + nom +
      '. Votre paiement de ' + fmt(current.prix) +
      ' pour « ' + current.title + ' » a été confirmé.';
  });
})();
