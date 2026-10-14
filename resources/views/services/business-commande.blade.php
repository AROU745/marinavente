@extends('layouts.app')

@section('content')
@php
    $flagUrl = asset('images/business-intl/flags/' . $produit['flagCode'] . '.svg');
    $catalogueUrl = url('/services/business-center/catalogue/' . ($produit['paysSlug'] ?? 'tous'));
@endphp

<section class="bco-page" id="bcoApp"
    data-slug="{{ $produit['slug'] }}"
    data-prix="{{ $produit['prix'] }}"
    data-title="{{ $produit['title'] }}"
    data-catalogue="{{ $catalogueUrl }}"
>
    <div class="bco-topbar">
        <div class="container bco-topbar-inner">
            <a href="{{ url('/services/business-center') }}" class="bco-brand">
                <span class="bco-brand-ico"><i class="fas fa-shopping-bag"></i><i class="fas fa-globe"></i></span>
                <span>
                    <strong>BUSINESS CENTER</strong>
                    <small>Produits disponibles à l'international &amp; au Togo</small>
                </span>
            </a>
            <div class="bco-topbar-actions">
                <span class="bco-loc"><i class="fas fa-globe"></i> {{ $produit['pays'] }}</span>
                <a href="{{ $catalogueUrl }}" class="bco-cart-btn">
                    <i class="fas fa-shopping-cart"></i> Catalogue
                </a>
            </div>
        </div>
    </div>

    <div class="container bco-wrap">
        <a href="{{ $catalogueUrl }}" class="bco-back"><i class="fas fa-arrow-left"></i> Retour au catalogue</a>
        <h1>Détails de la commande</h1>

        <div class="bco-layout">
            {{-- Colonne produit --}}
            <div class="bco-left">
                <article class="bco-product">
                    <div class="bco-product-media">
                        <i class="{{ $produit['icon'] }}"></i>
                    </div>
                    <div class="bco-product-info">
                        <h2>{{ $produit['title'] }}</h2>
                        <p class="bco-origin">
                            Origine : {{ $produit['pays'] }}
                            <img src="{{ $flagUrl }}" alt="">
                        </p>
                        <p class="bco-price" id="bcoUnitPrice">{{ number_format($produit['prix'], 0, ',', ' ') }} FCFA</p>
                        <ul class="bco-specs">
                            @foreach ($produit['specs'] as $spec)
                                <li><i class="fas fa-check"></i> {{ $spec }}</li>
                            @endforeach
                        </ul>
                    </div>
                </article>

                <div class="bco-block">
                    <label class="bco-label" for="bcoQty">Quantité</label>
                    <div class="bco-qty">
                        <button type="button" id="bcoQtyMinus" aria-label="Diminuer">−</button>
                        <input type="number" id="bcoQty" value="1" min="1" max="99">
                        <button type="button" id="bcoQtyPlus" aria-label="Augmenter">+</button>
                    </div>
                    <p class="bco-hint"><i class="fas fa-box"></i> Stock disponible</p>
                </div>

                <div class="bco-block">
                    <label class="bco-label" for="bcoSpecs">Caractéristiques exactes</label>
                    <p class="bco-help">Précisez vos besoins : couleur, taille, mémoire, clavier AZERTY, options, etc.</p>
                    <textarea id="bcoSpecs" maxlength="500" rows="5" placeholder="Ex. : clavier AZERTY, 16 Go RAM, couleur noire…"></textarea>
                    <div class="bco-counter"><span id="bcoSpecsCount">0</span>/500</div>
                </div>

                <div class="bco-block">
                    <label class="bco-label">Photos (exemple, référence, modèle souhaité)</label>
                    <p class="bco-help">Jusqu’à 5 images (aperçu local, non envoyées au serveur).</p>
                    <div class="bco-photos" id="bcoPhotos">
                        <label class="bco-photo-add" id="bcoPhotoAdd">
                            <input type="file" id="bcoPhotoInput" accept="image/*" multiple hidden>
                            <i class="fas fa-camera"></i>
                            <span>Cliquer pour ajouter</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Colonne formulaire --}}
            <div class="bco-right">
                <form id="bcoForm" autocomplete="on" onsubmit="return false;">
                    <div class="bco-card">
                        <h3>Informations de contact</h3>
                        <div class="bco-field">
                            <label for="bcoNom">Nom complet <span>*</span></label>
                            <input type="text" id="bcoNom" required placeholder="Votre nom et prénom">
                        </div>
                        <div class="bco-field">
                            <label for="bcoEmail">Email <em>(facultatif)</em></label>
                            <input type="email" id="bcoEmail" placeholder="ex. vous@email.com">
                        </div>
                        <div class="bco-field">
                            <label for="bcoTel">Numéro de téléphone <em>(facultatif)</em></label>
                            <div class="bco-phone">
                                <span class="bco-phone-code"><img src="{{ asset('images/business-intl/flags/tg.svg') }}" alt=""> +228</span>
                                <input type="tel" id="bcoTel" placeholder="90 00 00 00">
                            </div>
                        </div>
                    </div>

                    <div class="bco-card">
                        <h3>Adresse de livraison</h3>
                        <div class="bco-field">
                            <label for="bcoPaysLiv">Pays</label>
                            <select id="bcoPaysLiv">
                                <option value="Togo" selected>Togo</option>
                                <option value="Bénin">Bénin</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        <div class="bco-field">
                            <label for="bcoVille">Ville</label>
                            <input type="text" id="bcoVille" placeholder="Lomé">
                        </div>
                        <div class="bco-field">
                            <label for="bcoAdresse">Adresse complète</label>
                            <input type="text" id="bcoAdresse" placeholder="Quartier, rue, repère…">
                        </div>
                        <div class="bco-field">
                            <label for="bcoCp">Code postal <em>(facultatif)</em></label>
                            <input type="text" id="bcoCp" placeholder="BP / code">
                        </div>
                    </div>

                    <div class="bco-card">
                        <h3>Mode d'obtention</h3>
                        <div class="bco-modes" role="radiogroup" aria-label="Mode d'obtention">
                            <button type="button" class="bco-mode is-active" data-mode="devis">
                                <i class="fas fa-file-invoice"></i>
                                <strong>Demander un devis</strong>
                                <span>Recevez un devis personnalisé sous 24h.</span>
                            </button>
                            <button type="button" class="bco-mode" data-mode="achat">
                                <i class="fas fa-credit-card"></i>
                                <strong>Acheter maintenant</strong>
                                <span>Payez en ligne et recevez votre commande.</span>
                            </button>
                        </div>
                    </div>

                    <div class="bco-card" id="bcoPayBlock" hidden>
                        <h3>Méthode de paiement</h3>
                        <div class="bco-pay-methods">
                            <label class="bco-pay-opt is-active">
                                <input type="radio" name="bcoPayMethod" value="mobile" checked>
                                <i class="fas fa-mobile-alt"></i>
                                <span>Mobile Money<br><small>Flooz / TMoney</small></span>
                            </label>
                            <label class="bco-pay-opt">
                                <input type="radio" name="bcoPayMethod" value="carte">
                                <i class="fas fa-credit-card"></i>
                                <span>Carte bancaire<br><small>Visa / Mastercard</small></span>
                            </label>
                            <label class="bco-pay-opt">
                                <input type="radio" name="bcoPayMethod" value="virement">
                                <i class="fas fa-university"></i>
                                <span>Virement<br><small>Bancaire</small></span>
                            </label>
                        </div>
                        <div class="bco-pay-fields" id="bcoCardFields" hidden>
                            <div class="bco-field">
                                <label for="bcoCard">Numéro de carte</label>
                                <input type="text" id="bcoCard" inputmode="numeric" maxlength="19" placeholder="XXXX XXXX XXXX XXXX">
                            </div>
                            <div class="bco-form-row">
                                <div class="bco-field">
                                    <label for="bcoExp">Expiration</label>
                                    <input type="text" id="bcoExp" inputmode="numeric" maxlength="5" placeholder="MM/AA">
                                </div>
                                <div class="bco-field">
                                    <label for="bcoCvc">CVC</label>
                                    <input type="text" id="bcoCvc" inputmode="numeric" maxlength="4" placeholder="123">
                                </div>
                            </div>
                        </div>
                        <div class="bco-field" id="bcoMobileFields">
                            <label for="bcoMobileNum">Numéro Mobile Money</label>
                            <input type="tel" id="bcoMobileNum" placeholder="90 00 00 00">
                        </div>
                    </div>

                    <div class="bco-card bco-summary">
                        <h3>Résumé de la commande</h3>
                        <div class="bco-sum-line">
                            <span>{{ $produit['title'] }} × <em id="bcoSumQty">1</em></span>
                            <strong id="bcoSumSub">{{ number_format($produit['prix'], 0, ',', ' ') }} FCFA</strong>
                        </div>
                        <div class="bco-sum-line">
                            <span>Frais de livraison</span>
                            <strong>À définir</strong>
                        </div>
                        <div class="bco-sum-total">
                            <span>Total estimé</span>
                            <strong id="bcoSumTotal">{{ number_format($produit['prix'], 0, ',', ' ') }} FCFA</strong>
                        </div>
                    </div>

                    <div class="bco-actions">
                        <a href="{{ $catalogueUrl }}" class="btn bco-btn-outline">Continuer mes achats</a>
                        <button type="button" class="btn bco-btn-outline" id="bcoReset">Vider le formulaire</button>
                        <button type="button" class="btn bco-btn-solid" id="bcoSubmit">
                            <i class="fas fa-check"></i> <span id="bcoSubmitLabel">Valider ma demande</span>
                        </button>
                    </div>

                    <div class="bco-success" id="bcoSuccess" hidden>
                        <div class="bco-success-ico"><i class="fas fa-check"></i></div>
                        <h3 id="bcoSuccessTitle">Demande enregistrée</h3>
                        <p id="bcoSuccessMsg"></p>
                        <a href="{{ $catalogueUrl }}" class="btn bco-btn-solid">Retour au catalogue</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="bco-trust">
            <span><i class="fas fa-truck"></i> Livraison rapide</span>
            <span><i class="fas fa-award"></i> Produits de qualité</span>
            <span><i class="fas fa-shield-alt"></i> Paiement sécurisé</span>
            <span><i class="fas fa-headset"></i> Support 24/7</span>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/business-commande.js') }}"></script>
@endpush
