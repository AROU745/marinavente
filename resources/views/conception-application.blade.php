@extends('layouts.app')

@section('content')
<section class="dig-page dig-page-conception">
    <div class="container">
        <nav class="pe-breadcrumb" aria-label="Fil d'Ariane">
            <a href="{{ url('/') }}">Accueil</a>
            <span>&gt;</span>
            <a href="{{ url('/services/conception-application') }}">Conception d'application</a>
            <span>&gt;</span>
            <strong>Services</strong>
        </nav>

        <div class="dig-hero">
            <div class="dig-hero-text">
                <h1>Conception d'application <span class="cap-accent">web, mobile &amp; desktop</span></h1>
                <p>
                    Des solutions logicielles sur mesure pour digitaliser votre activité :
                    applications web, mobiles et desktop, de la conception à la maintenance.
                </p>
                <div class="dig-hero-points">
                    <div>
                        <span class="dig-hero-ico"><i class="fas fa-code"></i></span>
                        <strong>Développement sur mesure</strong>
                    </div>
                    <div>
                        <span class="dig-hero-ico"><i class="fas fa-mobile-alt"></i></span>
                        <strong>Multi-plateformes</strong>
                    </div>
                    <div>
                        <span class="dig-hero-ico"><i class="fas fa-headset"></i></span>
                        <strong>Support dédié</strong>
                    </div>
                </div>
            </div>
            <div class="dig-hero-visual dig-hero-visual-img">
                <img src="{{ asset('images/conception/hero-dev.jpg') }}" alt="Développement logiciel">
            </div>
        </div>

        <div class="dig-layout">
            <aside class="dig-side dig-side-left">
                <h3>Nos services</h3>
                <nav class="dig-nav">
                    <a href="{{ url('/conception-application') }}" class="is-active"><i class="fas fa-th-large"></i> Aperçu</a>
                    @foreach($services as $svc)
                        <a href="{{ url('/conception-application/' . $svc['slug']) }}"><i class="{{ $svc['icon'] }}"></i> {{ $svc['title'] }}</a>
                    @endforeach
                </nav>

                <h3 class="dig-side-title">Options</h3>
                <nav class="dig-nav">
                    <a href="#packs"><i class="fas fa-box-open"></i> Packs de Services</a>
                    <a href="{{ url('/contact') }}?sujet=devis-application"><i class="fas fa-file-alt"></i> Demande de devis</a>
                </nav>

                <div class="dig-help">
                    <div class="dig-help-ico"><i class="fas fa-headset"></i></div>
                    <p>Un projet logiciel en tête ? Parlons-en avec notre équipe technique.</p>
                    <a href="{{ url('/contact') }}" class="btn dig-btn-solid dig-btn-sm">Nous contacter</a>
                </div>
            </aside>

            <div class="dig-main" id="apercu">
                <h2>Nos services</h2>
                <div class="dig-services-grid dig-services-grid-img">
                    @foreach($services as $svc)
                        <article class="dig-svc-card dig-svc-card-img" id="svc-{{ $svc['slug'] }}">
                            <div class="dig-svc-media">
                                <img src="{{ asset('images/conception/' . ($svc['image'] ?? 'hero-dev.jpg')) }}" alt="{{ $svc['title'] }}">
                            </div>
                            <div class="dig-svc-icon"><i class="{{ $svc['icon'] }}"></i></div>
                            <h3>{{ $svc['title'] }}</h3>
                            <p>{{ $svc['description'] }}</p>
                            <a href="{{ url('/conception-application/' . $svc['slug']) }}" class="dig-link">
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
                                <p>Web, mobile, desktop, UI/UX ou maintenance.</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right dig-step-arrow"></i>
                        <div class="dig-step">
                            <span>2</span>
                            <div>
                                <strong>Pack ou devis sur-mesure</strong>
                                <p>Optez pour une formule prête ou une offre adaptée.</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right dig-step-arrow"></i>
                        <div class="dig-step">
                            <span>3</span>
                            <div>
                                <strong>Conception &amp; livraison</strong>
                                <p>Développement agile, tests et mise en production.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="dig-side dig-side-packs" id="packs">
                <h2>Choisir un pack</h2>
                <p class="dig-packs-intro">Des formules claires pour lancer votre projet logiciel.</p>

                @foreach($packs as $pack)
                    <article class="dig-pack {{ !empty($pack['popular']) ? 'is-popular' : '' }}">
                        @if(!empty($pack['popular']))
                            <span class="dig-pack-badge">Le plus populaire</span>
                        @endif
                        <h3>{{ $pack['name'] }}</h3>
                        <p class="dig-pack-price">
                            <strong>{{ number_format($pack['price'], 0, ',', ' ') }} FCFA</strong>
                            <span>à partir de</span>
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
                    <h3>Projet sur-mesure ?</h3>
                    <p>Besoin d'une offre adaptée à votre application ?</p>
                    <button type="button" class="btn dig-btn-solid dig-btn-sm dig-devis-btn" data-title="Application sur-mesure">
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
