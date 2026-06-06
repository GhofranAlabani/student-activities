<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            // إضافة user_id إذا غير موجود
            if (!Schema::hasColumn('announcements', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            }
            
            // إضافة is_active إذا غير موجود
            if (!Schema::hasColumn('announcements', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            
            // إضافة expires_at إذا غير موجود
            if (!Schema::hasColumn('announcements', 'expires_at')) {
                $table->date('expires_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'expires_at']);
        });
    }
};