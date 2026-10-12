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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->string('name');
            $table->string('priority')->nullable();
            $table->string('description')->nullable();
            $table->decimal('cost', 20, 2);
            $table->string('duration')->nullable();
            $table->enum('duration_period', ['days', 'weeks', 'months', 'years'])->nullable();
            $table->string('logo_url')->nullable();
            $table->string('award_letter')->nullable();
            $table->boolean('show')->default(false);
            $table->string('location')->nullable();
            // $table->unsignedInteger('state_id')->references('id')->on('states')->constrained()->onDelete('cascade');
            $table->unsignedInteger('contractor_id')->references('id')->on('contractors')->constrained()->onDelete('cascade');
            $table->unsignedInteger('consultant_id')->nullable()->references('id')->on('contractors')->constrained()->onDelete('cascade');
            $table->unsignedInteger('created_by_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'in_progress', 'done', 'completed',  'archive', 'cancelled'])->default('pending');
            $table->string('completed_by_id')->nullable();
            $table->date('completed_date')->nullable();
            $table->date('award_date')->nullable();
            $table->date('deadline')->nullable();
            $table->string('updated_by_ulid')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('directorate_id')->nullable()->constrained('directorates')->onDelete('set null');
            $table->unsignedInteger('project_type_id')->references('id')->on('project_types')->constrained()->onDelete('cascade');
            $table->unsignedInteger('office_id')->references('id')->on('offices')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('projects');
    }
};
