@extends('layouts.app')

@section('content')
<section class="st-page">
    {{-- Hero --}}
    <div class="container st-hero">
        <div class="st-hero-text">
            <a href="{{ url('/#services') }}" class="st-back">
                <i class="fas fa-arrow-left"></i> Nos services
            </a>
            <p class="st-eyebrow">MARINA — Sous-traitance internationale</p>
            <h1>SOUS-TRAITANCE <span>INTERNATIONALE</span></h1>
            <p>
                Connectons les entreprises en France aux talents togolais :
                missions à distance, qualité suivie et résultats professionnels.
            </p>
            <div class="st-hero-actions">
                <a href="{{ url('/services/sous-traitance/deposer-projet') }}" class="btn st-btn-solid">
                    <i class="fas fa-rocket"></i> Déposer un projet
                </a>
                <a href="{{ url('/services/sous-traitance/inscription') }}" class="btn st-btn-outline">
                    <i class="fas fa-user-plus"></i> Je m'inscris / Devenir prestataire
                </a>
            </div>
        </div>
        <div class="st-hero-media">
            <img src="{{ asset('images/sous-traitance/hero-map.jpg') }}" alt="Connexion France — Togo">
            <div class="st-map-badge">
                <span><i class="fas fa-map-marker-alt"></i> France</span>
                <i class="fas fa-exchange-alt"></i>
                <span><i class="fas fa-map-marker-alt"></i> Togo</span>
            </div>
        </div>
    </div>

    {{-- Domaines --}}
    <div class="st-domains" id="competences">
        <div class="container">
            <header class="st-section-head">
                <h2>Domaines de compétences</h2>
                <p>Des expertises adaptées aux besoins des entreprises européennes.</p>
            </header>
            <div class="st-domains-grid">
                @foreach ($domaines as $d)
                    <article class="st-domain-card">
                        <div class="st-domain-ico" style="--c: {{ $d['color'] }}"><i class="{{ $d['icon'] }}"></i></div>
                        <h3>{{ $d['title'] }}</h3>
                        <p>{{ $d['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Comment ça marche --}}
    <div class="container st-steps">
        <header class="st-section-head">
            <h2>Comment ça fonctionne ?</h2>
            <p>Un parcours simple, transparent et accompagné.</p>
        </header>
        <ol class="st-steps-list">
            @foreach ($etapes as $i => $e)
                <li>
                    <span class="st-step-num">{{ $i + 1 }}</span>
                    <strong>{{ $e['title'] }}</strong>
                    <p>{{ $e['text'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>

    {{-- Dual persona --}}
    <div class="st-dual">
        <div class="container st-dual-grid">
            <article class="st-persona">
                <img src="{{ asset('images/sous-traitance/france.jpg') }}" alt="Entreprises en France">
                <div class="st-persona-body">
                    <h3>Entreprises en France</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Réduction des coûts opérationnels</li>
                        <li><i class="fas fa-check"></i> Talents formés et suivis</li>
                        <li><i class="fas fa-check"></i> Coordination et reporting</li>
                        <li><i class="fas fa-check"></i> Qualité et délais maîtrisés</li>
                    </ul>
                    <a href="{{ url('/services/sous-traitance/deposer-projet') }}" class="btn st-btn-solid">Déposer un projet</a>
                </div>
            </article>
            <article class="st-persona">
                <img src="{{ asset('images/sous-traitance/togo-talent.jpg') }}" alt="Talents au Togo">
                <div class="st-persona-body">
                    <h3>Talents au Togo</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Missions internationales à distance</li>
                        <li><i class="fas fa-check"></i> Accompagnement et formation</li>
                        <li><i class="fas fa-check"></i> Paiements sécurisés</li>
                        <li><i class="fas fa-check"></i> Expérience pro valorisante</li>
                    </ul>
                    <a href="{{ url('/services/sous-traitance/inscription') }}" class="btn st-btn-solid">Je m'inscris</a>
                </div>
            </article>
        </div>
    </div>

    {{-- Confiance --}}
    <div class="container st-trust">
        <h2>Ils nous font confiance</h2>
        <div class="st-trust-logos">
            <span>Partenaires</span>
            <span>Entreprises</span>
            <span>PME</span>
            <span>Startups</span>
            <span>Institutions</span>
        </div>
    </div>

    {{-- Bandeau réassurance (comme maquette, dans le contenu) --}}
    <div class="st-features">
        <div class="container st-features-grid">
            <div>
                <i class="fas fa-shield-alt"></i>
                <strong>Sécurité</strong>
                <p>Vos données et paiements sont 100% sécurisés.</p>
            </div>
            <div>
                <i class="fas fa-headset"></i>
                <strong>Accompagnement</strong>
                <p>Une équipe à votre écoute à chaque étape.</p>
            </div>
            <div>
                <i class="fas fa-globe-africa"></i>
                <strong>Réseau international</strong>
                <p>Connectons les talents au monde entier.</p>
            </div>
            <div>
                <i class="fas fa-heart"></i>
                <strong>Performance</strong>
                <p>Ensemble, créons de la valeur et des opportunités business.</p>
            </div>
        </div>
    </div>
</section>
@endsection
