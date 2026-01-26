<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Using raw SQL to ensure the change happens, avoiding Doctrine limitations with Enums
        DB::statement("ALTER TABLE installment_plans MODIFY COLUMN status VARCHAR(50) DEFAULT 'active'");
        DB::statement("ALTER TABLE installments MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: Revert to Enum (Not recommended if data varies)
    }
};
