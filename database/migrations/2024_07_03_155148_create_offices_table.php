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
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->string('name')->nullable();
            $table->string('department_id')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedInteger('office_type_id')->references('id')->on('office_types')->constrained()->onDelete('cascade');
            $table->unsignedInteger('lga_id')->references('id')->on('lgas')->constrained()->onDelete('cascade');
            $table->unsignedInteger('created_by_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->unsignedInteger('updated_by_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->string('office_region_id')->nullable();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
