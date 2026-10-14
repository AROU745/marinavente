@extends('layouts.app')

@section('content')
<section class="boutique-hero">
    <div class="container">
        <div class="boutique-success-box">
            <div class="boutique-success-icon"><i class="fas fa-check"></i></div>
            <p class="boutique-eyebrow">Demande enregistrée</p>
            <h1>{{ $info['nom'] }}</h1>
            <p class="boutique-lead" style="margin: 0 auto 1.25rem;">
                Votre boutique a été générée avec {{ $info['produits'] }} produit(s).
                @if (!empty($info['email']))
                    Le lien unique de votre catalogue sera envoyé par e-mail à
                    <strong>{{ $info['email'] }}</strong> par l’équipe MARINA.
                @else
                    L’équipe MARINA vous communiquera le lien de votre catalogue (téléphone ou e-mail si fourni plus tard).
                @endif
            </p>
            <p class="boutique-lead" style="margin: 0 auto 1.5rem; font-size: 0.92rem;">
                Vous n’avez rien d’autre à faire pour le moment.
            </p>
            <a href="{{ url('/') }}" class="btn btn-boutique">
                <i class="fas fa-home"></i> Retour à l’accueil
            </a>
        </div>
    </div>
</section>
@endsection
