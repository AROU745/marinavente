<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $metaDescription ?? 'MARINA — Développement IT, communication digitale, immobilier & BTP et sous-traitance internationale' }}">
    <title>{{ $title ?? 'MARINA' }} — Services professionnels</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    <link rel="stylesheet" href="{{ asset('css/projets-env.css') }}">
    <link rel="stylesheet" href="{{ asset('css/soutenir.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partenaires.css') }}">
    <link rel="stylesheet" href="{{ asset('css/apropos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
    <link rel="stylesheet" href="{{ asset('css/formations.css') }}">
    <link rel="stylesheet" href="{{ asset('css/offreemplois.css') }}">
    <link rel="stylesheet" href="{{ asset('css/digitalservice.css') }}">
    <link rel="stylesheet" href="{{ asset('css/business-center.css') }}">
    <link rel="stylesheet" href="{{ asset('css/business-intl.css') }}">
    <link rel="stylesheet" href="{{ asset('css/business-catalogue.css') }}">
    <link rel="stylesheet" href="{{ asset('css/business-commande.css') }}">
    <link rel="stylesheet" href="{{ asset('css/immobilier-btp.css') }}">
    <link rel="stylesheet" href="{{ asset('css/immobilier-biens.css') }}">
    <link rel="stylesheet" href="{{ asset('css/immobilier-bien-show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sous-traitance.css') }}">
    <link rel="stylesheet" href="{{ asset('css/brand.css') }}">
    <link rel="stylesheet" href="{{ asset('css/boutiques.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="{{ $bodyClass ?? '' }}"@if (!empty($bodyBgColor)) style="background-color: {{ $bodyBgColor }};"@endif>
    @unless (!empty($hideSiteHeader))
        @include('partials.header')
    @endunless

    <main>
        @yield('content')
    </main>

    @unless (!empty($hideSiteFooter))
        @include('partials.footer')
    @endunless

    <script>
        const toggle = document.getElementById('menuToggle');
        const nav = document.getElementById('navMenu');
        if (toggle && nav) {
            toggle.addEventListener('click', () => nav.classList.toggle('open'));
        }
    </script>
    <script src="{{ asset('js/nav-scroll.js') }}"></script>
    @stack('scripts')
</body>
</html>
