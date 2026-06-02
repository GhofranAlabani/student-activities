<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ تم تعطيل هذا الكود لأن الجدول غير موجود
        // Schema::table('activity_student', function (Blueprint $table) {
        //     $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        // });
    }

    public function down(): void
    {
        // ✅ تم تعطيل هذا الكود أيضاً
        // Schema::table('activity_student', function (Blueprint $table) {
        //     $table->dropForeign(['user_id']);
        //     $table->dropColumn('user_id');
        // });
    }
};