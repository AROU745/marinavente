@extends('layouts.app')

@section('content')
@php
    $imgBase = asset('images/immobilier');
    $imblMeta = [
        'imgBase' => $imgBase,
        'priceSuffix' => $meta['priceSuffix'],
        'label' => $meta['label'],
        'type' => $type,
    ];
@endphp

<section class="imbl-page" id="imblApp"
    data-type="{{ $type }}"
    data-price-suffix="{{ $meta['priceSuffix'] }}"
    data-label="{{ $meta['label'] }}"
>
    <div class="imbl-topbar">
        <div class="container imbl-topbar-inner">
            <a href="{{ url('/services/immobilier-btp') }}" class="imbl-brand">
                <span class="imbl-brand-ico"><i class="fas fa-home"></i></span>
                <span>
                    <strong>BUSINESS CENTER Immobilier &amp; BTP</strong>
                    <small>Togo &amp; Côte d'Ivoire</small>
                </span>
            </a>
            <div class="imbl-topbar-actions">
                <a href="tel:+22890123456" class="imbl-phone"><i class="fas fa-phone"></i> +228 90 12 34 56</a>
                <button type="button" class="btn imbl-btn-solid imbl-rdv-btn">Prendre rendez-vous</button>
            </div>
        </div>
    </div>

    <div class="container imbl-head">
        <nav class="imbl-breadcrumb">
            <a href="{{ url('/') }}">Accueil</a>
            <span>/</span>
            <a href="{{ url('/services/immobilier-btp') }}">Biens</a>
            <span>/</span>
            <strong>{{ $meta['title'] }}</strong>
        </nav>

        <div class="imbl-head-grid">
            <div>
                <h1>{{ $meta['title'] }}</h1>
                <p>{{ $meta['intro'] }}</p>
                <div class="imbl-stats">
                    @foreach ($meta['stats'] as $st)
                        <div class="imbl-stat">
                            <i class="fas fa-home"></i>
                            <div>
                                <strong>{{ $st['value'] }}</strong>
                                <span>{{ $st['label'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <aside class="imbl-promo">
                <div class="imbl-promo-maps">
                    <span class="imbl-map-tg">TG</span>
                    <span class="imbl-map-ci">CI</span>
                </div>
                <div>
                    <strong>Investissez au Togo &amp; en Côte d'Ivoire</strong>
                    <p>Accompagnement local et suivi à distance pour la diaspora.</p>
                    <a href="{{ url('/services/immobilier-btp/biens/investir') }}" class="imbl-promo-link">En savoir plus</a>
                </div>
            </aside>
        </div>
    </div>

    <div class="container imbl-layout">
        <div class="imbl-main">
            <div class="imbl-toolbar">
                <p id="imblCount">—</p>
                <div class="imbl-toolbar-right">
                    <label>
                        Trier par
                        <select id="imblSort">
                            <option value="recent">Plus récents</option>
                            <option value="prix-asc">Prix croissant</option>
                            <option value="prix-desc">Prix décroissant</option>
                            <option value="surface">Surface</option>
                        </select>
                    </label>
                    <div class="imbl-view-toggle" role="group" aria-label="Affichage">
                        <button type="button" class="is-active" data-view="grid" aria-label="Grille"><i class="fas fa-th"></i></button>
                        <button type="button" data-view="list" aria-label="Liste"><i class="fas fa-list"></i></button>
                    </div>
                </div>
            </div>

            <div class="imbl-grid" id="imblGrid"></div>
            <p class="imbl-empty" id="imblEmpty" hidden>Aucun bien ne correspond à vos critères.</p>

            <div class="imbl-pagination" id="imblPagination"></div>
        </div>

        <aside class="imbl-sidebar">
            <form class="imbl-filters" id="imblFilters" onsubmit="return false;">
                <h2>Rechercher un bien</h2>
                <div class="imbl-field">
                    <input type="search" id="imblQ" placeholder="Mot-clé (villa, Agoè…)">
                </div>
                <div class="imbl-field">
                    <label for="imblPays">Pays</label>
                    <select id="imblPays">
                        <option value="">Tous les pays</option>
                        <option value="Togo">Togo</option>
                        <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                    </select>
                </div>
                <div class="imbl-field">
                    <label for="imblVille">Ville</label>
                    <select id="imblVille">
                        <option value="">Toutes les villes</option>
                    </select>
                </div>
                <div class="imbl-field">
                    <label for="imblBudget">Budget max</label>
                    <select id="imblBudget">
                        <option value="">Sans limite</option>
                        <option value="20000000">20 000 000</option>
                        <option value="50000000">50 000 000</option>
                        <option value="100000000">100 000 000</option>
                        <option value="200000000">200 000 000</option>
                        <option value="500000000">500 000 000</option>
                    </select>
                </div>
                @if ($type !== 'terrains')
                <div class="imbl-field">
                    <label for="imblChambres">Nombre de chambres</label>
                    <select id="imblChambres">
                        <option value="">Indifférent</option>
                        <option value="1">1+</option>
                        <option value="2">2+</option>
                        <option value="3">3+</option>
                        <option value="4">4+</option>
                        <option value="5">5+</option>
                    </select>
                </div>
                @else
                <input type="hidden" id="imblChambres" value="">
                @endif
                <div class="imbl-field">
                    <label for="imblSurface">Superficie min (m²)</label>
                    <select id="imblSurface">
                        <option value="">Indifférent</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="400">400</option>
                        <option value="800">800</option>
                        <option value="1000">1000</option>
                    </select>
                </div>
                <button type="button" class="btn imbl-btn-solid imbl-btn-full" id="imblSearch">
                    <i class="fas fa-search"></i> Rechercher
                </button>
                <button type="button" class="imbl-reset" id="imblReset">Réinitialiser les filtres</button>
            </form>

            <div class="imbl-side-cta">
                <i class="fas fa-home"></i>
                <h3>Besoin d'aide ?</h3>
                <p>Notre équipe vous accompagne pour trouver le bien idéal.</p>
                <a href="{{ url('/contact') }}" class="btn imbl-btn-outline imbl-btn-full"><i class="fas fa-phone"></i> Nous contacter</a>
            </div>

            <div class="imbl-side-cta imbl-side-cta-alt">
                <i class="fas fa-seedling"></i>
                <h3>Investisseurs &amp; Diaspora</h3>
                <p>Construisez ou investissez au pays avec un suivi à distance.</p>
                <a href="{{ url('/services/immobilier-btp#opportunites') }}" class="btn imbl-btn-outline imbl-btn-full">En savoir plus</a>
            </div>
        </aside>
    </div>
</section>

<script type="application/json" id="imblBiensData">@json($biens)</script>
<script type="application/json" id="imblMetaData">@json($imblMeta)</script>

{{-- Modal détail --}}
<div class="imbl-modal" id="imblDetailModal" hidden>
    <div class="imbl-modal-backdrop" data-imbl-close></div>
    <div class="imbl-modal-dialog" role="dialog" aria-modal="true">
        <button type="button" class="imbl-modal-close" data-imbl-close aria-label="Fermer"><i class="fas fa-times"></i></button>
        <div id="imblDetailBody"></div>
        <button type="button" class="btn imbl-btn-solid imbl-btn-full imbl-rdv-btn" style="margin-top:1rem">
            <i class="fas fa-calendar-check"></i> Prendre rendez-vous
        </button>
    </div>
</div>

{{-- Modal RDV --}}
<div class="imbl-modal" id="imblRdvModal" hidden>
    <div class="imbl-modal-backdrop" data-imbl-close-rdv></div>
    <div class="imbl-modal-dialog" role="dialog" aria-modal="true">
        <button type="button" class="imbl-modal-close" data-imbl-close-rdv aria-label="Fermer"><i class="fas fa-times"></i></button>
        <h2>Prendre rendez-vous</h2>
        <p class="imbl-modal-sub">Indiquez vos coordonnées. Nous vous rappelons rapidement.</p>
        <form id="imblRdvForm" onsubmit="return false;">
            <div class="imbl-field">
                <label for="imblRdvNom">Nom complet *</label>
                <input type="text" id="imblRdvNom" required>
            </div>
            <div class="imbl-field">
                <label for="imblRdvTel">Téléphone <em>(facultatif)</em></label>
                <input type="tel" id="imblRdvTel">
            </div>
            <div class="imbl-field">
                <label for="imblRdvMsg">Message</label>
                <textarea id="imblRdvMsg" rows="3" placeholder="Bien concerné, disponibilité…"></textarea>
            </div>
            <button type="submit" class="btn imbl-btn-solid imbl-btn-full">Envoyer</button>
            <p class="imbl-feedback" id="imblRdvFeedback" hidden></p>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/immobilier-biens.js') }}"></script>
@endpush
