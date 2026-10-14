@extends('layouts.app')

@section('content')
<section class="st-insc-page">
    <div class="container st-insc-wrap">
        <a href="{{ url('/services/sous-traitance') }}" class="st-back">
            <i class="fas fa-arrow-left"></i> Sous-traitance internationale
        </a>

        <div class="st-insc-layout">
            <div class="st-insc-intro">
                <p class="st-eyebrow">Inscription prestataire</p>
                <h1>Je m'inscris</h1>
                <p>
                    Rejoignez le réseau de talents MARINA et accédez à des missions
                    internationales à distance. Remplissez le formulaire : nous vous recontactons
                    sous 48h.
                </p>
                <ul class="st-insc-benefits">
                    <li><i class="fas fa-check-circle"></i> Missions pour clients européens</li>
                    <li><i class="fas fa-check-circle"></i> Accompagnement et suivi qualité</li>
                    <li><i class="fas fa-check-circle"></i> Paiements sécurisés</li>
                    <li><i class="fas fa-check-circle"></i> Formation continue</li>
                </ul>
            </div>

            <form class="st-insc-form" id="stInscForm" autocomplete="on" onsubmit="return false;">
                <h2>Vos informations</h2>

                <div class="st-field">
                    <label for="stNom">Nom complet <span>*</span></label>
                    <input type="text" id="stNom" required placeholder="Nom et prénom">
                </div>
                <div class="st-field-row">
                    <div class="st-field">
                        <label for="stEmail">Email <em>(facultatif)</em></label>
                        <input type="email" id="stEmail" placeholder="vous@email.com">
                    </div>
                    <div class="st-field">
                        <label for="stTel">Téléphone <em>(facultatif)</em></label>
                        <input type="tel" id="stTel" placeholder="+228 …">
                    </div>
                </div>
                <div class="st-field">
                    <label for="stVille">Ville / Pays</label>
                    <input type="text" id="stVille" placeholder="Lomé, Togo">
                </div>
                <div class="st-field">
                    <label for="stDomaine">Domaine de compétence <span>*</span></label>
                    <select id="stDomaine" required>
                        <option value="">Choisir…</option>
                        <option>Développement Web</option>
                        <option>Design &amp; Graphisme</option>
                        <option>Marketing Digital</option>
                        <option>Rédaction &amp; Traduction</option>
                        <option>Support &amp; Assistance</option>
                        <option>Autre</option>
                    </select>
                </div>
                <div class="st-field">
                    <label for="stProfil">Présentez votre profil <span>*</span></label>
                    <textarea id="stProfil" rows="5" required placeholder="Compétences, expériences, outils maîtrisés…"></textarea>
                </div>
                <div class="st-field">
                    <label for="stDispo">Disponibilité</label>
                    <select id="stDispo">
                        <option>Immédiate</option>
                        <option>Sous 2 semaines</option>
                        <option>Sous 1 mois</option>
                        <option>À discuter</option>
                    </select>
                </div>

                <button type="submit" class="btn st-btn-solid st-btn-full" id="stInscSubmit">
                    <i class="fas fa-paper-plane"></i> Envoyer mon inscription
                </button>
                <p class="st-insc-feedback" id="stInscFeedback" hidden></p>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/sous-traitance.js') }}"></script>
@endpush
