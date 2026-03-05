<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->date('registration_deadline')->nullable()->after('end_date');
        });

        Schema::table('tournament_teams', function (Blueprint $table) {
            $table->text('players_list')->nullable()->after('logo');
        });
    }

    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('registration_deadline');
        });

        Schema::table('tournament_teams', function (Blueprint $table) {
            $table->dropColumn('players_list');
        });
    }
};
