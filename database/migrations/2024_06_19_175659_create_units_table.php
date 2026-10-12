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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->string('name')->nullable();
            $table->longText('function')->nullable();
            $table->unsignedInteger('department_id')->references('id')->on('departments')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->unsignedInteger('created_by_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
