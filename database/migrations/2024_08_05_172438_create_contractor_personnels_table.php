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
        Schema::create('contractor_personnels', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->string('address')->nullable();
            $table->string('designation')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedInteger('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->unsignedInteger('contractor_id')->nullable()->references('id')->on('contractors')->constrained()->onDelete('cascade');
            $table->unsignedInteger('created_by_id')->nullable()->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->unsignedInteger('updated_by_id')->nullable();
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractor_personnels');
    }
};
