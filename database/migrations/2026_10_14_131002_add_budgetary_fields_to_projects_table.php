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
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('fiscal_year_id')->nullable()->after('priority')->constrained('fiscal_years')->nullOnDelete();
            $table->foreignId('funding_source_id')->nullable()->after('fiscal_year_id')->constrained('funding_sources')->nullOnDelete();
            $table->string('budget_code', 100)->nullable()->after('funding_source_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['fiscal_year_id']);
            $table->dropForeign(['funding_source_id']);
            $table->dropColumn(['fiscal_year_id', 'funding_source_id', 'budget_code']);
        });
    }
};
