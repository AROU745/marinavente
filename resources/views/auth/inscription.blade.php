@extends('layouts.app')

@section('content')
<section class="auth-page">
    <div class="container">
        <div class="auth-form-box">
            <p class="boutique-eyebrow">Créer un compte</p>
            <h1>Inscription</h1>
            <p class="auth-lead" style="margin-bottom:1.25rem;">Renseignez vos informations pour créer votre compte.</p>

            @if ($errors->any())
                <div class="boutique-form-errors" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('inscription.submit') }}" method="post">
                @csrf
                <div class="boutique-field">
                    <label for="name">Nom d’utilisateur</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="120" autocomplete="username">
                </div>
                <div class="boutique-field">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                </div>
                <div class="boutique-field">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password">
                </div>
                <div class="boutique-field">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                </div>
                <button type="submit" class="btn btn-boutique auth-submit">
                    <i class="fas fa-user-plus"></i> S’inscrire
                </button>
            </form>

            <p class="auth-switch">
                Déjà un compte ? <a href="{{ route('connexion') }}">Se connecter</a>
            </p>
        </div>
    </div>
</section>
@endsection
