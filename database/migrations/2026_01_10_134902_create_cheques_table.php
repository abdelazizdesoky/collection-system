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
      Schema::create('cheques', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->constrained();
    $table->string('cheque_no');
    $table->string('bank_name');
    $table->decimal('amount', 12, 2);
    $table->date('due_date');
    $table->enum('status', ['pending', 'cleared', 'bounced'])->default('pending');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
