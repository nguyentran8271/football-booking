<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm field icon cho home_cards
        Schema::table('home_cards', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('description');
        });

        // Thêm field title cho home_stats và đổi tên label
        Schema::table('home_stats', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
        });

        // Thêm field title và description cho featured_fields
        Schema::table('featured_fields', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
            $table->text('description')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('home_cards', function (Blueprint $table) {
            $table->dropColumn('icon');
        });

        Schema::table('home_stats', function (Blueprint $table) {
            $table->dropColumn('title');
        });

        Schema::table('featured_fields', function (Blueprint $table) {
            $table->dropColumn(['title', 'description']);
        });
    }
};
