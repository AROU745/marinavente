<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoutiqueProduitMedia extends Model
{
    protected $table = 'boutique_produit_medias';

    public const TYPE_SOURCE_IA = 'source_ia';
    public const TYPE_SOURCE_VIDEO = 'source_video';
    public const TYPE_PHOTO = 'photo';
    public const TYPE_PHOTO_IA = 'photo_ia';
    public const TYPE_VIDEO_IA = 'video_ia';

    protected $fillable = [
        'boutique_produit_id',
        'type',
        'chemin',
        'ordre',
    ];

    protected $casts = [
        'ordre' => 'integer',
    ];

    public function produit(): BelongsTo
    {
        return $this->belongsTo(BoutiqueProduit::class, 'boutique_produit_id');
    }

    public function url(): string
    {
        return asset('storage/' . $this->chemin);
    }

    public function isVideo(): bool
    {
        return $this->type === self::TYPE_VIDEO_IA
            || str_ends_with(strtolower($this->chemin), '.mp4')
            || str_ends_with(strtolower($this->chemin), '.webm');
    }
}
