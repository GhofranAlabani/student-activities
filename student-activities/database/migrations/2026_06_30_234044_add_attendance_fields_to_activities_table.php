<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->boolean('attendance_enabled')->default(true)->after('status');
            $table->decimal('location_latitude', 10, 7)->nullable()->after('location');
            $table->decimal('location_longitude', 10, 7)->nullable()->after('location_latitude');
            $table->integer('location_radius')->default(50)->after('location_longitude'); // بالمتر
            $table->time('check_in_start')->nullable()->after('time');
            $table->time('check_in_end')->nullable()->after('check_in_start');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn([
                'attendance_enabled',
                'location_latitude',
                'location_longitude',
                'location_radius',
                'check_in_start',
                'check_in_end'
            ]);
        });
    }
};