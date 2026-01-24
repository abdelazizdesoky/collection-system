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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_plan_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('collector_id')->constrained();
            $table->foreignId('customer_id')->constrained();
            $table->enum('visit_type', ['collection', 'order', 'issue', 'general']);
            $table->datetime('visit_time');
            $table->text('notes')->nullable();
            $table->string('attachment')->nullable();
            // Collection-specific fields
            $table->foreignId('collection_id')->nullable()->constrained();
            // Issue-specific fields
            $table->text('issue_description')->nullable();
            $table->enum('issue_status', ['pending', 'resolved', 'escalated'])->nullable();
            // Order-specific fields
            $table->text('order_details')->nullable();
            $table->decimal('order_amount', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
