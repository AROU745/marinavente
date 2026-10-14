@extends('layouts.app')

@section('content')
<section class="contact-page">
    {{-- Hero --}}
    <div class="contact-hero-wrap">
        <div class="container">
            <nav class="pe-breadcrumb" aria-label="Fil d'Ariane">
                <a href="{{ url('/') }}">Accueil</a>
                <span>&gt;</span>
                <strong>Contact</strong>
            </nav>

            <div class="contact-hero-grid">
                <div class="contact-hero-content">
                    <p class="contact-eyebrow">Contactez-nous</p>
                    <h1>Nous sommes à votre écoute</h1>
                    <p class="contact-lead">
                        Une question, un projet, une envie de collaborer ? Notre équipe vous répond
                        rapidement pour avancer ensemble sur vos besoins IT, digitaux, immobiliers ou business.
                    </p>
                    <div class="contact-hero-actions">
                        <a href="tel:+22890000000" class="btn btn-primary">
                            <i class="fas fa-phone"></i> Nous appeler
                        </a>
                        <a href="mailto:contact@marinagradepsf.org" class="btn btn-outline-green">
                            <i class="fas fa-envelope"></i> Envoyer un email
                        </a>
                    </div>
                </div>
                <div class="contact-hero-visual">
                    <img src="{{ asset('images/contact/hero-blocks.jpg') }}" alt="Restons en contact">
                </div>
            </div>
        </div>
    </div>

    {{-- Infos cards --}}
    <div class="container contact-info-section">
        <div class="contact-info-grid">
            <article class="contact-info-card">
                <div class="icon"><i class="fas fa-phone"></i></div>
                <h3>Téléphone</h3>
                <p class="contact-info-main">+228 90 XX XX XX</p>
                <span>Lun – Ven : 8h00 – 17h00</span>
            </article>
            <article class="contact-info-card">
                <div class="icon"><i class="fas fa-envelope"></i></div>
                <h3>Email</h3>
                <p class="contact-info-main">contact@marinagradepsf.org</p>
                <span>Nous répondons sous 24h</span>
            </article>
            <article class="contact-info-card">
                <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                <h3>Adresse</h3>
                <p class="contact-info-main">Lomé, République Togolaise</p>
                <span>Quartier Agoè, Lomé – Togo</span>
            </article>
            <article class="contact-info-card">
                <div class="icon"><i class="fas fa-clock"></i></div>
                <h3>Horaires</h3>
                <p class="contact-info-main">Lun – Ven : 8h00 – 17h00</p>
                <span>Sam : 9h00 – 13h00</span>
            </article>
            <article class="contact-info-card">
                <div class="icon"><i class="fas fa-share-alt"></i></div>
                <h3>Réseaux sociaux</h3>
                <div class="contact-socials">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </article>
        </div>
    </div>

    {{-- Formulaire + Carte --}}
    <div class="container contact-main-section">
        <div class="contact-main-grid">
            <div class="contact-form-box">
                <h2>Envoyez-nous un message</h2>
                <p class="contact-form-intro">
                    Remplissez le formulaire ci-dessous, nous vous répondrons dans les plus brefs délais.
                </p>

                <form class="contact-form" id="contactForm" action="#" method="post" onsubmit="return false;">
                    <div class="contact-form-row">
                        <div class="contact-field">
                            <label for="nom">Nom complet *</label>
                            <input type="text" id="nom" name="nom" required placeholder="Votre nom">
                        </div>
                        <div class="contact-field">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required placeholder="vous@email.com">
                        </div>
                    </div>

                    <div class="contact-form-row">
                        <div class="contact-field">
                            <label for="telephone">Téléphone</label>
                            <input type="tel" id="telephone" name="telephone" placeholder="+228 XX XX XX XX">
                        </div>
                        <div class="contact-field">
                            <label for="sujet">Sujet *</label>
                            <select id="sujet" name="sujet" required>
                                <option value="">Sélectionnez un sujet</option>
                                <option value="information">Demande d'information</option>
                                <option value="partenariat">Partenariat</option>
                                <option value="don">Don / Soutien</option>
                                <option value="benevolat">Bénévolat</option>
                                <option value="emploi">Emploi / Formation</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                    </div>

                    <div class="contact-field">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="6" required placeholder="Écrivez votre message..."></textarea>
                    </div>

                    <label class="contact-check">
                        <input type="checkbox" name="consent" required>
                        <span>J'accepte que mes données soient utilisées pour me recontacter. *</span>
                    </label>

                    <button type="submit" class="btn btn-primary contact-submit">
                        <i class="fas fa-paper-plane"></i> Envoyer le message
                    </button>

                    <div class="contact-feedback" id="contactFeedback" hidden></div>
                </form>
            </div>

            <aside class="contact-map-box">
                <h2>Où nous trouver</h2>
                <div class="contact-map">
                    <iframe
                        title="Carte MARINA — Lomé"
                        src="https://www.openstreetmap.org/export/embed.html?bbox=1.1800%2C6.1800%2C1.2800%2C6.2600&amp;layer=mapnik&amp;marker=6.2200%2C1.2300"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
                <div class="contact-bureau">
                    <h3>Notre bureau</h3>
                    <p><strong>MARINA</strong></p>
                    <p>Quartier Agoè, Lomé — République Togolaise</p>
                    <p class="contact-repere">Repère : Près du marché d'Agoè, à proximité de la grande mosquée.</p>
                </div>
            </aside>
        </div>
    </div>

    {{-- FAQ --}}
    <div class="contact-faq-section">
        <div class="container">
            <h2 class="contact-faq-title">Questions fréquentes</h2>
            <div class="contact-faq-list" id="contactFaq">
                <details class="contact-faq-item">
                    <summary>
                        Comment puis-je faire appel à vos services ?
                        <i class="fas fa-chevron-right"></i>
                    </summary>
                    <p>
                        Contactez-nous via le formulaire, par email ou téléphone. Nous analysons votre besoin
                        et vous proposons une solution adaptée (IT, digital, immobilier &amp; BTP ou sous-traitance).
                    </p>
                </details>
                <details class="contact-faq-item">
                    <summary>
                        Comment devenir partenaire de MARINA ?
                        <i class="fas fa-chevron-right"></i>
                    </summary>
                    <p>
                        Rendez-vous sur la page
                        <a href="{{ url('/partenaires') }}">Devenir partenaire</a>
                        et remplissez le formulaire. Notre équipe vous recontactera rapidement.
                    </p>
                </details>
                <details class="contact-faq-item">
                    <summary>
                        Quels sont vos domaines d'expertise ?
                        <i class="fas fa-chevron-right"></i>
                    </summary>
                    <p>
                        MARINA est une entreprise spécialisée en développement IT, communication digitale,
                        immobilier &amp; BTP et sous-traitance internationale, avec aussi formation et business center.
                    </p>
                </details>
                <details class="contact-faq-item">
                    <summary>
                        Dans quels délais recevrai-je une réponse ?
                        <i class="fas fa-chevron-right"></i>
                    </summary>
                    <p>
                        Nous nous engageons à répondre sous 24 heures ouvrées pour les messages
                        reçus via le formulaire ou par email.
                    </p>
                </details>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="contact-cta-section">
        <div class="container">
            <div class="contact-cta-box">
                <div class="contact-cta-media">
                    <img src="{{ asset('images/contact/cta-plant.jpg') }}" alt="Solutions IT et digitales MARINA">
                </div>
                <div class="contact-cta-content">
                    <h2>Parlons de votre projet</h2>
                    <p>IT, communication digitale, immobilier &amp; BTP ou sous-traitance : notre équipe est à votre écoute.</p>
                </div>
                <a href="{{ url('/contact') }}#contactForm" class="btn btn-primary">
                    <i class="fas fa-envelope"></i> Nous écrire
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (function () {
        const form = document.getElementById('contactForm');
        const feedback = document.getElementById('contactFeedback');
        if (!form || !feedback) return;

        form.addEventListener('submit', function () {
            feedback.hidden = false;
            feedback.className = 'contact-feedback is-success';
            feedback.textContent = 'Merci ! Votre message a bien été envoyé. Nous vous répondrons sous 24h.';
            form.reset();
        });
    })();
</script>
@endpush
