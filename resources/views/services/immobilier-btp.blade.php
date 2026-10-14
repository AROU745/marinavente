@extends('layouts.app')

@section('content')
<section class="imb-page">
    {{-- Hero --}}
    <div class="container imb-hero">
        <div class="imb-hero-text">
            <a href="{{ url('/#services') }}" class="imb-back">
                <i class="fas fa-arrow-left"></i> Nos services
            </a>
            <h1>
                <span class="imb-title-dark">IMMOBILIER &amp; BTP</span>
                <span class="imb-title-red">INVESTIR AU TOGO &amp; EN CÔTE D'IVOIRE</span>
            </h1>
            <p>
                Trouvez une maison, achetez un terrain ou construisez en toute sérénité —
                avec un accompagnement local et un suivi transparent, même depuis l’étranger.
            </p>
            <div class="imb-hero-features">
                <div class="imb-hf">
                    <i class="fas fa-shield-alt"></i>
                    <span>Transactions sécurisées</span>
                </div>
                <div class="imb-hf">
                    <i class="fas fa-user-tie"></i>
                    <span>Accompagnement personnalisé</span>
                </div>
                <div class="imb-hf">
                    <i class="fas fa-video"></i>
                    <span>Suivi de chantier à distance</span>
                </div>
                <div class="imb-hf">
                    <i class="fas fa-cube"></i>
                    <span>Visite 3D des maisons</span>
                </div>
                <div class="imb-hf">
                    <i class="fas fa-file-invoice"></i>
                    <span>Devis détaillés &amp; transparents</span>
                </div>
            </div>
            <div class="imb-hero-actions">
                <a href="{{ url('/services/immobilier-btp/biens/vente') }}" class="btn imb-btn-solid">
                    <i class="fas fa-home"></i> Découvrir nos biens
                </a>
                <button type="button" class="btn imb-btn-outline imb-devis-btn" data-sujet="Devis général Immobilier & BTP">
                    <i class="fas fa-file-alt"></i> Demander un devis
                </button>
            </div>
        </div>
        <div class="imb-hero-media">
            <img src="{{ asset('images/immobilier/hero-villa.jpg') }}" alt="Villa moderne — Immobilier & BTP">
            <aside class="imb-hero-card">
                <h3>Investissez au Togo &amp; en Côte d'Ivoire</h3>
                <ul>
                    <li>Rendement attractif</li>
                    <li>Cadre juridique accompagné</li>
                    <li>Accompagnement de A à Z</li>
                </ul>
                <div class="imb-maps">
                    <div class="imb-map imb-map-tg">
                        <span class="imb-pin"></span>
                        <strong>Togo</strong>
                    </div>
                    <div class="imb-map imb-map-ci">
                        <span class="imb-pin"></span>
                        <strong>Côte d'Ivoire</strong>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    {{-- Nos solutions --}}
    <div class="imb-solutions" id="solutions">
        <div class="container">
            <header class="imb-section-head">
                <span></span>
                <h2>Nos solutions</h2>
                <span></span>
            </header>
            <div class="imb-grid">
                @foreach ($solutions as $s)
                    <article class="imb-card" id="{{ $s['id'] }}">
                        <div class="imb-card-media">
                            <img src="{{ asset('images/immobilier/' . $s['image']) }}" alt="{{ $s['title'] }}">
                            <span class="imb-card-ico"><i class="{{ $s['icon'] }}"></i></span>
                        </div>
                        <div class="imb-card-body">
                            <h3>{{ $s['title'] }}</h3>
                            <p>{{ $s['text'] }}</p>
                            @if ($s['action'] === 'devis')
                                <button type="button" class="imb-link imb-devis-btn" data-sujet="{{ $s['title'] }}">
                                    {{ $s['cta'] }} <i class="fas fa-arrow-right"></i>
                                </button>
                            @else
                                <a href="{{ $s['href'] }}" class="imb-link">
                                    {{ $s['cta'] }} <i class="fas fa-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Diaspora / investisseurs --}}
    <div class="imb-diaspora" id="opportunites">
        <div class="container imb-diaspora-grid">
            <div class="imb-diaspora-visual">
                <div class="imb-globe" aria-hidden="true">
                    <i class="fas fa-globe-africa"></i>
                    <i class="fas fa-plane"></i>
                </div>
                <img src="{{ asset('images/immobilier/diaspora.jpg') }}" alt="Investisseur à distance">
            </div>
            <div class="imb-diaspora-text">
                <h2>Vous vivez à l'étranger ? Nous construisons pour vous au pays !</h2>
                <p>
                    Suivi photo/vidéo, reporting régulier et interlocuteur dédié :
                    pilotez votre projet immobilier au Togo ou en Côte d'Ivoire en toute confiance.
                </p>
                <div class="imb-diaspora-steps">
                    <div><i class="fas fa-file-invoice-dollar"></i><span>Devis complet</span></div>
                    <div><i class="fas fa-hard-hat"></i><span>Suivi de chantier</span></div>
                    <div><i class="fas fa-camera"></i><span>Rapports photos/vidéos</span></div>
                    <div><i class="fas fa-key"></i><span>Remise des clés</span></div>
                </div>
            </div>
            <aside class="imb-why">
                <h3>Pourquoi investir au Togo &amp; en Côte d'Ivoire ?</h3>
                <ul>
                    <li><i class="fas fa-check"></i> Économie en croissance</li>
                    <li><i class="fas fa-check"></i> Marché immobilier en essor</li>
                    <li><i class="fas fa-check"></i> Demande locative forte</li>
                    <li><i class="fas fa-check"></i> Accompagnement sur place</li>
                </ul>
                <button type="button" class="btn imb-btn-solid imb-devis-btn" data-sujet="Investissement diaspora Togo / Côte d'Ivoire">
                    Parler à un conseiller
                </button>
            </aside>
        </div>
    </div>

    {{-- Réassurance --}}
    <div class="imb-trust">
        <div class="container imb-trust-grid">
            <div><strong>+500</strong><span>Clients &amp; projets</span></div>
            <div><i class="fas fa-map-marker-alt"></i><span>Togo &amp; Côte d'Ivoire</span></div>
            <div><strong>+200</strong><span>Références livrées</span></div>
            <div><i class="fas fa-hands-helping"></i><span>Accompagnement</span></div>
            <div><i class="fas fa-lock"></i><span>Paiement sécurisé</span></div>
            <div><i class="fas fa-headset"></i><span>Support client 7j/7</span></div>
        </div>
    </div>
