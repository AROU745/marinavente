@extends('layouts.app')

@section('content')
<section class="part-page">
    <div class="container pe-breadcrumb">
        <a href="{{ url('/') }}">Accueil</a>
        <span>/</span>
        <strong>Nos partenariats</strong>
    </div>

    {{-- Hero --}}
    <div class="part-hero" style="background-image:url('{{ asset('images/projets/hero-seedling.jpg') }}')">
        <div class="container part-hero-inner">
            <p class="part-eyebrow">Devenir partenaire</p>
            <h1>Construisons ensemble des partenariats business</h1>
            <p class="part-lead">
                MARINA collabore avec des entreprises, startups et institutions pour développer
                des projets IT, digitaux, immobiliers et de sous-traitance internationale.
            </p>
            <div class="part-values">
                <div>
                    <i class="fas fa-shield-alt"></i>
                    <strong>Transparence</strong>
                    <span>Suivi régulier et rapports détaillés</span>
                </div>
                <div>
                    <i class="fas fa-chart-line"></i>
                    <strong>Impact mesurable</strong>
                    <span>Des résultats concrets et suivis</span>
                </div>
                <div>
                    <i class="fas fa-handshake"></i>
                    <strong>Engagement mutuel</strong>
                    <span>Un partenariat basé sur la confiance</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Types de partenaires --}}
    <div class="container part-types">
        <h2>Nos formes de partenariat</h2>
        <div class="part-types-grid">
            <article class="part-type-card">
                <div class="part-type-icon"><i class="fas fa-heart"></i></div>
                <h3>Partenaire Financier</h3>
                <p>Soutenez nos projets par un investissement ou un financement pour accélérer la croissance.</p>
                <a href="#demande" class="btn btn-outline-green btn-sm" data-type="financier">En savoir plus</a>
            </article>
            <article class="part-type-card">
                <div class="part-type-icon"><i class="fas fa-headset"></i></div>
                <h3>Partenaire Technique</h3>
                <p>Apportez votre expertise, vos compétences ou un accompagnement opérationnel.</p>
                <a href="#demande" class="btn btn-outline-green btn-sm" data-type="technique">En savoir plus</a>
            </article>
            <article class="part-type-card">
                <div class="part-type-icon"><i class="fas fa-gift"></i></div>
                <h3>Partenaire en Nature</h3>
                <p>Donnez du matériel, des espaces ou des ressources pour renforcer nos opérations.</p>
                <a href="#demande" class="btn btn-outline-green btn-sm" data-type="nature">En savoir plus</a>
            </article>
            <article class="part-type-card">
                <div class="part-type-icon"><i class="fas fa-bullhorn"></i></div>
                <h3>Partenaire Média</h3>
                <p>Amplifiez notre message et valorisez nos services auprès de vos audiences.</p>
                <a href="#demande" class="btn btn-outline-green btn-sm" data-type="media">En savoir plus</a>
            </article>
            <aside class="part-quote">
                <i class="fas fa-quote-left"></i>
                <p>Un partenariat solide est la clé d'une croissance partagée et durable.</p>
            </aside>
        </div>
    </div>

    {{-- Formulaire --}}
    <div class="container part-form-section" id="demande">
        <div class="part-form-layout">
            <div class="part-form-box">
                <h2>Demande de partenariat</h2>
                <p class="part-form-intro">
                    Remplissez le formulaire ci-dessous, notre équipe vous recontactera dans les plus brefs délais.
                </p>

                <form class="part-form" id="partnershipForm" action="#" method="post" onsubmit="return false;">
                    <div class="part-form-row">
                        <div class="part-field">
                            <label for="typePartenariat">Type de partenariat souhaité</label>
                            <select id="typePartenariat" name="type">
                                <option value="">Sélectionnez</option>
                                <option value="financier" {{ ($type ?? '') === 'financier' ? 'selected' : '' }}>Partenaire Financier</option>
                                <option value="technique" {{ ($type ?? '') === 'technique' ? 'selected' : '' }}>Partenaire Technique</option>
                                <option value="nature" {{ ($type ?? '') === 'nature' ? 'selected' : '' }}>Partenaire en Nature</option>
                                <option value="media" {{ ($type ?? '') === 'media' ? 'selected' : '' }}>Partenaire Média</option>
                            </select>
                        </div>
                        <div class="part-field">
                            <label for="organisation">Nom de votre organisation / entreprise / votre nom</label>
                            <input type="text" id="organisation" name="organisation" placeholder="Organisation ou votre nom">
                        </div>
                    </div>

                    <div class="part-form-row">
                        <div class="part-field">
                            <label for="contact">Nom du contact</label>
                            <input type="text" id="contact" name="contact">
                        </div>
                        <div class="part-field">
                            <label for="fonction">Fonction</label>
                            <input type="text" id="fonction" name="fonction">
                        </div>
                    </div>

                    <div class="part-form-row">
                        <div class="part-field">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="part-field">
                            <label for="telephone">Téléphone</label>
                            <input type="tel" id="telephone" name="telephone" placeholder="+228 90 00 00 00">
                        </div>
                    </div>

                    <div class="part-form-row">
                        <div class="part-field">
                            <label for="pays">Pays</label>
                            <select id="pays" name="pays">
                                <option value="Togo" selected>Togo</option>
                                <option value="Benin">Bénin</option>
                                <option value="Ghana">Ghana</option>
                                <option value="France">France</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        <div class="part-field">
                            <label for="ville">Ville</label>
                            <input type="text" id="ville" name="ville">
                        </div>
                    </div>

                    <div class="part-field">
                        <label for="siteweb">Site web (si disponible)</label>
                        <input type="url" id="siteweb" name="siteweb" placeholder="https://">
                    </div>

                    <div class="part-field">
                        <label for="pourquoi">Pourquoi souhaitez-vous devenir partenaire ?</label>
                        <textarea id="pourquoi" name="pourquoi" rows="4"></textarea>
                    </div>

                    <div class="part-field">
                        <label for="contribuer">Comment souhaitez-vous contribuer ?</label>
                        <textarea id="contribuer" name="contribuer" rows="4"></textarea>
                    </div>

                    <div class="part-field">
                        <label for="fichier">Pièces jointes (facultatif)</label>
                        <div class="part-file">
                            <input type="file" id="fichier" name="fichier" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <span>PDF, DOC, JPG… Max 5 Mo</span>
                        </div>
                    </div>

                    <label class="part-check">
                        <input type="checkbox" name="accept">
                        J'accepte que mes informations soient utilisées pour la gestion de ma demande de partenariat.
                    </label>

                    <button type="submit" class="btn btn-primary part-submit">
                        <i class="fas fa-paper-plane"></i> Envoyer ma demande
                    </button>
                    <p class="part-form-msg" id="partFormMsg" hidden></p>
                </form>
            </div>

            <aside class="part-side">
                <div class="part-side-card">
                    <h3>Pourquoi devenir partenaire ?</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Impact concret et mesurable</li>
                        <li><i class="fas fa-check"></i> Image / RSE valorisée</li>
                        <li><i class="fas fa-check"></i> Visibilité auprès de nos audiences</li>
                        <li><i class="fas fa-check"></i> Transparence et reporting</li>
                        <li><i class="fas fa-check"></i> Accès à un réseau engagé</li>
                    </ul>
                </div>

                <div class="part-side-card">
                    <h3>Notre engagement</h3>
                    <div class="part-engage">
                        <div><i class="fas fa-cogs"></i><div><strong>Transparence totale</strong><span>Processus clair et suivi régulier</span></div></div>
                        <div><i class="fas fa-chart-line"></i><div><strong>Suivi &amp; Évaluation</strong><span>Rapports d'impact détaillés</span></div></div>
                        <div><i class="fas fa-award"></i><div><strong>Reconnaissance</strong><span>Mise en avant de votre engagement</span></div></div>
                    </div>
                </div>

                <div class="part-side-card part-side-contact">
                    <h3>Besoin d'informations ?</h3>
                    <p><i class="fas fa-phone"></i> +228 90 XX XX XX</p>
                    <p><i class="fas fa-envelope"></i> partenariat@marinagradepsf.org</p>
                    <p><i class="fas fa-map-marker-alt"></i> Lomé, Togo</p>
                    <a href="{{ url('/contact') }}" class="btn btn-outline-green btn-sm">Nous contacter</a>
                </div>
            </aside>
        </div>
    </div>

    {{-- CTA --}}
    <div class="pe-cta-band">
        <div class="container pe-cta-inner">
            <div class="pe-cta-icon"><i class="fas fa-hand-holding-heart"></i></div>
            <div class="pe-cta-text">
                <strong>Ensemble, développons des projets ambitieux.</strong>
                Chaque partenariat renforce notre capacité à livrer des services de qualité.
            </div>
            <a href="{{ url('/#services') }}" class="btn btn-primary">
                Découvrir nos services <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    document.querySelectorAll('a[data-type]').forEach((a) => {
        a.addEventListener('click', () => {
            const sel = document.getElementById('typePartenariat');
            if (sel) sel.value = a.dataset.type || '';
        });
    });

    const form = document.getElementById('partnershipForm');
    const msg = document.getElementById('partFormMsg');
    if (!form) return;

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        msg.hidden = false;
        msg.textContent = 'Merci ! Votre demande de partenariat a bien été enregistrée. Notre équipe vous recontactera rapidement.';
        form.reset();
    });
})();
</script>
@endpush
