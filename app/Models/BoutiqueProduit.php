<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoutiqueProduit extends Model
{
    protected $table = 'boutique_produits';

    public const PRIX_PHOTOS_IA = 1000;
    public const PRIX_VIDEO_IA = 2000;

    protected $fillable = [
        'boutique_id',
        'nom',
        'prix',
        'tailles',
        'couleurs',
        'quantite',
        'photo',
        'ordre',
        'en_banniere',
        'ordre_banniere',
        'demande_photos_ia',
        'demande_video_ia',
        'montant_ia',
        'statut_ia',
    ];

    protected $casts = [
        'prix' => 'float',
        'quantite' => 'integer',
        'ordre' => 'integer',
        'en_banniere' => 'boolean',
        'ordre_banniere' => 'integer',
        'demande_photos_ia' => 'boolean',
        'demande_video_ia' => 'boolean',
        'montant_ia' => 'integer',
    ];

    public function boutique(): BelongsTo
    {
        return $this->belongsTo(Boutique::class);
    }

    public function medias(): HasMany
    {
        return $this->hasMany(BoutiqueProduitMedia::class, 'boutique_produit_id')->orderBy('ordre');
    }

    public function commandesIa(): HasMany
    {
        return $this->hasMany(\App\Models\CommandeIa::class, 'boutique_produit_id')->latest();
    }

    public function sourcesIa(): HasMany
    {
        return $this->medias()->where('type', BoutiqueProduitMedia::TYPE_SOURCE_IA);
    }

    public function sourcesVideoIa(): HasMany
    {
        return $this->medias()->where('type', BoutiqueProduitMedia::TYPE_SOURCE_VIDEO);
    }

    public function photosIa(): HasMany
    {
        return $this->medias()->where('type', BoutiqueProduitMedia::TYPE_PHOTO_IA);
    }

    public function videosIa(): HasMany
    {
        return $this->medias()->where('type', BoutiqueProduitMedia::TYPE_VIDEO_IA);
    }

    public function photoUrl(): ?string
    {
        if (!$this->photo) {
            return null;
        }

        return asset('storage/' . $this->photo);
    }

    public static function calculerMontantIa(bool $photos, bool $video): int
    {
        $total = 0;
        if ($photos) {
            $total += self::PRIX_PHOTOS_IA;
        }
        if ($video) {
            $total += self::PRIX_VIDEO_IA;
        }

        return $total;
    }

    public function hasDemandeIa(): bool
    {
        return $this->demande_photos_ia || $this->demande_video_ia;
    }

    public function galleryMedias()
    {
        // Photos produit + photos IA générées + sources (en attendant) + vidéos
        $items = collect();

        if ($this->photoUrl()) {
            $items->push([
                'type' => 'photo',
                'url' => $this->photoUrl(),
                'label' => 'Photo produit',
                'is_video' => false,
            ]);
        }

        foreach ($this->photosIa as $m) {
            $items->push([
                'type' => 'photo_ia',
                'url' => $m->url(),
                'label' => 'Photo produit (IA)',
                'is_video' => false,
            ]);
        }

        foreach ($this->videosIa as $m) {
            $items->push([
                'type' => 'video_ia',
                'url' => $m->url(),
                'label' => 'Vidéo produit (IA)',
                'is_video' => true,
            ]);
        }

        // Sources IA si pas encore de rendu généré
        if ($this->photosIa->isEmpty() && $this->videosIa->isEmpty()) {
            foreach ($this->sourcesIa as $m) {
                $items->push([
                    'type' => 'source_ia',
                    'url' => $m->url(),
                    'label' => 'Image source photos (IA en cours)',
                    'is_video' => false,
                ]);
            }
            foreach ($this->sourcesVideoIa as $m) {
                $isVid = $m->isVideo();
                $items->push([
                    'type' => 'source_video',
                    'url' => $m->url(),
                    'label' => $isVid ? 'Vidéo source (IA en cours)' : 'Image source vidéo (IA en cours)',
                    'is_video' => $isVid,
                ]);
            }
        }

        return $items;
    }
}
