@extends('layouts.app')

@section('content')
<section class="about-page">
    {{-- Hero --}}
    <div class="about-hero-wrap">
        <div class="container">
            <nav class="pe-breadcrumb" aria-label="Fil d'Ariane">
                <a href="{{ url('/') }}"><i class="fas fa-home"></i> Accueil</a>
                <span>&gt;</span>
                <strong>À propos</strong>
            </nav>

            <div class="about-hero-grid">
                <div class="about-hero-content">
                    <p class="about-eyebrow">À propos de nous</p>
                    <h1>MARINA, votre partenaire <span>de croissance</span></h1>
                    <p class="about-lead">
                        MARINA est une entreprise spécialisée en développement IT, communication digitale,
                        immobilier &amp; BTP et sous-traitance internationale. Nous accompagnons entreprises,
                        institutions et particuliers avec des solutions professionnelles et opérationnelles.
                    </p>
                </div>
                <div class="about-hero-visual">
                    <img src="{{ asset('images/apropos/hero-hands.jpg') }}" alt="Développement IT et Intelligence Artificielle — MARINA">
                </div>
            </div>
        </div>

        <div class="about-hero-stats">
            <div class="container about-hero-stats-inner">
                <div class="about-stat-item">
                    <i class="fas fa-calendar-check"></i>
                    <div>
                        <strong>10+</strong>
                        <span>Années d'expérience</span>
                    </div>
                </div>
                <div class="about-stat-item">
                    <i class="fas fa-folder-open"></i>
                    <div>
                        <strong>120+</strong>
                        <span>Projets livrés</span>
                    </div>
                </div>
                <div class="about-stat-item">
                    <i class="fas fa-users"></i>
                    <div>
                        <strong>300+</strong>
                        <span>Clients accompagnés</span>
                    </div>
                </div>
                <div class="about-stat-item">
                    <i class="fas fa-globe"></i>
                    <div>
                        <strong>4</strong>
                        <span>Pôles d'expertise</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mission, Vision, Valeurs --}}
    <div class="about-mvv-section">
        <div class="container">
            <h2 class="about-section-title">Notre mission, notre vision, nos valeurs</h2>
            <div class="about-mvv-grid">
                <article class="about-mvv-card">
                    <div class="icon"><i class="fas fa-bullseye"></i></div>
                    <h3>Notre mission</h3>
                    <p>
                        Fournir des services IT, digitaux, immobiliers et de sous-traitance internationale
                        de qualité, pour accélérer la performance et la croissance de nos clients.
                    </p>
                </article>
                <article class="about-mvv-card">
                    <div class="icon"><i class="fas fa-eye"></i></div>
                    <h3>Notre vision</h3>
                    <p>
                        Devenir un acteur de référence au Togo et à l'international sur le développement
                        d'applications, la communication digitale, l'immobilier &amp; BTP et l'outsourcing.
                    </p>
                </article>
                <article class="about-mvv-card">
                    <div class="icon"><i class="fas fa-heart"></i></div>
                    <h3>Nos valeurs</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Professionnalisme et fiabilité</li>
                        <li><i class="fas fa-check"></i> Transparence et engagement</li>
                        <li><i class="fas fa-check"></i> Qualité de service</li>
                        <li><i class="fas fa-check"></i> Innovation et résultats</li>
                        <li><i class="fas fa-check"></i> Orientation client</li>
                    </ul>
                </article>
            </div>
        </div>
    </div>

    {{-- Histoire + Timeline --}}
    <div class="about-history-section">
        <div class="container about-history-grid">
            <div class="about-history-content">
                <h2 class="about-section-title left">Notre histoire</h2>
                <p>
                    MARINA a été créée pour répondre aux besoins croissants des entreprises en solutions
                    numériques, en communication et en services opérationnels à l'international.
                </p>
                <p>
                    Aujourd'hui, nous accompagnons nos clients sur quatre pôles forts : le développement IT,
                    la communication digitale, l'immobilier &amp; BTP, et la sous-traitance internationale.
                </p>
                <a href="{{ url('/#services') }}" class="btn btn-outline-green">
                    Découvrir nos services <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="about-timeline">
                <div class="about-timeline-line" aria-hidden="true"></div>
                <div class="about-timeline-items">
                    <div class="about-timeline-item">
                        <div class="about-timeline-icon"><i class="fas fa-flag"></i></div>
                        <span class="about-timeline-year">2014</span>
                        <span class="about-timeline-label">Création de l'entreprise</span>
                    </div>
                    <div class="about-timeline-item">
                        <div class="about-timeline-icon"><i class="fas fa-laptop-code"></i></div>
                        <span class="about-timeline-year">2016</span>
                        <span class="about-timeline-label">Lancement des services IT</span>
                    </div>
                    <div class="about-timeline-item">
                        <div class="about-timeline-icon"><i class="fas fa-bullhorn"></i></div>
                        <span class="about-timeline-year">2018</span>
                        <span class="about-timeline-label">Pôle communication digitale</span>
                    </div>
                    <div class="about-timeline-item">
                        <div class="about-timeline-icon"><i class="fas fa-hard-hat"></i></div>
                        <span class="about-timeline-year">2021</span>
                        <span class="about-timeline-label">Immobilier &amp; BTP</span>
                    </div>
                    <div class="about-timeline-item">
                        <div class="about-timeline-icon"><i class="fas fa-globe"></i></div>
                        <span class="about-timeline-year">Aujourd'hui</span>
                        <span class="about-timeline-label">Sous-traitance internationale</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Axes d'action --}}
    <div class="about-axes-section">
        <div class="container">
            <h2 class="about-section-title">Nos domaines d'expertise</h2>
            <div class="about-axes-grid">
                <article class="about-axis-card">
                    <div class="icon"><i class="fas fa-laptop-code"></i></div>
                    <h3>Développement IT</h3>
                    <p>Applications web, mobile, desktop, formation informatique et solutions sur mesure.</p>
                </article>
                <article class="about-axis-card">
                    <div class="icon"><i class="fas fa-bullhorn"></i></div>
                    <h3>Communication Digitale</h3>
                    <p>Stratégie, réseaux sociaux, contenu et visibilité pour votre marque.</p>
                </article>
                <article class="about-axis-card">
                    <div class="icon"><i class="fas fa-hard-hat"></i></div>
                    <h3>Immobilier &amp; BTP</h3>
                    <p>Conseil, suivi de chantiers et opportunités immobilières.</p>
                </article>
                <article class="about-axis-card">
                    <div class="icon"><i class="fas fa-globe"></i></div>
                    <h3>Sous-traitance internationale</h3>
                    <p>Missions remote et mise en relation avec des talents qualifiés.</p>
                </article>
                <article class="about-axis-card">
                    <div class="icon"><i class="fas fa-briefcase"></i></div>
                    <h3>Business Center</h3>
                    <p>Approvisionnement, importation et livraison de produits au Togo et à l'international.</p>
                </article>
            </div>
        </div>
    </div>

    {{-- Impact en chiffres --}}
    <div class="about-impact-section">
        <div class="container">
            <h2 class="about-section-title">MARINA en chiffres</h2>
            <div class="about-impact-grid">
                <div class="about-impact-card">
                    <i class="fas fa-laptop-code"></i>
                    <strong>80+</strong>
                    <span>Applications &amp; sites livrés</span>
                </div>
                <div class="about-impact-card">
                    <i class="fas fa-bullhorn"></i>
                    <strong>150+</strong>
                    <span>Campagnes digitales</span>
                </div>
                <div class="about-impact-card">
                    <i class="fas fa-building"></i>
                    <strong>60+</strong>
                    <span>Projets immobilier &amp; BTP</span>
                </div>
                <div class="about-impact-card">
                    <i class="fas fa-globe"></i>
                    <strong>200+</strong>
                    <span>Missions de sous-traitance</span>
                </div>
                <div class="about-impact-card">
                    <i class="fas fa-graduation-cap"></i>
                    <strong>3 500+</strong>
                    <span>Personnes formées</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Partenaires --}}
    <div class="about-partners-section">
        <div class="container">
            <h2 class="about-section-title">Ils nous font confiance</h2>
            <div class="about-partners-row">
                <span class="about-partner-logo">Entreprises</span>
                <span class="about-partner-logo">Startups</span>
                <span class="about-partner-logo">PME</span>
                <span class="about-partner-logo">Institutions</span>
                <span class="about-partner-logo">Diaspora</span>
                <span class="about-partner-more">Et de nombreux clients au Togo et à l'international</span>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="about-cta-section">
        <div class="container">
            <div class="about-cta-box">
                <div class="about-cta-media">
                    <img src="{{ asset('images/apropos/cta-heart.jpg') }}" alt="Développement d'applications avec MARINA">
                </div>
                <div class="about-cta-content">
                    <h2>Travaillez avec MARINA</h2>
                    <p>Parlons de votre projet IT, digital, immobilier ou de sous-traitance.</p>
                </div>
                <div class="about-cta-actions">
                    <a href="{{ url('/partenaires') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Devenir partenaire
                    </a>
                    <a href="{{ url('/contact') }}" class="btn btn-outline-green">
                        <i class="fas fa-phone"></i> Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
