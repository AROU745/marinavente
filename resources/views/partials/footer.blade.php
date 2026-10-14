<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="{{ url('/') }}" class="logo">
                    <img src="{{ asset('images/logo-marina.png') }}" alt="MARINA" class="logo-marina">
                </a>
                <p>
                    Entreprise de développement IT, communication digitale,
                    immobilier &amp; BTP et sous-traitance internationale.
                </p>
                <div class="socials">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="X / Twitter"><i class="fab fa-x-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Liens rapides</h4>
                <ul>
                    <li><a href="{{ url('/') }}">Accueil</a></li>
                    <li><a href="{{ request()->is('/') ? '#services' : url('/#services') }}">Services</a></li>
                    <li><a href="{{ url('/apropos') }}">À propos</a></li>
                    <li><a href="{{ url('/contact') }}">Contact</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Nos services</h4>
                <ul>
                    <li><a href="{{ url('/services/formation-informatique') }}">Formation Informatique</a></li>
                    <li><a href="{{ url('/services/trouver-un-emploi') }}">Trouver un emploi</a></li>
                    <li><a href="{{ url('/services/communication-digitale') }}">Communication Digitale</a></li>
                    <li><a href="{{ url('/services/business-center') }}">Business Center</a></li>
                    <li><a href="{{ url('/services/sous-traitance') }}">Sous-traitance</a></li>
                    <li><a href="{{ url('/services/conception-application') }}">Conception d'application</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contact</h4>
                <ul class="contact-list">
                    <li><i class="fas fa-phone"></i> <span>+228 XX XX XX XX</span></li>
                    <li><i class="fas fa-envelope"></i> <span>contact@marinagradepsf.org</span></li>
                    <li><i class="fas fa-map-marker-alt"></i> <span>Lomé, Togo</span></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} MARINA. Tous droits réservés.</p>
            <nav>
                <a href="#">Mentions légales</a>
                <a href="#">Politique de confidentialité</a>
            </nav>
        </div>
    </div>
</footer>
