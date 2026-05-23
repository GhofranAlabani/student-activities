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
    Schema::create('reward_requests', function (Blueprint $table) {
        $table->id();

        // الطالب
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // المكافأة
        $table->foreignId('reward_id')->constrained()->onDelete('cascade');

        // الحالة
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

        // المشرف الذي وافق أو رفض
        $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();

        // وقت الطلب والمعالجة
        $table->timestamp('requested_at')->useCurrent();
        $table->timestamp('processed_at')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_requests');
    }
};
