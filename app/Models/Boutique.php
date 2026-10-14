<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Boutique extends Model
{
    /** Large palette réutilisable pour bannière, catalogue et footer. */
    public const THEMES = [
        'marina' => ['label' => 'Marina', 'desc' => 'Orange vif'],
        'ocean' => ['label' => 'Océan', 'desc' => 'Bleu turquoise'],
        'nuit' => ['label' => 'Nuit', 'desc' => 'Sombre & or'],
        'sable' => ['label' => 'Sable', 'desc' => 'Tons terreux'],
        'berry' => ['label' => 'Berry', 'desc' => 'Rose fuchsia'],
        'foret' => ['label' => 'Forêt', 'desc' => 'Vert nature'],
        'lavande' => ['label' => 'Lavande', 'desc' => 'Violet doux'],
        'corail' => ['label' => 'Corail', 'desc' => 'Pêche vivant'],
        'graphite' => ['label' => 'Graphite', 'desc' => 'Gris urbain'],
        'ivoire' => ['label' => 'Ivoire', 'desc' => 'Crème élégant'],
        'emeraude' => ['label' => 'Émeraude', 'desc' => 'Vert bijou'],
        'indigo' => ['label' => 'Indigo', 'desc' => 'Bleu profond'],
        'sunset' => ['label' => 'Sunset', 'desc' => 'Coucher de soleil'],
        'mint' => ['label' => 'Menthe', 'desc' => 'Vert frais'],
        'bordeaux' => ['label' => 'Bordeaux', 'desc' => 'Rouge vin'],
        'ciel' => ['label' => 'Ciel', 'desc' => 'Bleu clair'],
        'chocolat' => ['label' => 'Chocolat', 'desc' => 'Brun cacao'],
        'neon' => ['label' => 'Néon', 'desc' => 'Électricité'],
        'sakura' => ['label' => 'Sakura', 'desc' => 'Rose pastel'],
        'olive' => ['label' => 'Olive', 'desc' => 'Vert olive'],
        'acier' => ['label' => 'Acier', 'desc' => 'Bleu métallique'],
        'ambre' => ['label' => 'Ambre', 'desc' => 'Jaune chaud'],
        'prune' => ['label' => 'Prune', 'desc' => 'Pourpre'],
        'arctic' => ['label' => 'Arctique', 'desc' => 'Blanc glacé'],
    ];

    protected $fillable = [
        'nom_entreprise',
        'telephone',
        'email',
        'logo',
        'adresse',
        'ville',
        'whatsapp',
        'horaires',
        'footer_texte',
        'facebook',
        'instagram',
        'tiktok',
        'slug',
        'lien_unique',
        'statut',
        'theme',
        'theme_banniere',
        'theme_catalogue',
        'theme_footer',
        'couleur_fond',
    ];

    public function couleurFond(): string
    {
        $color = $this->couleur_fond ?: '#ffffff';
        if (!preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $color)) {
            return '#ffffff';
        }

        return $color;
    }

    public function logoUrl(): string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }

        return asset('images/logo-marina.png');
    }

    public function hasCustomLogo(): bool
    {
        return !empty($this->logo);
    }

    public function tousProduits(): HasMany
    {
        return $this->hasMany(BoutiqueProduit::class);
    }

    public function produits(): HasMany
    {
        return $this->hasMany(BoutiqueProduit::class)
            ->where('en_banniere', false)
            ->orderBy('ordre');
    }

    public function produitsBanniere(): HasMany
    {
        return $this->hasMany(BoutiqueProduit::class)
            ->where('en_banniere', true)
            ->orderBy('ordre_banniere')
            ->orderBy('ordre');
    }

    public function commandesIa(): HasMany
    {
        return $this->hasMany(\App\Models\CommandeIa::class)->latest();
    }

    public function themeKey(string $zone = 'catalogue'): string
    {
        $map = [
            'banniere' => $this->theme_banniere,
            'catalogue' => $this->theme_catalogue ?: $this->theme,
            'footer' => $this->theme_footer,
        ];
        $theme = $map[$zone] ?? 'marina';
        $theme = $theme ?: 'marina';

        return array_key_exists($theme, self::THEMES) ? $theme : 'marina';
    }

    public function catalogueUrl(): string
    {
        return url('/boutique/' . $this->slug);
    }

    public static function genererSlugUnique(string $nom): string
    {
        $base = Str::slug($nom);
        if ($base === '') {
            $base = 'boutique';
        }

        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    public static function genererLienUnique(): string
    {
        do {
            $token = Str::lower(Str::random(10));
        } while (static::where('lien_unique', $token)->exists());

        return $token;
    }
}
