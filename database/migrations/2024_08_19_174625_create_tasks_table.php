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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->unsignedInteger('milestone_id')->references('id')->on('milestones')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->date('start_date');
            $table->date('due_date');
            $table->decimal('cost', 20, 2);
            $table->date('gap')->nullable();
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
        Schema::dropIfExists('tasks');
    }
};
