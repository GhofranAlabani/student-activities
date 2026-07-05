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
            $table->enum('type', ['general', 'urgent', 'info'])->default('general'); // نوع الإعلان
            $table->boolean('is_active')->default(true); // هل الإعلان نشط؟
            $table->date('start_date')->nullable(); // تاريخ البدء
            $table->date('end_date')->nullable(); // تاريخ الانتهاء ✅ (هذا المهم)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // من أنشأ الإعلان
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};