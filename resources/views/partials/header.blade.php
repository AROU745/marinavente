@php
    $hideBoutiqueCta = request()->routeIs('boutiques.show');
    $authUser = auth()->user();
@endphp
<header class="site-header">
    <div class="container header-inner">
        <a href="{{ url('/') }}" class="logo">
            <img src="{{ asset('images/logo-marina.png') }}" alt="MARINA" class="logo-marina">
        </a>

        <button class="menu-toggle" id="menuToggle" aria-label="Menu">
            <i class="fas fa-bars"></i>
        </button>

        <nav class="nav-menu" id="navMenu">
            <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Accueil</a>
            <a href="{{ request()->is('/') ? '#services' : url('/#services') }}" class="nav-services">Services</a>
            <a href="{{ url('/apropos') }}" class="{{ request()->is('apropos') ? 'active' : '' }}">À propos</a>
            <a href="{{ url('/contact') }}" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a>
            @auth
                @if ($authUser->isAdmin())
                    <a href="{{ route('boutiques.admin') }}" class="{{ request()->routeIs('boutiques.admin') ? 'active' : '' }}">Admin</a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">Mon espace</a>
                @endif
            @endauth
            @guest
                <a href="{{ route('connexion') }}" class="nav-auth-mobile {{ request()->routeIs('connexion', 'user.login', 'admin.login') ? 'active' : '' }}">
                    <i class="fas fa-right-to-bracket"></i> Connexion
                </a>
                <a href="{{ route('inscription') }}" class="nav-auth-mobile {{ request()->routeIs('inscription', 'user.register', 'admin.register') ? 'active' : '' }}">
                    <i class="fas fa-user-plus"></i> Inscription
                </a>
            @endguest
            @unless ($hideBoutiqueCta)
                <a href="{{ route('boutiques.create') }}" class="nav-boutique-mobile {{ request()->routeIs('boutiques.create') ? 'active' : '' }}">
                    <i class="fas fa-store"></i> Créer mon site
                </a>
            @endunless
        </nav>

        <div class="header-actions">
            @guest
                <a href="{{ route('connexion') }}" class="btn btn-outline header-cta-secondary header-auth-btn">
                    <i class="fas fa-right-to-bracket"></i> Connexion
                </a>
                <a href="{{ route('inscription') }}" class="btn btn-outline header-cta-secondary header-auth-btn header-auth-register">
                    <i class="fas fa-user-plus"></i> Inscription
                </a>
            @else
                @if ($authUser->isAdmin())
                    <a href="{{ route('boutiques.admin') }}" class="btn btn-outline header-cta-secondary">Admin</a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline header-cta-secondary">Mon espace</a>
                @endif
                <form action="{{ $authUser->isAdmin() ? route('admin.logout') : route('logout') }}" method="post" class="header-logout-form">
                    @csrf
                    <button type="submit" class="btn btn-outline header-cta-secondary">Déconnexion</button>
                </form>
            @endguest
            @unless ($hideBoutiqueCta)
                <a href="{{ route('boutiques.create') }}" class="btn btn-boutique header-cta-boutique" title="Créer et gérer votre boutique en ligne">
                    <i class="fas fa-wand-magic-sparkles" aria-hidden="true"></i>
                    Créer mon site
                </a>
            @endunless
        </div>
    </div>
</header>
