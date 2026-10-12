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
        Schema::create('role_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->foreignId('role_category_id')->nullable()->constrained('role_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_categories');

        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['role_category_id']);
            $table->dropColumn('role_category_id');
        });

    }
};
