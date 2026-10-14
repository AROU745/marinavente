<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes_ia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->constrained('boutiques')->cascadeOnDelete();
            $table->foreignId('boutique_produit_id')->constrained('boutique_produits')->cascadeOnDelete();
            $table->string('type', 40); // photos_ia | video_ia | photos_et_video
            $table->unsignedInteger('montant')->default(0);
            $table->unsignedTinyInteger('nb_sources')->default(0);
            $table->string('statut', 30)->default('en_attente'); // en_attente | en_cours | terminee
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes_ia');
    }
};
