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
            $table->boolean('is_adhoc')->default(false)->after('id');
            $table->boolean('is_installment')->default(false)->after('is_adhoc');
            $table->string('status')->default('approved')->after('notes'); // pending, approved, rejected
            $table->text('reviewer_notes')->nullable()->after('status');
            $table->foreignId('reviewed_by_id')->nullable()->after('reviewer_notes')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by_id');
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->foreignId('visit_plan_item_id')->nullable()->change();
            $table->boolean('is_adhoc')->default(false)->after('id');
            $table->string('status')->default('approved')->after('notes'); // pending, approved, rejected
            $table->text('reviewer_notes')->nullable()->after('status');
            $table->foreignId('reviewed_by_id')->nullable()->after('reviewer_notes')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by_id');
        });

        Schema::table('sale_invoices', function (Blueprint $table) {
            $table->boolean('is_adhoc')->default(false)->after('id');
            $table->text('reviewer_notes')->nullable()->after('status');
            $table->foreignId('reviewed_by_id')->nullable()->after('reviewer_notes')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by_id');
            $table->dropColumn(['is_adhoc', 'is_installment', 'status', 'reviewer_notes', 'reviewed_at']);
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->foreignId('visit_plan_item_id')->nullable(false)->change();
            $table->dropConstrainedForeignId('reviewed_by_id');
            $table->dropColumn(['is_adhoc', 'status', 'reviewer_notes', 'reviewed_at']);
        });

        Schema::table('sale_invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by_id');
            $table->dropColumn(['is_adhoc', 'reviewer_notes', 'reviewed_at']);
        });
    }
};
