@extends('layouts.app')

@section('content')
<section class="st-insc-page">
    <div class="container st-insc-wrap">
        <a href="{{ url('/services/sous-traitance') }}" class="st-back">
            <i class="fas fa-arrow-left"></i> Sous-traitance internationale
        </a>

        <div class="st-insc-layout">
            <div class="st-insc-intro">
                <p class="st-eyebrow">Entreprises / donneurs d'ordre</p>
                <h1>Déposer un projet</h1>
                <p>
                    Confiez une mission à distance à des talents togolais qualifiés.
                    Décrivez votre besoin : nous vous proposons des profils adaptés.
                </p>
                <ul class="st-insc-benefits">
                    <li><i class="fas fa-check-circle"></i> Devis clair et transparent</li>
                    <li><i class="fas fa-check-circle"></i> Coordination dédiée</li>
                    <li><i class="fas fa-check-circle"></i> Suivi qualité</li>
                    <li><i class="fas fa-check-circle"></i> Impact social positif</li>
                </ul>
            </div>

            <form class="st-insc-form" id="stProjetForm" autocomplete="on" onsubmit="return false;">
                <h2>Votre projet</h2>

                <div class="st-field">
                    <label for="stPNom">Nom / Entreprise <span>*</span></label>
                    <input type="text" id="stPNom" required placeholder="Votre nom ou raison sociale">
                </div>
                <div class="st-field-row">
                    <div class="st-field">
                        <label for="stPEmail">Email <em>(facultatif)</em></label>
                        <input type="email" id="stPEmail" placeholder="contact@entreprise.com">
                    </div>
                    <div class="st-field">
                        <label for="stPTel">Téléphone <em>(facultatif)</em></label>
                        <input type="tel" id="stPTel" placeholder="+33 …">
                    </div>
                </div>
                <div class="st-field">
                    <label for="stPPays">Pays</label>
                    <input type="text" id="stPPays" value="France" placeholder="France">
                </div>
                <div class="st-field">
                    <label for="stPType">Type de mission <span>*</span></label>
                    <select id="stPType" required>
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
                    <label for="stPDesc">Description du projet <span>*</span></label>
                    <textarea id="stPDesc" rows="5" required placeholder="Objectifs, livrables, délais, budget indicatif…"></textarea>
                </div>

                <div class="st-field st-files-field">
                    <label>Pièces jointes <em>(facultatif)</em></label>
                    <p class="st-files-help">Ajoutez des images (JPG, PNG, WEBP) ou des PDF pour illustrer votre projet. Max. 5 fichiers, 5 Mo chacun.</p>
                    <div class="st-files" id="stPFiles">
                        <label class="st-file-add" id="stPFileAdd">
                            <input type="file" id="stPFileInput" accept="image/jpeg,image/png,image/webp,image/gif,application/pdf,.pdf" multiple hidden>
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Ajouter image ou PDF</span>
                        </label>
                    </div>
                    <p class="st-files-error" id="stPFileError" hidden></p>
                </div>

                <button type="submit" class="btn st-btn-solid st-btn-full" id="stProjetSubmit">
                    <i class="fas fa-rocket"></i> Envoyer mon projet
                </button>
                <p class="st-insc-feedback" id="stProjetFeedback" hidden></p>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/sous-traitance.js') }}"></script>
@endpush
