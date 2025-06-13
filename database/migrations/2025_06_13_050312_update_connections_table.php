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
        Schema::table('connections', function (Blueprint $table) {
            // Drop old status enum
            $table->dropColumn('status');
        });

        Schema::table('connections', function (Blueprint $table) {
            // Add new status enum with uppercase values
            $table->enum('status', ['PENDING', 'ACTIVE'])->default('PENDING');
        });
    }

    public function down(): void
    {
        Schema::table('connections', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('connections', function (Blueprint $table) {
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
        });
    }
};
