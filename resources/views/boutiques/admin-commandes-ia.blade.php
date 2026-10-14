@extends('layouts.app')

@section('content')
<section class="boutique-hero">
    <div class="container" style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-end;gap:1rem;">
        <div>
            <p class="boutique-eyebrow">Administration MARINA</p>
            <h1>
                @if ($boutiqueFiltre ?? null)
                    Commandes — {{ $boutiqueFiltre->nom_entreprise }}
                @else
                    Commandes IA
                @endif
            </h1>
            <p class="boutique-lead">
                @if ($boutiqueFiltre ?? null)
                    Demandes IA de cette boutique uniquement.
                @else
                    Photos et vidéos IA à réaliser pour les boutiques.
                @endif
            </p>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:0.65rem;align-items:center;">
            @if ($boutiqueFiltre ?? null)
                <a href="{{ route('boutiques.admin.commandes-ia') }}" class="btn btn-outline">
                    Toutes les commandes
                </a>
            @endif
            <a href="{{ route('boutiques.admin') }}" class="btn btn-outline">
                <i class="fas fa-store"></i> Boutiques
            </a>
        </div>
    </div>
</section>

<div class="container boutique-admin-table-wrap">
    @if (session('success'))
        <p class="auth-success" style="margin-bottom:1.25rem;">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <div class="auth-errors" style="margin-bottom:1.25rem;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($commandes->isEmpty())
        <p class="boutique-lead">Aucune commande IA pour le moment.</p>
    @else
        <div class="ia-commandes-list">
            @foreach ($commandes as $commande)
                @php
                    $produit = $commande->produit;
                    $sources = $produit
                        ? $produit->medias->where('type', \App\Models\BoutiqueProduitMedia::TYPE_SOURCE_IA)
                        : collect();
                    $sourcesVideo = $produit
                        ? $produit->medias->where('type', \App\Models\BoutiqueProduitMedia::TYPE_SOURCE_VIDEO)
                        : collect();
                    $rendusPhotos = $produit
                        ? $produit->medias->where('type', \App\Models\BoutiqueProduitMedia::TYPE_PHOTO_IA)
                        : collect();
                    $rendusVideos = $produit
                        ? $produit->medias->where('type', \App\Models\BoutiqueProduitMedia::TYPE_VIDEO_IA)
                        : collect();
                    $besoinPhotos = $produit && $produit->demande_photos_ia;
                    $besoinVideo = $produit && $produit->demande_video_ia;
                @endphp
                <article class="ia-commande-card statut-{{ $commande->statut }}">
                    <div class="ia-commande-head">
                        <div>
                            <p class="ia-commande-ref">Commande #{{ $commande->id }}</p>
                            <h2>{{ $produit->nom ?? 'Produit supprimé' }}</h2>
                            <p class="ia-commande-meta">
                                Boutique : <strong>{{ $commande->boutique->nom_entreprise ?? '—' }}</strong>
                                · {{ $commande->labelType() }}
                                · {{ number_format($commande->montant, 0, ',', ' ') }} FCFA
                                · {{ $commande->nb_sources }} photo{{ $commande->nb_sources > 1 ? 's' : '' }} source{{ $commande->nb_sources > 1 ? 's' : '' }}
                            </p>
                        </div>
                        <span class="ia-commande-badge">{{ $commande->labelStatut() }}</span>
                    </div>

                    <div class="ia-commande-blocks">
                        @if ($besoinPhotos)
                            <div>
                                <p class="ia-block-title">Sources photos (client)</p>
                                @if ($sources->isNotEmpty())
                                    <div class="ia-commande-sources">
                                        @foreach ($sources as $media)
                                            <a href="{{ $media->url() }}" target="_blank" rel="noopener" class="ia-commande-thumb">
                                                <img src="{{ $media->url() }}" alt="Source photos">
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="ia-block-empty">Aucune image source photos.</p>
                                @endif
                            </div>
                        @endif

                        @if ($besoinVideo)
                            <div>
                                <p class="ia-block-title">Sources vidéo (client)</p>
                                @if ($sourcesVideo->isNotEmpty())
                                    <div class="ia-commande-sources">
                                        @foreach ($sourcesVideo as $media)
                                            @if ($media->isVideo())
                                                <a href="{{ $media->url() }}" target="_blank" rel="noopener" class="ia-commande-thumb is-video" title="Vidéo source">
                                                    <span class="thumb-video-badge"><i class="fas fa-play"></i></span>
                                                    <video src="{{ $media->url() }}" muted preload="metadata"></video>
                                                </a>
                                            @else
                                                <a href="{{ $media->url() }}" target="_blank" rel="noopener" class="ia-commande-thumb">
                                                    <img src="{{ $media->url() }}" alt="Source vidéo">
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <p class="ia-block-empty">Aucune source vidéo.</p>
                                @endif
                            </div>
                        @endif

                        <div>
                            <p class="ia-block-title">Rendu livré (fiche produit)</p>
                            @if ($rendusPhotos->isEmpty() && $rendusVideos->isEmpty())
                                <p class="ia-block-empty">Pas encore de photos / vidéo livrées.</p>
                            @else
                                <div class="ia-commande-sources">
                                    @foreach ($rendusPhotos as $media)
                                        <a href="{{ $media->url() }}" target="_blank" rel="noopener" class="ia-commande-thumb" title="Photo IA">
                                            <img src="{{ $media->url() }}" alt="Photo IA">
                                        </a>
                                    @endforeach
                                    @foreach ($rendusVideos as $media)
                                        <a href="{{ $media->url() }}" target="_blank" rel="noopener" class="ia-commande-thumb is-video" title="Vidéo IA">
                                            <span class="thumb-video-badge"><i class="fas fa-play"></i></span>
                                            <video src="{{ $media->url() }}" muted preload="metadata"></video>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <form
                        action="{{ route('boutiques.admin.commandes-ia.update', $commande) }}"
                        method="post"
                        enctype="multipart/form-data"
                        class="ia-commande-form"
                    >
                        @csrf
                        @method('PATCH')

                        <div class="ia-upload-grid">
                            @if ($besoinPhotos)
                                <div class="ia-upload-field">
                                    <label>
                                        <i class="fas fa-images"></i>
                                        Upload photos IA (max 5)
                                        <span class="ia-upload-hint">{{ $rendusPhotos->count() }}/5 déjà livrées</span>
                                    </label>
                                    <input type="file" name="photos_ia[]" accept="image/*" multiple>
                                </div>
                            @endif

                            @if ($besoinVideo)
                                <div class="ia-upload-field">
                                    <label>
                                        <i class="fas fa-video"></i>
                                        Upload vidéo IA
                                        <span class="ia-upload-hint">{{ $rendusVideos->isNotEmpty() ? '1 déjà livrée (sera remplacée)' : 'Aucune encore' }}</span>
                                    </label>
                                    <input type="file" name="video_ia" accept="video/mp4,video/webm,video/quicktime">
                                </div>
                            @endif
                        </div>

                        <div class="ia-commande-actions">
                            <label>
                                Statut
                                <select name="statut">
                                    <option value="en_attente" @selected($commande->statut === 'en_attente')>À faire</option>
                                    <option value="en_cours" @selected($commande->statut === 'en_cours')>En cours</option>
                                    <option value="terminee" @selected($commande->statut === 'terminee')>Terminée</option>
                                </select>
                            </label>
                            <button type="submit" class="btn btn-boutique">
                                <i class="fas fa-cloud-upload-alt"></i> Envoyer à l’utilisateur
                            </button>
                            @if ($commande->boutique && $produit)
                                <a
                                    href="{{ route('boutiques.produit', [$commande->boutique->slug, $produit->id]) }}"
                                    class="btn btn-outline"
                                    target="_blank"
                                    rel="noopener"
                                >
                                    Voir la fiche
                                </a>
                            @endif
                        </div>
                    </form>
                </article>
            @endforeach
        </div>

        <div style="margin-top:1.5rem;">
            {{ $commandes->links() }}
        </div>
    @endif
</div>
@endsection
