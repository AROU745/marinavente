<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MARINA — Entreprise de développement IT, communication digitale, immobilier & BTP et sous-traitance internationale.">
    <title>MARINA — IT, Digital, Immobilier & Sous-traitance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/brand.css') }}">
</head>
<body>

@include('partials.header')

{{-- ========== HERO CAROUSEL (aligné sur les 8 services) ========== --}}
<section class="hero" id="heroCarousel" aria-label="Nos services MARINA">
    <div class="hero-slides">
        <div class="hero-slide is-active" style="background-image:url('{{ asset('images/hero/informatique-ia.jpg') }}')" data-slide="0">
            <div class="container">
                <div class="hero-content">
                    <span class="hero-tag"><i class="fas fa-laptop-code"></i> Formation Informatique</span>
                    <h1>Formation <span class="highlight">Informatique</span></h1>
                    <p>Cours pratiques en bureautique, web, design et outils numériques pour tous niveaux.</p>
                    <div class="hero-actions">
                        <a href="{{ url('/services/formation-informatique') }}" class="btn btn-primary">Découvrir</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-slide" style="background-image:url('{{ asset('images/hero/talents.jpg') }}')" data-slide="1">
            <div class="container">
                <div class="hero-content">
                    <span class="hero-tag"><i class="fas fa-briefcase"></i> Trouver un emploi</span>
                    <h1>Trouver un <span class="highlight">emploi</span></h1>
                    <p>Accompagnement à l'insertion : CV, entretiens et mise en relation avec des employeurs.</p>
                    <div class="hero-actions">
                        <a href="{{ url('/services/trouver-un-emploi') }}" class="btn btn-primary">Découvrir</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-slide" style="background-image:url('{{ asset('images/conception/web.jpg') }}')" data-slide="2">
            <div class="container">
                <div class="hero-content">
                    <span class="hero-tag"><i class="fas fa-bullhorn"></i> Communication Digitale</span>
                    <h1>Communication <span class="highlight">Digitale</span></h1>
                    <p>Stratégie digitale, réseaux sociaux, contenu et visibilité pour votre entreprise.</p>
                    <div class="hero-actions">
                        <a href="{{ url('/services/communication-digitale') }}" class="btn btn-primary">Découvrir</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-slide" style="background-image:url('{{ asset('images/business-intl/hero-port.jpg') }}')" data-slide="3">
            <div class="container">
                <div class="hero-content">
                    <span class="hero-tag"><i class="fas fa-box"></i> Business Center</span>
                    <h1>Achat &amp; <span class="highlight">importation</span></h1>
                    <p>
                        Commandez des produits depuis la Chine, la Turquie, le Nigeria et le Togo.
                        Nous gérons l'approvisionnement, l'importation et la livraison rapide.
                    </p>
                    <div class="hero-actions">
                        <a href="{{ url('/services/business-center') }}" class="btn btn-primary">Découvrir</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-slide" style="background-image:url('{{ asset('images/immobilier/hero-villa.jpg') }}')" data-slide="4">
            <div class="container">
                <div class="hero-content">
                    <span class="hero-tag"><i class="fas fa-hard-hat"></i> Immobilier &amp; BTP</span>
                    <h1>Immobilier &amp; <span class="highlight">BTP</span></h1>
                    <p>Conseil, suivi de chantiers et opportunités immobilières pour projets durables.</p>
                    <div class="hero-actions">
                        <a href="{{ url('/services/immobilier-btp') }}" class="btn btn-primary">Découvrir</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-slide" style="background-image:url('{{ asset('images/sous-traitance/hero-map.jpg') }}')" data-slide="5">
            <div class="container">
                <div class="hero-content">
                    <span class="hero-tag"><i class="fas fa-globe"></i> Sous-traitance internationale</span>
                    <h1>Sous-traitance <span class="highlight">internationale</span></h1>
                    <p>Missions remote pour clients européens, valorisant les talents togolais.</p>
                    <div class="hero-actions">
                        <a href="{{ url('/services/sous-traitance') }}" class="btn btn-primary">Découvrir</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-slide" style="background-image:url('{{ asset('images/conception/hero-dev.jpg') }}')" data-slide="6">
            <div class="container">
                <div class="hero-content">
                    <span class="hero-tag"><i class="fas fa-laptop-code"></i> Conception d'application</span>
                    <h1>Conception d'application <span class="highlight">web, mobile et desktop</span></h1>
                    <p>Conception et développement d'applications sur mesure : web, mobile et logiciels desktop.</p>
                    <div class="hero-actions">
                        <a href="{{ url('/services/conception-application') }}" class="btn btn-primary">Découvrir</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="hero-nav hero-prev" type="button" aria-label="Slide précédent"><i class="fas fa-chevron-left"></i></button>
    <button class="hero-nav hero-next" type="button" aria-label="Slide suivant"><i class="fas fa-chevron-right"></i></button>

    <div class="hero-dots" role="tablist" aria-label="Navigation du carrousel">
        <button type="button" class="is-active" aria-label="Formation Informatique" data-goto="0"></button>
        <button type="button" aria-label="Trouver un emploi" data-goto="1"></button>
        <button type="button" aria-label="Communication Digitale" data-goto="2"></button>
        <button type="button" aria-label="Business Center" data-goto="3"></button>
        <button type="button" aria-label="Immobilier & BTP" data-goto="4"></button>
        <button type="button" aria-label="Sous-traitance internationale" data-goto="5"></button>
        <button type="button" aria-label="Conception d'application" data-goto="6"></button>
    </div>
