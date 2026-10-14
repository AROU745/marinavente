(function () {
  const devisModal = document.getElementById('digDevisModal');
  const payModal = document.getElementById('digPayModal');
  if (!devisModal || !payModal) return;

  const devisNom = document.getElementById('digDevisNom');
  const devisEmail = document.getElementById('digDevisEmail');
  const devisPhone = document.getElementById('digDevisPhone');
  const devisService = document.getElementById('digDevisService');
  const devisMsg = document.getElementById('digDevisMsg');
  const devisFeedback = document.getElementById('digDevisFeedback');
  const devisForm = document.getElementById('digDevisForm');

  const payFields = document.getElementById('digPayFields');
  const paySuccess = document.getElementById('digPaySuccess');
  const payService = document.getElementById('digPayService');
  const payAmount = document.getElementById('digPayAmount');
  const payClientNom = document.getElementById('digPayClientNom');
  const payEmail = document.getElementById('digPayEmail');
  const payAppDesc = document.getElementById('digPayAppDesc');
  const payMethodInput = document.getElementById('digPayMethod');
  const payNom = document.getElementById('digPayNom');
  const payCard = document.getElementById('digPayCard');
  const payExp = document.getElementById('digPayExp');
  const payCvc = document.getElementById('digPayCvc');
  const payMmOperator = document.getElementById('digPayMmOperator');
  const payMmPhone = document.getElementById('digPayMmPhone');
  const payCryptoType = document.getElementById('digPayCryptoType');
  const payCryptoWallet = document.getElementById('digPayCryptoWallet');
  const paySubmit = document.getElementById('digPaySubmit');
  const paySuccessMsg = document.getElementById('digPaySuccessMsg');
  const methodBtns = Array.from(document.querySelectorAll('.dig-pay-method'));
  const panels = {
    mobile: document.getElementById('digPayPanelMobile'),
    visa: document.getElementById('digPayPanelVisa'),
    crypto: document.getElementById('digPayPanelCrypto'),
  };

  let currentPay = { title: '', prix: 0 };
  let lastDevisTitle = '';
  let currentMethod = 'mobile';

  const fmt = (n) => Number(n).toLocaleString('fr-FR') + ' FCFA';
  const onlyDigits = (v) => String(v || '').replace(/\D/g, '');
  const methodLabels = { mobile: 'Mobile Money', visa: 'Visa', crypto: 'Crypto' };

  function openModal(modal) {
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
  }

  function closeModal(modal) {
    modal.hidden = true;
    if (devisModal.hidden && payModal.hidden) {
      document.body.style.overflow = '';
    }
  }

  function setMethod(method) {
    currentMethod = method;
    payMethodInput.value = method;
    methodBtns.forEach((btn) => {
      const active = btn.dataset.method === method;
      btn.classList.toggle('is-active', active);
      btn.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
    Object.keys(panels).forEach((key) => {
      if (panels[key]) panels[key].hidden = key !== method;
    });
  }

  function openDevis(title) {
    lastDevisTitle = title || '';
    devisForm.reset();
    devisFeedback.hidden = true;
    devisService.value = lastDevisTitle;
    openModal(devisModal);
    devisNom.focus();
  }

  function openPay(title, prix) {
    currentPay = { title: title || '', prix: Number(prix || 0) };
    document.getElementById('digPayForm').reset();
    payFields.hidden = false;
    paySuccess.hidden = true;
    paySubmit.disabled = false;
    payService.textContent = currentPay.title;
    payAmount.textContent = fmt(currentPay.prix);
    paySubmit.innerHTML = '<i class="fas fa-lock"></i> Payer maintenant — ' + fmt(currentPay.prix);
    setMethod('mobile');
    openModal(payModal);
    payClientNom.focus();
  }

  document.querySelectorAll('.dig-devis-btn').forEach((btn) => {
    btn.addEventListener('click', () => openDevis(btn.dataset.title));
  });

  document.querySelectorAll('.dig-pay-btn').forEach((btn) => {
    btn.addEventListener('click', () => openPay(btn.dataset.title, btn.dataset.prix));
  });

  methodBtns.forEach((btn) => {
    btn.addEventListener('click', () => setMethod(btn.dataset.method));
  });

  document.querySelectorAll('[data-dig-close]').forEach((el) => {
    el.addEventListener('click', () => {
      const which = el.getAttribute('data-dig-close');
      closeModal(which === 'pay' ? payModal : devisModal);
    });
  });

  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
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
    const email = devisEmail ? devisEmail.value.trim() : '';
    const phone = devisPhone ? devisPhone.value.trim() : '';
    const svc = devisService.value || lastDevisTitle;
    let contactHint = '';
    if (email || phone) {
      contactHint = ' (';
      if (email) contactHint += email;
      if (email && phone) contactHint += ' / ';
      if (phone) contactHint += phone;
      contactHint += ')';
    }
    devisFeedback.hidden = false;
    devisFeedback.className = 'dig-modal-feedback is-success';
    devisFeedback.textContent =
      'Merci ' + nom +
      ' ! Votre demande de devis pour « ' + svc +
      ' » a bien été envoyée' + contactHint +
      '. Nous vous recontacterons rapidement.';
    devisNom.value = '';
    if (devisEmail) devisEmail.value = '';
    if (devisPhone) devisPhone.value = '';
    devisMsg.value = '';
    devisService.value = svc;
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

  function validateCommon() {
    if (!payClientNom.value.trim()) {
      payClientNom.focus();
      return false;
    }
    const email = payEmail.value.trim();
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      payEmail.focus();
      return false;
    }
    if (!payAppDesc.value.trim()) {
      payAppDesc.focus();
      return false;
    }
    return true;
  }

  function validatePay() {
    if (!validateCommon()) return false;

    if (currentMethod === 'mobile') {
      if (!payMmOperator.value) {
        payMmOperator.focus();
        return false;
      }
      if (onlyDigits(payMmPhone.value).length < 8) {
        payMmPhone.focus();
        return false;
      }
      return true;
    }

    if (currentMethod === 'visa') {
      if (!payNom.value.trim()) {
        payNom.focus();
        return false;
      }
      if (onlyDigits(payCard.value).length < 13) {
        payCard.focus();
        return false;
      }
      const exp = onlyDigits(payExp.value);
      const month = Number(exp.slice(0, 2));
      if (exp.length !== 4 || month < 1 || month > 12) {
        payExp.focus();
        return false;
      }
      if (onlyDigits(payCvc.value).length < 3) {
        payCvc.focus();
        return false;
      }
      return true;
    }

    if (currentMethod === 'crypto') {
      if (!payCryptoType.value) {
        payCryptoType.focus();
        return false;
      }
      if (payCryptoWallet.value.trim().length < 10) {
        payCryptoWallet.focus();
        return false;
      }
      return true;
    }

    return false;
  }

  paySubmit.addEventListener('click', () => {
    if (!validatePay()) return;
    paySubmit.disabled = true;
    paySubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Paiement en cours...';
    setTimeout(() => {
      payFields.hidden = true;
      paySuccess.hidden = false;
      paySuccessMsg.textContent =
        'Merci ' + payClientNom.value.trim() +
        '. Votre paiement pour « ' + currentPay.title +
        ' » (' + fmt(currentPay.prix) + ') via ' + methodLabels[currentMethod] +
        ' est confirmé. Un reçu sera envoyé à ' + payEmail.value.trim() + '.';
      paySubmit.disabled = false;
    }, 1200);
  });

  window.addEventListener('pageshow', () => {
    closeModal(devisModal);
    closeModal(payModal);
  });
})();
