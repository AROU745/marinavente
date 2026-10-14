@extends('layouts.app')

@section('content')
<section class="form-page">
    <div class="container">
        <nav class="pe-breadcrumb" aria-label="Fil d'Ariane">
            <a href="{{ url('/') }}">Accueil</a>
            <span>&gt;</span>
            <strong>Formations</strong>
        </nav>

        <div class="form-head">
            <div class="form-head-text">
                <h1>Nos formations</h1>
                <p>
                    Découvrez notre catalogue de formations pratiques et certifiantes
                    pour développer vos compétences et booster votre avenir.
                </p>
            </div>
            <form class="form-search" action="{{ url('/formations') }}" method="get" role="search">
                <i class="fas fa-search" aria-hidden="true"></i>
                <input
                    type="search"
                    name="q"
                    value="{{ $q }}"
                    placeholder="Rechercher une formation..."
                    aria-label="Rechercher une formation"
                >
            </form>
        </div>

        @if($formations->isEmpty())
            <div class="form-empty">
                <p>Aucune formation ne correspond à votre recherche.</p>
                <a href="{{ url('/formations') }}" class="btn btn-outline-green">Voir toutes les formations</a>
            </div>
        @else
            <div class="form-grid">
                @foreach($formations as $formation)
                    <article class="form-card">
                        <div class="form-card-media form-media-{{ $formation['categoryClass'] }}">
                            <i class="{{ $formation['icon'] }}"></i>
                        </div>
                        <div class="form-card-body">
                            <span class="form-badge form-badge-{{ $formation['categoryClass'] }}">
                                {{ $formation['category'] }}
                            </span>
                            <h3>{{ $formation['title'] }}</h3>
                            <p>{{ $formation['description'] }}</p>
                            <div class="form-meta">
                                <span><i class="fas fa-clock"></i> {{ $formation['duree'] }}</span>
                                <span><i class="fas fa-signal"></i> {{ $formation['niveau'] }}</span>
                            </div>
                            <div class="form-prices">
                                <span>En ligne : <strong>{{ number_format($formation['prixEnLigne'], 0, ',', ' ') }} FCFA</strong></span>
                                <span>Présentiel : <strong>{{ number_format($formation['prixPresentiel'], 0, ',', ' ') }} FCFA</strong></span>
                            </div>
                            <div class="form-card-actions">
                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm form-buy-btn"
                                    data-slug="{{ $formation['slug'] }}"
                                    data-title="{{ $formation['title'] }}"
                                    data-prix-enligne="{{ $formation['prixEnLigne'] }}"
                                    data-prix-presentiel="{{ $formation['prixPresentiel'] }}"
                                >
                                    <i class="fas fa-shopping-cart"></i> Acheter la formation
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-outline-green btn-sm form-follow-btn"
                                    data-slug="{{ $formation['slug'] }}"
                                    data-title="{{ $formation['title'] }}"
                                    data-prix-enligne="{{ $formation['prixEnLigne'] }}"
                                    data-prix-presentiel="{{ $formation['prixPresentiel'] }}"
                                >
                                    <i class="fas fa-play"></i> Suivre la formation
                                </button>
                            </div>
                        </div>
                        <div class="form-card-don">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Formation professionnelle animée par des experts MARINA.</span>
                            <i class="fas fa-info-circle" title="Des formations pratiques pour renforcer vos compétences."></i>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($lastPage > 1)
                <nav class="form-pagination" aria-label="Pagination des formations">
                    @for($i = 1; $i <= min(3, $lastPage); $i++)
                        <a href="{{ url('/formations') }}?page={{ $i }}{{ $q !== '' ? '&q=' . urlencode($q) : '' }}"
                           class="{{ $page === $i ? 'is-active' : '' }}">{{ $i }}</a>
                    @endfor
                    @if($lastPage > 4)
                        <span class="form-pagination-dots">…</span>
                        <a href="{{ url('/formations') }}?page={{ $lastPage }}{{ $q !== '' ? '&q=' . urlencode($q) : '' }}"
                           class="{{ $page === $lastPage ? 'is-active' : '' }}">{{ $lastPage }}</a>
                    @elseif($lastPage === 4)
                        <a href="{{ url('/formations') }}?page=4{{ $q !== '' ? '&q=' . urlencode($q) : '' }}"
                           class="{{ $page === 4 ? 'is-active' : '' }}">4</a>
                    @endif
                    @if($page < $lastPage)
                        <a class="form-pagination-next" href="{{ url('/formations') }}?page={{ $page + 1 }}{{ $q !== '' ? '&q=' . urlencode($q) : '' }}">
                            Suivant <i class="fas fa-chevron-right"></i>
                        </a>
                    @endif
                </nav>
            @endif
        @endif
    </div>

    {{-- Impact banner --}}
    <div class="container form-impact-wrap">
        <div class="form-impact">
            <div class="form-impact-brand">
                <div class="form-impact-logo"><i class="fas fa-hand-holding-heart"></i></div>
                <div>
                    <h2>Formez-vous avec les experts MARINA</h2>
                    <p>
                        Des formations pratiques en informatique et digital pour développer
                        vos compétences et accélérer votre carrière ou votre entreprise.
                    </p>
                </div>
            </div>
            <div class="form-impact-visual" aria-hidden="true">
                <i class="fas fa-users"></i>
                <i class="fas fa-laptop-code"></i>
            </div>
            <ul class="form-impact-list">
                <li><i class="fas fa-check"></i> Formateurs professionnels du métier</li>
                <li><i class="fas fa-check"></i> Contenu pratique et orienté résultats</li>
                <li><i class="fas fa-check"></i> Accompagnement vers l'emploi et les projets</li>
            </ul>
            <div class="form-impact-badge">
                <strong>Formations certifiantes MARINA</strong>
                <i class="fas fa-award"></i>
            </div>
        </div>
    </div>