</section>

{{-- ========== SERVICES ========== --}}
<section class="services" id="services">
    <div class="container">
        <div class="services-head">
            <span class="section-label">Nos services</span>
            <h2 class="section-title">Des solutions professionnelles pour <span>votre croissance</span></h2>
        </div>

        <div class="services-layout">
            <div class="services-grid">
                <article class="service-card">
                    <div class="service-icon blue"><i class="fas fa-laptop-code"></i></div>
                    <h3>Formation Informatique</h3>
                    <p>Cours pratiques en bureautique, web, design et outils numériques pour tous niveaux.</p>
                    <a href="{{ url('/services/formation-informatique') }}" class="link">Découvrir <i class="fas fa-arrow-right"></i></a>
                </article>

                <article class="service-card">
                    <div class="service-icon orange"><i class="fas fa-briefcase"></i></div>
                    <h3>Trouver un emploi</h3>
                    <p>Accompagnement à l'insertion : CV, entretiens et mise en relation avec des employeurs.</p>
                    <a href="{{ url('/services/trouver-un-emploi') }}" class="link">Découvrir <i class="fas fa-arrow-right"></i></a>
                </article>

                <article class="service-card">
                    <div class="service-icon purple"><i class="fas fa-bullhorn"></i></div>
                    <h3>Communication Digitale</h3>
                    <p>Stratégie digitale, réseaux sociaux, contenu et visibilité pour votre entreprise.</p>
                    <a href="{{ url('/services/communication-digitale') }}" class="link">Découvrir <i class="fas fa-arrow-right"></i></a>
                </article>

                <article class="service-card service-card-bc">
                    <span class="service-badge-new">Nouveau</span>
                    <div class="service-icon teal service-icon-bc">
                        <i class="fas fa-globe"></i>
                        <i class="fas fa-box"></i>
                    </div>
                    <h3>Business Center</h3>
                    <p>
                        Produits de qualité disponibles
                        <strong class="service-bc-highlight">à l'international et au Togo</strong>.
                        Nous vous accompagnons dans l'approvisionnement, l'importation et la livraison rapide.
                    </p>
                    <a href="{{ url('/services/business-center') }}" class="link">Découvrir <i class="fas fa-arrow-right"></i></a>
                </article>

                <article class="service-card">
                    <div class="service-icon red"><i class="fas fa-hard-hat"></i></div>
                    <h3>Immobilier & BTP</h3>
                    <p>Conseil, suivi de chantiers et opportunités immobilières pour projets durables.</p>
                    <a href="{{ url('/services/immobilier-btp') }}" class="link">Découvrir <i class="fas fa-arrow-right"></i></a>
                </article>

                <article class="service-card">
                    <div class="service-icon indigo"><i class="fas fa-globe"></i></div>
                    <h3>Sous-traitance internationale</h3>
                    <p>Missions remote pour clients européens, valorisant les talents togolais.</p>
                    <a href="{{ url('/services/sous-traitance') }}" class="link">Découvrir <i class="fas fa-arrow-right"></i></a>
                </article>

                <article class="service-card">
                    <div class="service-icon cyan"><i class="fas fa-laptop-code"></i></div>
                    <h3>Conception d'application web, mobile et desktop</h3>
                    <p>Conception et développement d'applications sur mesure : web, mobile et logiciels desktop.</p>
                    <a href="{{ url('/services/conception-application') }}" class="link">Découvrir <i class="fas fa-arrow-right"></i></a>
                </article>
            </div>

            <aside class="mission-panel" id="apropos">
                <div class="mission-icon"><i class="fas fa-bullseye"></i></div>
                <h3>Qui sommes-nous ?</h3>
                <p>
                    MARINA est une entreprise spécialisée en développement IT, communication digitale,
                    immobilier &amp; BTP et sous-traitance internationale. Nous accompagnons entreprises
                    et particuliers avec des solutions concrètes et professionnelles.
                </p>
                <a href="{{ url('/apropos') }}" class="btn btn-white">En savoir plus</a>
                <div class="mission-features">
                    <div class="mission-feature">
                        <i class="fas fa-laptop-code"></i>
                        <span>Développement IT</span>
                    </div>
                    <div class="mission-feature">
                        <i class="fas fa-bullhorn"></i>
                        <span>Communication digitale</span>
                    </div>
                    <div class="mission-feature">
                        <i class="fas fa-hard-hat"></i>
                        <span>Immobilier &amp; BTP</span>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- ========== OUTSOURCING ========== --}}
