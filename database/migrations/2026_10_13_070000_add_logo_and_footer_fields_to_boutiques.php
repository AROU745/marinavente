<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('email');
            $table->string('adresse')->nullable()->after('logo');
            $table->string('ville')->nullable()->after('adresse');
            $table->string('whatsapp', 40)->nullable()->after('ville');
            $table->string('horaires')->nullable()->after('whatsapp');
            $table->text('footer_texte')->nullable()->after('horaires');
            $table->string('facebook')->nullable()->after('footer_texte');
            $table->string('instagram')->nullable()->after('facebook');
            $table->string('tiktok')->nullable()->after('instagram');
        });
    }

    public function down(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->dropColumn([
                'logo',
                'adresse',
                'ville',
                'whatsapp',
                'horaires',
                'footer_texte',
                'facebook',
                'instagram',
                'tiktok',
            ]);
        });
    }
};
