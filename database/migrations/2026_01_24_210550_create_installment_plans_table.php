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
        Schema::create('installment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('invoice_no')->nullable();
            $table->decimal('total_amount', 15, 2);
            $table->decimal('down_payment', 15, 2)->default(0);
            $table->decimal('increase_percentage', 5, 2)->default(0); // e.g. 10.00 for 10%
            $table->decimal('financed_amount', 15, 2); // (Total - Down) * (1 + increase)
            $table->integer('duration_months');
            $table->decimal('monthly_amount', 15, 2);
            $table->date('start_date');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_plans');
    }
};
