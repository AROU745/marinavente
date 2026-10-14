@extends('layouts.app')

@section('content')
<section class="dig-page dig-detail-page dig-page-conception">
    <div class="container">
        <nav class="pe-breadcrumb" aria-label="Fil d'Ariane">
            <a href="{{ url('/') }}">Accueil</a>
            <span>&gt;</span>
            <a href="{{ url('/conception-application') }}">Conception d'application</a>
            <span>&gt;</span>
            <strong>{{ $service['title'] }}</strong>
        </nav>

        <a href="{{ url('/conception-application') }}" class="dig-back">
            <i class="fas fa-arrow-left"></i> Retour aux services
        </a>

        <div class="dig-detail-hero">
            <div class="dig-detail-hero-text">
                <div class="dig-detail-hero-ico"><i class="{{ $service['icon'] }}"></i></div>
                <h1>{{ $service['title'] }}</h1>
                <p>{{ $service['lead'] }}</p>
                <div class="dig-hero-points">
                    @foreach($service['points'] as $i => $point)
                        <div>
                            <span class="dig-hero-ico">
                                <i class="fas fa-{{ ['code', 'mobile-alt', 'cogs'][$i] ?? 'check' }}"></i>
                            </span>
                            <strong>{{ $point }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="dig-detail-hero-visual dig-hero-visual-img">
                <img src="{{ asset('images/conception/' . ($service['image'] ?? 'hero-dev.jpg')) }}" alt="{{ $service['title'] }}">
            </div>
        </div>

        <div class="dig-detail-grid">
            <div class="dig-detail-main">
                <h2>Aperçu du service</h2>
                <p class="dig-detail-about">{{ $service['about'] }}</p>

                <h2>Ce qui est inclus</h2>
                <ul class="dig-inclus">
                    @foreach($service['inclus'] as $item)
                        <li><i class="fas fa-check"></i> {{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <aside class="dig-detail-card">
                <p class="dig-detail-price">
                    À partir de <strong>{{ number_format($service['prix'], 0, ',', ' ') }} FCFA</strong>
                </p>
                <ul class="dig-detail-meta">
                    <li><i class="fas fa-clock"></i> Délai moyen : {{ $service['delai'] }}</li>
                    <li><i class="fas fa-box"></i> {{ $service['livrablesLabel'] }}</li>
                    <li><i class="fas fa-headset"></i> {{ $service['support'] }}</li>
                </ul>
                <button
                    type="button"
                    class="btn dig-btn-solid dig-btn-full dig-pay-btn"
                    data-title="{{ $service['title'] }}"
                    data-prix="{{ $service['prix'] }}"
                >
                    Payer maintenant
                </button>
                <button
                    type="button"
                    class="btn dig-btn-outline dig-btn-full dig-devis-btn"
                    data-title="{{ $service['title'] }}"
                >
                    Demander un devis
                </button>
            </aside>
        </div>

        <div class="dig-section">
            <h2>Livrables</h2>
            <div class="dig-livrables">
                @foreach($service['livrables'] as $liv)
                    <div class="dig-liv-card">
                        <i class="{{ $liv['icon'] }}"></i>
                        <strong>{{ $liv['title'] }}</strong>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="dig-section">
            <h2>Pourquoi nous choisir ?</h2>
            <div class="dig-pourquoi">
                @foreach($service['pourquoi'] as $item)
                    <article class="dig-pourquoi-card">
                        <div class="dig-svc-icon"><i class="{{ $item['icon'] }}"></i></div>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="dig-cta-banner">
            <p>Prêt à lancer votre application ? Payez maintenant ou demandez un devis personnalisé.</p>
            <div class="dig-cta-actions">
                <button
                    type="button"
                    class="btn dig-cta-pay dig-pay-btn"
                    data-title="{{ $service['title'] }}"
                    data-prix="{{ $service['prix'] }}"
                >
                    Payer maintenant
                </button>
                <button
                    type="button"
                    class="btn dig-cta-devis dig-devis-btn"
                    data-title="{{ $service['title'] }}"
                >
                    Demander un devis
                </button>
            </div>
        </div>
    </div>
</section>

@include('partials.dig-modals')
@endsection

@push('scripts')
<script src="{{ asset('js/digitalservice.js') }}"></script>
@endpush
