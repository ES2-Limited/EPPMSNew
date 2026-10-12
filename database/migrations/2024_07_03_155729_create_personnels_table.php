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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->string('address')->nullable();
            $table->string('designation')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedInteger('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->unsignedInteger('office_id')->references('id')->on('offices')->constrained()->onDelete('cascade');
            $table->unsignedInteger('created_by_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->unsignedInteger('updated_by_id')->nullable();
            $table->unsignedInteger('directorate_id')->references('id')->on('directorates')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->unsignedInteger('role_id')->references('id')->on('roles')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('personnels');
    }
};
