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
        Schema::create('visit_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // System name (key)
            $table->string('label'); // Display label
            $table->boolean('is_system')->default(false); // Validations specific to system types
            $table->timestamps();
        });

        // Insert default types
        DB::table('visit_types')->insert([
            ['name' => 'collection', 'label' => 'تحصيل', 'is_system' => true],
            ['name' => 'order', 'label' => 'عمل أوردر', 'is_system' => true],
            ['name' => 'issue', 'label' => 'حل مشكلة', 'is_system' => true],
            ['name' => 'general', 'label' => 'زيارة عامة', 'is_system' => true],
        ]);

        // Modify visits table to use foreign key
        // We will keep the 'visit_type' enum column for now to avoid losing data if any, 
        // but we'll add visit_type_id and sync it in controller logic. (Or we can just add the new column)
        // Since the prompt asks for "types other than specific ones", creating a table is best.
        
        Schema::table('visits', function (Blueprint $table) {
            // Making it nullable for migration, then we should populate it
            $table->foreignId('visit_type_id')->nullable()->after('customer_id')->constrained('visit_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropForeign(['visit_type_id']);
            $table->dropColumn('visit_type_id');
        });

        Schema::dropIfExists('visit_types');
    }
};
