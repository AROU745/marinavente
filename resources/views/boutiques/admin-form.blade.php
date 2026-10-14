@extends('layouts.app')

@section('content')
@php
    $isEdit = (bool) $boutique;
    $oldBan = old('banniere');
    $oldProd = old('produits');
@endphp
<section class="boutique-hero">
    <div class="container">
        <p class="boutique-eyebrow">Administration MARINA</p>
        <h1>{{ $isEdit ? 'Modifier la boutique' : 'Créer une boutique' }}</h1>
        <p class="boutique-lead">
            Modification complète : infos, thèmes, bannière, catalogue et images. Seuls le nom et le téléphone sont obligatoires.
        </p>
    </div>
</section>

<div class="container boutique-form-wrap">
    @if ($errors->any())
        <div class="boutique-form-errors" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        id="adminBoutiqueForm"
        action="{{ $isEdit ? route('boutiques.admin.update', $boutique) : route('boutiques.admin.store') }}"
        method="post"
        enctype="multipart/form-data"
    >
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="boutique-card">
            <h2><i class="fas fa-store"></i> Informations</h2>
            <div class="boutique-grid-2">
                <div class="boutique-field">
                    <label for="nom_entreprise">Nom de la boutique *</label>
                    <input type="text" id="nom_entreprise" name="nom_entreprise" required maxlength="120"
                           value="{{ old('nom_entreprise', $boutique->nom_entreprise ?? '') }}">
                </div>
                <div class="boutique-field">
                    <label for="telephone">Téléphone *</label>
                    <input type="tel" id="telephone" name="telephone" required maxlength="40"
                           value="{{ old('telephone', $boutique->telephone ?? '') }}">
                </div>
                <div class="boutique-field">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" maxlength="160"
                           value="{{ old('email', $boutique->email ?? '') }}">
                </div>
                <div class="boutique-field">
                    <label for="logo">Logo</label>
                    @if ($isEdit)
                        <div class="admin-current-media">
                            <img src="{{ $boutique->logoUrl() }}" alt="Logo" class="admin-thumb-preview">
                            @if ($boutique->hasCustomLogo())
                                <label class="banner-check" style="margin-top:0.5rem;">
                                    <input type="checkbox" name="supprimer_logo" value="1">
                                    <span>Supprimer le logo (revenir au logo MARINA)</span>
                                </label>
                            @endif
                        </div>
                    @endif
                    <input type="file" id="logo" name="logo" accept="image/*">
                    <small>JPG, PNG, WEBP — max. 4 Mo</small>
                </div>
                <div class="boutique-field">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" maxlength="255"
                           value="{{ old('adresse', $boutique->adresse ?? '') }}">
                </div>
                <div class="boutique-field">
                    <label for="ville">Ville</label>
                    <input type="text" id="ville" name="ville" maxlength="120"
                           value="{{ old('ville', $boutique->ville ?? '') }}">
                </div>
                <div class="boutique-field">
                    <label for="whatsapp">WhatsApp</label>
                    <input type="tel" id="whatsapp" name="whatsapp" maxlength="40"
                           value="{{ old('whatsapp', $boutique->whatsapp ?? '') }}">
                </div>
                <div class="boutique-field">
                    <label for="horaires">Horaires</label>
                    <input type="text" id="horaires" name="horaires" maxlength="160"
                           value="{{ old('horaires', $boutique->horaires ?? '') }}">
                </div>
                <div class="boutique-field span-2">
                    <label for="footer_texte">Texte footer</label>
                    <textarea id="footer_texte" name="footer_texte" rows="3" maxlength="500">{{ old('footer_texte', $boutique->footer_texte ?? '') }}</textarea>
                </div>
                <div class="boutique-field">
                    <label for="facebook">Facebook (URL)</label>
                    <input type="url" id="facebook" name="facebook" maxlength="255"
                           value="{{ old('facebook', $boutique->facebook ?? '') }}">
                </div>
                <div class="boutique-field">
                    <label for="instagram">Instagram (URL)</label>
                    <input type="url" id="instagram" name="instagram" maxlength="255"
                           value="{{ old('instagram', $boutique->instagram ?? '') }}">
                </div>
                <div class="boutique-field">
                    <label for="tiktok">TikTok (URL)</label>
                    <input type="url" id="tiktok" name="tiktok" maxlength="255"
                           value="{{ old('tiktok', $boutique->tiktok ?? '') }}">
                </div>
                <div class="boutique-field">
                    <label for="statut">Statut</label>
                    <select id="statut" name="statut" class="admin-select">
                        @foreach (['active' => 'Active', 'brouillon' => 'Brouillon', 'suspendue' => 'Suspendue'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('statut', $boutique->statut ?? 'active') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="boutique-card">
            <h2><i class="fas fa-palette"></i> Thèmes</h2>
            <h3 class="theme-zone-title">Catalogue</h3>
            <div class="theme-picker theme-picker-compact">
                @foreach ($themes as $key => $theme)
                    <label class="theme-option theme-preview-{{ $key }}">
                        <input type="radio" name="theme_catalogue" value="{{ $key }}"
                            {{ old('theme_catalogue', $boutique->theme_catalogue ?? 'marina') === $key ? 'checked' : '' }}>
                        <span class="theme-swatch"></span>
                        <span class="theme-meta"><strong>{{ $theme['label'] }}</strong></span>
                    </label>
                @endforeach
            </div>
            <h3 class="theme-zone-title" style="margin-top:1.25rem;">Bannière</h3>
            <div class="theme-picker theme-picker-compact">
                @foreach ($themes as $key => $theme)
                    <label class="theme-option theme-preview-{{ $key }}">
                        <input type="radio" name="theme_banniere" value="{{ $key }}"
                            {{ old('theme_banniere', $boutique->theme_banniere ?? 'marina') === $key ? 'checked' : '' }}>
                        <span class="theme-swatch"></span>
                        <span class="theme-meta"><strong>{{ $theme['label'] }}</strong></span>
                    </label>
                @endforeach
            </div>
            <h3 class="theme-zone-title" style="margin-top:1.25rem;">Footer</h3>
            <div class="theme-picker theme-picker-compact">
                @foreach ($themes as $key => $theme)
                    <label class="theme-option theme-preview-{{ $key }}">
                        <input type="radio" name="theme_footer" value="{{ $key }}"
                            {{ old('theme_footer', $boutique->theme_footer ?? 'marina') === $key ? 'checked' : '' }}>
                        <span class="theme-swatch"></span>
                        <span class="theme-meta"><strong>{{ $theme['label'] }}</strong></span>
                    </label>
                @endforeach
            </div>

            <h3 class="theme-zone-title" style="margin-top:1.5rem;">Couleur d’arrière-plan (body)</h3>
            <div class="bg-color-picker">
                <input type="color" id="couleur_fond" name="couleur_fond" value="{{ old('couleur_fond', $boutique->couleur_fond ?? '#ffffff') }}" aria-label="Couleur de fond">
                <input type="text" id="couleur_fond_hex" value="{{ old('couleur_fond', $boutique->couleur_fond ?? '#ffffff') }}" maxlength="7" class="bg-color-hex" aria-label="Code hexadécimal">
                <span class="bg-color-preview" id="couleurFondPreview" style="background:{{ old('couleur_fond', $boutique->couleur_fond ?? '#ffffff') }}"></span>
            </div>
        </div>

        <div class="boutique-card">
            <h2><i class="fas fa-images"></i> Bannière défilante <span class="opt-badge">max. 3</span></h2>
            <p class="banner-counter" id="adminBanCounter">Slides : <strong>0</strong> / 3</p>
            <div id="adminBanniereList">
                @php
                    $banSource = $oldBan ?? $banniereItems->map(fn ($p) => [
                        'id' => $p->id,
                        'nom' => $p->nom,
                        'prix' => $p->prix,
                        'photo_url' => $p->photoUrl(),
                    ])->values()->all();
                @endphp
                @foreach ($banSource as $i => $b)
                    <div class="boutique-produit-row banniere-row">
                        <button type="button" class="btn-remove-produit btn-remove-ban" title="Retirer"><i class="fas fa-times"></i></button>
                        <h3>Slide bannière <span class="ban-num">{{ $i + 1 }}</span></h3>
                        @if (!empty($b['id']))
                            <input type="hidden" name="banniere[{{ $i }}][id]" value="{{ $b['id'] }}">
                        @endif
                        <div class="boutique-produit-grid">
                            <div class="boutique-field span-2">
                                <label>Nom</label>
                                <input type="text" name="banniere[{{ $i }}][nom]" value="{{ $b['nom'] ?? '' }}" maxlength="160">
                            </div>
                            <div class="boutique-field">
                                <label>Prix (FCFA)</label>
                                <input type="number" name="banniere[{{ $i }}][prix]" value="{{ $b['prix'] ?? '' }}" min="0" step="1">
                            </div>
                            <div class="boutique-field">
                                <label>Photo</label>
                                @if (!empty($b['photo_url']))
                                    <div class="admin-current-media">
                                        <img src="{{ $b['photo_url'] }}" alt="" class="admin-thumb-preview">
                                        <label class="banner-check" style="margin-top:0.4rem;">
                                            <input type="checkbox" name="banniere[{{ $i }}][supprimer_photo]" value="1">
                                            <span>Supprimer cette image</span>
                                        </label>
                                    </div>
                                @endif
                                <input type="file" name="banniere[{{ $i }}][photo]" accept="image/*" class="ban-photo">
                                <small>Nouvelle image pour remplacer</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn-add-produit" id="adminAddBanBtn">
                <i class="fas fa-plus"></i> Ajouter un slide
            </button>
        </div>

        <div class="boutique-card">
            <h2><i class="fas fa-tags"></i> Produits catalogue</h2>
            <div id="adminProduitsList">
                @php
                    $prodSource = $oldProd ?? $catalogueItems->map(fn ($p) => [
                        'id' => $p->id,
                        'nom' => $p->nom,
                        'prix' => $p->prix,
                        'tailles' => $p->tailles,
                        'couleurs' => $p->couleurs,
                        'quantite' => $p->quantite,
                        'photo_url' => $p->photoUrl(),
                        'demande_photos_ia' => $p->demande_photos_ia,
                        'demande_video_ia' => $p->demande_video_ia,
                    ])->values()->all();
                @endphp
                @foreach ($prodSource as $i => $p)
                    <div class="boutique-produit-row">
                        <button type="button" class="btn-remove-produit" title="Retirer"><i class="fas fa-times"></i></button>
                        <h3>Produit <span class="produit-num">{{ $i + 1 }}</span></h3>
                        @if (!empty($p['id']))
                            <input type="hidden" name="produits[{{ $i }}][id]" value="{{ $p['id'] }}">
                        @endif
                        <div class="boutique-produit-grid">
                            <div class="boutique-field span-2">
                                <label>Nom</label>
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
                                <input type="text" name="produits[{{ $i }}][tailles]" value="{{ $p['tailles'] ?? '' }}">
                            </div>
                            <div class="boutique-field">
                                <label>Couleurs</label>
                                <input type="text" name="produits[{{ $i }}][couleurs]" value="{{ $p['couleurs'] ?? '' }}">
                            </div>
                            <div class="boutique-field">
                                <label>Photo</label>
                                @if (!empty($p['photo_url']))
                                    <div class="admin-current-media">
                                        <img src="{{ $p['photo_url'] }}" alt="" class="admin-thumb-preview">
                                        <label class="banner-check" style="margin-top:0.4rem;">
                                            <input type="checkbox" name="produits[{{ $i }}][supprimer_photo]" value="1">
                                            <span>Supprimer cette image</span>
                                        </label>
                                    </div>
                                @endif
                                <input type="file" name="produits[{{ $i }}][photo]" accept="image/*">
                                <small>Nouvelle image pour remplacer</small>
                            </div>
                            <div class="boutique-field span-2 ia-options-block">
                                <p class="ia-options-title"><i class="fas fa-wand-magic-sparkles"></i> Options IA</p>
                                <label class="banner-check ia-opt">
                                    <input type="checkbox" name="produits[{{ $i }}][demande_photos_ia]" value="1" class="ia-photos-cb" {{ !empty($p['demande_photos_ia']) ? 'checked' : '' }}>
                                    <span>5 photos IA — <strong>1 000 FCFA</strong></span>
                                </label>
                                <div class="ia-photos-panel {{ !empty($p['demande_photos_ia']) ? '' : 'is-hidden' }}">
                                    <label>Photos sources (1 à 5)</label>
                                    <input type="file" name="produits[{{ $i }}][sources_ia][]" accept="image/*" multiple class="ia-sources-input">
                                    <div class="ia-sources-actions">
                                        <span class="ia-sources-count">0/5 photo</span>
                                        <input type="hidden" name="produits[{{ $i }}][envoyer_photos_ia]" value="0" class="ia-envoyer-flag">
                                        <button type="button" class="ia-envoyer-btn"><i class="fas fa-paper-plane"></i> Envoyer</button>
                                    </div>
                                    <p class="ia-envoyer-status"></p>
                                    <small>Dès 1 photo. Max 5. Commande photos → admin.</small>
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
                                        <input type="hidden" name="produits[{{ $i }}][envoyer_video_ia]" value="0" class="ia-envoyer-flag">
                                        <button type="button" class="ia-envoyer-btn"><i class="fas fa-paper-plane"></i> Envoyer</button>
                                    </div>
                                    <p class="ia-envoyer-status"></p>
                                    <small>Même procédé : dès 1 fichier. Max 5 images. Commande vidéo → admin.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn-add-produit" id="adminAddProdBtn">
                <i class="fas fa-plus"></i> Ajouter un produit
            </button>
        </div>

        <div class="boutique-form-actions">
            <a href="{{ route('boutiques.admin') }}" class="btn btn-outline">Retour</a>
            <button type="submit" class="btn btn-boutique">
                <i class="fas fa-save"></i>
                {{ $isEdit ? 'Enregistrer toutes les modifications' : 'Créer la boutique' }}
            </button>
        </div>
    </form>
</div>

<template id="adminBanTpl">
    <div class="boutique-produit-row banniere-row">
        <button type="button" class="btn-remove-produit btn-remove-ban" title="Retirer"><i class="fas fa-times"></i></button>
        <h3>Slide bannière <span class="ban-num">1</span></h3>
        <div class="boutique-produit-grid">
            <div class="boutique-field span-2">
                <label>Nom</label>
                <input type="text" name="banniere[__INDEX__][nom]" maxlength="160">
            </div>
            <div class="boutique-field">
                <label>Prix (FCFA)</label>
                <input type="number" name="banniere[__INDEX__][prix]" min="0" step="1">
            </div>
            <div class="boutique-field">
                <label>Photo</label>
                <input type="file" name="banniere[__INDEX__][photo]" accept="image/*" class="ban-photo">
            </div>
        </div>
    </div>
</template>

<template id="adminProdTpl">
    <div class="boutique-produit-row">
        <button type="button" class="btn-remove-produit" title="Retirer"><i class="fas fa-times"></i></button>
        <h3>Produit <span class="produit-num">1</span></h3>
        <div class="boutique-produit-grid">
            <div class="boutique-field span-2">
                <label>Nom</label>
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
                <input type="text" name="produits[__INDEX__][tailles]">
            </div>
            <div class="boutique-field">
                <label>Couleurs</label>
                <input type="text" name="produits[__INDEX__][couleurs]">
            </div>
            <div class="boutique-field">
                <label>Photo</label>
                <input type="file" name="produits[__INDEX__][photo]" accept="image/*">
            </div>
            <div class="boutique-field span-2 ia-options-block">
                <p class="ia-options-title"><i class="fas fa-wand-magic-sparkles"></i> Options IA</p>
                <label class="banner-check ia-opt">
                    <input type="checkbox" name="produits[__INDEX__][demande_photos_ia]" value="1" class="ia-photos-cb">
                    <span>5 photos IA — <strong>1 000 FCFA</strong></span>
                </label>
                <div class="ia-photos-panel is-hidden">
                    <label>Photos sources (1 à 5)</label>
                    <input type="file" name="produits[__INDEX__][sources_ia][]" accept="image/*" multiple class="ia-sources-input">
                    <div class="ia-sources-actions">
                        <span class="ia-sources-count">0/5 photo</span>
                        <input type="hidden" name="produits[__INDEX__][envoyer_photos_ia]" value="0" class="ia-envoyer-flag">
                        <button type="button" class="ia-envoyer-btn"><i class="fas fa-paper-plane"></i> Envoyer</button>
                    </div>
                    <p class="ia-envoyer-status"></p>
                    <small>Dès 1 photo. Max 5. Commande photos → admin.</small>
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
                    <small>Même procédé : dès 1 fichier. Max 5 images. Commande vidéo → admin.</small>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script src="{{ asset('js/boutiques-admin-form.js') }}"></script>
<script src="{{ asset('js/boutiques-couleur-fond.js') }}"></script>
<script src="{{ asset('js/boutiques-ia-options.js') }}"></script>
@endpush
