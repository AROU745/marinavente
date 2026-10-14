@extends('layouts.app')

@section('content')
<section class="auth-page">
    <div class="container">
        <div class="auth-form-box" style="max-width:560px;text-align:left;">
            @if (session('success'))
                <p class="auth-success">{{ session('success') }}</p>
            @endif
            <p class="boutique-eyebrow">Espace utilisateur</p>
            <h1>Bonjour, {{ $user->name }}</h1>
            <p class="auth-lead" style="margin:0 0 1.5rem;">{{ $user->email }}</p>

            <div class="auth-dash-actions">
                <a href="{{ route('boutiques.create') }}" class="btn btn-boutique">
                    <i class="fas fa-store"></i> Créer mon site
                </a>
                <a href="{{ url('/contact') }}" class="btn btn-outline">Nous contacter</a>
                <form action="{{ route('logout') }}" method="post" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline">Déconnexion</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
