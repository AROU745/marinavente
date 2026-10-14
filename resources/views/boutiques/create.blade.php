@extends('layouts.app')

@section('content')
<section class="boutique-hero">
    <div class="container">
        <nav class="pe-breadcrumb" aria-label="Fil d'Ariane">
            <a href="{{ url('/') }}">Accueil</a>
            <span>&gt;</span>
            <strong>Créer ma boutique</strong>
        </nav>
        <p class="boutique-eyebrow">E-commerce simplifié</p>
        <h1>Lancez votre catalogue en ligne</h1>
        <p class="boutique-lead">
            La bannière est optionnelle (1, 2 ou 3 slides). Ensuite, composez votre catalogue commerce
            et choisissez un thème distinct pour la bannière, le catalogue et le pied de page.
        </p>
        <div class="boutique-steps" id="wizardSteps">
            <span class="boutique-step-pill is-active" data-step="1"><i class="fas fa-building"></i> Entreprise</span>
            <span class="boutique-step-pill" data-step="2"><i class="fas fa-images"></i> Bannière</span>
            <span class="boutique-step-pill" data-step="3"><i class="fas fa-store"></i> Catalogue</span>
        </div>
    </div>
</section>

<div class="container boutique-form-wrap">
    @if ($errors->any())
        <div class="boutique-form-errors" role="alert">
            <strong>Veuillez corriger les erreurs suivantes :</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('boutiques.store') }}" method="post" enctype="multipart/form-data" id="boutiqueForm">
        @csrf

        {{-- ÉTAPE 1 — Entreprise --}}
        <div class="wizard-panel is-active" data-panel="1">
            <div class="boutique-card">
                <h2><i class="fas fa-store"></i> Votre entreprise</h2>
                <div class="boutique-grid-2">
                    <div class="boutique-field">
                        <label for="nom_entreprise">Nom de la boutique *</label>
                        <input type="text" id="nom_entreprise" name="nom_entreprise" value="{{ old('nom_entreprise') }}" data-req required maxlength="120" placeholder="Ex. Mode Lomé">
                    </div>
                    <div class="boutique-field">
                        <label for="telephone">Numéro de téléphone *</label>
                        <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" data-req required maxlength="40" placeholder="+228 90 XX XX XX">
                    </div>
                    <div class="boutique-field">
                        <label for="email">Adresse e-mail <span style="font-weight:400;color:var(--text-muted)">(optionnel)</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" maxlength="160" placeholder="vous@email.com">
                    </div>
                    <div class="boutique-field">
                        <label for="logo">Logo de l'entreprise <span style="font-weight:400;color:var(--text-muted)">(optionnel)</span></label>
                        <input type="file" id="logo" name="logo" accept="image/*">
                        <small>Sans logo, le logo MARINA sera utilisé automatiquement — max. 4 Mo</small>
                    </div>
                </div>
            </div>
            <div class="boutique-form-actions">
                <button type="button" class="btn btn-boutique" id="toStep2">Continuer vers la bannière <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>

        {{-- ÉTAPE 2 — Bannière optionnelle --}}
        <div class="wizard-panel" data-panel="2" hidden>
            <div class="boutique-card">
                <h2><i class="fas fa-images"></i> Bannière défilante <span class="opt-badge">optionnel</span></h2>
                <p class="boutique-lead" style="margin-bottom: 1rem; font-size: 0.92rem;">
                    Vous pouvez passer cette étape. Sinon, ajoutez <strong>1, 2 ou 3</strong> produits maximum
                    (pas d’obligation d’en mettre 3).
                </p>

                <label class="banner-check" style="margin-bottom: 1.25rem;">
                    <input type="checkbox" name="activer_banniere" value="1" id="activerBanniere" {{ old('activer_banniere') ? 'checked' : '' }}>
                    <span><i class="fas fa-toggle-on"></i> Activer une bannière défilante</span>
                </label>

                <div id="banniereBlock" class="{{ old('activer_banniere') ? '' : 'is-disabled' }}">
                    <h3 class="theme-zone-title">Thème de la bannière</h3>
                    <div class="theme-picker theme-picker-compact" role="radiogroup" aria-label="Thème bannière">
                        @foreach ($themes as $key => $theme)
                            <label class="theme-option theme-preview-{{ $key }}">
                                <input type="radio" name="theme_banniere" value="{{ $key }}" {{ old('theme_banniere', 'marina') === $key ? 'checked' : '' }}>
                                <span class="theme-swatch" aria-hidden="true"></span>
                                <span class="theme-meta"><strong>{{ $theme['label'] }}</strong></span>
                            </label>
                        @endforeach
                    </div>

                    <p class="banner-counter" id="bannerCounter" style="margin-top:1.25rem;">Slides bannière : <strong>0</strong> / 3</p>
                    <div id="banniereList"></div>
                    <button type="button" class="btn-add-produit" id="addBanniereBtn">
                        <i class="fas fa-plus"></i> Ajouter un slide (max. 3)
                    </button>
                </div>
            </div>
            <div class="boutique-form-actions">
                <button type="button" class="btn btn-outline" id="backStep1">Retour</button>
                <button type="button" class="btn btn-outline" id="skipBanniere">Passer sans bannière</button>
                <button type="button" class="btn btn-boutique" id="finBanniere">
                    <i class="fas fa-check"></i> Fin bannière — catalogue
                </button>
            </div>
        </div>

        {{-- ÉTAPE 3 — Catalogue --}}
        <div class="wizard-panel" data-panel="3" hidden>
            <div class="boutique-card">
                <h2><i class="fas fa-palette"></i> Thème du catalogue <span class="opt-badge">optionnel</span></h2>
                <div class="theme-picker theme-picker-compact" role="radiogroup" aria-label="Thème catalogue">
                    @foreach ($themes as $key => $theme)
                        <label class="theme-option theme-preview-{{ $key }}">
                            <input type="radio" name="theme_catalogue" value="{{ $key }}" {{ old('theme_catalogue', 'marina') === $key ? 'checked' : '' }}>
                            <span class="theme-swatch" aria-hidden="true"></span>
                            <span class="theme-meta"><strong>{{ $theme['label'] }}</strong></span>
                        </label>
                    @endforeach
                </div>

                <h3 class="theme-zone-title" style="margin-top:1.5rem;">Couleur d’arrière-plan (body)</h3>
                <p class="boutique-lead" style="margin-bottom:0.75rem;font-size:0.9rem;">
                    Choisissez la couleur de fond de toute votre boutique.
                </p>
                <div class="bg-color-picker">
                    <input type="color" id="couleur_fond" name="couleur_fond" value="{{ old('couleur_fond', '#ffffff') }}" aria-label="Couleur de fond">
                    <input type="text" id="couleur_fond_hex" value="{{ old('couleur_fond', '#ffffff') }}" maxlength="7" class="bg-color-hex" aria-label="Code hexadécimal">
                    <span class="bg-color-preview" id="couleurFondPreview" style="background:{{ old('couleur_fond', '#ffffff') }}"></span>
                </div>
            </div>

            <div class="boutique-card">
                <h2><i class="fas fa-tags"></i> Produits du catalogue <span class="opt-badge">optionnel</span></h2>
                <p class="boutique-lead" style="margin-bottom: 1rem; font-size: 0.92rem;">
                    Aucun champ n’est obligatoire. Ajoutez des articles quand vous voulez — bouton <strong>Commander</strong> sur la boutique.
                </p>

                <div id="produitsList">
                    @php $oldProduits = old('produits', []); @endphp
                    @foreach ($oldProduits as $i => $p)
                        <div class="boutique-produit-row">
                            <button type="button" class="btn-remove-produit" title="Retirer" aria-label="Retirer"><i class="fas fa-times"></i></button>
                            <h3>Produit catalogue <span class="produit-num">{{ $i + 1 }}</span></h3>
                            <div class="boutique-produit-grid">
                                <div class="boutique-field span-2">
                                    <label>Nom du produit</label>
                                    <input type="text" name="produits[{{ $i }}][nom]" value="{{ $p['nom'] ?? '' }}" maxlength="160">
                                </div>
                                <div class="boutique-field">
                                    <label>Prix (FCFA)</label>
                                    <input type="number" name="produits[{{ $i }}][prix]" value="{{ $p['prix'] ?? '' }}" min="0" step="1">
                                </div>
                                <div class="boutique-field">
                                    <label>Quantité</label>
                                    <input type="number" name="produits[{{ $i }}][quantite]" value="{{ $p['quantite'] ?? '' }}" min="0">
                                </div>
                                <div class="boutique-field">
                                    <label>Tailles</label>
                                    <input type="text" name="produits[{{ $i }}][tailles]" value="{{ $p['tailles'] ?? '' }}" placeholder="S, M, L, XL">
                                </div>
                                <div class="boutique-field">
                                    <label>Couleurs</label>
                                    <input type="text" name="produits[{{ $i }}][couleurs]" value="{{ $p['couleurs'] ?? '' }}" placeholder="Rouge, Bleu…">
                                </div>
                                <div class="boutique-field">
                                    <label>Photo</label>
                                    <input type="file" name="produits[{{ $i }}][photo]" accept="image/*">
                                    <small>JPG, PNG ou WEBP — max. 4 Mo</small>
                                </div>
                                <div class="boutique-field span-2 ia-options-block">
                                    <p class="ia-options-title"><i class="fas fa-wand-magic-sparkles"></i> Options IA (facturation)</p>

                                    <label class="banner-check ia-opt">
                                        <input type="checkbox" name="produits[{{ $i }}][demande_photos_ia]" value="1" class="ia-photos-cb" {{ !empty($p['demande_photos_ia']) ? 'checked' : '' }}>
                                        <span>5 photos IA — <strong>1 000 FCFA</strong></span>
                                    </label>
                                    <div class="ia-photos-panel {{ !empty($p['demande_photos_ia']) ? '' : 'is-hidden' }}">
                                        <label>Photos sources (1 à 5 — pas besoin d’attendre 5)</label>
                                        <input type="file" name="produits[{{ $i }}][sources_ia][]" accept="image/*" multiple class="ia-sources-input">
                                        <div class="ia-sources-actions">
                                            <span class="ia-sources-count">0/5 photo</span>
                                            <input type="hidden" name="produits[{{ $i }}][envoyer_photos_ia]" value="{{ !empty($p['envoyer_photos_ia']) ? '1' : '0' }}" class="ia-envoyer-flag">
                                            <button type="button" class="ia-envoyer-btn"><i class="fas fa-paper-plane"></i> Envoyer</button>
                                        </div>
                                        <p class="ia-envoyer-status"></p>
                                        <small>Dès 1 photo. Max 5. Commande photos → admin à l’enregistrement.</small>
                                    </div>

                                    <label class="banner-check ia-opt" style="margin-top:0.75rem;">
                                        <input type="checkbox" name="produits[{{ $i }}][demande_video_ia]" value="1" class="ia-video-cb" {{ !empty($p['demande_video_ia']) ? 'checked' : '' }}>
                                        <span>1 vidéo IA — <strong>2 000 FCFA</strong></span>
                                    </label>
                                    <div class="ia-video-panel {{ !empty($p['demande_video_ia']) ? '' : 'is-hidden' }}">
                                        <label>Images sources pour la vidéo (1 à 5)</label>
                                        <input type="file" name="produits[{{ $i }}][sources_video_ia][]" accept="image/*" multiple class="ia-sources-input">
                                        <label style="margin-top:0.55rem;">Ou une vidéo source (optionnel)</label>
                                        <input type="file" name="produits[{{ $i }}][source_video_clip]" accept="video/mp4,video/webm,video/quicktime" class="ia-video-clip-input">
                                        <div class="ia-sources-actions">
                                            <span class="ia-sources-count">0/5 fichier</span>
                                            <input type="hidden" name="produits[{{ $i }}][envoyer_video_ia]" value="{{ !empty($p['envoyer_video_ia']) ? '1' : '0' }}" class="ia-envoyer-flag">
                                            <button type="button" class="ia-envoyer-btn"><i class="fas fa-paper-plane"></i> Envoyer</button>
                                        </div>
                                        <p class="ia-envoyer-status"></p>
                                        <small>Même procédé que les photos : dès 1 fichier. Max 5 images. Commande vidéo → admin.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn-add-produit" id="addProduitBtn">
                    <i class="fas fa-plus"></i> Ajouter un produit catalogue
                </button>
            </div>

            <div class="boutique-card">
                <h2><i class="fas fa-shoe-prints"></i> Pied de page — contacts &amp; thème</h2>
                <p class="boutique-lead" style="margin-bottom: 1rem; font-size: 0.92rem;">
                    Remplissez les informations visibles en bas de votre boutique, puis choisissez le thème du footer.
                </p>
                <div class="boutique-grid-2">
                    <div class="boutique-field span-2">
                        <label for="footer_texte">Texte de présentation</label>
                        <textarea id="footer_texte" name="footer_texte" rows="3" maxlength="500" placeholder="Courte description de votre commerce…">{{ old('footer_texte') }}</textarea>
                    </div>
                    <div class="boutique-field">
                        <label for="adresse">Adresse</label>
                        <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}" maxlength="255" placeholder="Quartier, rue…">
                    </div>
                    <div class="boutique-field">
                        <label for="ville">Ville</label>
                        <input type="text" id="ville" name="ville" value="{{ old('ville') }}" maxlength="120" placeholder="Lomé">
                    </div>
                    <div class="boutique-field">
                        <label for="whatsapp">WhatsApp</label>
                        <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" maxlength="40" placeholder="+228 90 XX XX XX">
                        <small>Utilisé aussi pour le bouton Commander si renseigné</small>
                    </div>
                    <div class="boutique-field">
                        <label for="horaires">Horaires</label>
                        <input type="text" id="horaires" name="horaires" value="{{ old('horaires') }}" maxlength="160" placeholder="Lun–Sam 8h–18h">
                    </div>
                    <div class="boutique-field">
                        <label for="facebook">Facebook (URL)</label>
                        <input type="url" id="facebook" name="facebook" value="{{ old('facebook') }}" maxlength="255" placeholder="https://facebook.com/…">
                    </div>
                    <div class="boutique-field">
                        <label for="instagram">Instagram (URL)</label>
                        <input type="url" id="instagram" name="instagram" value="{{ old('instagram') }}" maxlength="255" placeholder="https://instagram.com/…">
                    </div>
                    <div class="boutique-field">
                        <label for="tiktok">TikTok (URL)</label>
                        <input type="url" id="tiktok" name="tiktok" value="{{ old('tiktok') }}" maxlength="255" placeholder="https://tiktok.com/@…">
                    </div>
                </div>

                <h3 class="theme-zone-title" style="margin-top:1.5rem;">Thème du pied de page <span class="opt-badge">optionnel</span></h3>
                <div class="theme-picker theme-picker-compact" role="radiogroup" aria-label="Thème footer">
                    @foreach ($themes as $key => $theme)
                        <label class="theme-option theme-preview-{{ $key }}">
                            <input type="radio" name="theme_footer" value="{{ $key }}" {{ old('theme_footer', 'marina') === $key ? 'checked' : '' }}>
                            <span class="theme-swatch" aria-hidden="true"></span>
                            <span class="theme-meta"><strong>{{ $theme['label'] }}</strong></span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="boutique-form-actions">
                <button type="button" class="btn btn-outline" id="backStep2">Retour bannière</button>
                <button type="submit" class="btn btn-boutique">
                    <i class="fas fa-wand-magic-sparkles"></i> Générer ma boutique
                </button>
            </div>
        </div>
    </form>
