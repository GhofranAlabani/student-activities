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
    Schema::create('rewards', function (Blueprint $table) {
        $table->id();

        $table->string('title'); // اسم المكافأة
        $table->text('description')->nullable();

        $table->integer('points_required'); // عدد النقاط المطلوبة

        $table->integer('quantity')->default(0); // الكمية المتاحة

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
