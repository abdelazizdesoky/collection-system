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
        // Add code to customers
        Schema::table('customers', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
        });

        // Add code to collectors
        Schema::table('collectors', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
        });

        // Add print_count to collections
        Schema::table('collections', function (Blueprint $table) {
            $table->integer('print_count')->default(0)->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('code');
        });

        Schema::table('collectors', function (Blueprint $table) {
            $table->dropColumn('code');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('print_count');
        });
    }
};
