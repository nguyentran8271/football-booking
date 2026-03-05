<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('featured_fields', function (Blueprint $table) {
            // Xóa các field cũ nếu tồn tại
            if (Schema::hasColumn('featured_fields', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('featured_fields', 'address')) {
                $table->dropColumn('address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('featured_fields', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('address')->nullable();
        });
    }
};
