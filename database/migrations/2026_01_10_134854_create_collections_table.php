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
       Schema::create('collections', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->constrained();
    $table->foreignId('collector_id')->constrained();
    $table->decimal('amount', 12, 2);
    $table->enum('payment_type', ['cash', 'cheque']);
    $table->date('collection_date');
    $table->string('receipt_no')->unique();
    $table->text('notes')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
