@extends('layouts.app')

@section('content')
<section class="svc-page ong-env-page" style="--svc: #2E7D32; --svc-soft: #E8F5E9;">
    {{-- Hero --}}
    <div class="container svc-hero">
        <div class="svc-hero-text">
            <a href="{{ url('/#services') }}" class="svc-back">
                <i class="fas fa-arrow-left"></i> Nos services
            </a>
            <h1>ONG &amp; Environnement</h1>
            <p>
                Agissons ensemble pour la planète. Reboisement, sensibilisation écologique
                et projets verts pour un avenir durable au Togo et ailleurs.
            </p>
            <div class="svc-hero-actions">
                <a href="{{ url('/projetsenvironnement') }}" class="btn btn-primary svc-cta">
                    Découvrir nos actions
                </a>
                <a href="{{ url('/partenaires') }}" class="btn btn-outline-green svc-cta-secondary">
                    <i class="fas fa-heart"></i> Devenir partenaire
                </a>
            </div>
            <p class="svc-partner-hint">
                <i class="fas fa-users"></i>
                Nos partenaires, donateurs et bénévoles engagés
            </p>
        </div>
        <div class="svc-hero-media">
            <img src="{{ asset('images/services/ong-environnement.jpg') }}" alt="ONG & Environnement">
        </div>
    </div>

    {{-- Domaines d'action --}}
    <div class="container svc-domains">
        <h2>Nos domaines d'action</h2>
        <div class="svc-cards svc-cards-3">
            <article class="svc-card">
                <div class="svc-card-icon"><i class="fas fa-tree"></i></div>
                <h3>Reboisement</h3>
                <p>Plantation communautaire et restauration des écosystèmes.</p>
            </article>
            <article class="svc-card">
                <div class="svc-card-icon"><i class="fas fa-bullhorn"></i></div>
                <h3>Sensibilisation</h3>
                <p>Éducation environnementale auprès des communautés.</p>
            </article>
            <article class="svc-card">
                <div class="svc-card-icon"><i class="fas fa-leaf"></i></div>
                <h3>Projets verts</h3>
                <p>Initiatives durables à impact mesurable.</p>
            </article>
        </div>
    </div>

    {{-- Partenaires + Soutien --}}
    <div class="ong-bottom">
        <div class="container ong-bottom-grid">
            <div class="ong-why">
                <h2>Pourquoi devenir partenaire ?</h2>
                <div class="ong-why-grid">
                    <div class="ong-why-item">
                        <i class="fas fa-users"></i>
                        <h3>Impact concret</h3>
                        <p>Contribuez à des projets mesurables et durables.</p>
                    </div>
                    <div class="ong-why-item">
                        <i class="fas fa-handshake"></i>
                        <h3>Transparence</h3>
                        <p>Suivi régulier et rapports d'impact détaillés.</p>
                    </div>
                    <div class="ong-why-item">
                        <i class="fas fa-award"></i>
                        <h3>Visibilité</h3>
                        <p>Mettez en valeur votre engagement sociétal.</p>
                    </div>
                    <div class="ong-why-item">
                        <i class="fas fa-heart"></i>
                        <h3>Engagement humain</h3>
                        <p>Rejoignez une communauté de personnes engagées.</p>
                    </div>
                </div>
            </div>

            <aside class="ong-support">
                <div class="ong-support-icon"><i class="fas fa-leaf"></i></div>
                <h2>Soutenez nos actions</h2>
                <p>Votre soutien nous aide à réaliser plus de projets et à toucher plus de vies.</p>
                <a href="{{ url('/partenaires') }}" class="btn btn-white">
                    Nous soutenir maintenant <i class="fas fa-arrow-right"></i>
                </a>
            </aside>
        </div>
    </div>
</section>
@endsection
