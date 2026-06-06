<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان الإعلان
            $table->text('content'); // محتوى الإعلان
            $table->string('type')->default('activity'); // نوع الإعلان (activity, general, warning)
            $table->foreignId('activity_id')->nullable()->constrained()->onDelete('cascade'); // النشاط المرتبط
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // من أنشأ الإعلان
            $table->boolean('is_active')->default(true); // هل الإعلان نشط
            $table->date('expires_at')->nullable(); // تاريخ انتهاء الإعلان
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};