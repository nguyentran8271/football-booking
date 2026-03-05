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
        Schema::table('bookings', function (Blueprint $table) {
            // Xóa cột cũ nếu tồn tại
            if (Schema::hasColumn('bookings', 'start_time')) {
                $table->dropColumn(['start_time', 'end_time']);
            }

            // Thêm cột shift nếu chưa có
            if (!Schema::hasColumn('bookings', 'shift')) {
                $table->integer('shift')->after('date')->comment('Ca: 1=7-9h, 2=9-11h, 3=11-13h, 4=13-15h, 5=15-17h, 6=17-19h, 7=19-21h, 8=21-23h');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('shift');
            $table->time('start_time')->after('date');
            $table->time('end_time')->after('start_time');
        });
    }
};