</div>

<template id="banniereRowTemplate">
    <div class="boutique-produit-row banniere-row">
        <button type="button" class="btn-remove-produit btn-remove-ban" title="Retirer" aria-label="Retirer"><i class="fas fa-times"></i></button>
        <h3>Slide bannière <span class="ban-num">1</span></h3>
        <div class="boutique-produit-grid">
            <div class="boutique-field span-2">
                <label>Nom *</label>
                <input type="text" name="banniere[__INDEX__][nom]" maxlength="160" placeholder="Produit mis en avant">
            </div>
            <div class="boutique-field">
                <label>Prix (FCFA)</label>
                <input type="number" name="banniere[__INDEX__][prix]" min="0" step="1" placeholder="0">
            </div>
            <div class="boutique-field">
                <label>Photo *</label>
                <input type="file" name="banniere[__INDEX__][photo]" accept="image/*" class="ban-photo">
            </div>
        </div>
    </div>
</template>

<template id="produitRowTemplate">
    <div class="boutique-produit-row">
        <button type="button" class="btn-remove-produit" title="Retirer" aria-label="Retirer"><i class="fas fa-times"></i></button>
        <h3>Produit catalogue <span class="produit-num">1</span></h3>
        <div class="boutique-produit-grid">
            <div class="boutique-field span-2">
                <label>Nom du produit</label>
                <input type="text" name="produits[__INDEX__][nom]" maxlength="160">
            </div>
            <div class="boutique-field">
                <label>Prix (FCFA)</label>
                <input type="number" name="produits[__INDEX__][prix]" min="0" step="1">
            </div>
            <div class="boutique-field">
                <label>Quantité</label>
                <input type="number" name="produits[__INDEX__][quantite]" min="0">
            </div>
            <div class="boutique-field">
                <label>Tailles</label>
                <input type="text" name="produits[__INDEX__][tailles]" placeholder="S, M, L, XL">
            </div>
            <div class="boutique-field">
                <label>Couleurs</label>
                <input type="text" name="produits[__INDEX__][couleurs]" placeholder="Rouge, Bleu…">
            </div>
            <div class="boutique-field">
                <label>Photo</label>
                <input type="file" name="produits[__INDEX__][photo]" accept="image/*">
                <small>JPG, PNG ou WEBP — max. 4 Mo</small>
            </div>
            <div class="boutique-field span-2 ia-options-block">
                <p class="ia-options-title"><i class="fas fa-wand-magic-sparkles"></i> Options IA (facturation)</p>
                <label class="banner-check ia-opt">
                    <input type="checkbox" name="produits[__INDEX__][demande_photos_ia]" value="1" class="ia-photos-cb">
                    <span>5 photos IA — <strong>1 000 FCFA</strong></span>
                </label>
                <div class="ia-photos-panel is-hidden">
                    <label>Photos sources (1 à 5 — pas besoin d’attendre 5)</label>
                    <input type="file" name="produits[__INDEX__][sources_ia][]" accept="image/*" multiple class="ia-sources-input">
                    <div class="ia-sources-actions">
                        <span class="ia-sources-count">0/5 photo</span>
                        <input type="hidden" name="produits[__INDEX__][envoyer_photos_ia]" value="0" class="ia-envoyer-flag">
                        <button type="button" class="ia-envoyer-btn"><i class="fas fa-paper-plane"></i> Envoyer</button>
                    </div>
                    <p class="ia-envoyer-status"></p>
                    <small>Dès 1 photo. Max 5. Commande photos → admin à l’enregistrement.</small>
                </div>
                <label class="banner-check ia-opt" style="margin-top:0.75rem;">
                    <input type="checkbox" name="produits[__INDEX__][demande_video_ia]" value="1" class="ia-video-cb">
                    <span>1 vidéo IA — <strong>2 000 FCFA</strong></span>
                </label>
                <div class="ia-video-panel is-hidden">
                    <label>Images sources pour la vidéo (1 à 5)</label>
                    <input type="file" name="produits[__INDEX__][sources_video_ia][]" accept="image/*" multiple class="ia-sources-input">
                    <label style="margin-top:0.55rem;">Ou une vidéo source (optionnel)</label>
                    <input type="file" name="produits[__INDEX__][source_video_clip]" accept="video/mp4,video/webm,video/quicktime" class="ia-video-clip-input">
                    <div class="ia-sources-actions">
                        <span class="ia-sources-count">0/5 fichier</span>
                        <input type="hidden" name="produits[__INDEX__][envoyer_video_ia]" value="0" class="ia-envoyer-flag">
                        <button type="button" class="ia-envoyer-btn"><i class="fas fa-paper-plane"></i> Envoyer</button>
                    </div>
                    <p class="ia-envoyer-status"></p>
                    <small>Même procédé que les photos : dès 1 fichier. Max 5 images. Commande vidéo → admin.</small>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script src="{{ asset('js/boutiques-create.js') }}"></script>
<script src="{{ asset('js/boutiques-couleur-fond.js') }}"></script>
<script src="{{ asset('js/boutiques-ia-options.js') }}"></script>
@endpush
