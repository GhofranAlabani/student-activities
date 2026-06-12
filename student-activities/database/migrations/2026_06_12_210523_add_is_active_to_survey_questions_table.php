<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('survey_questions', 'is_active')) {
            Schema::table('survey_questions', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('order');
            });
        }
    }

    public function down(): void
    {
        Schema::table('survey_questions', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};