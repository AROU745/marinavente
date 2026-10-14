@extends('layouts.app')

@section('content')
<section class="auth-page">
    <div class="container">
        <div class="auth-form-box">
            <p class="boutique-eyebrow">Espace utilisateur</p>
            <h1>Connexion</h1>

            @if ($errors->any())
                <div class="boutique-form-errors" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.login.submit') }}" method="post">
                @csrf
                <div class="boutique-field">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                </div>
                <div class="boutique-field">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>
                <label class="auth-remember">
                    <input type="checkbox" name="remember" value="1"> Se souvenir de moi
                </label>
                <button type="submit" class="btn btn-boutique auth-submit">Se connecter</button>
            </form>

            <p class="auth-switch">
                Pas de compte ? <a href="{{ route('user.register') }}">S’inscrire</a><br>
                <a href="{{ route('admin.login') }}">Connexion administrateur</a>
            </p>
        </div>
    </div>
</section>
@endsection
