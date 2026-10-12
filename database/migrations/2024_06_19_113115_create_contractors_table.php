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
        Schema::create('contractors', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedInteger('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->unsignedInteger('created_by_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->unsignedInteger('firm_type_id')->references('id')->on('firm_types')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('contractors');
    }
};
