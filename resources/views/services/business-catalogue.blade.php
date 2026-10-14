@extends('layouts.app')

@section('content')
<section class="bcc-page" id="bccApp"
    data-pays-actif="{{ $paysActif }}"
>
    <div class="bcc-topbar">
        <div class="container bcc-topbar-inner">
            <a href="{{ url('/services/business-center') }}" class="bcc-brand">
                <span class="bcc-brand-ico"><i class="fas fa-shopping-bag"></i><i class="fas fa-globe"></i></span>
                <span>
                    <strong>BUSINESS CENTER</strong>
                    <small>Produits disponibles à l'international &amp; au Togo</small>
                </span>
            </a>
            <div class="bcc-topbar-actions">
                <label class="bcc-loc">
                    <i class="fas fa-globe"></i>
                    <select id="bccPaysSelect" aria-label="Pays d'origine">
                        <option value="tous" @selected($paysActif === 'tous')>Tous les pays</option>
                        @foreach ($paysList as $p)
                            <option value="{{ $p['slug'] }}" @selected($paysActif === $p['slug'])>{{ $p['pays'] }}</option>
                        @endforeach
                    </select>
                </label>
                <button type="button" class="bcc-cart-btn" id="bccCartOpen">
                    <i class="fas fa-shopping-cart"></i> Panier
                    <span class="bcc-cart-badge" id="bccCartBadge">0</span>
                </button>
            </div>
        </div>
    </div>

    <div class="container bcc-head">
        <div>
            <h1>Catalogue de produits</h1>
            <p>Commandez des produits de qualité — filtre actif : <strong id="bccPaysLabel">{{ $paysLabel }}</strong></p>
        </div>
        <div class="bcc-tools">
            <div class="bcc-search">
                <i class="fas fa-search"></i>
                <input type="search" id="bccSearch" placeholder="Rechercher un produit..." autocomplete="off">
            </div>
            <label class="bcc-sort">
                Trier par :
                <select id="bccSort">
                    <option value="popularite">Popularité</option>
                    <option value="prix-asc">Prix croissant</option>
                    <option value="prix-desc">Prix décroissant</option>
                    <option value="nom">Nom A–Z</option>
                </select>
            </label>
        </div>
        <div class="bcc-trust-mini">
            <span><i class="fas fa-award"></i> Produits de qualité</span>
            <span><i class="fas fa-truck"></i> Livraison rapide</span>
            <span><i class="fas fa-shield-alt"></i> Paiement sécurisé</span>
            <span><i class="fas fa-headset"></i> Support 24/7</span>
        </div>
    </div>

    <div class="container bcc-layout">
        <aside class="bcc-sidebar">
            <h2>Catégories</h2>
            <ul class="bcc-cats" id="bccCats">
                <li>
                    <button type="button" class="is-active" data-cat="all">
                        <i class="fas fa-th-large"></i> Tous les produits
                        <em>{{ $countAllFiltered }}</em>
                    </button>
                </li>
                @foreach ($categories as $cat => $n)
                    <li @if ($n < 1) hidden @endif>
                        <button type="button" data-cat="{{ $cat }}">
                            <i class="fas fa-tag"></i> {{ $cat }}
                            <em>{{ $n }}</em>
                        </button>
                    </li>
                @endforeach
            </ul>

            <h2>Pays d'origine</h2>
            <ul class="bcc-pays-filters" id="bccPaysFilters">
                <li>
                    <label>
                        <input type="radio" name="bccPays" value="tous" @checked($paysActif === 'tous')>
                        Tous les pays <em>{{ $countsPays['tous'] ?? 0 }}</em>
                    </label>
                </li>
                @foreach ($paysList as $p)
                    <li>
                        <label>
                            <input type="radio" name="bccPays" value="{{ $p['slug'] }}" @checked($paysActif === $p['slug'])>
                            <img src="{{ asset('images/business-intl/flags/' . $p['flagCode'] . '.svg') }}" alt="">
                            {{ $p['pays'] }}
                            <em>{{ $countsPays[$p['slug']] ?? 0 }}</em>
                        </label>
                    </li>
                @endforeach
            </ul>

            <div class="bcc-promo">
                <i class="fas fa-gift"></i>
                <p>Livraison rapide au Togo et depuis l'international.</p>
            </div>
        </aside>

        <div class="bcc-main">
            <p class="bcc-count" id="bccCount"></p>
            <div class="bcc-grid" id="bccGrid"></div>
            <p class="bcc-empty" id="bccEmpty" hidden>Aucun produit ne correspond à votre recherche.</p>
        </div>
    </div>

    <div class="bcc-stats">
        <div class="container bcc-stats-grid">
            <div><strong>+ {{ count($produits) }}</strong><span>Produits disponibles</span></div>
            <div><strong>4</strong><span>Pays partenaires</span></div>
            <div><strong>Livraison rapide</strong><span>Partout au Togo</span></div>
            <div><strong>Paiement sécurisé</strong><span>Plusieurs méthodes</span></div>
            <div><strong>Support client</strong><span>7j/7 — 24h/24</span></div>
        </div>
    </div>
