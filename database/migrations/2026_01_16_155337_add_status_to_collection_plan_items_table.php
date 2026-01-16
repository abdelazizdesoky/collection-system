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
        Schema::table('collection_plan_items', function (Blueprint $table) {
            $table->string('status')->default('pending'); // pending, collected, skipped
            $table->foreignId('collection_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_plan_items', function (Blueprint $table) {
            $table->dropForeign(['collection_id']);
            $table->dropColumn(['status', 'collection_id']);
        });
    }
};