</section>

{{-- Modal devis --}}
<div class="imb-modal" id="imbDevisModal" hidden>
    <div class="imb-modal-backdrop" data-imb-close></div>
    <div class="imb-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="imbDevisTitle">
        <button type="button" class="imb-modal-close" data-imb-close aria-label="Fermer">
            <i class="fas fa-times"></i>
        </button>
        <h2 id="imbDevisTitle">Demander un devis</h2>
        <p class="imb-modal-sub">Décrivez votre projet. Nous vous répondons sous 24h.</p>
        <form id="imbDevisForm" autocomplete="off" onsubmit="return false;">
            <div class="imb-field">
                <label for="imbNom">Votre nom *</label>
                <input type="text" id="imbNom" required placeholder="Nom et prénom">
            </div>
            <div class="imb-field">
                <label for="imbEmail">Email <em>(facultatif)</em></label>
                <input type="email" id="imbEmail" placeholder="vous@email.com">
            </div>
            <div class="imb-field">
                <label for="imbTel">Téléphone <em>(facultatif)</em></label>
                <input type="tel" id="imbTel" placeholder="+228 / +225 …">
            </div>
            <div class="imb-field">
                <label for="imbSujet">Sujet</label>
                <input type="text" id="imbSujet" readonly>
            </div>
            <div class="imb-field">
                <label for="imbPays">Pays du projet</label>
                <select id="imbPays">
                    <option value="Togo">Togo</option>
                    <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                    <option value="Les deux">Les deux</option>
                </select>
            </div>
            <div class="imb-field">
                <label for="imbMsg">Décrivez votre projet *</label>
                <textarea id="imbMsg" rows="4" required placeholder="Type de bien, budget, délais, localisation…"></textarea>
            </div>
            <button type="submit" class="btn imb-btn-solid imb-btn-full" id="imbDevisSubmit">
                <i class="fas fa-paper-plane"></i> Envoyer la demande
            </button>
            <p class="imb-modal-feedback" id="imbDevisFeedback" hidden></p>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/immobilier-btp.js') }}"></script>
@endpush