</section>

<script type="application/json" id="bccProduitsData">@json($produits)</script>

{{-- Panier --}}
<div class="bcc-modal" id="bccCartModal" hidden>
    <div class="bcc-modal-backdrop" data-bcc-close="cart"></div>
    <div class="bcc-modal-dialog" role="dialog" aria-modal="true">
        <button type="button" class="bcc-modal-close" data-bcc-close="cart" aria-label="Fermer"><i class="fas fa-times"></i></button>
        <h2>Votre panier</h2>
        <div id="bccCartList" class="bcc-cart-list"></div>
        <div class="bcc-cart-total">Total : <strong id="bccCartTotal">0 FCFA</strong></div>
        <button type="button" class="btn bcc-btn-solid bcc-btn-full" id="bccCheckout" disabled>
            <i class="fas fa-lock"></i> Commander maintenant
        </button>
    </div>
</div>

{{-- Paiement impulsif --}}
<div class="bcc-modal" id="bccPayModal" hidden>
    <div class="bcc-modal-backdrop" data-bcc-close="pay"></div>
    <div class="bcc-modal-dialog" role="dialog" aria-modal="true">
        <button type="button" class="bcc-modal-close" data-bcc-close="pay" aria-label="Fermer"><i class="fas fa-times"></i></button>
        <h2>Payer maintenant</h2>
        <p class="bcc-modal-sub" id="bccPaySummary">—</p>
        <form id="bccPayForm" autocomplete="off" onsubmit="return false;">
            <div id="bccPayFields">
                <div class="bcc-field">
                    <label for="bccPayNom">Nom sur la carte *</label>
                    <input type="text" id="bccPayNom" placeholder="Nom complet">
                </div>
                <div class="bcc-field">
                    <label for="bccPayCard">Numéro de carte *</label>
                    <input type="text" id="bccPayCard" inputmode="numeric" maxlength="19" placeholder="XXXX XXXX XXXX XXXX">
                </div>
                <div class="bcc-form-row">
                    <div class="bcc-field">
                        <label for="bccPayExp">Expiration *</label>
                        <input type="text" id="bccPayExp" inputmode="numeric" maxlength="5" placeholder="MM/AA">
                    </div>
                    <div class="bcc-field">
                        <label for="bccPayCvc">CVC *</label>
                        <input type="text" id="bccPayCvc" inputmode="numeric" maxlength="4" placeholder="123">
                    </div>
                </div>
                <div class="bcc-amount-box">Montant : <strong id="bccPayAmount">—</strong></div>
                <button type="button" class="btn bcc-btn-solid bcc-btn-full" id="bccPaySubmit">
                    <i class="fas fa-lock"></i> Payer maintenant
                </button>
            </div>
            <div id="bccPaySuccess" hidden>
                <div class="bcc-success">
                    <div class="bcc-success-ico"><i class="fas fa-check"></i></div>
                    <h3>Commande confirmée !</h3>
                    <p id="bccPaySuccessMsg"></p>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/business-catalogue.js') }}"></script>
@endpush
