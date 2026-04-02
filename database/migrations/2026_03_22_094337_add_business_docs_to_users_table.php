<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tax_number')->nullable()->after('id_card_image');
            $table->string('id_card_back_image')->nullable()->after('tax_number');
            $table->string('business_license_image')->nullable()->after('id_card_back_image');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tax_number', 'id_card_back_image', 'business_license_image']);
        });
    }
};
