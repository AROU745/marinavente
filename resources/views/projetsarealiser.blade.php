@extends('layouts.app')

@section('content')
<section class="pe-page">
    <div class="container pe-breadcrumb">
        <a href="{{ url('/') }}">Accueil</a>
        <span>/</span>
        <a href="{{ url('/projetsenvironnement') }}">Projets Environnement</a>
        <span>/</span>
        <strong>À réaliser</strong>
    </div>

    <div class="container pe-list-hero">
        <a href="{{ url('/projetsenvironnement') }}" class="svc-back">
            <i class="fas fa-arrow-left"></i> Retour aux projets
        </a>
        <h1>Projets <span>à réaliser</span></h1>
        <p>
            Soutenez nos prochaines actions environnementales. Chaque contribution
            accélère le financement et l'impact sur le terrain.
        </p>
    </div>

    <div class="container pe-section">
        <div class="pe-grid pe-grid-full">
            @foreach($projets as $projet)
                <article class="pe-card">
                    <div class="pe-card-media">
                        <img src="{{ asset('images/' . $projet['image']) }}" alt="{{ $projet['title'] }}">
                        <span class="pe-badge">{{ $projet['badge'] }}</span>
                    </div>
                    <div class="pe-card-body">
                        <h3>{{ $projet['title'] }}</h3>
                        <p>{{ $projet['description'] }}</p>
                        <div class="pe-meta">
                            <span><i class="fas fa-map-marker-alt"></i> {{ $projet['lieu'] }}</span>
                            <span><i class="fas fa-{{ $projet['metricIcon'] }}"></i> {{ $projet['metric'] }}</span>
                        </div>
                        <div class="pe-progress">
                            <div class="pe-progress-bar"><span style="width: {{ $projet['progress'] }}%"></span></div>
                            <small>{{ $projet['progress'] }}% financé</small>
                        </div>
                        <div class="pe-card-actions">
                            <a href="{{ url('/soutenir/' . $projet['slug']) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-heart"></i> Soutenir ce projet
                            </a>
                            <button type="button" class="btn btn-outline-green btn-sm pe-play"
                                data-image="{{ asset('images/' . $projet['image']) }}"
                                data-video="{{ !empty($projet['video']) ? url('/videos/' . $projet['video']) : '' }}"
                                data-title="{{ $projet['title'] }}">
                                <i class="fas fa-play"></i> Voir la vidéo
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