<section class="outsourcing" id="emploi">
    <div class="container">
        <div class="outsourcing-grid">
            <div class="outsourcing-left">
                <h2>Sous-traitance internationale : <span>l'Europe nous fait confiance</span>, le Togo en bénéficie</h2>
                <p class="outsourcing-intro">
                    Nous connectons des talents togolais à des entreprises européennes pour des missions
                    100&nbsp;% en ligne, sécurisées et rémunérées.
                </p>
                <ul class="check-list">
                    <li>
                        <span class="check-icon"><i class="fas fa-check"></i></span>
                        <span>Travail à distance flexible et accessible partout au Togo</span>
                    </li>
                    <li>
                        <span class="check-icon"><i class="fas fa-check"></i></span>
                        <span>Paiement sécurisé et garanti pour chaque mission</span>
                    </li>
                    <li>
                        <span class="check-icon"><i class="fas fa-check"></i></span>
                        <span>Accompagnement, formation et suivi personnalisé</span>
                    </li>
                    <li>
                        <span class="check-icon"><i class="fas fa-check"></i></span>
                        <span>Opportunités dans le digital, l'admin, le design et plus</span>
                    </li>
                </ul>
                <div class="map-wrap">
                    <img src="{{ asset('images/map.png') }}" alt="Carte du monde — Europe et Afrique">
                </div>
            </div>

            <div class="job-card">
                <h3>Vous cherchez un job en ligne ?</h3>
                <div class="job-steps">
                    <div class="job-step">
                        <span class="num">1</span>
                        <div>
                            <strong>Créez votre compte</strong>
                            <span>Inscription gratuite en quelques minutes</span>
                        </div>
                    </div>
                    <div class="job-step">
                        <span class="num">2</span>
                        <div>
                            <strong>Parcourez les missions</strong>
                            <span>Des offres adaptées à vos compétences</span>
                        </div>
                    </div>
                    <div class="job-step">
                        <span class="num">3</span>
                        <div>
                            <strong>Postulez et commencez</strong>
                            <span>Travaillez, livrez et soyez payé</span>
                        </div>
                    </div>
                </div>
                <a href="{{ url('/services/sous-traitance/inscription') }}" class="btn btn-primary btn-job">Je m'inscris gratuitement</a>
            </div>
        </div>

        <div class="devices-block">
            <div class="devices-visual">
                <img src="{{ asset('images/devices.png') }}" alt="Plateforme MARINA">
            </div>
            <div class="trust-row">
                <div class="trust-item">
                    <i class="fas fa-wifi"></i>
                    <strong>100% en ligne</strong>
                </div>
                <div class="trust-item">
                    <i class="fas fa-shield-alt"></i>
                    <strong>Missions sécurisées</strong>
                </div>
                <div class="trust-item">
                    <i class="fas fa-money-bill-wave"></i>
                    <strong>Paiement garanti</strong>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== STATS ========== --}}
