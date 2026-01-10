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
        Schema::table('collection_plans', function (Blueprint $table) {
            // Drop the old enum column and add a new string column
            $table->dropColumn('type');
        });

        Schema::table('collection_plans', function (Blueprint $table) {
            // Add new columns with proper structure
            $table->string('name')->nullable()->after('collector_id');
            $table->string('collection_type')->default('regular')->after('date');
            $table->string('type')->nullable()->after('collection_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_plans', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('collection_type');
            $table->dropColumn('type');
        });

        Schema::table('collection_plans', function (Blueprint $table) {
            $table->enum('type', ['daily', 'weekly'])->default('daily');
        });
    }
};
