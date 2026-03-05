<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Stats cho trang chủ sân
        Schema::create('owner_stats', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('label');
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Benefits cho trang chủ sân
        Schema::create('owner_benefits', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // How it works steps
        Schema::create('owner_steps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->integer('step_number');
            $table->timestamps();
        });

        // Content sections (text + image)
        Schema::create('owner_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('image')->nullable();
            $table->enum('image_position', ['left', 'right'])->default('right');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_stats');
        Schema::dropIfExists('owner_benefits');
        Schema::dropIfExists('owner_steps');
        Schema::dropIfExists('owner_sections');
    }
};
