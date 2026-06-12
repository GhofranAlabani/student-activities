<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول الأسئلة - نتحقق أولاً إذا كان موجوداً
        if (!Schema::hasTable('survey_questions')) {
            Schema::create('survey_questions', function (Blueprint $table) {
                $table->id();
                $table->string('question');
                $table->enum('type', ['text', 'radio', 'checkbox'])->default('text');
                $table->text('options')->nullable();
                $table->boolean('is_required')->default(true);
                $table->integer('order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // جدول الإجابات
        if (!Schema::hasTable('survey_responses')) {
            Schema::create('survey_responses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('activity_id')->constrained()->onDelete('cascade');
                $table->foreignId('question_id')->constrained('survey_questions')->onDelete('cascade');
                $table->text('answer');
                $table->timestamps();
                
                $table->unique(['user_id', 'activity_id', 'question_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
        Schema::dropIfExists('survey_questions');
    }
};