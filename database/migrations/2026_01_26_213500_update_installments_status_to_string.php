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
        Schema::table('installment_plans', function (Blueprint $table) {
            // Change status from ENUM to STRING to allow new statuses like 'partial', 'defaulted', etc.
            $table->string('status', 50)->default('active')->change();
        });

        Schema::table('installments', function (Blueprint $table) {
            // Change status from ENUM to STRING
            $table->string('status', 50)->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('installment_plans', function (Blueprint $table) {
            // Reverting might lose data if new statuses are used, but for strict reverse:
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active')->change();
        });

        Schema::table('installments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending')->change();
        });
    }
};
