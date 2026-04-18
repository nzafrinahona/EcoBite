<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cafeteria_id')->constrained()->cascadeOnDelete();
            $table->date('week_start');
            $table->date('week_end');
            $table->integer('total_listings')->default(0);
            $table->integer('total_reservations')->default(0);
            $table->integer('completed_pickups')->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->string('most_popular_item')->nullable();
            $table->timestamps();

            $table->index('cafeteria_id');
            $table->index(['week_start', 'week_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};
