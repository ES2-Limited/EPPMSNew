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
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('cost_type', ['payable', 'non-payable']);
            $table->decimal('amount', 20, 2)->nullable();
            $table->date('deadline');
            $table->ulid('project_ulid');
            $table->foreign('project_ulid')->references('ulid')->on('projects')->onDelete('cascade');
            $table->enum('status', ['pending', 'in_progress', 'done', 'completed',  'archive', 'cancelled'])->default('pending');
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
        Schema::dropIfExists('milestones');
    }
};
