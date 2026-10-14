@extends('layouts.app')

@section('content')
<section class="auth-page">
    <div class="container">
        <div class="auth-form-box">
            <p class="boutique-eyebrow">Espace administrateur</p>
            <h1>Connexion</h1>

            @if (session('error'))
                <div class="boutique-form-errors" role="alert">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <p class="auth-success">{{ session('success') }}</p>
            @endif
            @if ($errors->any())
                <div class="boutique-form-errors" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="post">
                @csrf
                <div class="boutique-field">
                    <label for="email">E-mail ou identifiant</label>
                    <input type="text" id="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="marina ou marina@admin.local">
                    <small>Compte par défaut : marina / marina4321</small>
                </div>
                <div class="boutique-field">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>
                <label class="auth-remember">
                    <input type="checkbox" name="remember" value="1"> Se souvenir de moi
                </label>
                <button type="submit" class="btn btn-boutique auth-submit">
                    <i class="fas fa-lock"></i> Se connecter
                </button>
            </form>

            <p class="auth-switch">
                Pas de compte admin ? <a href="{{ route('admin.register') }}">S’inscrire</a><br>
                <a href="{{ route('user.login') }}">Connexion utilisateur</a>
            </p>
        </div>
    </div>
</section>
@endsection
