(function () {
  function bindForm(formId, feedbackId, requiredIds, successMsg) {
    const form = document.getElementById(formId);
    const feedback = document.getElementById(feedbackId);
    if (!form || !feedback) return;

    form.addEventListener('submit', () => {
      for (const id of requiredIds) {
        const el = document.getElementById(id);
        if (!el) continue;
        if (!String(el.value || '').trim()) {
          el.focus();
          return;
        }
      }
      const nom =
        document.getElementById(requiredIds[0])?.value?.trim() || 'vous';
      feedback.hidden = false;
      feedback.textContent = successMsg(nom);
      form.querySelectorAll('.st-field, .st-field-row, button[type="submit"]').forEach((el) => {
        el.hidden = true;
      });
    });
  }

  bindForm(
    'stInscForm',
    'stInscFeedback',
    ['stNom', 'stDomaine', 'stProfil'],
    (nom) =>
      'Merci ' +
      nom +
      '. Votre inscription prestataire a été enregistrée. Nous vous recontactons sous 48h.'
  );

  // --- Déposer un projet : pièces jointes image / PDF ---
  const MAX_FILES = 5;
  const MAX_SIZE = 5 * 1024 * 1024; // 5 Mo
  const allowedExt = /\.(jpe?g|png|webp|gif|pdf)$/i;
  const filesWrap = document.getElementById('stPFiles');
  const fileInput = document.getElementById('stPFileInput');
  const fileAdd = document.getElementById('stPFileAdd');
  const fileError = document.getElementById('stPFileError');
  let attached = [];

  function showFileError(msg) {
    if (!fileError) return;
    fileError.hidden = !msg;
    fileError.textContent = msg || '';
  }

  function isAllowed(file) {
    const typeOk =
      file.type.startsWith('image/') ||
      file.type === 'application/pdf' ||
      allowedExt.test(file.name);
    return typeOk;
  }

  function renderFiles() {
    if (!filesWrap || !fileAdd) return;
    filesWrap.querySelectorAll('.st-file-item').forEach((el) => el.remove());
    attached.forEach((item, idx) => {
      const div = document.createElement('div');
      div.className = 'st-file-item';
      const isPdf =
        item.file.type === 'application/pdf' || /\.pdf$/i.test(item.file.name);
      if (isPdf) {
        div.innerHTML =
          '<div class="st-file-pdf"><i class="fas fa-file-pdf"></i></div>' +
          '<div class="st-file-name">' +
          item.file.name +
          '</div>' +
          '<button type="button" data-rm-file="' +
          idx +
          '" aria-label="Supprimer"><i class="fas fa-times"></i></button>';
      } else {
        div.innerHTML =
          '<img src="' +
          item.url +
          '" alt="">' +
          '<div class="st-file-name">' +
          item.file.name +
          '</div>' +
          '<button type="button" data-rm-file="' +
          idx +
          '" aria-label="Supprimer"><i class="fas fa-times"></i></button>';
      }
      filesWrap.insertBefore(div, fileAdd);
    });
    fileAdd.style.display = attached.length >= MAX_FILES ? 'none' : '';
  }

  if (fileInput && filesWrap) {
    fileInput.addEventListener('change', () => {
      showFileError('');
      const list = Array.from(fileInput.files || []);
      list.forEach((file) => {
        if (attached.length >= MAX_FILES) {
          showFileError('Maximum ' + MAX_FILES + ' fichiers.');
          return;
        }
        if (!isAllowed(file)) {
          showFileError('Formats acceptés : images (JPG, PNG, WEBP, GIF) et PDF.');
          return;
        }
        if (file.size > MAX_SIZE) {
          showFileError('« ' + file.name + ' » dépasse 5 Mo.');
          return;
        }
        const url = file.type.startsWith('image/') ? URL.createObjectURL(file) : '';
        attached.push({ file: file, url: url });
      });
      fileInput.value = '';
      renderFiles();
    });

    filesWrap.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-rm-file]');
      if (!btn) return;
      const idx = Number(btn.getAttribute('data-rm-file'));
      const removed = attached.splice(idx, 1)[0];
      if (removed && removed.url) URL.revokeObjectURL(removed.url);
      showFileError('');
      renderFiles();
    });
  }

  const projetForm = document.getElementById('stProjetForm');
  const projetFeedback = document.getElementById('stProjetFeedback');
  if (projetForm && projetFeedback) {
    projetForm.addEventListener('submit', () => {
      const requiredIds = ['stPNom', 'stPType', 'stPDesc'];
      for (const id of requiredIds) {
        const el = document.getElementById(id);
        if (!el || !String(el.value || '').trim()) {
          el && el.focus();
          return;
        }
      }
      const nom = document.getElementById('stPNom').value.trim();
      const nFiles = attached.length;
      let msg =
        'Merci ' +
        nom +
        '. Votre projet a été transmis. Notre équipe revient vers vous rapidement.';
      if (nFiles > 0) {
        msg +=
          ' ' +
          nFiles +
          ' pièce' +
          (nFiles > 1 ? 's' : '') +
          ' jointe' +
          (nFiles > 1 ? 's' : '') +
          ' reçue' +
          (nFiles > 1 ? 's' : '') +
          '.';
      }
      projetFeedback.hidden = false;
      projetFeedback.textContent = msg;
      projetForm.querySelectorAll('.st-field, .st-field-row, button[type="submit"]').forEach((el) => {
        el.hidden = true;
      });
    });
  }
})();
