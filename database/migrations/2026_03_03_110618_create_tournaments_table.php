<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('field_id')->constrained('fields')->onDelete('cascade');

            $table->string('name');
            $table->text('description')->nullable();

            $table->date('start_date');
            $table->date('end_date');

            $table->integer('max_teams');
            $table->integer('players_per_team'); // 5, 7, 11

            $table->decimal('entry_fee', 10, 2)->default(0);
            $table->text('prize')->nullable();

            $table->string('banner')->nullable();

            $table->enum('status', ['upcoming', 'ongoing', 'finished'])->default('upcoming');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};
