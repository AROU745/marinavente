@extends('layouts.app')

@section('content')
<section class="pe-page">
    {{-- Breadcrumb --}}
    <div class="container pe-breadcrumb">
        <a href="{{ url('/') }}">Accueil</a>
        <span>/</span>
        <a href="{{ url('/services/ong-environnement') }}">ONG &amp; Environnement</a>
        <span>/</span>
        <strong>Projets</strong>
    </div>

    {{-- Hero --}}
    <div class="container pe-hero">
        <div class="pe-hero-text">
            <h1>Nos projets <span>Environnement</span></h1>
            <p>
                Découvrez nos actions concrètes en faveur de l'environnement à travers nos projets.
                Ensemble, construisons un avenir durable.
            </p>
            <div class="pe-features">
                <div class="pe-feature">
                    <i class="fas fa-leaf"></i>
                    <div>
                        <strong>Impact durable</strong>
                        <span>Des actions à long terme</span>
                    </div>
                </div>
                <div class="pe-feature">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <strong>Transparence</strong>
                        <span>Suivi et rapports réguliers</span>
                    </div>
                </div>
                <div class="pe-feature">
                    <i class="fas fa-users"></i>
                    <div>
                        <strong>Partenariats locaux</strong>
                        <span>Communautés impliquées</span>
                    </div>
                </div>
                <div class="pe-feature">
                    <i class="fas fa-chart-line"></i>
                    <div>
                        <strong>Résultats mesurables</strong>
                        <span>Des impacts concrets</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="pe-hero-media">
            <img src="{{ asset('images/projets/hero-seedling.jpg') }}" alt="Projets environnement">
        </div>
    </div>

    {{-- Projets à réaliser --}}
    <div class="container pe-section">
        <div class="pe-section-head">
            <h2>Projets à réaliser</h2>
            <a href="{{ url('/projetsarealiser') }}" class="pe-more">Voir plus de projets <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="pe-grid">
            @foreach($aRealiser as $projet)
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

    {{-- Projets réalisés --}}
    <div class="container pe-section">
        <div class="pe-section-head">
            <h2>Projets réalisés</h2>
            <a href="{{ url('/projetrealiser') }}" class="pe-more">Voir plus de projets <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="pe-grid">
            @foreach($realises as $projet)
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

    {{-- CTA --}}
    <div class="pe-cta-band" id="contact">
        <div class="container pe-cta-inner">
            <div class="pe-cta-icon"><i class="fas fa-hand-holding-heart"></i></div>
            <div class="pe-cta-text">
                <strong>Votre soutien fait la différence.</strong>
                Chaque contribution nous permet d'agir davantage pour la planète et les communautés.
                Ensemble, créons un impact durable.
            </div>
            <a href="{{ url('/partenaires') }}" class="btn btn-primary">
                <i class="fas fa-heart"></i> Devenir partenaire
            </a>
        </div>
    </div>
</section>

@include('partials.pe-modal')
@endsection

@push('scripts')
<script src="{{ asset('js/pe-modal.js') }}"></script>
@endpush
