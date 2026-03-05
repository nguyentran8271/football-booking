<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->decimal('field_quality_rating', 2, 1)->nullable()->after('rating');
            $table->decimal('lighting_rating', 2, 1)->nullable()->after('field_quality_rating');
            $table->decimal('hygiene_rating', 2, 1)->nullable()->after('lighting_rating');
            $table->decimal('staff_rating', 2, 1)->nullable()->after('hygiene_rating');
            $table->decimal('price_rating', 2, 1)->nullable()->after('staff_rating');
            $table->json('images')->nullable()->after('comment');
            $table->integer('helpful_count')->default(0)->after('images');
            $table->string('location')->nullable()->after('helpful_count');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn([
                'field_quality_rating',
                'lighting_rating',
                'hygiene_rating',
                'staff_rating',
                'price_rating',
                'images',
                'helpful_count',
                'location'
            ]);
        });
    }
};
