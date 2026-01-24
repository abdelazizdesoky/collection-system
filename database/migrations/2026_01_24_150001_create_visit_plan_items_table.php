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
        Schema::create('visit_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained();
            $table->integer('priority')->default(0);
            $table->enum('status', ['pending', 'visited', 'skipped'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_plan_items');
    }
};
