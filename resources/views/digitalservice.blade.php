@extends('layouts.app')

@section('content')
<section class="dig-page">
    <div class="container">
        <nav class="pe-breadcrumb" aria-label="Fil d'Ariane">
            <a href="{{ url('/') }}">Accueil</a>
            <span>&gt;</span>
            <a href="{{ url('/services/communication-digitale') }}">Communication Digitale</a>
            <span>&gt;</span>
            <strong>Services</strong>
        </nav>

        {{-- Hero --}}
        <div class="dig-hero">
            <div class="dig-hero-text">
                <h1>Communication Digitale</h1>
                <p>
                    Des solutions digitales complètes pour développer votre visibilité,
                    engager votre audience et booster votre croissance.
                </p>
                <div class="dig-hero-points">
                    <div>
                        <span class="dig-hero-ico"><i class="fas fa-hand-holding-heart"></i></span>
                        <strong>Accompagnement Personnalisé</strong>
                    </div>
                    <div>
                        <span class="dig-hero-ico"><i class="fas fa-chart-bar"></i></span>
                        <strong>Résultats Mesurables</strong>
                    </div>
                    <div>
                        <span class="dig-hero-ico"><i class="fas fa-headset"></i></span>
                        <strong>Support Réactif</strong>
                    </div>
                </div>
            </div>
            <div class="dig-hero-visual" aria-hidden="true">
                <div class="dig-hero-glow">
                    <i class="fas fa-bullhorn dig-ico-main"></i>
                    <i class="fab fa-instagram dig-float dig-f1"></i>
                    <i class="fab fa-facebook dig-float dig-f2"></i>
                    <i class="fab fa-tiktok dig-float dig-f3"></i>
                    <i class="fas fa-chart-line dig-float dig-f4"></i>
                    <i class="fas fa-mobile-alt dig-float dig-f5"></i>
                </div>
            </div>
        </div>

        {{-- Layout 3 colonnes --}}
        <div class="dig-layout">
            {{-- Sidebar gauche --}}
            <aside class="dig-side dig-side-left">
                <h3>Nos services</h3>
                <nav class="dig-nav">
                    <a href="{{ url('/digitalservice') }}" class="is-active"><i class="fas fa-th-large"></i> Aperçu</a>
                    @foreach($services as $svc)
                        <a href="{{ url('/digitalservice/' . $svc['slug']) }}"><i class="{{ $svc['icon'] }}"></i> {{ $svc['title'] }}</a>
                    @endforeach
                </nav>

                <h3 class="dig-side-title">Options</h3>
                <nav class="dig-nav">
                    <a href="#packs"><i class="fas fa-box-open"></i> Packs de Services</a>
                    <a href="{{ url('/contact') }}?sujet=devis"><i class="fas fa-file-alt"></i> Mes demandes de devis</a>
                </nav>

                <div class="dig-help">
                    <div class="dig-help-ico"><i class="fas fa-headset"></i></div>
                    <p>Besoin d'aide ? Notre équipe est là pour vous accompagner.</p>
                    <a href="{{ url('/contact') }}" class="btn dig-btn-solid dig-btn-sm">Nous contacter</a>
                </div>
            </aside>

            {{-- Contenu central --}}
            <div class="dig-main" id="apercu">
                <h2>Nos services</h2>
                <div class="dig-services-grid">
                    @foreach($services as $svc)
                        <article class="dig-svc-card" id="svc-{{ $svc['slug'] }}">
                            <div class="dig-svc-icon"><i class="{{ $svc['icon'] }}"></i></div>
                            <h3>{{ $svc['title'] }}</h3>
                            <p>{{ $svc['description'] }}</p>
                            <a href="{{ url('/digitalservice/' . $svc['slug']) }}" class="dig-link">
                                Voir les détails <i class="fas fa-arrow-right"></i>
                            </a>
                            <div class="dig-svc-actions">
                                <button
                                    type="button"
                                    class="btn dig-btn-solid dig-btn-sm dig-pay-btn"
                                    data-title="{{ $svc['title'] }}"
                                    data-prix="{{ $svc['prix'] }}"
                                >
                                    Payer maintenant
                                </button>
                                <button
                                    type="button"
                                    class="btn dig-btn-outline dig-btn-sm dig-devis-btn"
                                    data-title="{{ $svc['title'] }}"
                                >
                                    Demander un devis
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="dig-how">
                    <h2>Comment ça marche ?</h2>
                    <div class="dig-steps">
                        <div class="dig-step">
                            <span>1</span>
                            <div>
                                <strong>Choisissez un service</strong>
                                <p>Sélectionnez le service qui correspond à vos besoins.</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right dig-step-arrow"></i>
                        <div class="dig-step">
                            <span>2</span>
                            <div>
                                <strong>Choisissez un pack ou demandez un devis</strong>
                                <p>Optez pour une formule prête ou une offre sur-mesure.</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right dig-step-arrow"></i>
                        <div class="dig-step">
                            <span>3</span>
                            <div>
                                <strong>Validation &amp; Démarrage</strong>
                                <p>Nous validons avec vous et lançons rapidement.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar packs --}}
            <aside class="dig-side dig-side-packs" id="packs">
                <h2>Choisir un pack</h2>
                <p class="dig-packs-intro">Des formules claires pour accélérer votre communication.</p>

                @foreach($packs as $pack)
                    <article class="dig-pack {{ !empty($pack['popular']) ? 'is-popular' : '' }}">
                        @if(!empty($pack['popular']))
                            <span class="dig-pack-badge">Le plus populaire</span>
                        @endif
                        <h3>{{ $pack['name'] }}</h3>
                        <p class="dig-pack-price">
                            <strong>{{ number_format($pack['price'], 0, ',', ' ') }} FCFA</strong>
                            <span>/ mois</span>
                        </p>
                        <ul>
                            @foreach($pack['features'] as $feat)
                                <li><i class="fas fa-check"></i> {{ $feat }}</li>
                            @endforeach
                        </ul>
                        <button
                            type="button"
                            class="btn dig-btn-sm dig-pay-btn {{ !empty($pack['popular']) ? 'dig-btn-solid' : 'dig-btn-outline' }}"
                            data-title="{{ $pack['name'] }}"
                            data-prix="{{ $pack['price'] }}"
                        >
                            Payer maintenant
                        </button>
                    </article>
                @endforeach

                <div class="dig-custom">
                    <h3>Service sur-mesure ?</h3>
                    <p>Besoin d'une offre adaptée à votre projet ?</p>
                    <button type="button" class="btn dig-btn-solid dig-btn-sm dig-devis-btn" data-title="Service sur-mesure">
                        Demander un devis personnalisé
                    </button>
                </div>
            </aside>
        </div>
    </div>
</section>

@include('partials.dig-modals')
@endsection

@push('scripts')
<script src="{{ asset('js/digitalservice.js') }}"></script>
@endpush
