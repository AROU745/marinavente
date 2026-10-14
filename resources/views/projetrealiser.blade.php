@extends('layouts.app')

@section('content')
<section class="pe-page">
    <div class="container pe-breadcrumb">
        <a href="{{ url('/') }}">Accueil</a>
        <span>/</span>
        <a href="{{ url('/projetsenvironnement') }}">Projets Environnement</a>
        <span>/</span>
        <strong>Réalisés</strong>
    </div>

    <div class="container pe-list-hero">
        <a href="{{ url('/projetsenvironnement') }}" class="svc-back">
            <i class="fas fa-arrow-left"></i> Retour aux projets
        </a>
        <h1>Projets <span>réalisés</span></h1>
        <p>
            Retrouvez les actions déjà menées sur le terrain : reboisement, eau potable,
            protection des mangroves et plus encore.
        </p>
    </div>

    <div class="container pe-section">
        <div class="pe-grid pe-grid-full">
            @foreach($projets as $projet)
                <article class="pe-card pe-card-done">
                    <div class="pe-card-media pe-media-clickable pe-play"
                        data-image="{{ asset('images/' . $projet['image']) }}"
                        data-video="{{ !empty($projet['video']) ? url('/videos/' . $projet['video']) : '' }}"
                        data-title="{{ $projet['title'] }}"
                        role="button"
                        tabindex="0">
                        <img src="{{ asset('images/' . $projet['image']) }}" alt="{{ $projet['title'] }}">
                        <span class="pe-badge pe-badge-done">Réalisé</span>
                        @if(!empty($projet['video']))
                            <span class="pe-play-overlay"><i class="fas fa-play"></i></span>
                        @endif
                    </div>
                    <div class="pe-card-body">
                        <h3>{{ $projet['title'] }}</h3>
                        <p>{{ $projet['description'] }}</p>
                        <div class="pe-meta">
                            <span><i class="fas fa-map-marker-alt"></i> {{ $projet['lieu'] }}</span>
                            <span><i class="fas fa-calendar-alt"></i> {{ $projet['annee'] }}</span>
                        </div>
                        <div class="pe-card-actions">
                            <a href="{{ url('/soutenir/' . $projet['slug']) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-heart"></i> Soutenir ce projet
                            </a>
                            <button type="button" class="btn btn-outline-green btn-sm pe-play"
                                data-image="{{ asset('images/' . $projet['image']) }}"
                                data-video="{{ !empty($projet['video']) ? url('/videos/' . $projet['video']) : '' }}"
                                data-title="{{ $projet['title'] }}">
                                <i class="fas fa-{{ !empty($projet['video']) ? 'play' : 'image' }}"></i>
                                {{ !empty($projet['video']) ? 'Voir la vidéo' : "Voir l'image" }}
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

@include('partials.pe-modal')
@endsection

@push('scripts')
<script src="{{ asset('js/pe-modal.js') }}"></script>
@endpush
