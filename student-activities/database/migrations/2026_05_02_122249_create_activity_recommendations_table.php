<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('activity_recommendations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        $table->foreignId('current_activity_id')->nullable()->constrained('activities')->nullOnDelete();

        $table->foreignId('suggested_activity_id')->constrained('activities')->onDelete('cascade');

        $table->string('reason')->nullable();

        $table->enum('status', [
            'new',
            'viewed',
            'registered',
            'rejected'
        ])->default('new');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_recommendations');
    }
};
