<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('type')->default('general')->after('user_id');
            $table->foreignId('activity_id')->nullable()->after('message')->constrained()->onDelete('cascade');
            $table->string('icon')->default('bell')->after('activity_id');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['type', 'activity_id', 'icon']);
        });
    }
};