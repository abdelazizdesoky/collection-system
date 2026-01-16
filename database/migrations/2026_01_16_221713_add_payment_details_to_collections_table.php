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
        Schema::table('collections', function (Blueprint $table) {
            $table->string('attachment')->nullable()->after('notes');
            $table->string('bank_name')->nullable()->after('attachment');
            $table->string('reference_no')->nullable()->after('bank_name');
            $table->string('payment_type')->change(); // Temporary change to modify type
        });

        // Use raw query for enum modification as change() might have issues with enums in some DBs
        DB::statement("ALTER TABLE collections MODIFY COLUMN payment_type ENUM('cash', 'cheque', 'bank_transfer') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            //
        });
    }
};
