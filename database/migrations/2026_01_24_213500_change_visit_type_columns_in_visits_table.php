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
        Schema::table('visits', function (Blueprint $table) {
            $table->string('visit_type')->change();
            $table->string('issue_status')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->enum('visit_type', ['collection', 'order', 'issue', 'general'])->change();
            $table->enum('issue_status', ['pending', 'resolved', 'escalated'])->nullable()->change();
        });
    }
};
