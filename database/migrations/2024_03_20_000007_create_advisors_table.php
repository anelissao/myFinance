<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advisors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('specialization');
            $table->decimal('rating', 2, 1)->default(0);
            $table->text('description');
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advisors');
    }
}; 