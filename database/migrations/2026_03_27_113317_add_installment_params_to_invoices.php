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
        Schema::table('sale_invoices', function (Blueprint $table) {
            $table->decimal('installment_interest', 5, 2)->default(0)->after('payment_type');
            $table->integer('installment_duration')->default(12)->after('installment_interest');
            $table->date('installment_start_date')->nullable()->after('installment_duration');
        });

        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->decimal('installment_interest', 5, 2)->default(0)->after('payment_type');
            $table->integer('installment_duration')->default(12)->after('installment_interest');
            $table->date('installment_start_date')->nullable()->after('installment_duration');
        });
    }

    public function down(): void
    {
        Schema::table('sale_invoices', function (Blueprint $table) {
            $table->dropColumn(['installment_interest', 'installment_duration', 'installment_start_date']);
        });

        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->dropColumn(['installment_interest', 'installment_duration', 'installment_start_date']);
        });
    }
};
