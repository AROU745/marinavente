<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->string('theme', 40)->default('marina')->after('statut');
        });

        Schema::table('boutique_produits', function (Blueprint $table) {
            $table->boolean('en_banniere')->default(false)->after('ordre');
            $table->unsignedTinyInteger('ordre_banniere')->nullable()->after('en_banniere');
        });
    }

    public function down(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->dropColumn('theme');
        });

        Schema::table('boutique_produits', function (Blueprint $table) {
            $table->dropColumn(['en_banniere', 'ordre_banniere']);
        });
    }
};