</section>

{{-- Modal Suivre la formation --}}
<div class="fm-modal" id="fmModal" hidden>
    <div class="fm-modal-backdrop" data-fm-close></div>
    <div class="fm-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="fmModalTitle">
        <button type="button" class="fm-modal-close" data-fm-close aria-label="Fermer">
            <i class="fas fa-times"></i>
        </button>

        <header class="fm-modal-header">
            <h2 id="fmModalTitle">Suivre la formation</h2>
            <p class="fm-modal-course" id="fmCourseTitle">—</p>
        </header>

        <div class="fm-steps" id="fmSteps">
            <div class="fm-step is-active" data-step="1"><span>1</span> Informations</div>
            <div class="fm-step" data-step="2"><span>2</span> Paiement</div>
            <div class="fm-step" data-step="3"><span>3</span> Confirmation</div>
        </div>

        <form id="fmForm" onsubmit="return false;" autocomplete="off">
            {{-- Étape 1 --}}
            <div class="fm-panel is-active" data-panel="1">
                <div id="fmModeBlock">
                <h3 class="fm-section-title">1. Choisissez le mode de formation</h3>
                <div class="fm-mode-grid" id="fmModes">
                    <label class="fm-choice is-selected">
                        <input type="radio" name="mode" value="enligne" checked>
                        <span class="fm-choice-check"><i class="fas fa-check"></i></span>
                        <i class="fas fa-laptop fm-choice-icon"></i>
                        <strong>En ligne</strong>
                        <span class="fm-choice-desc">Apprenez à votre rythme depuis chez vous.</span>
                        <em class="fm-choice-price" id="fmPrixEnLigne">20 000 FCFA</em>
                    </label>
                    <label class="fm-choice">
                        <input type="radio" name="mode" value="presentiel">
                        <span class="fm-choice-check"><i class="fas fa-check"></i></span>
                        <i class="fas fa-users fm-choice-icon"></i>
                        <strong>Présentiel</strong>
                        <span class="fm-choice-desc">Apprenez en classe avec un formateur.</span>
                        <em class="fm-choice-price" id="fmPrixPresentiel">25 000 FCFA</em>
                    </label>
                    <label class="fm-choice">
                        <input type="radio" name="mode" value="autre">
                        <span class="fm-choice-check"><i class="fas fa-check"></i></span>
                        <i class="fas fa-ellipsis-h fm-choice-icon"></i>
                        <strong>Autre</strong>
                        <span class="fm-choice-desc">Formation personnalisée à convenir.</span>
                        <em class="fm-choice-price">Sur devis</em>
                    </label>
                </div>

                <div class="fm-mode-extra" id="fmModePresentielExtra" hidden>
                    <div class="fm-field">
                        <label for="fmDatePresentiel">Date souhaitée pour le présentiel *</label>
                        <input type="date" id="fmDatePresentiel" name="date_presentiel">
                    </div>
                </div>

                <div class="fm-mode-extra" id="fmModeAutreExtra" hidden>
                    <div class="fm-field">
                        <label for="fmFormationPerso">Quelle formation souhaitez-vous ? *</label>
                        <input
                            type="text"
                            id="fmFormationPerso"
                            name="formation_perso"
                            placeholder="Ex. : Initiation à Power BI, Montage vidéo..."
                        >
                    </div>
                </div>
                </div>{{-- /fmModeBlock --}}

                {{-- Paiement impulsif (mode En ligne / Acheter) --}}
                <div id="fmQuickPay" class="fm-quick-pay">
                    <h3 class="fm-section-title">Paiement immédiat</h3>
                    <p class="fm-hint">Entrez votre nom et les informations de votre carte pour finaliser maintenant.</p>
                    <div class="fm-fields">
                        <div class="fm-field">
                            <label for="fmNomQuick">Nom sur la carte *</label>
                            <input type="text" id="fmNomQuick" name="nom_carte" placeholder="Nom complet comme sur la carte" autocomplete="off">
                        </div>
                        <div class="fm-field">
                            <label for="fmCardNumber">Numéro de carte *</label>
                            <div class="fm-input-icon">
                                <input type="text" id="fmCardNumber" name="card_number" inputmode="numeric" maxlength="19" placeholder="XXXX XXXX XXXX XXXX" autocomplete="off">
                                <i class="fas fa-credit-card"></i>
                            </div>
                        </div>
                        <div class="fm-form-row">
                            <div class="fm-field">
                                <label for="fmCardExp">Expiration *</label>
                                <input type="text" id="fmCardExp" name="card_exp" inputmode="numeric" maxlength="5" placeholder="MM/AA" autocomplete="off">
                            </div>
                            <div class="fm-field">
                                <label for="fmCardCvc">CVC *</label>
                                <input type="text" id="fmCardCvc" name="card_cvc" inputmode="numeric" maxlength="4" placeholder="123" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="fm-amount-box">
                        Montant à payer : <strong id="fmQuickAmount">20 000 FCFA</strong>
                    </div>
                </div>

                {{-- Formulaire classique (Présentiel / Autre) --}}
                <div id="fmClassicInfo">
                    <h3 class="fm-section-title">2. Vos informations personnelles</h3>
                    <div class="fm-fields">
                        <div class="fm-field">
                            <label for="fmNom">Nom complet *</label>
                            <input type="text" id="fmNom" name="nom" placeholder="Entrez votre nom complet">
                        </div>
                        <div class="fm-field">
                            <label for="fmEmail">Email *</label>
                            <input type="email" id="fmEmail" name="email" placeholder="Entrez votre email">
                        </div>
                        <div class="fm-field">
                            <label for="fmTel">Téléphone / WhatsApp</label>
                            <div class="fm-input-icon">
                                <input type="tel" id="fmTel" name="telephone" placeholder="Entrez votre numéro (optionnel)">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                        </div>
                    </div>

                    <h3 class="fm-section-title">3. Méthode de paiement</h3>
                    <div class="fm-pay-grid" id="fmPays">
                        <label class="fm-choice is-selected">
                            <input type="radio" name="paiement" value="enligne" checked>
                            <span class="fm-choice-check"><i class="fas fa-check"></i></span>
                            <i class="fas fa-credit-card fm-choice-icon"></i>
                            <strong>Paiement en ligne</strong>
                            <span class="fm-choice-desc">Payez maintenant par Mobile Money, Carte bancaire, etc.</span>
                        </label>
                        <label class="fm-choice">
                            <input type="radio" name="paiement" value="presentiel">
                            <span class="fm-choice-check"><i class="fas fa-check"></i></span>
                            <i class="fas fa-calendar-alt fm-choice-icon"></i>
                            <strong>Paiement en présentiel</strong>
                            <span class="fm-choice-desc">Réservez votre rendez-vous et payez sur place.</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Étape 2 --}}
            <div class="fm-panel" data-panel="2">
                <div class="fm-summary" id="fmSummary"></div>

                <div class="fm-pay-online" id="fmPayOnline">
                    <h3 class="fm-section-title">Finalisez votre paiement</h3>
                    <div class="fm-pay-methods">
                        <label class="fm-pay-chip is-selected"><input type="radio" name="moyen" value="flooz" checked> Flooz</label>
                        <label class="fm-pay-chip"><input type="radio" name="moyen" value="tmoney"> T-Money</label>
                        <label class="fm-pay-chip"><input type="radio" name="moyen" value="carte"> Carte bancaire</label>
                    </div>
                    <div class="fm-field">
                        <label for="fmPayPhone">Numéro Mobile Money</label>
                        <input type="tel" id="fmPayPhone" name="pay_phone" placeholder="+228 XX XX XX XX">
                    </div>
                </div>

                <div class="fm-pay-onsite" id="fmPayOnsite" hidden>
                    <h3 class="fm-section-title">Réserver votre rendez-vous</h3>
                    <p class="fm-hint">Choisissez une date pour venir payer et démarrer votre formation sur place.</p>
                    <div class="fm-field">
                        <label for="fmRdv">Date souhaitée *</label>
                        <input type="date" id="fmRdv" name="rdv">
                    </div>
                    <div class="fm-field">
                        <label for="fmNote">Message (optionnel)</label>
                        <textarea id="fmNote" name="note" rows="3" placeholder="Précisez vos disponibilités..."></textarea>
                    </div>
                </div>
            </div>

            {{-- Étape 3 --}}
            <div class="fm-panel" data-panel="3">
                <div class="fm-success">
                    <div class="fm-success-icon"><i class="fas fa-check"></i></div>
                    <h3>Inscription confirmée !</h3>
                    <p id="fmSuccessMsg">
                        Merci. Votre demande d'inscription a bien été enregistrée.
                        Notre équipe vous contactera rapidement.
                    </p>
                </div>
            </div>

            <div class="fm-modal-footer" id="fmFooter">
                <button type="button" class="btn btn-primary fm-continue" id="fmContinue">
                    Continuer <i class="fas fa-arrow-right"></i>
                </button>
                <p class="fm-secure"><i class="fas fa-lock"></i> Vos informations sont sécurisées et confidentielles</p>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const nav = performance.getEntriesByType('navigation')[0];
    if (nav && nav.type === 'reload' && window.location.search) {
        window.location.replace(window.location.pathname);
    }
})();
</script>
<script src="{{ asset('js/formation-modal.js') }}"></script>
@endpush
