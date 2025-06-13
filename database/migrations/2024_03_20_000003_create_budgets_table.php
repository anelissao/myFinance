<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->date('month');
            $table->decimal('planned_amount', 10, 2);
            $table->decimal('actual_amount', 10, 2)->default(0);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'category_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
}; 