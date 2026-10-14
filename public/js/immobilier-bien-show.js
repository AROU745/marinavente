(function () {
  const galleryEl = document.getElementById('imbdGalleryData');
  const metaEl = document.getElementById('imbdMetaData');
  if (!galleryEl || !metaEl) return;

  const gallery = JSON.parse(galleryEl.textContent || '[]');
  const meta = JSON.parse(metaEl.textContent || '{}');
  const imgBase = meta.imgBase || '/images/immobilier';
  let index = 0;

  const mainImg = document.getElementById('imbdMainImg');
  const counter = document.getElementById('imbdCounter');
  const thumbs = document.getElementById('imbdThumbs');
  const modal = document.getElementById('imbdMsgModal');

  function show(i) {
    if (!gallery.length) return;
    index = (i + gallery.length) % gallery.length;
    mainImg.src = imgBase + '/' + gallery[index];
    counter.textContent = index + 1 + '/' + gallery.length;
    thumbs.querySelectorAll('.imbd-thumb').forEach((t, ti) => {
      t.classList.toggle('is-active', ti === index);
    });
  }

  document.getElementById('imbdPrev').addEventListener('click', () => show(index - 1));
  document.getElementById('imbdNext').addEventListener('click', () => show(index + 1));
  thumbs.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-index]');
    if (!btn) return;
    show(Number(btn.dataset.index));
  });

  const fav = document.getElementById('imbdFav');
  fav.addEventListener('click', () => {
    fav.classList.toggle('is-on');
    const on = fav.classList.contains('is-on');
    fav.innerHTML = on
      ? '<i class="fas fa-heart"></i> Favoris'
      : '<i class="far fa-heart"></i> Favoris';
  });

  document.getElementById('imbdShare').addEventListener('click', async () => {
    const url = window.location.href;
    try {
      if (navigator.share) {
        await navigator.share({ title: meta.title || document.title, url: url });
      } else if (navigator.clipboard) {
        await navigator.clipboard.writeText(url);
        alert('Lien copié dans le presse-papiers.');
      }
    } catch (_) {}
  });

  document.getElementById('imbdReport').addEventListener('click', () => {
    alert('Merci. Votre signalement a été enregistré.');
  });

  function openModal() {
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
  }
  function closeModal() {
    modal.hidden = true;
    document.body.style.overflow = '';
  }

  document.getElementById('imbdContactOpen').addEventListener('click', openModal);
  document.getElementById('imbdMsgOpen').addEventListener('click', openModal);
  document.querySelectorAll('[data-imbd-close]').forEach((el) => {
    el.addEventListener('click', closeModal);
  });

  document.getElementById('imbdMsgForm').addEventListener('submit', () => {
    const nom = document.getElementById('imbdNom');
    const msg = document.getElementById('imbdMessage');
    if (!nom.value.trim()) {
      nom.focus();
      return;
    }
    if (!msg.value.trim()) {
      msg.focus();
      return;
    }
    const feedback = document.getElementById('imbdFeedback');
    feedback.hidden = false;
    feedback.textContent = 'Merci ' + nom.value.trim() + ' ! Votre message a été envoyé. Nous vous recontacterons rapidement.';
    nom.value = '';
    document.getElementById('imbdTel').value = '';
    msg.value = '';
  });

  /* ========== Visite 3D cinématique (type vidéo) ========== */
  const tourDataEl = document.getElementById('imbdTourData');
  const tourModal = document.getElementById('imbdTourModal');
  if (!tourDataEl || !tourModal) return;

  const tourData = JSON.parse(tourDataEl.textContent || '{}');
  const scenes = Array.isArray(tourData.scenes) ? tourData.scenes : [];
  if (!scenes.length) return;

  const SCENE_MS = 7500;
  const speeds = [1, 1.5, 2];
  const tourImgBase = tourData.imgBase || imgBase;
  const stage = document.getElementById('imbdTourStage');
  const layerA = document.getElementById('imbdTourLayerA');
  const layerB = document.getElementById('imbdTourLayerB');
  const roomsEl = document.getElementById('imbdTourRooms');
  const roomLabel = document.getElementById('imbdTourRoomLabel');
  const chapterEl = document.getElementById('imbdTourChapter');
  const tourDesc = document.getElementById('imbdTourDesc');
  const hint = document.getElementById('imbdTourHint');
  const playBtn = document.getElementById('imbdTourPlay');
  const seek = document.getElementById('imbdTourSeek');
  const timeEl = document.getElementById('imbdTourTime');
  const durationEl = document.getElementById('imbdTourDuration');
  const speedBtn = document.getElementById('imbdTourSpeed');

  let sceneIndex = 0;
  let activeLayer = layerA;
  let idleLayer = layerB;
  let playing = false;
  let speedIdx = 0;
  let speed = 1;
  let sceneStartedAt = 0;
  let sceneElapsed = 0;
  let rafId = 0;
  let manualOffset = 0;
  let dragging = false;
  let startX = 0;
  let startOffset = 0;
  let userPausedWalk = false;

  const totalMs = () => scenes.length * SCENE_MS;
  const fmtTime = (ms) => {
    const s = Math.max(0, Math.floor(ms / 1000));
    const m = Math.floor(s / 60);
    const r = s % 60;
    return m + ':' + String(r).padStart(2, '0');
  };

  function getPan(layer) {
    return layer.querySelector('.imbd-tour3d-pan');
  }

  function getImg(layer) {
    return layer.querySelector('img');
  }

  function renderRooms() {
    roomsEl.innerHTML = scenes.map((s, i) => (
      '<button type="button" class="imbd-tour3d-room' +
        (i === sceneIndex ? ' is-active' : '') +
        (i < sceneIndex ? ' is-done' : '') +
        '" data-scene="' + i + '">' +
        '<img src="' + tourImgBase + '/' + s.image + '" alt="">' +
        '<span><strong>' + s.title + '</strong><small>' + fmtTime(i * SCENE_MS) + ' · pièce ' + (i + 1) + '</small></span>' +
      '</button>'
    )).join('');
  }

  function applyManualPan() {
    const pan = getPan(activeLayer);
    pan.classList.remove('is-walk');
    const x = Math.max(-18, Math.min(18, manualOffset));
    pan.style.transform =
      'perspective(1000px) translate3d(' + (-x) + '%, 0, 30px) scale(1.12) rotateY(' + (x * -0.3) + 'deg)';
  }

  function startWalkAnim() {
    const pan = getPan(activeLayer);
    pan.classList.remove('is-walk');
    void pan.offsetWidth;
    if (playing && !userPausedWalk) {
      pan.style.transform = '';
      pan.style.animationDuration = (SCENE_MS / speed) + 'ms';
      pan.classList.add('is-walk');
    }
  }

  function setPlayIcon() {
    playBtn.innerHTML = playing ? '<i class="fas fa-pause"></i>' : '<i class="fas fa-play"></i>';
    stage.classList.toggle('is-playing', playing);
  }

  function updateHud() {
    const globalElapsed = sceneIndex * SCENE_MS + sceneElapsed;
    const total = totalMs();
    seek.value = String(Math.round((globalElapsed / total) * 1000));
    timeEl.textContent = fmtTime(globalElapsed);
    durationEl.textContent = fmtTime(total);
    chapterEl.textContent = (sceneIndex + 1) + ' / ' + scenes.length;
  }

  function showScene(i, opts) {
    const options = opts || {};
    sceneIndex = ((i % scenes.length) + scenes.length) % scenes.length;
    const scene = scenes[sceneIndex];
    const incoming = idleLayer;
    const outgoing = activeLayer;
    const img = getImg(incoming);

    img.src = tourImgBase + '/' + scene.image;
    img.alt = scene.title;
    getPan(incoming).classList.remove('is-walk');
    getPan(incoming).style.transform = '';

    incoming.classList.add('is-active');
    outgoing.classList.remove('is-active');

    idleLayer = outgoing;
    activeLayer = incoming;

    roomLabel.textContent = scene.title;
    tourDesc.textContent = scene.desc || '';
    manualOffset = 0;
    userPausedWalk = false;
    sceneElapsed = options.elapsed || 0;
    sceneStartedAt = performance.now() - sceneElapsed / speed;
    renderRooms();
    updateHud();

    if (playing) startWalkAnim();
  }

  function tick(now) {
    if (!playing) return;
    sceneElapsed = (now - sceneStartedAt) * speed;
    if (sceneElapsed >= SCENE_MS) {
      if (sceneIndex >= scenes.length - 1) {
        playing = false;
        sceneElapsed = SCENE_MS;
        setPlayIcon();
        updateHud();
        getPan(activeLayer).classList.remove('is-walk');
        return;
      }
      showScene(sceneIndex + 1);
    } else {
      updateHud();
    }
    rafId = requestAnimationFrame(tick);
  }

  function play() {
    if (sceneIndex === scenes.length - 1 && sceneElapsed >= SCENE_MS - 40) {
      showScene(0);
    }
    playing = true;
    userPausedWalk = false;
    sceneStartedAt = performance.now() - sceneElapsed / speed;
    setPlayIcon();
    startWalkAnim();
    cancelAnimationFrame(rafId);
    rafId = requestAnimationFrame(tick);
  }

  function pause() {
    playing = false;
    setPlayIcon();
    cancelAnimationFrame(rafId);
    getPan(activeLayer).classList.remove('is-walk');
  }

  function togglePlay() {
    if (playing) pause();
    else play();
  }

  function seekTo(ratio) {
    const total = totalMs();
    const t = Math.max(0, Math.min(total, ratio * total));
    const idx = Math.min(scenes.length - 1, Math.floor(t / SCENE_MS));
    const elapsed = t - idx * SCENE_MS;
    const wasPlaying = playing;
    pause();
    showScene(idx, { elapsed: elapsed });
    if (wasPlaying) play();
    else updateHud();
  }

  function openTour() {
    tourModal.hidden = false;
    document.body.style.overflow = 'hidden';
    durationEl.textContent = fmtTime(totalMs());
    showScene(0);
    if (hint) {
      hint.style.opacity = '1';
      setTimeout(() => { if (hint) hint.style.opacity = '0'; }, 3500);
    }
    play();
  }

  function closeTour() {
    pause();
    tourModal.hidden = true;
    if (document.fullscreenElement) {
      document.exitFullscreen().catch(() => {});
    }
    if (modal.hidden) document.body.style.overflow = '';
  }

  ['imbdTourOpen', 'imbdTourOpenBanner', 'imbdTourOpenSide'].forEach((id) => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('click', openTour);
  });

  document.getElementById('imbdTourClose').addEventListener('click', closeTour);
  document.getElementById('imbdTourPrev').addEventListener('click', () => {
    const was = playing;
    pause();
    showScene(sceneIndex - 1);
    if (was) play();
  });
  document.getElementById('imbdTourNext').addEventListener('click', () => {
    const was = playing;
    pause();
    showScene(sceneIndex + 1);
    if (was) play();
  });
  playBtn.addEventListener('click', togglePlay);

  speedBtn.addEventListener('click', () => {
    speedIdx = (speedIdx + 1) % speeds.length;
    speed = speeds[speedIdx];
    speedBtn.textContent = speed + 'x';
    if (playing) {
      sceneStartedAt = performance.now() - sceneElapsed / speed;
      startWalkAnim();
    }
  });

  seek.addEventListener('input', () => {
    seekTo(Number(seek.value) / 1000);
  });

  roomsEl.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-scene]');
    if (!btn) return;
    const was = playing;
    pause();
    showScene(Number(btn.dataset.scene));
    if (was) play();
  });

  document.getElementById('imbdTourFs').addEventListener('click', () => {
    if (!document.fullscreenElement) tourModal.requestFullscreen().catch(() => {});
    else document.exitFullscreen().catch(() => {});
  });

  stage.addEventListener('pointerdown', (e) => {
    if (e.target.closest('button')) return;
    dragging = true;
    userPausedWalk = true;
    if (playing) getPan(activeLayer).classList.remove('is-walk');
    stage.classList.add('is-dragging');
    startX = e.clientX;
    startOffset = manualOffset;
    stage.setPointerCapture(e.pointerId);
  });

  stage.addEventListener('pointermove', (e) => {
    if (!dragging) return;
    manualOffset = startOffset + ((e.clientX - startX) / stage.clientWidth) * 36;
    applyManualPan();
  });

  function endDrag(e) {
    if (!dragging) return;
    dragging = false;
    stage.classList.remove('is-dragging');
    try { stage.releasePointerCapture(e.pointerId); } catch (_) {}
  }

  stage.addEventListener('pointerup', endDrag);
  stage.addEventListener('pointercancel', endDrag);

  document.addEventListener('keydown', (e) => {
    if (tourModal.hidden) return;
    if (e.key === 'Escape') closeTour();
    if (e.key === ' ') {
      e.preventDefault();
      togglePlay();
    }
    if (e.key === 'ArrowLeft') document.getElementById('imbdTourPrev').click();
    if (e.key === 'ArrowRight') document.getElementById('imbdTourNext').click();
  });
})();
