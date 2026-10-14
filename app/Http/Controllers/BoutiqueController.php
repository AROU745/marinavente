<?php

namespace App\Http\Controllers;

use App\Models\Boutique;
use App\Models\BoutiqueProduit;
use App\Models\BoutiqueProduitMedia;
use App\Models\CommandeIa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BoutiqueController extends Controller
{
    public function create()
    {
        return view('boutiques.create', [
            'title' => 'Créer ma boutique',
            'metaDescription' => 'Créez votre boutique en ligne : bannière optionnelle, catalogue et thèmes au choix.',
            'bodyClass' => 'page-boutique-create',
            'themes' => Boutique::THEMES,
        ]);
    }

    public function store(Request $request)
    {
        $themeKeys = array_keys(Boutique::THEMES);

        // URLs sociales vides → null (évite l’échec de la règle url)
        foreach (['facebook', 'instagram', 'tiktok', 'email'] as $field) {
            if ($request->input($field) === '') {
                $request->merge([$field => null]);
            }
        }

        $validated = $request->validate([
            'nom_entreprise' => ['required', 'string', 'max:120'],
            'telephone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:120'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'horaires' => ['nullable', 'string', 'max:160'],
            'footer_texte' => ['nullable', 'string', 'max:500'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'tiktok' => ['nullable', 'url', 'max:255'],
            'theme_banniere' => ['nullable', Rule::in($themeKeys)],
            'theme_catalogue' => ['nullable', Rule::in($themeKeys)],
            'theme_footer' => ['nullable', Rule::in($themeKeys)],
            'couleur_fond' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/'],
            'activer_banniere' => ['nullable', 'boolean'],
            'banniere' => ['nullable', 'array', 'max:3'],
            'banniere.*.nom' => ['nullable', 'string', 'max:160'],
            'banniere.*.prix' => ['nullable', 'numeric', 'min:0'],
            'banniere.*.photo' => ['nullable', 'image', 'max:4096'],
            'produits' => ['nullable', 'array'],
            'produits.*.nom' => ['nullable', 'string', 'max:160'],
            'produits.*.prix' => ['nullable', 'numeric', 'min:0'],
            'produits.*.tailles' => ['nullable', 'string', 'max:160'],
            'produits.*.couleurs' => ['nullable', 'string', 'max:160'],
            'produits.*.quantite' => ['nullable', 'integer', 'min:0'],
            'produits.*.photo' => ['nullable', 'image', 'max:4096'],
            'produits.*.demande_photos_ia' => ['nullable', 'boolean'],
            'produits.*.demande_video_ia' => ['nullable', 'boolean'],
            'produits.*.envoyer_photos_ia' => ['nullable', 'boolean'],
            'produits.*.envoyer_video_ia' => ['nullable', 'boolean'],
            'produits.*.sources_ia' => ['nullable', 'array', 'max:5'],
            'produits.*.sources_ia.*' => ['nullable', 'image', 'max:4096'],
            'produits.*.sources_video_ia' => ['nullable', 'array', 'max:5'],
            'produits.*.sources_video_ia.*' => ['nullable', 'image', 'max:4096'],
            'produits.*.source_video_clip' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:51200'],
        ], [
            'nom_entreprise.required' => 'Le nom de la boutique est obligatoire.',
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'email.email' => 'Indiquez une adresse e-mail valide.',
            'logo.image' => 'Le logo doit être une image (JPG, PNG, WEBP…).',
            'facebook.url' => 'Le lien Facebook doit être une URL valide (https://…).',
            'instagram.url' => 'Le lien Instagram doit être une URL valide (https://…).',
            'tiktok.url' => 'Le lien TikTok doit être une URL valide (https://…).',
            'banniere.max' => 'La bannière accepte au maximum 3 produits.',
            'produits.*.sources_ia.max' => 'Maximum 5 photos sources pour l’IA.',
            'produits.*.sources_video_ia.max' => 'Maximum 5 images sources pour la vidéo IA.',
        ]);

        $activerBanniere = $request->boolean('activer_banniere');
        $banniereItems = [];

        if ($activerBanniere) {
            foreach ($validated['banniere'] ?? [] as $i => $item) {
                $hasPhoto = $request->hasFile("banniere.$i.photo");
                $hasNom = !empty($item['nom']);
                if ($hasPhoto && $hasNom) {
                    $banniereItems[$i] = $item;
                }
            }
            if (count($banniereItems) > 3) {
                return back()->withInput()->withErrors([
                    'banniere' => 'Maximum 3 produits en bannière.',
                ]);
            }
        }

        // Ne garder que les lignes catalogue avec au moins un nom
        $produitsItems = [];
        foreach ($validated['produits'] ?? [] as $i => $item) {
            if (!empty($item['nom'])) {
                $produitsItems[$i] = $item;
            }
        }

        $boutique = DB::transaction(function () use ($request, $validated, $banniereItems, $produitsItems) {
            $slug = Boutique::genererSlugUnique($validated['nom_entreprise']);
            $lien = Boutique::genererLienUnique();
            $themeCat = $validated['theme_catalogue'] ?? 'marina';
            $themeBan = $validated['theme_banniere'] ?? $themeCat;
            $themeFoot = $validated['theme_footer'] ?? 'marina';

            $boutique = Boutique::create([
                'nom_entreprise' => $validated['nom_entreprise'],
                'telephone' => $validated['telephone'],
                'email' => $validated['email'] ?? null,
                'adresse' => $validated['adresse'] ?? null,
                'ville' => $validated['ville'] ?? null,
                'whatsapp' => $validated['whatsapp'] ?? null,
                'horaires' => $validated['horaires'] ?? null,
                'footer_texte' => $validated['footer_texte'] ?? null,
                'facebook' => $validated['facebook'] ?? null,
                'instagram' => $validated['instagram'] ?? null,
                'tiktok' => $validated['tiktok'] ?? null,
                'slug' => $slug,
                'lien_unique' => $lien,
                'statut' => 'active',
                'theme' => $themeCat,
                'theme_banniere' => $themeBan,
                'theme_catalogue' => $themeCat,
                'theme_footer' => $themeFoot,
                'couleur_fond' => $validated['couleur_fond'] ?? '#ffffff',
            ]);

            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('boutiques/' . $boutique->id, 'public');
                $boutique->update(['logo' => $logoPath]);
            }

            $bannerOrder = 0;
            foreach ($banniereItems as $index => $item) {
                $photoPath = $request->file("banniere.$index.photo")
                    ->store('boutiques/' . $boutique->id . '/banniere', 'public');

                BoutiqueProduit::create([
                    'boutique_id' => $boutique->id,
                    'nom' => $item['nom'],
                    'prix' => $item['prix'] ?? 0,
                    'tailles' => null,
                    'couleurs' => null,
                    'quantite' => 0,
                    'photo' => $photoPath,
                    'ordre' => $bannerOrder,
                    'en_banniere' => true,
                    'ordre_banniere' => $bannerOrder,
                ]);
                $bannerOrder++;
            }

            $ordre = 0;
            foreach ($produitsItems as $index => $produitData) {
                $photoPath = null;
                if ($request->hasFile("produits.$index.photo")) {
                    $photoPath = $request->file("produits.$index.photo")
                        ->store('boutiques/' . $boutique->id, 'public');
                }

                $photosIa = !empty($produitData['demande_photos_ia']);
                $videoIa = !empty($produitData['demande_video_ia']);

                $produit = BoutiqueProduit::create([
                    'boutique_id' => $boutique->id,
                    'nom' => $produitData['nom'],
                    'prix' => $produitData['prix'] ?? 0,
                    'tailles' => $produitData['tailles'] ?? null,
                    'couleurs' => $produitData['couleurs'] ?? null,
                    'quantite' => $produitData['quantite'] ?? 0,
                    'photo' => $photoPath,
                    'ordre' => $ordre++,
                    'en_banniere' => false,
                    'ordre_banniere' => null,
                    'demande_photos_ia' => $photosIa,
                    'demande_video_ia' => $videoIa,
                    'montant_ia' => BoutiqueProduit::calculerMontantIa($photosIa, $videoIa),
                    'statut_ia' => ($photosIa || $videoIa) ? 'en_attente' : 'aucune',
                ]);

                $this->storeSourcesIaAndCommande(
                    $request,
                    $boutique,
                    $produit,
                    $index,
                    $photosIa,
                    $videoIa
                );
            }

            return $boutique;
        });

        return redirect()
            ->route('boutiques.success')
            ->with('boutique_success', [
                'nom' => $boutique->nom_entreprise,
                'email' => $boutique->email,
                'produits' => $boutique->produits()->count(),
            ]);
    }

    public function success()
    {
        $info = session('boutique_success');
        if (!$info) {
            return redirect()->route('boutiques.create');
        }

        return view('boutiques.success', [
            'info' => $info,
            'title' => 'Boutique créée',
            'metaDescription' => 'Votre boutique a été générée. MARINA vous enverra le lien par e-mail.',
            'bodyClass' => 'page-boutique-success',
        ]);
    }

    public function show(string $slug)
    {
        $boutique = Boutique::with(['produits', 'produitsBanniere'])
            ->where('slug', $slug)
            ->where('statut', 'active')
            ->firstOrFail();

        return view('boutiques.show', [
            'boutique' => $boutique,
            'title' => $boutique->nom_entreprise,
            'metaDescription' => 'Catalogue en ligne de ' . $boutique->nom_entreprise,
            'bodyClass' => 'page-boutique-show',
            'bodyBgColor' => $boutique->couleurFond(),
            'hideSiteHeader' => true,
            'hideSiteFooter' => true,
        ]);
    }

    public function showProduit(string $slug, BoutiqueProduit $produit)
    {
        $boutique = Boutique::where('slug', $slug)
            ->where('statut', 'active')
            ->firstOrFail();

        if ($produit->boutique_id !== $boutique->id || $produit->en_banniere) {
            abort(404);
        }

        $produit->load(['medias', 'photosIa', 'videosIa', 'sourcesIa', 'sourcesVideoIa']);

        $numero = $boutique->produits()
            ->where('ordre', '<=', $produit->ordre)
            ->count();

        return view('boutiques.produit', [
            'boutique' => $boutique,
            'produit' => $produit,
            'numero' => max(1, $numero),
            'gallery' => $produit->galleryMedias(),
            'title' => $produit->nom . ' — ' . $boutique->nom_entreprise,
            'metaDescription' => 'Fiche article ' . $produit->nom,
            'bodyClass' => 'page-boutique-produit shop-zone-theme-' . $boutique->themeKey('catalogue'),
            'bodyBgColor' => $boutique->couleurFond(),
            'hideSiteHeader' => true,
            'hideSiteFooter' => true,
        ]);
    }

    public function adminIndex()
    {
        $boutiques = Boutique::withCount('tousProduits')
            ->withCount([
                'commandesIa as commandes_ia_nouvelles_count' => function ($q) {
                    $q->where('statut', CommandeIa::STATUT_ATTENTE);
                },
                'commandesIa as commandes_ia_count',
            ])
            ->latest()
            ->paginate(20);

        $totalNouvelles = CommandeIa::where('statut', CommandeIa::STATUT_ATTENTE)->count();

        return view('boutiques.admin', [
            'boutiques' => $boutiques,
            'totalNouvellesIa' => $totalNouvelles,
            'title' => 'Administration — Boutiques',
            'metaDescription' => 'Liste des boutiques créées sur MARINA.',
            'bodyClass' => 'page-boutique-admin',
        ]);
    }

    public function adminCreate()
    {
        return view('boutiques.admin-form', [
            'boutique' => null,
            'banniereItems' => collect(),
            'catalogueItems' => collect(),
            'themes' => Boutique::THEMES,
            'title' => 'Créer une boutique',
            'metaDescription' => 'Créer une boutique depuis l\'espace administrateur.',
            'bodyClass' => 'page-boutique-admin',
        ]);
    }

    public function adminStore(Request $request)
    {
        $validated = $this->validateAdminBoutique($request);
        $themeCat = $validated['theme_catalogue'] ?? 'marina';

        $boutique = DB::transaction(function () use ($request, $validated, $themeCat) {
            $boutique = Boutique::create([
                'nom_entreprise' => $validated['nom_entreprise'],
                'telephone' => $validated['telephone'],
                'email' => $validated['email'] ?? null,
                'adresse' => $validated['adresse'] ?? null,
                'ville' => $validated['ville'] ?? null,
                'whatsapp' => $validated['whatsapp'] ?? null,
                'horaires' => $validated['horaires'] ?? null,
                'footer_texte' => $validated['footer_texte'] ?? null,
                'facebook' => $validated['facebook'] ?? null,
                'instagram' => $validated['instagram'] ?? null,
                'tiktok' => $validated['tiktok'] ?? null,
                'slug' => Boutique::genererSlugUnique($validated['nom_entreprise']),
                'lien_unique' => Boutique::genererLienUnique(),
                'statut' => $validated['statut'] ?? 'active',
                'theme' => $themeCat,
                'theme_banniere' => $validated['theme_banniere'] ?? $themeCat,
                'theme_catalogue' => $themeCat,
                'theme_footer' => $validated['theme_footer'] ?? $themeCat,
                'couleur_fond' => $validated['couleur_fond'] ?? '#ffffff',
            ]);

            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('boutiques/' . $boutique->id, 'public');
                $boutique->update(['logo' => $logoPath]);
            }

            $this->syncAdminBanniere($request, $boutique, $validated['banniere'] ?? []);
            $this->syncAdminCatalogue($request, $boutique, $validated['produits'] ?? []);

            return $boutique;
        });

        return redirect()
            ->route('boutiques.admin')
            ->with('success', 'Boutique « ' . $boutique->nom_entreprise . ' » créée.');
    }

    public function adminEdit(Boutique $boutique)
    {
        $boutique->load(['produitsBanniere', 'produits']);

        return view('boutiques.admin-form', [
            'boutique' => $boutique,
            'banniereItems' => $boutique->produitsBanniere,
            'catalogueItems' => $boutique->produits,
            'themes' => Boutique::THEMES,
            'title' => 'Modifier — ' . $boutique->nom_entreprise,
            'metaDescription' => 'Modifier la boutique ' . $boutique->nom_entreprise,
            'bodyClass' => 'page-boutique-admin',
        ]);
    }

    public function adminUpdate(Request $request, Boutique $boutique)
    {
        $validated = $this->validateAdminBoutique($request);
        $themeCat = $validated['theme_catalogue'] ?? $boutique->theme_catalogue ?? 'marina';

        DB::transaction(function () use ($request, $boutique, $validated, $themeCat) {
            $data = [
                'nom_entreprise' => $validated['nom_entreprise'],
                'telephone' => $validated['telephone'],
                'email' => $validated['email'] ?? null,
                'adresse' => $validated['adresse'] ?? null,
                'ville' => $validated['ville'] ?? null,
                'whatsapp' => $validated['whatsapp'] ?? null,
                'horaires' => $validated['horaires'] ?? null,
                'footer_texte' => $validated['footer_texte'] ?? null,
                'facebook' => $validated['facebook'] ?? null,
                'instagram' => $validated['instagram'] ?? null,
                'tiktok' => $validated['tiktok'] ?? null,
                'statut' => $validated['statut'] ?? $boutique->statut,
                'theme' => $themeCat,
                'theme_banniere' => $validated['theme_banniere'] ?? $themeCat,
                'theme_catalogue' => $themeCat,
                'theme_footer' => $validated['theme_footer'] ?? $themeCat,
                'couleur_fond' => $validated['couleur_fond'] ?? $boutique->couleur_fond ?? '#ffffff',
            ];

            if ($boutique->nom_entreprise !== $validated['nom_entreprise']) {
                $data['slug'] = Boutique::genererSlugUnique($validated['nom_entreprise']);
            }

            if ($request->boolean('supprimer_logo') && $boutique->logo) {
                Storage::disk('public')->delete($boutique->logo);
                $data['logo'] = null;
            }

            if ($request->hasFile('logo')) {
                if ($boutique->logo) {
                    Storage::disk('public')->delete($boutique->logo);
                }
                $data['logo'] = $request->file('logo')->store('boutiques/' . $boutique->id, 'public');
            }

            $boutique->update($data);

            $this->syncAdminBanniere($request, $boutique, $validated['banniere'] ?? []);
            $this->syncAdminCatalogue($request, $boutique, $validated['produits'] ?? []);
        });

        return redirect()
            ->route('boutiques.admin')
            ->with('success', 'Boutique « ' . $boutique->fresh()->nom_entreprise . ' » mise à jour.');
    }

    public function adminDestroy(Boutique $boutique)
    {
        $nom = $boutique->nom_entreprise;
        $id = $boutique->id;

        DB::transaction(function () use ($boutique, $id) {
            $boutique->tousProduits()->delete();
            $boutique->delete();
            Storage::disk('public')->deleteDirectory('boutiques/' . $id);
        });

        return redirect()
            ->route('boutiques.admin')
            ->with('success', 'Boutique « ' . $nom . ' » supprimée.');
    }

    private function validateAdminBoutique(Request $request): array
    {
        foreach (['facebook', 'instagram', 'tiktok', 'email'] as $field) {
            if ($request->input($field) === '') {
                $request->merge([$field => null]);
            }
        }

        $themeKeys = array_keys(Boutique::THEMES);

        return $request->validate([
            'nom_entreprise' => ['required', 'string', 'max:120'],
            'telephone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'supprimer_logo' => ['nullable', 'boolean'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:120'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'horaires' => ['nullable', 'string', 'max:160'],
            'footer_texte' => ['nullable', 'string', 'max:500'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'tiktok' => ['nullable', 'url', 'max:255'],
            'statut' => ['nullable', Rule::in(['active', 'brouillon', 'suspendue'])],
            'theme_banniere' => ['nullable', Rule::in($themeKeys)],
            'theme_catalogue' => ['nullable', Rule::in($themeKeys)],
            'theme_footer' => ['nullable', Rule::in($themeKeys)],
            'couleur_fond' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/'],
            'banniere' => ['nullable', 'array', 'max:3'],
            'banniere.*.id' => ['nullable', 'integer'],
            'banniere.*.nom' => ['nullable', 'string', 'max:160'],
            'banniere.*.prix' => ['nullable', 'numeric', 'min:0'],
            'banniere.*.photo' => ['nullable', 'image', 'max:4096'],
            'banniere.*.supprimer_photo' => ['nullable', 'boolean'],
            'produits' => ['nullable', 'array'],
            'produits.*.id' => ['nullable', 'integer'],
            'produits.*.nom' => ['nullable', 'string', 'max:160'],
            'produits.*.prix' => ['nullable', 'numeric', 'min:0'],
            'produits.*.tailles' => ['nullable', 'string', 'max:160'],
            'produits.*.couleurs' => ['nullable', 'string', 'max:160'],
            'produits.*.quantite' => ['nullable', 'integer', 'min:0'],
            'produits.*.photo' => ['nullable', 'image', 'max:4096'],
            'produits.*.supprimer_photo' => ['nullable', 'boolean'],
            'produits.*.demande_photos_ia' => ['nullable', 'boolean'],
            'produits.*.demande_video_ia' => ['nullable', 'boolean'],
            'produits.*.envoyer_photos_ia' => ['nullable', 'boolean'],
            'produits.*.envoyer_video_ia' => ['nullable', 'boolean'],
            'produits.*.sources_ia' => ['nullable', 'array', 'max:5'],
            'produits.*.sources_ia.*' => ['nullable', 'image', 'max:4096'],
            'produits.*.sources_video_ia' => ['nullable', 'array', 'max:5'],
            'produits.*.sources_video_ia.*' => ['nullable', 'image', 'max:4096'],
            'produits.*.source_video_clip' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:51200'],
        ], [
            'nom_entreprise.required' => 'Le nom de la boutique est obligatoire.',
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'banniere.max' => 'Maximum 3 slides en bannière.',
            'produits.*.sources_ia.max' => 'Maximum 5 photos sources pour l’IA.',
            'produits.*.sources_video_ia.max' => 'Maximum 5 images sources pour la vidéo IA.',
        ]);
    }

    private function syncAdminBanniere(Request $request, Boutique $boutique, array $items): void
    {
        $keptIds = [];
        $ordre = 0;

        foreach ($items as $index => $item) {
            $nom = trim((string) ($item['nom'] ?? ''));
            $hasNewPhoto = $request->hasFile("banniere.$index.photo");
            $id = !empty($item['id']) ? (int) $item['id'] : null;
            $existing = $id
                ? $boutique->tousProduits()->where('id', $id)->where('en_banniere', true)->first()
                : null;

            if ($nom === '' && !$existing && !$hasNewPhoto) {
                continue;
            }

            if ($nom === '' && $existing) {
                continue;
            }

            if (!$existing && (!$hasNewPhoto || $nom === '')) {
                continue;
            }

            $photoPath = $existing?->photo;
            if (!empty($item['supprimer_photo']) && $photoPath) {
                Storage::disk('public')->delete($photoPath);
                $photoPath = null;
            }
            if ($hasNewPhoto) {
                if ($photoPath) {
                    Storage::disk('public')->delete($photoPath);
                }
                $photoPath = $request->file("banniere.$index.photo")
                    ->store('boutiques/' . $boutique->id . '/banniere', 'public');
            }

            if (!$photoPath) {
                continue;
            }

            $payload = [
                'boutique_id' => $boutique->id,
                'nom' => $nom ?: ($existing->nom ?? 'Slide'),
                'prix' => $item['prix'] ?? 0,
                'tailles' => null,
                'couleurs' => null,
                'quantite' => 0,
                'photo' => $photoPath,
                'ordre' => $ordre,
                'en_banniere' => true,
                'ordre_banniere' => $ordre,
            ];

            if ($existing) {
                $existing->update($payload);
                $keptIds[] = $existing->id;
            } else {
                $created = BoutiqueProduit::create($payload);
                $keptIds[] = $created->id;
            }
            $ordre++;
        }

        $toDelete = $boutique->produitsBanniere()->when(
            count($keptIds) > 0,
            fn ($q) => $q->whereNotIn('id', $keptIds),
            fn ($q) => $q
        )->get();

        foreach ($toDelete as $prod) {
            if ($prod->photo) {
                Storage::disk('public')->delete($prod->photo);
            }
            $prod->delete();
        }
    }

    private function syncAdminCatalogue(Request $request, Boutique $boutique, array $items): void
    {
        $keptIds = [];
        $ordre = 0;

        foreach ($items as $index => $item) {
            $nom = trim((string) ($item['nom'] ?? ''));
            $id = !empty($item['id']) ? (int) $item['id'] : null;
            $existing = $id
                ? $boutique->tousProduits()->where('id', $id)->where('en_banniere', false)->first()
                : null;

            if ($nom === '' && !$existing) {
                continue;
            }
            if ($nom === '') {
                continue;
            }

            $photoPath = $existing?->photo;
            if (!empty($item['supprimer_photo']) && $photoPath) {
                Storage::disk('public')->delete($photoPath);
                $photoPath = null;
            }
            if ($request->hasFile("produits.$index.photo")) {
                if ($photoPath) {
                    Storage::disk('public')->delete($photoPath);
                }
                $photoPath = $request->file("produits.$index.photo")
                    ->store('boutiques/' . $boutique->id, 'public');
            }

            $photosIa = !empty($item['demande_photos_ia']);
            $videoIa = !empty($item['demande_video_ia']);

            $payload = [
                'boutique_id' => $boutique->id,
                'nom' => $nom,
                'prix' => $item['prix'] ?? 0,
                'tailles' => $item['tailles'] ?? null,
                'couleurs' => $item['couleurs'] ?? null,
                'quantite' => $item['quantite'] ?? 0,
                'photo' => $photoPath,
                'ordre' => $ordre,
                'en_banniere' => false,
                'ordre_banniere' => null,
                'demande_photos_ia' => $photosIa,
                'demande_video_ia' => $videoIa,
                'montant_ia' => BoutiqueProduit::calculerMontantIa($photosIa, $videoIa),
                'statut_ia' => ($photosIa || $videoIa)
                    ? (($existing && $existing->statut_ia === 'traite') ? 'traite' : 'en_attente')
                    : 'aucune',
            ];

            if ($existing) {
                $existing->update($payload);
                $produit = $existing;
                $keptIds[] = $existing->id;
            } else {
                $produit = BoutiqueProduit::create($payload);
                $keptIds[] = $produit->id;
            }

            $this->storeSourcesIaAndCommande(
                $request,
                $boutique,
                $produit,
                $index,
                $photosIa,
                $videoIa
            );
            $ordre++;
        }

        $toDelete = $boutique->produits()->when(
            count($keptIds) > 0,
            fn ($q) => $q->whereNotIn('id', $keptIds),
            fn ($q) => $q
        )->get();

        foreach ($toDelete as $prod) {
            foreach ($prod->medias as $media) {
                Storage::disk('public')->delete($media->chemin);
            }
            if ($prod->photo) {
                Storage::disk('public')->delete($prod->photo);
            }
            $prod->delete();
        }
    }

    private function storeSourcesIaAndCommande(
        Request $request,
        Boutique $boutique,
        BoutiqueProduit $produit,
        int $index,
        bool $photosIa,
        bool $videoIa
    ): void {
        // Sources pour pack photos IA
        if ($request->hasFile("produits.$index.sources_ia")) {
            $ordre = (int) $produit->sourcesIa()->max('ordre');
            $ordre = $ordre > 0 ? $ordre + 1 : 0;
            $reste = max(0, 5 - $produit->sourcesIa()->count());
            $n = 0;
            foreach ($request->file("produits.$index.sources_ia") as $file) {
                if (!$file || $n >= $reste) {
                    continue;
                }
                BoutiqueProduitMedia::create([
                    'boutique_produit_id' => $produit->id,
                    'type' => BoutiqueProduitMedia::TYPE_SOURCE_IA,
                    'chemin' => $file->store(
                        'boutiques/' . $boutique->id . '/ia-sources/' . $produit->id,
                        'public'
                    ),
                    'ordre' => $ordre++,
                ]);
                $n++;
            }
        }

        // Sources images pour vidéo IA (même procédé que les photos)
        if ($request->hasFile("produits.$index.sources_video_ia")) {
            $ordre = (int) $produit->sourcesVideoIa()->max('ordre');
            $ordre = $ordre > 0 ? $ordre + 1 : 0;
            $nbImages = $produit->sourcesVideoIa()->get()->filter(fn ($m) => !$m->isVideo())->count();
            $reste = max(0, 5 - $nbImages);
            $n = 0;
            foreach ($request->file("produits.$index.sources_video_ia") as $file) {
                if (!$file || $n >= $reste) {
                    continue;
                }
                BoutiqueProduitMedia::create([
                    'boutique_produit_id' => $produit->id,
                    'type' => BoutiqueProduitMedia::TYPE_SOURCE_VIDEO,
                    'chemin' => $file->store(
                        'boutiques/' . $boutique->id . '/ia-sources-video/' . $produit->id,
                        'public'
                    ),
                    'ordre' => $ordre++,
                ]);
                $n++;
            }
        }

        // Clip vidéo source optionnel
        if ($request->hasFile("produits.$index.source_video_clip")) {
            $file = $request->file("produits.$index.source_video_clip");
            BoutiqueProduitMedia::create([
                'boutique_produit_id' => $produit->id,
                'type' => BoutiqueProduitMedia::TYPE_SOURCE_VIDEO,
                'chemin' => $file->store(
                    'boutiques/' . $boutique->id . '/ia-sources-video/' . $produit->id,
                    'public'
                ),
                'ordre' => ((int) $produit->sourcesVideoIa()->max('ordre')) + 1,
            ]);
        }

        if (!$photosIa && !$videoIa) {
            return;
        }

        $nbSources = $produit->sourcesIa()->count() + $produit->sourcesVideoIa()->count();

        $ouverte = CommandeIa::where('boutique_produit_id', $produit->id)
            ->whereIn('statut', [CommandeIa::STATUT_ATTENTE, CommandeIa::STATUT_COURS])
            ->first();

        $payload = [
            'boutique_id' => $boutique->id,
            'boutique_produit_id' => $produit->id,
            'type' => CommandeIa::resolveType($photosIa, $videoIa),
            'montant' => BoutiqueProduit::calculerMontantIa($photosIa, $videoIa),
            'nb_sources' => $nbSources,
            'statut' => CommandeIa::STATUT_ATTENTE,
        ];

        if ($ouverte) {
            $ouverte->update($payload);
        } else {
            CommandeIa::create($payload);
        }
    }

    /**
     * Crée les commandes IA manquantes pour les produits déjà demandés.
     */
    public function syncCommandesIaManquantes(): int
    {
        $creees = 0;
        $produits = BoutiqueProduit::query()
            ->where(function ($q) {
                $q->where('demande_photos_ia', true)->orWhere('demande_video_ia', true);
            })
            ->where('en_banniere', false)
            ->get();

        foreach ($produits as $produit) {
            $existe = CommandeIa::where('boutique_produit_id', $produit->id)->exists();
            if ($existe) {
                continue;
            }

            CommandeIa::create([
                'boutique_id' => $produit->boutique_id,
                'boutique_produit_id' => $produit->id,
                'type' => CommandeIa::resolveType(
                    (bool) $produit->demande_photos_ia,
                    (bool) $produit->demande_video_ia
                ),
                'montant' => BoutiqueProduit::calculerMontantIa(
                    (bool) $produit->demande_photos_ia,
                    (bool) $produit->demande_video_ia
                ),
                'nb_sources' => $produit->sourcesIa()->count() + $produit->sourcesVideoIa()->count(),
                'statut' => $produit->statut_ia === 'traite'
                    ? CommandeIa::STATUT_TERMINEE
                    : CommandeIa::STATUT_ATTENTE,
            ]);
            $creees++;
        }

        return $creees;
    }

    public function adminCommandesIa(Request $request)
    {
        // Rattrapage : transformer les demandes IA existantes en commandes visibles
        $this->syncCommandesIaManquantes();

        $boutique = null;
        if ($request->filled('boutique')) {
            $boutique = Boutique::findOrFail($request->integer('boutique'));
        }

        $commandes = CommandeIa::with(['boutique', 'produit.medias'])
            ->when($boutique, fn ($q) => $q->where('boutique_id', $boutique->id))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('boutiques.admin-commandes-ia', [
            'commandes' => $commandes,
            'boutiqueFiltre' => $boutique,
            'title' => $boutique
                ? 'Commandes IA — ' . $boutique->nom_entreprise
                : 'Commandes IA',
            'metaDescription' => 'Commandes photos et vidéos IA à réaliser.',
            'bodyClass' => 'page-boutique-admin',
        ]);
    }

    public function adminUpdateCommandeIa(Request $request, CommandeIa $commande)
    {
        $validated = $request->validate([
            'statut' => ['nullable', Rule::in([
                CommandeIa::STATUT_ATTENTE,
                CommandeIa::STATUT_COURS,
                CommandeIa::STATUT_TERMINEE,
            ])],
            'photos_ia' => ['nullable', 'array', 'max:5'],
            'photos_ia.*' => ['nullable', 'image', 'max:5120'],
            'video_ia' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:51200'],
        ], [
            'photos_ia.max' => 'Maximum 5 photos IA.',
            'photos_ia.*.image' => 'Chaque fichier photo doit être une image.',
            'video_ia.mimetypes' => 'La vidéo doit être au format MP4, WEBM ou MOV.',
            'video_ia.max' => 'La vidéo ne doit pas dépasser 50 Mo.',
        ]);

        $produit = $commande->produit;
        $nbPhotosAjoutees = 0;
        $videoAjoutee = false;

        if ($produit && $request->hasFile('photos_ia')) {
            $deja = $produit->photosIa()->count();
            $reste = max(0, 5 - $deja);
            $ordre = (int) $produit->photosIa()->max('ordre');
            $ordre = $ordre > 0 ? $ordre + 1 : 0;

            foreach ($request->file('photos_ia') as $file) {
                if (!$file || $nbPhotosAjoutees >= $reste) {
                    continue;
                }
                $path = $file->store(
                    'boutiques/' . $commande->boutique_id . '/ia-rendu/' . $produit->id,
                    'public'
                );
                BoutiqueProduitMedia::create([
                    'boutique_produit_id' => $produit->id,
                    'type' => BoutiqueProduitMedia::TYPE_PHOTO_IA,
                    'chemin' => $path,
                    'ordre' => $ordre++,
                ]);
                $nbPhotosAjoutees++;
            }
        }

        if ($produit && $request->hasFile('video_ia')) {
            // Remplacer l'ancienne vidéo IA si présente
            foreach ($produit->videosIa as $old) {
                Storage::disk('public')->delete($old->chemin);
                $old->delete();
            }
            $path = $request->file('video_ia')->store(
                'boutiques/' . $commande->boutique_id . '/ia-rendu/' . $produit->id,
                'public'
            );
            BoutiqueProduitMedia::create([
                'boutique_produit_id' => $produit->id,
                'type' => BoutiqueProduitMedia::TYPE_VIDEO_IA,
                'chemin' => $path,
                'ordre' => 0,
            ]);
            $videoAjoutee = true;
        }

        $statut = $validated['statut'] ?? $commande->statut;

        // Auto-terminer si le rendu attendu est livré
        if ($produit) {
            $produit->refresh();
            $photosOk = !$produit->demande_photos_ia || $produit->photosIa()->count() > 0;
            $videoOk = !$produit->demande_video_ia || $produit->videosIa()->count() > 0;
            if ($photosOk && $videoOk && ($nbPhotosAjoutees > 0 || $videoAjoutee)) {
                $statut = CommandeIa::STATUT_TERMINEE;
            }
        }

        $commande->update([
            'statut' => $statut,
            'nb_sources' => $produit ? $produit->sourcesIa()->count() : $commande->nb_sources,
        ]);

        if ($produit) {
            $produit->update([
                'statut_ia' => $statut === CommandeIa::STATUT_TERMINEE ? 'traite' : 'en_attente',
            ]);
        }

        $msg = 'Commande IA mise à jour.';
        if ($nbPhotosAjoutees > 0 || $videoAjoutee) {
            $parts = [];
            if ($nbPhotosAjoutees > 0) {
                $parts[] = $nbPhotosAjoutees . ' photo' . ($nbPhotosAjoutees > 1 ? 's' : '');
            }
            if ($videoAjoutee) {
                $parts[] = '1 vidéo';
            }
            $msg = 'Rendu IA envoyé à l’utilisateur : ' . implode(' + ', $parts) . '.';
        }

        return back()->with('success', $msg);
    }
}
