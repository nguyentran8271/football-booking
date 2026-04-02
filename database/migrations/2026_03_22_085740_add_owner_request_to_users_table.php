<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('owner_request')->nullable()->after('role'); // null | 'pending' | 'rejected'
            $table->text('owner_request_note')->nullable()->after('owner_request');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['owner_request', 'owner_request_note']);
        });
    }
};
