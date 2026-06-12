<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('survey_questions', function (Blueprint $table) {
            if (!Schema::hasColumn('survey_questions', 'type')) {
                $table->enum('type', ['text', 'radio', 'checkbox'])->default('text')->after('question');
            }
            if (!Schema::hasColumn('survey_questions', 'options')) {
                $table->text('options')->nullable()->after('type');
            }
            if (!Schema::hasColumn('survey_questions', 'is_required')) {
                $table->boolean('is_required')->default(true)->after('options');
            }
            if (!Schema::hasColumn('survey_questions', 'order')) {
                $table->integer('order')->default(0)->after('is_required');
            }
            if (!Schema::hasColumn('survey_questions', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('survey_questions', function (Blueprint $table) {
            $table->dropColumn(['type', 'options', 'is_required', 'order', 'is_active']);
        });
    }
};