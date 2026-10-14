@extends('layouts.app')

@section('content')
<section class="dig-page dig-detail-page">
    <div class="container">
        <nav class="pe-breadcrumb" aria-label="Fil d'Ariane">
            <a href="{{ url('/') }}">Accueil</a>
            <span>&gt;</span>
            <a href="{{ url('/digitalservice') }}">Communication Digitale</a>
            <span>&gt;</span>
            <strong>{{ $service['title'] }}</strong>
        </nav>

        <a href="{{ url('/digitalservice') }}" class="dig-back">
            <i class="fas fa-arrow-left"></i> Retour aux services
        </a>

        {{-- Hero détail --}}
        <div class="dig-detail-hero">
            <div class="dig-detail-hero-text">
                <div class="dig-detail-hero-ico"><i class="{{ $service['icon'] }}"></i></div>
                <h1>{{ $service['title'] }}</h1>
                <p>{{ $service['lead'] }}</p>
                <div class="dig-hero-points">
                    @foreach($service['points'] as $i => $point)
                        <div>
                            <span class="dig-hero-ico">
                                <i class="fas fa-{{ ['hand-holding-heart', 'chart-bar', 'sliders-h'][$i] ?? 'check' }}"></i>
                            </span>
                            <strong>{{ $point }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="dig-detail-hero-visual" aria-hidden="true">
                <div class="dig-hero-glow dig-hero-glow-detail">
                    <i class="{{ $service['icon'] }} dig-ico-main"></i>
                    <i class="fas fa-bullseye dig-float dig-f2"></i>
                    <i class="fas fa-chart-bar dig-float dig-f4"></i>
                </div>
            </div>
        </div>

        {{-- Contenu 2 colonnes --}}
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
                    <span>/ mois</span>
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

        {{-- Livrables --}}
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

        {{-- Pourquoi --}}
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

        {{-- CTA bas --}}
        <div class="dig-cta-banner">
            <p>Prêt à booster votre présence en ligne ? Payez maintenant ou demandez un devis personnalisé.</p>
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
