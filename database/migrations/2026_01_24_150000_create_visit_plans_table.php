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
        Schema::create('visit_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collector_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('frequency', ['daily', 'weekly', 'monthly'])->default('daily');
            $table->enum('status', ['open', 'in_progress', 'closed'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_plans');
    }
};
