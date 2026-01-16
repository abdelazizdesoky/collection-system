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
        Schema::create('collection_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained();
            $table->decimal('expected_amount', 12, 2);
            $table->integer('priority')->default(1);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_plan_items');
    }
};
