<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('total_points')->default(0)->after('role');
            $table->integer('level')->default(1)->after('total_points');
            $table->integer('activities_completed')->default(0)->after('level');
            $table->integer('current_streak')->default(0)->after('activities_completed');
            $table->integer('longest_streak')->default(0)->after('current_streak');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'total_points',
                'level',
                'activities_completed',
                'current_streak',
                'longest_streak'
            ]);
        });
    }
};