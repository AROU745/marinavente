@extends('layouts.app')

@section('content')
<section class="auth-page">
    <div class="container">
        <div class="auth-form-box">
            <p class="boutique-eyebrow">Accès sécurisé</p>
            <h1>Connexion</h1>
            <p class="auth-lead" style="margin-bottom:1.25rem;">Entrez votre nom d’utilisateur et votre mot de passe.</p>

            @if ($errors->any())
                <div class="boutique-form-errors" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('connexion.submit') }}" method="post">
                @csrf
                <div class="boutique-field">
                    <label for="username">Nom d’utilisateur</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autocomplete="username" placeholder="Votre identifiant ou e-mail">
                </div>
                <div class="boutique-field">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Votre mot de passe">
                </div>
                <label class="auth-remember">
                    <input type="checkbox" name="remember" value="1"> Se souvenir de moi
                </label>
                <button type="submit" class="btn btn-boutique auth-submit">
                    <i class="fas fa-right-to-bracket"></i> Se connecter
                </button>
            </form>

            <p class="auth-switch">
                Pas encore de compte ? <a href="{{ route('inscription') }}">S’inscrire</a>
            </p>
        </div>
    </div>
</section>
@endsection
