ت<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 public function up(): void
{
    if (Schema::hasTable('activity_tags')) {
        return;
    }

    Schema::create('activity_tags', function (Blueprint $table) {
        $table->id();
        $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
        $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
        $table->unique(['activity_id', 'tag_id']);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('activity_tags');
    }
};