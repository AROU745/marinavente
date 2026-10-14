@extends('layouts.app')

@php
    $fmt = fn ($n) => number_format($n, 0, ',', ' ');
@endphp

@section('content')
<section class="soutenir-page">
    <div class="container pe-breadcrumb">
        <a href="{{ url('/') }}">Accueil</a>
        <span>/</span>
        <a href="{{ url(($statut ?? '') === 'realise' ? '/projetrealiser' : '/projetsarealiser') }}">
            {{ ($statut ?? '') === 'realise' ? 'Projets réalisés' : 'Projets à réaliser' }}
        </a>
        <span>/</span>
        <strong>{{ $projet['title'] }}</strong>
    </div>

    <div class="container soutenir-layout">
        {{-- Contenu principal --}}
        <div class="soutenir-main">
            <span class="soutenir-tag">{{ ($statut ?? '') === 'realise' ? 'Projet réalisé' : 'Projet à réaliser' }}</span>
            <h1>{{ $projet['title'] }}</h1>

            <div class="soutenir-meta-row">
                <div><i class="fas fa-tag"></i> <span>Catégorie</span><strong>{{ $projet['badge'] }}</strong></div>
                <div><i class="fas fa-map-marker-alt"></i> <span>Localisation</span><strong>{{ $projet['lieu'] }}</strong></div>
                <div><i class="fas fa-{{ $projet['metricIcon'] }}"></i> <span>Objectif</span><strong>{{ $projet['metric'] }}</strong></div>
                <div><i class="fas fa-clock"></i> <span>Durée</span><strong>{{ $projet['duree'] }}</strong></div>
            </div>

            <div class="soutenir-progress">
                <div class="pe-progress-bar"><span style="width: {{ $projet['progress'] }}%"></span></div>
                <small>{{ $projet['progress'] }}% financé</small>
            </div>

            <div class="soutenir-tabs">
                <button type="button" class="is-active" data-tab="apropos">À propos du projet</button>
                <button type="button" data-tab="objectifs">Objectifs</button>
                <button type="button" data-tab="impact">Impact attendu</button>
            </div>

            <div class="soutenir-tab-panel is-active" id="tab-apropos">
                <h2>À propos</h2>
                <p>{{ $projet['about'] }}</p>

                <div class="soutenir-media pe-play"
                    data-image="{{ asset('images/' . $projet['image']) }}"
                    data-video="{{ !empty($projet['video']) ? url('/videos/' . $projet['video']) : '' }}"
                    data-title="{{ $projet['title'] }}"
                    role="button" tabindex="0">
                    <img src="{{ asset('images/' . $projet['image']) }}" alt="{{ $projet['title'] }}">
                    <span class="pe-play-overlay"><i class="fas fa-play"></i></span>
                </div>

                <div class="soutenir-stats">
                    @foreach($projet['stats'] as $stat)
                        <div>
                            <strong>{{ $stat['value'] }}</strong>
                            <span>{{ $stat['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="soutenir-tab-panel" id="tab-objectifs">
                <h2>Objectifs</h2>
                <p>{{ $projet['description'] }}</p>
                <ul class="soutenir-list">
                    @foreach($projet['stats'] as $stat)
                        <li><i class="fas fa-check-circle"></i> {{ $stat['value'] }} {{ $stat['label'] }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="soutenir-tab-panel" id="tab-impact">
                <h2>Impact attendu</h2>
                <ul class="soutenir-list">
                    @foreach($projet['impacts'] as $impact)
                        <li><i class="fas fa-leaf"></i> {{ $impact }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="soutenir-diff">
                <h2>Votre soutien fait la différence</h2>
                <div class="soutenir-tiers">
                    @foreach($projet['impactDons'] as $tier)
                        <div class="soutenir-tier">
                            <strong>{{ $fmt($tier['montant']) }} FCFA</strong>
                            <span>= {{ $tier['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sidebar don --}}
        <aside class="soutenir-aside" id="don">
            <h2>Soutenir ce projet</h2>

            <div class="soutenir-amounts">
                <div>
                    <span>Montant de l'objectif</span>
                    <strong>{{ $fmt($projet['objectif']) }} FCFA</strong>
                </div>
                <div>
                    <span>Montant à collecter</span>
                    <strong class="warn">{{ $fmt($reste) }} FCFA</strong>
                </div>
                <div>
                    <span>Déjà collecté</span>
                    <strong class="ok">{{ $fmt($collecte) }} FCFA ({{ $projet['progress'] }}%)</strong>
                </div>
            </div>

            <div class="pe-progress-bar" style="margin: 0.75rem 0 1.25rem;"><span style="width: {{ $projet['progress'] }}%"></span></div>

            <p class="soutenir-label">Choisissez un montant</p>
            <div class="soutenir-presets" id="donPresets">
                @foreach($projet['dons'] as $montant)
                    <button type="button" data-amount="{{ $montant }}">{{ $fmt($montant) }} FCFA</button>
                @endforeach
            </div>

            <label class="soutenir-label" for="autreMontant">Autre montant</label>
            <div class="soutenir-custom">
                <input type="number" id="autreMontant" min="1000" step="1000" placeholder="Ex: 15 000">
                <span>FCFA</span>
            </div>

            <p class="soutenir-label">Mode de paiement</p>
            <div class="soutenir-pay">
                <label><input type="radio" name="paiement" value="mobile" checked> Mobile Money (Moov / TMoney)</label>
                <label><input type="radio" name="paiement" value="carte"> Carte bancaire (Visa / Mastercard)</label>
                <label><input type="radio" name="paiement" value="virement"> Virement bancaire</label>
                <label><input type="radio" name="paiement" value="autre"> Autre méthode</label>
            </div>

            <button type="button" class="btn btn-primary soutenir-submit" id="btnSoutenir">
                <i class="fas fa-heart"></i> Soutenir maintenant
            </button>
            <p class="soutenir-secure"><i class="fas fa-lock"></i> Paiement 100% sécurisé</p>
            <p class="soutenir-note" id="donFeedback" hidden></p>
        </aside>
    </div>
</section>

@include('partials.pe-modal')
@endsection

@push('scripts')
<script src="{{ asset('js/pe-modal.js') }}"></script>
<script>
(function () {
    const presets = document.getElementById('donPresets');
    const input = document.getElementById('autreMontant');
    const feedback = document.getElementById('donFeedback');
    const btn = document.getElementById('btnSoutenir');
    let selected = {{ $projet['dons'][0] }};

    presets.querySelectorAll('button').forEach((b) => {
        if (Number(b.dataset.amount) === selected) b.classList.add('is-active');
        b.addEventListener('click', () => {
            presets.querySelectorAll('button').forEach((x) => x.classList.remove('is-active'));
            b.classList.add('is-active');
            selected = Number(b.dataset.amount);
            input.value = '';
        });
    });

    input.addEventListener('input', () => {
        presets.querySelectorAll('button').forEach((x) => x.classList.remove('is-active'));
        selected = Number(input.value) || 0;
    });

    btn.addEventListener('click', () => {
        const montant = Number(input.value) || selected;
        const pay = document.querySelector('input[name="paiement"]:checked')?.value || 'mobile';
        if (!montant || montant < 1000) {
            feedback.hidden = false;
            feedback.textContent = 'Veuillez choisir un montant d\'au moins 1 000 FCFA.';
            return;
        }
        feedback.hidden = false;
        feedback.textContent = 'Merci ! Votre soutien de ' + montant.toLocaleString('fr-FR') +
            ' FCFA pour « {{ $projet['title'] }} » via ' + pay + ' sera bientôt traité.';
    });

    document.querySelectorAll('.soutenir-tabs button').forEach((tab) => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.soutenir-tabs button').forEach((t) => t.classList.remove('is-active'));
            document.querySelectorAll('.soutenir-tab-panel').forEach((p) => p.classList.remove('is-active'));
            tab.classList.add('is-active');
            document.getElementById('tab-' + tab.dataset.tab)?.classList.add('is-active');
        });
    });
})();
</script>
@endpush
