(function () {
  const modal = document.getElementById('fmModal');
  if (!modal) return;

  const form = document.getElementById('fmForm');
  const modalTitle = document.getElementById('fmModalTitle');
  const courseTitle = document.getElementById('fmCourseTitle');
  const prixEnLigneEl = document.getElementById('fmPrixEnLigne');
  const prixPresentielEl = document.getElementById('fmPrixPresentiel');
  const continueBtn = document.getElementById('fmContinue');
  const footer = document.getElementById('fmFooter');
  const summary = document.getElementById('fmSummary');
  const payOnline = document.getElementById('fmPayOnline');
  const payOnsite = document.getElementById('fmPayOnsite');
  const successMsg = document.getElementById('fmSuccessMsg');
  const presentielExtra = document.getElementById('fmModePresentielExtra');
  const autreExtra = document.getElementById('fmModeAutreExtra');
  const datePresentiel = document.getElementById('fmDatePresentiel');
  const formationPerso = document.getElementById('fmFormationPerso');
  const quickPay = document.getElementById('fmQuickPay');
  const classicInfo = document.getElementById('fmClassicInfo');
  const modeBlock = document.getElementById('fmModeBlock');
  const stepsBar = document.getElementById('fmSteps');
  const quickAmount = document.getElementById('fmQuickAmount');

  const nomQuick = document.getElementById('fmNomQuick');
  const cardNumber = document.getElementById('fmCardNumber');
  const cardExp = document.getElementById('fmCardExp');
  const cardCvc = document.getElementById('fmCardCvc');

  let step = 1;
  let flow = 'follow'; // 'follow' | 'buy'
  let current = { title: '', prixEnLigne: 20000, prixPresentiel: 25000 };

  const fmt = (n) => Number(n).toLocaleString('fr-FR') + ' FCFA';

  function todayISO() {
    const d = new Date();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return d.getFullYear() + '-' + m + '-' + day;
  }

  function onlyDigits(value) {
    return String(value || '').replace(/\D/g, '');
  }

  function formatCardNumber(value) {
    return onlyDigits(value).slice(0, 16).replace(/(\d{4})(?=\d)/g, '$1 ').trim();
  }

  function formatExp(value) {
    const digits = onlyDigits(value).slice(0, 4);
    if (digits.length <= 2) return digits;
    return digits.slice(0, 2) + '/' + digits.slice(2);
  }

  function updateModeExtras() {
    if (flow === 'buy') {
      if (modeBlock) modeBlock.hidden = true;
      presentielExtra.hidden = true;
      autreExtra.hidden = true;
      quickPay.hidden = false;
      classicInfo.hidden = true;
      stepsBar.hidden = true;
      stepsBar.classList.add('is-hidden');
      if (quickAmount) quickAmount.textContent = fmt(current.prixEnLigne);
      updateContinueLabel();
      return;
    }

    if (modeBlock) modeBlock.hidden = false;
    stepsBar.hidden = false;

    const mode = getSelected('mode');
    const isOnline = mode === 'enligne';

    presentielExtra.hidden = mode !== 'presentiel';
    autreExtra.hidden = mode !== 'autre';
    quickPay.hidden = !isOnline;
    classicInfo.hidden = isOnline;
    stepsBar.classList.toggle('is-hidden', isOnline);

    if (mode === 'presentiel') {
      datePresentiel.min = todayISO();
      datePresentiel.required = true;
      formationPerso.required = false;
    } else if (mode === 'autre') {
      formationPerso.required = true;
      datePresentiel.required = false;
    } else {
      datePresentiel.required = false;
      formationPerso.required = false;
    }

    if (quickAmount) quickAmount.textContent = fmt(current.prixEnLigne);
    updateContinueLabel();
  }

  function updateContinueLabel() {
    if (step !== 1) return;
    if (flow === 'buy' || getSelected('mode') === 'enligne') {
      continueBtn.innerHTML =
        '<i class="fas fa-lock"></i> Payer maintenant — ' + fmt(current.prixEnLigne);
    } else {
      continueBtn.innerHTML = 'Continuer <i class="fas fa-arrow-right"></i>';
    }
  }

  function setChoiceGroups() {
    document.querySelectorAll('#fmModes, #fmPays').forEach((group) => {
      group.querySelectorAll('.fm-choice').forEach((label) => {
        const input = label.querySelector('input[type="radio"]');
        if (!input) return;
        label.classList.toggle('is-selected', input.checked);
        input.addEventListener('change', () => {
          group.querySelectorAll('.fm-choice').forEach((l) => l.classList.remove('is-selected'));
          label.classList.add('is-selected');
          if (group.id === 'fmModes') {
            step = 1;
            updateModeExtras();
            renderStep();
          }
        });
      });
    });

    document.querySelectorAll('.fm-pay-chip').forEach((chip) => {
      const input = chip.querySelector('input');
      input.addEventListener('change', () => {
        document.querySelectorAll('.fm-pay-chip').forEach((c) => c.classList.remove('is-selected'));
        chip.classList.add('is-selected');
      });
    });
  }

  function resetFormState() {
    form.reset();
    step = 1;
    continueBtn.disabled = false;
    footer.hidden = false;
    summary.innerHTML = '';
    successMsg.textContent =
      "Merci. Votre demande d'inscription a bien été enregistrée. Notre équipe vous contactera rapidement.";

    const modeEnligne = form.querySelector('input[name="mode"][value="enligne"]');
    const payEnligne = form.querySelector('input[name="paiement"][value="enligne"]');
    const moyenFlooz = form.querySelector('input[name="moyen"][value="flooz"]');
    if (modeEnligne) modeEnligne.checked = true;
    if (payEnligne) payEnligne.checked = true;
    if (moyenFlooz) moyenFlooz.checked = true;

    document.querySelectorAll('#fmModes .fm-choice, #fmPays .fm-choice').forEach((l) => {
      const input = l.querySelector('input');
      l.classList.toggle('is-selected', !!(input && input.checked));
    });
    document.querySelectorAll('.fm-pay-chip').forEach((c) => {
      const input = c.querySelector('input');
      c.classList.toggle('is-selected', !!(input && input.checked));
    });

    document.querySelectorAll('.fm-step').forEach((s) => {
      s.classList.remove('is-active', 'is-done');
      if (Number(s.dataset.step) === 1) s.classList.add('is-active');
    });

    datePresentiel.min = todayISO();
    updateModeExtras();
    renderStep();
  }

  function openModal(btn, type) {
    flow = type === 'buy' ? 'buy' : 'follow';
    current = {
      title: btn.dataset.title || '',
      prixEnLigne: Number(btn.dataset.prixEnligne || 20000),
      prixPresentiel: Number(btn.dataset.prixPresentiel || 25000),
    };

    modalTitle.textContent = flow === 'buy' ? 'Acheter la formation' : 'Suivre la formation';
    courseTitle.textContent = current.title;
    prixEnLigneEl.textContent = fmt(current.prixEnLigne);
    prixPresentielEl.textContent = fmt(current.prixPresentiel);

    resetFormState();
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modal.hidden = true;
    document.body.style.overflow = '';
    flow = 'follow';
    resetFormState();
  }

  function getSelected(name) {
    const el = form.querySelector('input[name="' + name + '"]:checked');
    return el ? el.value : '';
  }

  function getPriceLabel() {
    if (flow === 'buy') return fmt(current.prixEnLigne);
    const mode = getSelected('mode');
    if (mode === 'enligne') return fmt(current.prixEnLigne);
    if (mode === 'presentiel') return fmt(current.prixPresentiel);
    return 'Sur devis';
  }

  function modeLabel(mode) {
    if (mode === 'enligne') return 'En ligne';
    if (mode === 'presentiel') return 'Présentiel';
    return 'Autre (sur devis)';
  }

  function formationLabel() {
    if (flow === 'buy') return current.title;
    const mode = getSelected('mode');
    if (mode === 'autre') {
      const perso = formationPerso.value.trim();
      return perso || current.title;
    }
    return current.title;
  }

  function validateQuickPay() {
    if (!nomQuick.value.trim()) {
      nomQuick.focus();
      return false;
    }
    const digits = onlyDigits(cardNumber.value);
    if (digits.length < 13) {
      cardNumber.focus();
      return false;
    }
    const exp = onlyDigits(cardExp.value);
    if (exp.length !== 4) {
      cardExp.focus();
      return false;
    }
    const month = Number(exp.slice(0, 2));
    if (month < 1 || month > 12) {
      cardExp.focus();
      return false;
    }
    if (onlyDigits(cardCvc.value).length < 3) {
      cardCvc.focus();
      return false;
    }
    return true;
  }

  function validateStep1Classic() {
    const nom = document.getElementById('fmNom');
    const email = document.getElementById('fmEmail');
    const mode = getSelected('mode');

    if (!nom.value.trim()) {
      nom.focus();
      return false;
    }
    if (!email.value.trim() || !email.checkValidity()) {
      email.focus();
      return false;
    }
    if (mode === 'presentiel' && !datePresentiel.value) {
      datePresentiel.focus();
      return false;
    }
    if (mode === 'autre' && !formationPerso.value.trim()) {
      formationPerso.focus();
      return false;
    }
    return true;
  }

  function buildSummary() {
    const mode = getSelected('mode');
    const paiement = getSelected('paiement');
    let extraRows = '';

    if (mode === 'presentiel' && datePresentiel.value) {
      extraRows +=
        '<p><span>Date présentiel</span><strong>' +
        new Date(datePresentiel.value + 'T00:00:00').toLocaleDateString('fr-FR') +
        '</strong></p>';
    }
    if (mode === 'autre' && formationPerso.value.trim()) {
      extraRows +=
        '<p><span>Formation demandée</span><strong>' +
        formationPerso.value.trim() +
        '</strong></p>';
    }

    summary.innerHTML =
      '<div class="fm-summary-card">' +
      '<h3>Récapitulatif</h3>' +
      '<p><span>Formation</span><strong>' + formationLabel() + '</strong></p>' +
      '<p><span>Mode</span><strong>' + modeLabel(mode) + '</strong></p>' +
      extraRows +
      '<p><span>Montant</span><strong>' + getPriceLabel() + '</strong></p>' +
      '<p><span>Participant</span><strong>' + document.getElementById('fmNom').value.trim() + '</strong></p>' +
      '<p><span>Email</span><strong>' + document.getElementById('fmEmail').value.trim() + '</strong></p>' +
      '<p><span>Paiement</span><strong>' +
      (paiement === 'enligne' ? 'En ligne' : 'En présentiel') +
      '</strong></p>' +
      '</div>';

    const isOnlinePay = paiement === 'enligne' && mode !== 'autre';
    payOnline.hidden = !isOnlinePay;
    payOnsite.hidden = isOnlinePay;

    const rdv = document.getElementById('fmRdv');
    if (mode === 'presentiel' && datePresentiel.value && rdv && !rdv.value) {
      rdv.value = datePresentiel.value;
      rdv.min = todayISO();
    }
  }

  function renderStep() {
    document.querySelectorAll('.fm-panel').forEach((p) => {
      p.classList.toggle('is-active', Number(p.dataset.panel) === step);
    });
    document.querySelectorAll('.fm-step').forEach((s) => {
      const n = Number(s.dataset.step);
      s.classList.toggle('is-active', n === step);
      s.classList.toggle('is-done', n < step);
    });

    if (step === 2) buildSummary();

    if (step === 3) {
      footer.hidden = true;
      const mode = getSelected('mode');
      const titre = formationLabel();

      if (flow === 'buy' || mode === 'enligne') {
        successMsg.textContent =
          'Paiement réussi ! Merci ' + nomQuick.value.trim() +
          '. Votre achat de « ' + titre + ' » (' + getPriceLabel() +
          ') est confirmé. Un reçu vous sera envoyé.';
      } else if (mode === 'autre' || getSelected('paiement') === 'presentiel') {
        successMsg.textContent =
          'Merci ' + document.getElementById('fmNom').value.trim() +
          '. Votre demande pour « ' + titre +
          ' » est enregistrée. Nous vous recontacterons pour finaliser.';
      } else {
        successMsg.textContent =
          'Merci ' + document.getElementById('fmNom').value.trim() +
          '. Votre inscription à « ' + titre +
          ' » (' + getPriceLabel() + ') est confirmée. Un email de confirmation vous sera envoyé.';
      }
    } else {
      footer.hidden = false;
      if (step === 2) {
        continueBtn.innerHTML = 'Confirmer <i class="fas fa-check"></i>';
      } else {
        updateContinueLabel();
      }
    }
  }

  function simulatePayment(done) {
    continueBtn.disabled = true;
    continueBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Paiement en cours...';
    setTimeout(() => {
      continueBtn.disabled = false;
      done();
    }, 1200);
  }

  function goToSuccessAfterPay() {
    simulatePayment(() => {
      step = 3;
      document.querySelectorAll('.fm-step').forEach((s) => {
        s.classList.add('is-done');
        s.classList.remove('is-active');
      });
      const last = document.querySelector('.fm-step[data-step="3"]');
      if (last) {
        last.classList.add('is-active');
        last.classList.remove('is-done');
      }
      if (flow === 'buy') {
        stepsBar.hidden = false;
        stepsBar.classList.remove('is-hidden');
      }
      renderStep();
    });
  }

  cardNumber.addEventListener('input', () => {
    cardNumber.value = formatCardNumber(cardNumber.value);
  });
  cardExp.addEventListener('input', () => {
    cardExp.value = formatExp(cardExp.value);
  });
  cardCvc.addEventListener('input', () => {
    cardCvc.value = onlyDigits(cardCvc.value).slice(0, 4);
  });

  document.querySelectorAll('.form-follow-btn').forEach((btn) => {
    btn.addEventListener('click', () => openModal(btn, 'follow'));
  });

  document.querySelectorAll('.form-buy-btn').forEach((btn) => {
    btn.addEventListener('click', () => openModal(btn, 'buy'));
  });

  modal.querySelectorAll('[data-fm-close]').forEach((el) => {
    el.addEventListener('click', closeModal);
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.hidden) closeModal();
  });

  continueBtn.addEventListener('click', () => {
    if (step === 1) {
      // Achat impulsif ou suivi en ligne
      if (flow === 'buy' || getSelected('mode') === 'enligne') {
        if (!validateQuickPay()) return;
        goToSuccessAfterPay();
        return;
      }

      if (!validateStep1Classic()) return;
      step = 2;
      renderStep();
      return;
    }

    if (step === 2) {
      const paiement = getSelected('paiement');
      const mode = getSelected('mode');
      const rdv = document.getElementById('fmRdv');
      if ((paiement === 'presentiel' || mode === 'autre') && !payOnsite.hidden && !rdv.value) {
        rdv.focus();
        return;
      }
      step = 3;
      renderStep();
    }
  });

  setChoiceGroups();

  function hardResetOnLoad() {
    closeModal();
    courseTitle.textContent = '—';
    modalTitle.textContent = 'Suivre la formation';
    current = { title: '', prixEnLigne: 20000, prixPresentiel: 25000 };
  }

  hardResetOnLoad();
  window.addEventListener('pageshow', hardResetOnLoad);
})();
