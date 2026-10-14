<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boutique_produits', function (Blueprint $table) {
            $table->boolean('demande_photos_ia')->default(false)->after('ordre_banniere');
            $table->boolean('demande_video_ia')->default(false)->after('demande_photos_ia');
            $table->unsignedInteger('montant_ia')->default(0)->after('demande_video_ia');
            $table->string('statut_ia', 30)->default('aucune')->after('montant_ia');
        });

        Schema::create('boutique_produit_medias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_produit_id')->constrained('boutique_produits')->cascadeOnDelete();
            $table->string('type', 30); // source_ia | photo | photo_ia | video_ia
            $table->string('chemin');
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boutique_produit_medias');

        Schema::table('boutique_produits', function (Blueprint $table) {
            $table->dropColumn(['demande_photos_ia', 'demande_video_ia', 'montant_ia', 'statut_ia']);
        });
    }
};
