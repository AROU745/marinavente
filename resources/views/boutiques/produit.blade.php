@extends('layouts.app')

@section('content')
@php
    $themeCat = $boutique->themeKey('catalogue');
    $orderPhone = $boutique->whatsapp ?: $boutique->telephone;
    $wa = preg_replace('/\D+/', '', $orderPhone);
    $writeMsg = rawurlencode(
        "Bonjour,\nJe vous contacte au sujet de l'article N° {$numero} : {$produit->nom} (" .
        number_format($produit->prix, 0, ',', ' ') . " FCFA)."
    );
    $writeUrl = $wa
        ? 'https://wa.me/' . $wa . '?text=' . $writeMsg
        : 'sms:' . preg_replace('/\s+/', '', $boutique->telephone) . '?body=' . $writeMsg;
    $first = $gallery->first();
@endphp

<div class="shop-shell shop-zone-theme-{{ $themeCat }} article-page">
    <header class="shop-header">
        <div class="container shop-header-inner">
            <a href="{{ route('boutiques.show', $boutique->slug) }}" class="shop-brand">
                <img src="{{ $boutique->logoUrl() }}" alt="{{ $boutique->nom_entreprise }}" class="shop-logo">
                <span class="shop-brand-name">{{ $boutique->nom_entreprise }}</span>
            </a>
            <a href="{{ route('boutiques.show', $boutique->slug) }}" class="shop-header-link">
                <i class="fas fa-arrow-left"></i> <span>Retour catalogue</span>
            </a>
        </div>
    </header>

    <div class="container article-layout">
        <div class="article-gallery-col">
            <div class="article-main-media" id="articleMainMedia">
                @if ($first)
                    @if ($first['is_video'])
                        <video src="{{ $first['url'] }}" controls playsinline class="article-main-video"></video>
                    @else
                        <img src="{{ $first['url'] }}" alt="{{ $produit->nom }}" id="articleMainImg">
                    @endif
                @else
                    <div class="article-empty-media">
                        <i class="fas fa-image"></i>
                        <p>Aucune image disponible</p>
                    </div>
                @endif
            </div>

            @if ($gallery->count() > 1)
                <div class="article-thumbs" role="list">
                    @foreach ($gallery as $item)
                        <button
                            type="button"
                            class="article-thumb {{ $loop->first ? 'is-active' : '' }}"
                            data-url="{{ $item['url'] }}"
                            data-video="{{ $item['is_video'] ? '1' : '0' }}"
                            data-label="{{ $item['label'] }}"
                            aria-label="{{ $item['label'] }}"
                        >
                            @if ($item['is_video'])
                                <span class="thumb-video-badge"><i class="fas fa-play"></i></span>
                                <video src="{{ $item['url'] }}" muted preload="metadata"></video>
                            @else
                                <img src="{{ $item['url'] }}" alt="">
                            @endif
                        </button>
                    @endforeach
                </div>
            @endif

            @if ($produit->hasDemandeIa())
                <div class="article-ia-banner">
                    <i class="fas fa-wand-magic-sparkles"></i>
                    <div>
                        @if ($produit->statut_ia === 'traite' || $produit->photosIa->isNotEmpty() || $produit->videosIa->isNotEmpty())
                            <strong>Photos &amp; vidéo produit disponibles</strong>
                            <p>
                                Visuels professionnels ajoutés à la galerie ci-dessus.
                                <span class="ia-status-pill is-done">Livré</span>
                            </p>
                        @else
                            <strong>Contenu IA en préparation</strong>
                            <p>
                                @if ($produit->demande_photos_ia) Pack 5 photos (1 000 FCFA) @endif
                                @if ($produit->demande_photos_ia && $produit->demande_video_ia) · @endif
                                @if ($produit->demande_video_ia) 1 vidéo (2 000 FCFA) @endif
                                — Total {{ number_format($produit->montant_ia, 0, ',', ' ') }} FCFA
                                <span class="ia-status-pill">En cours de traitement</span>
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="article-info-col">
            <p class="article-ref">Article N° {{ $numero }}</p>
            <h1>{{ $produit->nom }}</h1>
            <p class="article-price">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</p>

            <div class="article-meta">
                @if ($produit->tailles)
                    <div><span>Tailles</span><strong>{{ $produit->tailles }}</strong></div>
                @endif
                @if ($produit->couleurs)
                    <div><span>Couleurs</span><strong>{{ $produit->couleurs }}</strong></div>
                @endif
                <div>
                    <span>Stock</span>
                    <strong class="{{ $produit->quantite < 1 ? 'out' : '' }}">
                        {{ $produit->quantite > 0 ? $produit->quantite . ' disponibles' : 'Rupture' }}
                    </strong>
                </div>
            </div>

            <div class="article-actions">
                <a href="{{ $writeUrl }}" class="btn-product btn-ecrire-boutique" target="_blank" rel="noopener">
                    <i class="fas fa-comment-dots"></i> Écrire à la boutique
                </a>
                <button
                    type="button"
                    class="btn-product btn-commander"
                    data-product-id="{{ $produit->id }}"
                    data-nom="{{ $produit->nom }}"
                    data-prix="{{ $produit->prix }}"
                    {{ $produit->quantite < 1 ? 'disabled' : '' }}
                >
                    <i class="fas fa-shopping-bag"></i>
                    {{ $produit->quantite < 1 ? 'Indisponible' : 'Commander' }}
                </button>
            </div>

            <a href="{{ route('boutiques.show', $boutique->slug) }}" class="article-back-link">
                <i class="fas fa-store"></i> Voir tout le catalogue
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/boutiques-produit-page.js') }}"></script>
@endpush
