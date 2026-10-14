@extends('layouts.app')

@section('content')
<section class="bci-page">
    <div class="container bci-hero">
        <div class="bci-hero-text">
            <a href="{{ url('/#services') }}" class="bci-back">
                <i class="fas fa-arrow-left"></i> Nos services
            </a>
            <h1>Produits disponibles <span>à l'international</span></h1>
            <p>
                Commandez des produits de qualité depuis plusieurs pays
                et faites-vous livrer au Togo.
            </p>
            <div class="bci-hero-features">
                <div class="bci-hf">
                    <i class="fas fa-award"></i>
                    <div>
                        <strong>Produits de qualité</strong>
                        <span>Sélection rigoureuse des meilleurs produits.</span>
                    </div>
                </div>
                <div class="bci-hf">
                    <i class="fas fa-truck"></i>
                    <div>
                        <strong>Livraison rapide</strong>
                        <span>Expédition sécurisée et suivie.</span>
                    </div>
                </div>
                <div class="bci-hf">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Paiement sécurisé</strong>
                        <span>Payez en toute sécurité par plusieurs méthodes.</span>
                    </div>
                </div>
                <div class="bci-hf">
                    <i class="fas fa-headset"></i>
                    <div>
                        <strong>Support 24/7</strong>
                        <span>Notre équipe est disponible pour vous accompagner.</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="bci-hero-media">
            <img src="{{ asset('images/business-intl/hero-port.jpg') }}" alt="Logistique internationale — port et fret">
        </div>
    </div>

    <div class="bci-countries">
        <div class="container">
            <header class="bci-countries-head">
                <h2>Découvrez nos produits par pays</h2>
                <p>Accédez à une large sélection de produits de qualité depuis plusieurs pays partenaires.</p>
            </header>
            <div class="bci-grid">
                @foreach ($pays as $p)
                    <article class="bci-card" id="pays-{{ $p['slug'] }}">
                        <div class="bci-card-media">
                            <div class="bci-card-imgwrap">
                                <img src="{{ asset('images/business-intl/' . $p['image']) }}" alt="{{ $p['pays'] }}">
                            </div>
                            <span class="bci-flag" title="{{ $p['pays'] }}">
                                <img src="{{ asset('images/business-intl/flags/' . $p['flagCode'] . '.svg') }}" alt="Drapeau {{ $p['pays'] }}">
                            </span>
                        </div>
                        <div class="bci-card-body">
                            <h3>{{ $p['title'] }}</h3>
                            <p>{{ $p['text'] }}</p>
                            <ul class="bci-tags">
                                @foreach ($p['tags'] as $tag)
                                    <li><i class="{{ $tag['icon'] }}"></i> {{ $tag['label'] }}</li>
                                @endforeach
                            </ul>
                            <a
                                href="{{ url('/services/business-center/catalogue/' . $p['slug']) }}"
                                class="btn bci-btn"
                            >
                                Voir les produits <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bci-trust">
        <div class="container bci-trust-grid">
            <div class="bci-trust-item">
                <i class="fas fa-credit-card"></i>
                <div>
                    <strong>Paiement sécurisé</strong>
                    <p>Paiement 100% sécurisé par mobile money ou carte bancaire.</p>
                </div>
            </div>
            <div class="bci-trust-item">
                <i class="fas fa-truck"></i>
                <div>
                    <strong>Livraison au Togo</strong>
                    <p>Nous livrons partout au Togo en toute sécurité.</p>
                </div>
            </div>
            <div class="bci-trust-item">
                <i class="fas fa-box"></i>
                <div>
                    <strong>Suivi de commande</strong>
                    <p>Suivez votre commande en temps réel jusqu'à sa réception.</p>
                </div>
            </div>
            <div class="bci-trust-item">
                <i class="fas fa-headset"></i>
                <div>
                    <strong>Support dédié</strong>
                    <p>Une équipe dédiée pour répondre à toutes vos questions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="bc-modal" id="bcDevisModal" hidden>
    <div class="bc-modal-backdrop" data-bc-close="devis"></div>
    <div class="bc-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="bcDevisTitle">
        <button type="button" class="bc-modal-close" data-bc-close="devis" aria-label="Fermer">
            <i class="fas fa-times"></i>
        </button>
        <h2 id="bcDevisTitle">Voir les produits</h2>
        <p class="bc-modal-sub">Le pays est prérempli. Indiquez votre nom et les produits recherchés.</p>
        <form id="bcDevisForm" autocomplete="off" onsubmit="return false;">
            <div class="bc-field">
                <label for="bcDevisNom">Votre nom *</label>
                <input type="text" id="bcDevisNom" name="nom" required placeholder="Entrez votre nom complet">
            </div>
            <div class="bc-field">
                <label for="bcDevisEspace">Pays / Catalogue</label>
                <input type="text" id="bcDevisEspace" name="espace" readonly>
            </div>
            <div class="bc-field">
                <label for="bcDevisMsg">Produits recherchés *</label>
                <textarea id="bcDevisMsg" name="message" rows="4" required placeholder="Décrivez les produits, quantités, budget..."></textarea>
            </div>
            <button type="submit" class="btn bc-btn-solid bc-btn-full" id="bcDevisSubmit">
                <i class="fas fa-paper-plane"></i> Envoyer la demande
            </button>
            <p class="bc-modal-feedback" id="bcDevisFeedback" hidden></p>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/business-intl.js') }}"></script>
@endpush
