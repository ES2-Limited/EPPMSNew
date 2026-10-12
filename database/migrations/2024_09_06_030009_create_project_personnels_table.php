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
        Schema::create('project_personnels', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->unsignedInteger('personnel_id')->references('id')->on('personnels')->constrained()->onDelete('cascade');
            $table->ulid('project_ulid')->nullable();
            $table->foreign('project_ulid')->references('ulid')->on('projects')->onDelete('set null');
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            $table->unsignedInteger('department_id')->references('id')->on('departments')->constrained()->onDelete('cascade');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('created_by_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_personnels');
    }
};