<section class="stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <i class="fas fa-laptop-code"></i>
                <div>
                    <div class="num">+120</div>
                    <div class="label">Projets livrés</div>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-users"></i>
                <div>
                    <div class="num">+8500</div>
                    <div class="label">Clients &amp; apprenants</div>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-user-tie"></i>
                <div>
                    <div class="num">+300</div>
                    <div class="label">Entreprises accompagnées</div>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-handshake"></i>
                <div>
                    <div class="num">+50</div>
                    <div class="label">Partenaires business</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== PARTNERS ========== --}}
<section class="partners">
    <div class="container">
        <h2 class="section-title">Ils nous font confiance</h2>
        <div class="partners-row">
            <span class="partner-logo undp">UNDP</span>
            <span class="partner-logo unicef">UNICEF</span>
            <span class="partner-logo wb">THE WORLD BANK</span>
            <span class="partner-logo afd">AFD</span>
            <span class="partner-logo kfw">KfW</span>
            <span class="partner-logo plan">PLAN INTERNATIONAL</span>
        </div>
    </div>
</section>

{{-- ========== NEWSLETTER ========== --}}
<section class="newsletter" id="newsletter">
    <div class="container">
        <div class="newsletter-box">
            <div class="newsletter-content">
                <div class="icon-mail"><i class="fas fa-envelope"></i></div>
                <h2>Restez informé de nos services</h2>
                <p>Recevez nos actualités, offres et opportunités business directement par e-mail.</p>
                <form class="newsletter-form" action="#" method="post" onsubmit="return false;">
                    <input type="email" name="email" placeholder="Votre adresse e-mail" required>
                    <button type="submit" class="btn btn-white">S'abonner</button>
                </form>
            </div>
            <div class="newsletter-visual">
                <img src="{{ asset('images/newsletter-devices.png') }}" alt="Restez connecté">
            </div>
        </div>
    </div>
</section>

@include('partials.footer')

<script>
    const toggle = document.getElementById('menuToggle');
    const nav = document.getElementById('navMenu');
    if (toggle && nav) {
        toggle.addEventListener('click', () => nav.classList.toggle('open'));
    }

    (function () {
        const root = document.getElementById('heroCarousel');
        if (!root) return;

        const slides = Array.from(root.querySelectorAll('.hero-slide'));
        const dots = Array.from(root.querySelectorAll('.hero-dots button'));
        const prev = root.querySelector('.hero-prev');
        const next = root.querySelector('.hero-next');
        let index = 0;
        let timer;

        function goTo(i) {
            index = (i + slides.length) % slides.length;
            slides.forEach((s, n) => s.classList.toggle('is-active', n === index));
            dots.forEach((d, n) => d.classList.toggle('is-active', n === index));
        }

        function start() {
            stop();
            timer = setInterval(() => goTo(index + 1), 5500);
        }

        function stop() {
            if (timer) clearInterval(timer);
        }

        prev?.addEventListener('click', () => { goTo(index - 1); start(); });
        next?.addEventListener('click', () => { goTo(index + 1); start(); });
        dots.forEach((dot) => {
            dot.addEventListener('click', () => {
                goTo(Number(dot.dataset.goto));
                start();
            });
        });

        root.addEventListener('mouseenter', stop);
        root.addEventListener('mouseleave', start);

        goTo(0);
        start();
    })();
</script>
<script src="{{ asset('js/nav-scroll.js') }}"></script>
</body>
</html>
