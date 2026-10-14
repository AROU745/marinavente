@extends('layouts.app')

@section('content')
@php
    $imgBase = asset('images/immobilier');
    $catalogueUrl = url('/services/immobilier-btp/biens/' . $type);
    $gallery = $bien['gallery'] ?? [$bien['image']];
    $imbdMeta = ['imgBase' => $imgBase, 'title' => $bien['title']];
    $tourData = [
        'imgBase' => $imgBase,
        'title' => $bien['title'],
        'scenes' => $bien['tour3d'] ?? [],
    ];
@endphp

<section class="imbd-page" id="imbdApp" data-type="{{ $type }}">
    <div class="imbd-topbar">
        <div class="container imbd-topbar-inner">
            <a href="{{ url('/services/immobilier-btp') }}" class="imbd-brand">
                <span class="imbd-brand-ico"><i class="fas fa-home"></i></span>
                <span>
                    <strong>BUSINESS CENTER Immobilier &amp; BTP</strong>
                    <small>Togo &amp; Côte d'Ivoire</small>
                </span>
            </a>
            <div class="imbd-topbar-actions">
                <a href="{{ $catalogueUrl }}" class="imbd-link-back"><i class="fas fa-th"></i> Catalogue</a>
                <button type="button" class="btn imbd-btn-solid" id="imbdContactOpen">Contacter</button>
            </div>
        </div>
    </div>

    <div class="container imbd-wrap">
        <nav class="imbd-breadcrumb">
            <a href="{{ url('/') }}">Accueil</a>
            <span>/</span>
            <a href="{{ url('/services/immobilier-btp') }}">Immobilier</a>
            <span>/</span>
            <a href="{{ $catalogueUrl }}">{{ $meta['title'] }}</a>
            <span>/</span>
            <strong>{{ $bien['title'] }}</strong>
        </nav>

        <div class="imbd-layout">
            <div class="imbd-main">
                {{-- Gallery --}}
                <div class="imbd-gallery">
                    <div class="imbd-gallery-main">
                        <button type="button" class="imbd-nav imbd-prev" id="imbdPrev" aria-label="Précédent"><i class="fas fa-chevron-left"></i></button>
                        <img id="imbdMainImg" src="{{ $imgBase }}/{{ $gallery[0] }}" alt="{{ $bien['title'] }}">
                        <button type="button" class="imbd-nav imbd-next" id="imbdNext" aria-label="Suivant"><i class="fas fa-chevron-right"></i></button>
                        <span class="imbd-counter" id="imbdCounter">1/{{ count($gallery) }}</span>
                        @if (!empty($bien['visite3d']))
                            <button type="button" class="imbd-tour3d-cta" id="imbdTourOpen">
                                <i class="fas fa-cube"></i> Visite 3D
                            </button>
                        @endif
                    </div>
                    <div class="imbd-thumbs" id="imbdThumbs">
                        @foreach ($gallery as $i => $img)
                            <button type="button" class="imbd-thumb {{ $i === 0 ? 'is-active' : '' }}" data-index="{{ $i }}">
                                <img src="{{ $imgBase }}/{{ $img }}" alt="">
                            </button>
                        @endforeach
                    </div>
                </div>

                @if (!empty($bien['visite3d']))
                    <div class="imbd-tour3d-banner">
                        <div>
                        <strong><i class="fas fa-film"></i> Visite virtuelle cinématique</strong>
                        <p>Balade automatique dans la maison, comme une vidéo immersive.</p>
                        </div>
                        <button type="button" class="btn imbd-btn-solid" id="imbdTourOpenBanner">
                            Lancer la visite 3D
                        </button>
                    </div>
                @endif

                <div class="imbd-head">
                    <div>
                        <h1>{{ $bien['title'] }}</h1>
                        <p class="imbd-loc"><i class="fas fa-map-marker-alt"></i> {{ $bien['quartier'] }}, {{ $bien['ville'] }} — {{ $bien['pays'] }}</p>
                    </div>
                    <div class="imbd-head-actions">
                        <button type="button" class="imbd-btn-ghost" id="imbdFav"><i class="far fa-heart"></i> Favoris</button>
                        <button type="button" class="imbd-btn-ghost" id="imbdShare"><i class="fas fa-share-alt"></i> Partager</button>
                    </div>
                </div>

                <p class="imbd-price">{{ $bien['prixLabel'] }}</p>
                @if (!empty($bien['prixAvant']))
                    <p class="imbd-price-old"><s>{{ number_format($bien['prixAvant'], 0, ',', ' ') }} FCFA</s></p>
                @endif
                @if (!empty($bien['rendement']))
                    <p class="imbd-rendement">Rendement estimé : {{ $bien['rendement'] }}</p>
                @endif

                <p class="imbd-resume">{{ $bien['resume'] }}</p>

                <h2>Détails de la propriété</h2>
                <div class="imbd-details-grid">
                    <div><i class="fas fa-home"></i><span>Type de bien</span><strong>{{ $bien['typeBien'] }}</strong></div>
                    <div><i class="fas fa-tag"></i><span>Statut</span><strong>{{ $bien['statut'] }}</strong></div>
                    <div><i class="fas fa-ruler-combined"></i><span>Surface</span><strong>{{ $bien['surface'] }} m²</strong></div>
                    <div><i class="fas fa-vector-square"></i><span>Surface terrain</span><strong>{{ $bien['surfaceTerrain'] }} m²</strong></div>
                    @if (($bien['chambres'] ?? 0) > 0)
                        <div><i class="fas fa-bed"></i><span>Chambres</span><strong>{{ $bien['chambres'] }}</strong></div>
                        <div><i class="fas fa-couch"></i><span>Salons</span><strong>{{ $bien['salons'] }}</strong></div>
                        <div><i class="fas fa-bath"></i><span>Salles de bain</span><strong>{{ $bien['sdb'] }}</strong></div>
                        <div><i class="fas fa-toilet"></i><span>Toilettes visiteurs</span><strong>{{ $bien['toilettes'] }}</strong></div>
                        <div><i class="fas fa-utensils"></i><span>Cuisine</span><strong>{{ $bien['cuisine'] }}</strong></div>
                        <div><i class="fas fa-car"></i><span>Garage</span><strong>{{ $bien['garage'] }}</strong></div>
                    @endif
                    <div><i class="fas fa-calendar-check"></i><span>Disponibilité</span><strong>{{ $bien['disponibilite'] }}</strong></div>
                    <div><i class="fas fa-hourglass-half"></i><span>Durée minimale</span><strong>{{ $bien['dureeMin'] }}</strong></div>
                </div>

                <h2>Description complète</h2>
                <p class="imbd-desc">{{ $bien['description'] }}</p>

                <h2>Équipements &amp; commodités</h2>
                <div class="imbd-tags">
                    @foreach ($bien['equipements'] as $eq)
                        <span><i class="fas fa-check"></i> {{ $eq }}</span>
                    @endforeach
                </div>

                <h2>Localisation</h2>
                <div class="imbd-loc-block">
                    <div class="imbd-map-placeholder">
                        <i class="fas fa-map-marked-alt"></i>
                        <strong>{{ $bien['quartier'] }}, {{ $bien['ville'] }}</strong>
                        <small>{{ $bien['pays'] }}</small>
                    </div>
                    <ul>
                        @foreach ($bien['localisationPoints'] as $pt)
                            <li><i class="fas fa-check-circle"></i> {{ $pt }}</li>
                        @endforeach
                    </ul>
                </div>

                @if (count($similaires))
                    <div class="imbd-similar-head">
                        <h2>Biens similaires</h2>
                        <a href="{{ $catalogueUrl }}">Voir tous</a>
                    </div>
                    <div class="imbd-similar">
                        @foreach ($similaires as $s)
                            <a href="{{ url('/services/immobilier-btp/biens/' . $type . '/' . $s['slug']) }}" class="imbd-similar-card">
                                <img src="{{ $imgBase }}/{{ $s['image'] }}" alt="{{ $s['title'] }}">
                                <div>
                                    <strong>{{ number_format($s['prix'], 0, ',', ' ') }} FCFA{{ $type === 'location' ? ' / mois' : '' }}</strong>
                                    <span>{{ $s['title'] }}</span>
                                    <small><i class="fas fa-map-marker-alt"></i> {{ $s['ville'] }}, {{ $s['quartier'] }}</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="imbd-side">
                <div class="imbd-card">
                    <div class="imbd-agency">
                        <span class="imbd-agency-ico"><i class="fas fa-building"></i></span>
                        <div>
                            <strong>BUSINESS CENTER Immobilier</strong>
                            <small>Agence · Membre depuis 2022</small>
                        </div>
                    </div>
                    <ul class="imbd-contact-list">
                        <li><i class="fas fa-phone"></i> +228 90 12 34 56</li>
                        <li><i class="fab fa-whatsapp"></i> +228 91 23 45 67</li>
                        <li><i class="fas fa-envelope"></i> immobilier@marinagradepsf.org</li>
                    </ul>
                    <button type="button" class="btn imbd-btn-solid imbd-btn-full" id="imbdMsgOpen">
                        <i class="fas fa-comment"></i> Écrire un message
                    </button>
                    @if (!empty($bien['visite3d']))
                        <button type="button" class="btn imbd-btn-outline imbd-btn-full" id="imbdTourOpenSide">
                            <i class="fas fa-cube"></i> Visite 3D de la maison
                        </button>
                    @endif
                    <a href="tel:+22890123456" class="btn imbd-btn-outline imbd-btn-full">
                        <i class="fas fa-phone"></i> Appeler maintenant
                    </a>
                    <p class="imbd-reply"><i class="fas fa-clock"></i> Répond généralement en moins d'1 heure</p>
                </div>

                <div class="imbd-card">
                    <h3>Informations supplémentaires</h3>
                    <ul class="imbd-meta">
                        <li><span>Réf. annonce</span><strong>#{{ $bien['ref'] }}</strong></li>
                        <li><span>Catégorie</span><strong>{{ $bien['typeBien'] }}</strong></li>
                        <li><span>Statut</span><strong>{{ $bien['statut'] }}</strong></li>
                        <li><span>Vues</span><strong>{{ $bien['vues'] }}</strong></li>
                    </ul>
                    <button type="button" class="imbd-report" id="imbdReport">Signaler cette annonce</button>
                </div>
            </aside>
        </div>
    </div>
