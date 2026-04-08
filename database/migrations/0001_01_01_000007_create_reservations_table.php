<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('food_item_id')->constrained('food_items')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 8, 2);
            $table->string('pickup_code', 5)->unique();
            $table->enum('status', ['pending', 'completed', 'cancelled', 'no-show'])->default('pending');
            $table->timestamp('pickup_time')->nullable();
            $table->timestamps();

            $table->index('student_id');
            $table->index('food_item_id');
            $table->index('status');
            $table->index('pickup_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
