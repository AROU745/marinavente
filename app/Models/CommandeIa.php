<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommandeIa extends Model
{
    protected $table = 'commandes_ia';

    public const TYPE_PHOTOS = 'photos_ia';
    public const TYPE_VIDEO = 'video_ia';
    public const TYPE_BOTH = 'photos_et_video';

    public const STATUT_ATTENTE = 'en_attente';
    public const STATUT_COURS = 'en_cours';
    public const STATUT_TERMINEE = 'terminee';

    protected $fillable = [
        'boutique_id',
        'boutique_produit_id',
        'type',
        'montant',
        'nb_sources',
        'statut',
        'notes',
    ];

    protected $casts = [
        'montant' => 'integer',
        'nb_sources' => 'integer',
    ];

    public function boutique(): BelongsTo
    {
        return $this->belongsTo(Boutique::class);
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(BoutiqueProduit::class, 'boutique_produit_id');
    }

    public function labelType(): string
    {
        return match ($this->type) {
            self::TYPE_PHOTOS => '5 photos IA',
            self::TYPE_VIDEO => '1 vidéo IA',
            self::TYPE_BOTH => '5 photos + 1 vidéo IA',
            default => $this->type,
        };
    }

    public function labelStatut(): string
    {
        return match ($this->statut) {
            self::STATUT_ATTENTE => 'À faire',
            self::STATUT_COURS => 'En cours',
            self::STATUT_TERMINEE => 'Terminée',
            default => $this->statut,
        };
    }

    public static function resolveType(bool $photos, bool $video): string
    {
        if ($photos && $video) {
            return self::TYPE_BOTH;
        }
        if ($video) {
            return self::TYPE_VIDEO;
        }

        return self::TYPE_PHOTOS;
    }
}
