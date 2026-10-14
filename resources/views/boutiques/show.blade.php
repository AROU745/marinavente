@extends('layouts.app')

@section('content')
@php
    $banniere = $boutique->produitsBanniere->filter(fn ($p) => $p->photoUrl())->take(3);
    $themeBan = $boutique->themeKey('banniere');
    $themeCat = $boutique->themeKey('catalogue');
    $themeFoot = $boutique->themeKey('footer');
    $logo = $boutique->logoUrl();
    $orderPhone = $boutique->whatsapp ?: $boutique->telephone;
    $wa = preg_replace('/\D+/', '', $orderPhone);
@endphp

<div class="shop-shell">
    <header class="shop-header shop-zone-theme-{{ $themeCat }}">
        <div class="container shop-header-inner">
            <a href="{{ route('boutiques.show', $boutique->slug) }}" class="shop-brand">
                <img src="{{ $logo }}" alt="{{ $boutique->nom_entreprise }}" class="shop-logo">
                <span class="shop-brand-name">{{ $boutique->nom_entreprise }}</span>
            </a>
            <div class="shop-header-actions">
                @if ($boutique->telephone)
                    <a href="tel:{{ preg_replace('/\s+/', '', $boutique->telephone) }}" class="shop-header-link">
                        <i class="fas fa-phone"></i> <span>{{ $boutique->telephone }}</span>
                    </a>
                @endif
                @if ($boutique->email)
                    <a href="mailto:{{ $boutique->email }}" class="shop-header-link">
                        <i class="fas fa-envelope"></i> <span>{{ $boutique->email }}</span>
                    </a>
                @endif
            </div>
        </div>
    </header>

    @if ($banniere->isNotEmpty())
        <section class="shop-banner shop-zone-theme-{{ $themeBan }}" aria-label="Produits mis en avant">
            <div class="shop-banner-track" id="shopBannerTrack">
                @foreach ($banniere as $slide)
                    <article class="shop-banner-slide {{ $loop->first ? 'is-active' : '' }}">
                        <img src="{{ $slide->photoUrl() }}" alt="{{ $slide->nom }}">
                        <div class="shop-banner-overlay">
                            <p class="shop-banner-eyebrow">À la une</p>
                            <h2>{{ $slide->nom }}</h2>
                            @if ($slide->prix > 0)
                                <p class="shop-banner-price">{{ number_format($slide->prix, 0, ',', ' ') }} FCFA</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
            @if ($banniere->count() > 1)
                <button type="button" class="shop-banner-nav prev" id="bannerPrev" aria-label="Produit précédent">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="shop-banner-nav next" id="bannerNext" aria-label="Produit suivant">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="shop-banner-dots" id="bannerDots" role="tablist">
                    @foreach ($banniere as $slide)
                        <button type="button" class="{{ $loop->first ? 'is-active' : '' }}" data-index="{{ $loop->index }}" aria-label="Slide {{ $loop->iteration }}"></button>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    <section class="boutique-hero shop-hero shop-zone-theme-{{ $themeCat }}">
        <div class="container">
            <div class="boutique-show-header boutique-show-header-centered">
                <h1 class="shop-title-h1">{{ $boutique->nom_entreprise }}</h1>
                <h2 class="shop-title-h2">Catalogue de boutique</h2>
                @if ($boutique->telephone)
                    <a href="tel:{{ preg_replace('/\s+/', '', $boutique->telephone) }}" class="contact-chip">
                        <i class="fas fa-phone"></i> {{ $boutique->telephone }}
                    </a>
                @endif
            </div>
        </div>
    </section>

    <div class="shop-catalogue-wrap shop-zone-theme-{{ $themeCat }}">
        <div class="container">
            @if ($boutique->produits->isEmpty())
                <p class="boutique-lead">Aucun produit pour le moment.</p>
            @else
                <div class="boutique-catalogue">
                    @foreach ($boutique->produits as $produit)
                        @php $numeroProduit = $loop->iteration; @endphp
                        <article class="boutique-product-card">
                            <span class="product-number" aria-label="Numéro produit {{ $numeroProduit }}">N° {{ $numeroProduit }}</span>
                            <div class="thumb">
                                @if ($produit->photoUrl())
                                    <img src="{{ $produit->photoUrl() }}" alt="{{ $produit->nom }}">
                                @else
                                    <i class="fas fa-image" aria-hidden="true"></i>
                                @endif
                            </div>
                            <div class="body">
                                <h3>{{ $produit->nom }}</h3>
                                <p class="price">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</p>
                                <div class="meta">
                                    @if ($produit->tailles)
                                        <div><strong>Tailles :</strong> {{ $produit->tailles }}</div>
                                    @endif
                                    @if ($produit->couleurs)
                                        <div><strong>Couleurs :</strong> {{ $produit->couleurs }}</div>
                                    @endif
                                </div>
                                <span class="boutique-stock {{ $produit->quantite < 1 ? 'out' : '' }}">
                                    @if ($produit->quantite > 0)
                                        {{ $produit->quantite }} en stock
                                    @else
                                        Rupture de stock
                                    @endif
                                </span>
                                @php
                                    $writeMsg = rawurlencode(
                                        "Bonjour,\nJe vous contacte au sujet de l'article N° {$numeroProduit} : {$produit->nom} (" .
                                        number_format($produit->prix, 0, ',', ' ') . " FCFA)."
                                    );
                                    $writeUrl = $wa
                                        ? 'https://wa.me/' . $wa . '?text=' . $writeMsg
                                        : 'sms:' . preg_replace('/\s+/', '', $boutique->telephone) . '?body=' . $writeMsg;
                                @endphp
                                <div class="product-actions">
                                    <a
                                        href="{{ route('boutiques.produit', [$boutique->slug, $produit->id]) }}"
                                        class="btn-product btn-voir-article"
                                    >
                                        <i class="fas fa-images"></i> Voir la fiche
                                    </a>
                                    <a href="{{ $writeUrl }}" class="btn-product btn-ecrire-boutique" target="_blank" rel="noopener">
                                        <i class="fas fa-comment-dots"></i> Écrire à la boutique
                                    </a>
                                    <button
                                        type="button"
                                        class="btn-product btn-commander"
                                        data-product-id="{{ $produit->id }}"
                                        data-nom="{{ e($produit->nom) }}"
                                        data-prix="{{ $produit->prix }}"
                                        {{ $produit->quantite < 1 ? 'disabled' : '' }}
                                    >
                                        <i class="fas fa-shopping-bag"></i>
                                        {{ $produit->quantite < 1 ? 'Indisponible' : 'Commander' }}
                                    </button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <footer class="shop-footer shop-zone-theme-{{ $themeFoot }}">
        <div class="container shop-footer-grid">
            <div class="shop-footer-brand">
                <img src="{{ $logo }}" alt="{{ $boutique->nom_entreprise }}" class="shop-footer-logo">
                <strong>{{ $boutique->nom_entreprise }}</strong>
                @if ($boutique->footer_texte)
                    <p>{{ $boutique->footer_texte }}</p>
                @else
                    <p>Boutique en ligne</p>
                @endif
            </div>

            <div class="shop-footer-col">
                <h4>Contact</h4>
                <ul>
                    @if ($boutique->telephone)
                        <li>
                            <a href="tel:{{ preg_replace('/\s+/', '', $boutique->telephone) }}">
                                <i class="fas fa-phone"></i> {{ $boutique->telephone }}
                            </a>
                        </li>
                    @endif
                    @if ($boutique->whatsapp)
                        <li>
                            <a href="https://wa.me/{{ preg_replace('/\D+/', '', $boutique->whatsapp) }}" target="_blank" rel="noopener">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </li>
                    @endif
                    @if ($boutique->email)
                        <li>
                            <a href="mailto:{{ $boutique->email }}">
                                <i class="fas fa-envelope"></i> {{ $boutique->email }}
                            </a>
                        </li>
                    @endif
                    @if ($boutique->adresse || $boutique->ville)
                        <li>
                            <span>
                                <i class="fas fa-map-marker-alt"></i>
                                {{ trim(($boutique->adresse ?? '') . ($boutique->adresse && $boutique->ville ? ', ' : '') . ($boutique->ville ?? '')) }}
                            </span>
                        </li>
                    @endif
                    @if ($boutique->horaires)
                        <li>
                            <span><i class="fas fa-clock"></i> {{ $boutique->horaires }}</span>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="shop-footer-col">
                <h4>Réseaux</h4>
                <div class="shop-footer-socials">
                    @if ($boutique->facebook)
                        <a href="{{ $boutique->facebook }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if ($boutique->instagram)
                        <a href="{{ $boutique->instagram }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if ($boutique->tiktok)
                        <a href="{{ $boutique->tiktok }}" target="_blank" rel="noopener" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    @endif
                    @if (!$boutique->facebook && !$boutique->instagram && !$boutique->tiktok)
                        <p class="shop-footer-muted">Aucun réseau renseigné</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="container shop-footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $boutique->nom_entreprise }}</span>
            <span class="shop-footer-muted">Propulsé par MARINA</span>
        </div>
    </footer>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/boutiques-banner.js') }}"></script>
<script>
(function () {
  document.querySelectorAll('.btn-commander').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (btn.disabled) return;
      alert('Paiement bientôt disponible pour « ' + (btn.getAttribute('data-nom') || 'cet article') + ' ».');
    });
  });
})();
</script>
@endpush
