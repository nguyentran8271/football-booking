<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tournament_teams', function (Blueprint $table) {
            $table->string('payment_method')->default('direct')->after('status'); // direct | sepay
            $table->string('payment_status')->default('unpaid')->after('payment_method'); // unpaid | paid
            $table->string('payment_invoice')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('tournament_teams', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status', 'payment_invoice']);
        });
    }
};
