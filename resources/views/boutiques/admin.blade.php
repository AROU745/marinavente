@extends('layouts.app')

@section('content')
<section class="boutique-hero">
    <div class="container" style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-end;gap:1rem;">
        <div>
            <p class="boutique-eyebrow">Administration MARINA</p>
            <h1>Boutiques créées</h1>
            <p class="boutique-lead">
                Créez, modifiez ou supprimez des boutiques. Consultez les liens catalogues.
            </p>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:0.65rem;align-items:center;">
            <a href="{{ route('boutiques.admin.commandes-ia') }}" class="btn btn-outline btn-commandes-ia-top">
                <i class="fas fa-wand-magic-sparkles"></i> Commandes IA
                @if (($totalNouvellesIa ?? 0) > 0)
                    <span class="notif-badge" title="Nouvelles commandes">{{ $totalNouvellesIa }}</span>
                @endif
            </a>
            <a href="{{ route('boutiques.admin.create') }}" class="btn btn-boutique">
                <i class="fas fa-plus"></i> Créer une boutique
            </a>
            <form action="{{ route('admin.logout') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-outline">Déconnexion</button>
            </form>
        </div>
    </div>
</section>

<div class="container boutique-admin-table-wrap">
    @if (session('success'))
        <p class="auth-success" style="margin-bottom:1.25rem;">{{ session('success') }}</p>
    @endif

    @if ($boutiques->isEmpty())
        <p class="boutique-lead">Aucune boutique pour le moment.</p>
        <a href="{{ route('boutiques.admin.create') }}" class="btn btn-boutique">
            <i class="fas fa-plus"></i> Créer la première boutique
        </a>
    @else
        <table class="boutique-admin-table">
            <thead>
                <tr>
                    <th>Boutique</th>
                    <th>Téléphone</th>
                    <th>E-mail</th>
                    <th>Produits</th>
                    <th>Lien catalogue</th>
                    <th>Créée le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($boutiques as $b)
                    @php
                        $sujet = rawurlencode('Votre boutique en ligne — ' . $b->nom_entreprise);
                        $corps = rawurlencode(
                            "Bonjour,\n\nVotre boutique \"" . $b->nom_entreprise . "\" est prête.\n\nVoici le lien de votre catalogue :\n" . $b->catalogueUrl() . "\n\nCordialement,\nL'équipe MARINA"
                        );
                        $mailto = $b->email
                            ? 'mailto:' . $b->email . '?subject=' . $sujet . '&body=' . $corps
                            : null;
                    @endphp
                    <tr>
                        <td><strong>{{ $b->nom_entreprise }}</strong></td>
                        <td>{{ $b->telephone }}</td>
                        <td>
                            @if ($b->email)
                                <a href="mailto:{{ $b->email }}">{{ $b->email }}</a>
                            @else
                                <span class="token">—</span>
                            @endif
                        </td>
                        <td>{{ $b->tous_produits_count }}</td>
                        <td>
                            <a href="{{ route('boutiques.show', $b->slug) }}" class="link-catalogue" target="_blank" rel="noopener">
                                {{ $b->catalogueUrl() }}
                            </a>
                        </td>
                        <td>{{ $b->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="admin-actions">
                                <a
                                    href="{{ route('boutiques.admin.commandes-ia', ['boutique' => $b->id]) }}"
                                    class="btn-admin-action btn-voir-commandes"
                                    title="Voir les commandes"
                                >
                                    <i class="fas fa-clipboard-list"></i>
                                    @if (($b->commandes_ia_nouvelles_count ?? 0) > 0)
                                        <span class="notif-badge" title="Nouvelle commande">{{ $b->commandes_ia_nouvelles_count }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('boutiques.show', $b->slug) }}" class="btn-admin-action" target="_blank" title="Voir la boutique">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('boutiques.admin.edit', $b) }}" class="btn-admin-action" title="Modifier">
                                    <i class="fas fa-pen"></i>
                                </a>
                                @if ($mailto)
                                    <a href="{{ $mailto }}" class="btn-admin-action" title="Envoyer le lien">
                                        <i class="fas fa-paper-plane"></i>
                                    </a>
                                @endif
                                <form action="{{ route('boutiques.admin.destroy', $b) }}" method="post" onsubmit="return confirm('Supprimer définitivement la boutique « {{ $b->nom_entreprise }} » ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-admin-action is-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $boutiques->links() }}
    @endif
</div>
@endsection