</section>

<script type="application/json" id="imbdGalleryData">@json($gallery)</script>
<script type="application/json" id="imbdMetaData">@json($imbdMeta)</script>
<script type="application/json" id="imbdTourData">@json($tourData)</script>

{{-- Modal visite 3D (balade cinématique type vidéo) --}}
@if (!empty($bien['visite3d']))
<div class="imbd-tour3d" id="imbdTourModal" hidden>
    <div class="imbd-tour3d-top">
        <div>
            <strong><i class="fas fa-film"></i> Visite 3D</strong>
            <span id="imbdTourTitle">{{ $bien['title'] }}</span>
        </div>
        <div class="imbd-tour3d-top-actions">
            <button type="button" class="imbd-tour3d-fs" id="imbdTourFs" aria-label="Plein écran"><i class="fas fa-expand"></i></button>
            <button type="button" class="imbd-tour3d-close" id="imbdTourClose" aria-label="Fermer"><i class="fas fa-times"></i></button>
        </div>
    </div>
    <div class="imbd-tour3d-body">
        <div class="imbd-tour3d-stage" id="imbdTourStage">
            <div class="imbd-tour3d-layer is-active" id="imbdTourLayerA">
                <div class="imbd-tour3d-pan"><img alt=""></div>
            </div>
            <div class="imbd-tour3d-layer" id="imbdTourLayerB">
                <div class="imbd-tour3d-pan"><img alt=""></div>
            </div>
            <div class="imbd-tour3d-vignette" aria-hidden="true"></div>
            <div class="imbd-tour3d-hint" id="imbdTourHint">
                <i class="fas fa-play-circle"></i> Balade automatique · Pause pour explorer à la main
            </div>
            <div class="imbd-tour3d-room-label" id="imbdTourRoomLabel">Salon</div>
            <div class="imbd-tour3d-chapter" id="imbdTourChapter">1 / 5</div>
        </div>
        <aside class="imbd-tour3d-side">
            <p class="imbd-tour3d-desc" id="imbdTourDesc"></p>
            <p class="imbd-tour3d-rooms-title">Parcours de la visite</p>
            <div class="imbd-tour3d-rooms" id="imbdTourRooms"></div>
            <div class="imbd-tour3d-help">
                <i class="fas fa-info-circle"></i>
                La visite se lit comme une vidéo : la caméra se promène dans chaque pièce, puis passe à la suivante.
            </div>
        </aside>
    </div>
    <div class="imbd-tour3d-player">
        <button type="button" class="imbd-tour3d-play" id="imbdTourPlay" aria-label="Lecture">
            <i class="fas fa-pause"></i>
        </button>
        <button type="button" class="imbd-tour3d-skip" id="imbdTourPrev" aria-label="Pièce précédente">
            <i class="fas fa-step-backward"></i>
        </button>
        <div class="imbd-tour3d-timeline">
            <input type="range" id="imbdTourSeek" min="0" max="1000" value="0" step="1" aria-label="Progression">
            <div class="imbd-tour3d-time">
                <span id="imbdTourTime">0:00</span>
                <span id="imbdTourDuration">0:00</span>
            </div>
        </div>
        <button type="button" class="imbd-tour3d-skip" id="imbdTourNext" aria-label="Pièce suivante">
            <i class="fas fa-step-forward"></i>
        </button>
        <button type="button" class="imbd-tour3d-speed" id="imbdTourSpeed" title="Vitesse">1x</button>
    </div>
