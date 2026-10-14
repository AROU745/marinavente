@extends('layouts.app')

@section('content')
<section class="jobs-page">
    <div class="container">
        <nav class="pe-breadcrumb" aria-label="Fil d'Ariane">
            <a href="{{ url('/') }}">Accueil</a>
            <span>&gt;</span>
            <strong>Offres d'emploi</strong>
        </nav>

        <div class="jobs-hero">
            <div class="jobs-hero-text">
                <h1>Nos <span>offres d'emploi</span></h1>
                <p>
                    Découvrez nos opportunités de carrière et rejoignez une équipe
                    passionnée pour faire la différence.
                </p>
            </div>
            <div class="jobs-hero-visual" aria-hidden="true">
                <div class="jobs-hero-bubble"><i class="fas fa-laptop-code"></i></div>
                <div class="jobs-hero-bubble"><i class="fas fa-user-tie"></i></div>
                <div class="jobs-hero-bubble"><i class="fas fa-hard-hat"></i></div>
                <div class="jobs-hero-bubble"><i class="fas fa-briefcase"></i></div>
            </div>
        </div>

        <form class="jobs-filters" action="{{ url('/offreemplois') }}" method="get" autocomplete="off">
            <div class="jobs-filter-search">
                <i class="fas fa-search"></i>
                <input type="search" name="q" value="{{ $q }}" placeholder="Rechercher un poste, un mot-clé..." autocomplete="off">
            </div>
            <select name="categorie" aria-label="Catégorie">
                <option value="">Catégorie</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected($categorie === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
            <select name="lieu" aria-label="Lieu">
                <option value="">Lieu</option>
                @foreach($lieux as $l)
                    <option value="{{ $l }}" @selected($lieu === $l)>{{ $l }}</option>
                @endforeach
            </select>
            <select name="contrat" aria-label="Type de contrat">
                <option value="">Type de contrat</option>
                @foreach($contrats as $c)
                    <option value="{{ $c }}" @selected($contrat === $c)>{{ $c }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn jobs-search-btn">
                <i class="fas fa-search"></i> Rechercher
            </button>
        </form>

        @if($offres->isEmpty())
            <div class="jobs-empty">
                <p>Aucune offre ne correspond à votre recherche.</p>
                <a href="{{ url('/offreemplois') }}" class="btn btn-outline-green">Voir toutes les offres</a>
            </div>
        @else
            <div class="jobs-grid">
                @foreach($offres as $offre)
                    <article class="jobs-card">
                        <div class="jobs-card-media">
                            <i class="{{ $offre['icon'] }}"></i>
                            @if(!empty($offre['nouveau']))
                                <span class="jobs-badge">Nouveau</span>
                            @endif
                        </div>
                        <div class="jobs-card-body">
                            <div class="jobs-card-title">
                                <span class="jobs-card-icon"><i class="{{ $offre['icon'] }}"></i></span>
                                <div>
                                    <h3>{{ $offre['title'] }}</h3>
                                    <p class="jobs-meta">{{ $offre['contrat'] }} • {{ $offre['lieu'] }}</p>
                                </div>
                            </div>
                            <p class="jobs-desc">{{ $offre['description'] }}</p>
                            <p class="jobs-skills-label">Compétences requises</p>
                            <div class="jobs-skills">
                                @foreach(array_slice($offre['skills'], 0, 4) as $skill)
                                    <span>{{ $skill }}</span>
                                @endforeach
                            </div>
                            <button
                                type="button"
                                class="jobs-details jobs-details-btn"
                                data-slug="{{ $offre['slug'] }}"
                            >
                                Voir les détails <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>

            <script type="application/json" id="jobsData">@json($offres->values())</script>

            @if($lastPage > 1)
                @php
                    $qs = http_build_query(array_filter([
                        'q' => $q ?: null,
                        'categorie' => $categorie ?: null,
                        'lieu' => $lieu ?: null,
                        'contrat' => $contrat ?: null,
                    ]));
                    $base = url('/offreemplois') . ($qs ? '?' . $qs . '&' : '?');
                @endphp
                <nav class="jobs-pagination" aria-label="Pagination des offres">
                    @for($i = 1; $i <= min(3, $lastPage); $i++)
                        <a href="{{ $base }}page={{ $i }}" class="{{ $page === $i ? 'is-active' : '' }}">{{ $i }}</a>
                    @endfor
                    @if($lastPage > 4)
                        <span>…</span>
                        <a href="{{ $base }}page={{ $lastPage }}" class="{{ $page === $lastPage ? 'is-active' : '' }}">{{ $lastPage }}</a>
                    @elseif($lastPage === 4)
                        <a href="{{ $base }}page=4" class="{{ $page === 4 ? 'is-active' : '' }}">4</a>
                    @endif
                    @if($page < $lastPage)
                        <a class="jobs-next" href="{{ $base }}page={{ $page + 1 }}">
                            Suivant <i class="fas fa-chevron-right"></i>
                        </a>
                    @endif
                </nav>
            @endif
        @endif
    </div>
</section>

{{-- Modal détail offre --}}
<div class="job-modal" id="jobModal" hidden>
    <div class="job-modal-backdrop" data-job-close></div>
    <div class="job-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="jobModalTitle">
        <button type="button" class="job-modal-close" data-job-close aria-label="Fermer">
            <i class="fas fa-times"></i>
        </button>

        <header class="job-modal-header">
            <div class="job-modal-icon" id="jobModalIcon"><i class="fas fa-briefcase"></i></div>
            <div class="job-modal-heading">
                <h2 id="jobModalTitle">—</h2>
                <p class="job-modal-meta" id="jobModalMeta"><i class="fas fa-map-marker-alt"></i> —</p>
            </div>
            <span class="job-modal-badge" id="jobModalBadge" hidden>Nouveau</span>
        </header>

        <p class="job-modal-about" id="jobModalAbout"></p>

        <div class="job-modal-cols">
            <div>
                <h3><i class="fas fa-list-ul"></i> Responsabilités</h3>
                <ul id="jobModalResponsabilites"></ul>
            </div>
            <div>
                <h3><i class="fas fa-user"></i> Profil recherché</h3>
                <ul id="jobModalProfil"></ul>
            </div>
        </div>

        <div class="job-modal-skills-block">
            <h3><i class="fas fa-cogs"></i> Compétences techniques requises</h3>
            <div class="job-modal-skills" id="jobModalSkills"></div>
        </div>

        <div class="job-modal-footer-grid">
            <div>
                <h3><i class="fas fa-info-circle"></i> Informations complémentaires</h3>
                <ul class="job-modal-info" id="jobModalInfo"></ul>
            </div>
            <div>
                <h3><i class="fas fa-paper-plane"></i> Comment postuler ?</h3>
                <p class="job-modal-apply-text" id="jobModalApplyText"></p>
                <a href="#" class="btn btn-primary job-modal-apply" id="jobModalApplyBtn">
                    <i class="fas fa-paper-plane"></i> Postuler maintenant
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const nav = performance.getEntriesByType('navigation')[0];
    if (nav && nav.type === 'reload' && window.location.search) {
        window.location.replace(window.location.pathname);
        return;
    }

    const modal = document.getElementById('jobModal');
    if (!modal) return;

    let jobsBySlug = {};
    try {
        const raw = document.getElementById('jobsData');
        const list = raw ? JSON.parse(raw.textContent) : [];
        list.forEach((job) => {
            if (job && job.slug) jobsBySlug[job.slug] = job;
        });
    } catch (e) {
        console.error('jobsData', e);
    }

    const els = {
        title: document.getElementById('jobModalTitle'),
        meta: document.getElementById('jobModalMeta'),
        icon: document.getElementById('jobModalIcon'),
        badge: document.getElementById('jobModalBadge'),
        about: document.getElementById('jobModalAbout'),
        resp: document.getElementById('jobModalResponsabilites'),
        profil: document.getElementById('jobModalProfil'),
        skills: document.getElementById('jobModalSkills'),
        info: document.getElementById('jobModalInfo'),
        applyText: document.getElementById('jobModalApplyText'),
        applyBtn: document.getElementById('jobModalApplyBtn'),
    };

    function listHtml(items) {
        return (items || []).map((item) => '<li>' + item + '</li>').join('');
    }

    function openJob(job) {
        if (!job) return;
        els.title.textContent = job.title || '—';
        els.meta.innerHTML = '<i class="fas fa-map-marker-alt"></i> ' +
            (job.contrat || '') + ' • ' + (job.lieu || '');
        els.icon.innerHTML = '<i class="' + (job.icon || 'fas fa-briefcase') + '"></i>';
        els.badge.hidden = !job.nouveau;
        els.about.textContent = job.about || job.description || '';
        els.resp.innerHTML = listHtml(job.responsabilites);
        els.profil.innerHTML = listHtml(job.profil);
        els.skills.innerHTML = (job.skills || []).map((s) => '<span>' + s + '</span>').join('');
        els.info.innerHTML =
            '<li><strong>Type de contrat :</strong> ' + (job.contrat || '—') + '</li>' +
            '<li><strong>Durée :</strong> ' + (job.duree || '—') + '</li>' +
            '<li><strong>Lieu :</strong> ' + (job.lieu || '—') + '</li>' +
            '<li><strong>Disponibilité :</strong> ' + (job.disponibilite || 'Immédiate') + '</li>';

        const email = job.email || 'recrutement@marinagradepsf.org';
        const sujet = encodeURIComponent((job.title || 'Candidature') + ' - ' + (job.contrat || ''));
        els.applyText.innerHTML =
            'Envoyez votre CV et lettre de motivation à <strong>' + email +
            '</strong> avec l\'objet : <strong>' + (job.title || '') + ' - ' + (job.contrat || '') + '</strong>.';
        els.applyBtn.href = 'mailto:' + email + '?subject=' + sujet;

        modal.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    function closeJob() {
        modal.hidden = true;
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.jobs-details-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            openJob(jobsBySlug[btn.dataset.slug]);
        });
    });

    modal.querySelectorAll('[data-job-close]').forEach((el) => {
        el.addEventListener('click', closeJob);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.hidden) closeJob();
    });
})();
</script>
@endpush
