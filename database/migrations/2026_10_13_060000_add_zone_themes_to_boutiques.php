<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->string('theme_banniere', 40)->default('marina')->after('theme');
            $table->string('theme_catalogue', 40)->default('marina')->after('theme_banniere');
            $table->string('theme_footer', 40)->default('marina')->after('theme_catalogue');
        });

        if (Schema::hasColumn('boutiques', 'theme')) {
            foreach (DB::table('boutiques')->select('id', 'theme')->get() as $row) {
                $t = $row->theme ?: 'marina';
                DB::table('boutiques')->where('id', $row->id)->update([
                    'theme_banniere' => $t,
                    'theme_catalogue' => $t,
                    'theme_footer' => $t,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->dropColumn(['theme_banniere', 'theme_catalogue', 'theme_footer']);
        });
    }
};