</div>
@endif

{{-- Modal contact --}}
<div class="imbd-modal" id="imbdMsgModal" hidden>
    <div class="imbd-modal-backdrop" data-imbd-close></div>
    <div class="imbd-modal-dialog" role="dialog" aria-modal="true">
        <button type="button" class="imbd-modal-close" data-imbd-close aria-label="Fermer"><i class="fas fa-times"></i></button>
        <h2>Contacter l'agence</h2>
        <p class="imbd-modal-sub">À propos de : {{ $bien['title'] }}</p>
        <form id="imbdMsgForm" onsubmit="return false;">
            <div class="imbd-field">
                <label for="imbdNom">Nom complet *</label>
                <input type="text" id="imbdNom" required>
            </div>
            <div class="imbd-field">
                <label for="imbdTel">Téléphone <em>(facultatif)</em></label>
                <input type="tel" id="imbdTel">
            </div>
            <div class="imbd-field">
                <label for="imbdMessage">Message *</label>
                <textarea id="imbdMessage" rows="4" required placeholder="Je souhaite visiter / en savoir plus…"></textarea>
            </div>
            <button type="submit" class="btn imbd-btn-solid imbd-btn-full">Envoyer</button>
            <p class="imbd-feedback" id="imbdFeedback" hidden></p>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/immobilier-bien-show.js') }}"></script>
@endpush
