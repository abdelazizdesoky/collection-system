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
        Schema::create('collection_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collector_id')->constrained();
            $table->date('date');
            $table->enum('type', ['daily', 'weekly']);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_plans');
    }
};
