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
        Schema::create('tasks_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->ulid('task_ulid')->nullable();
            $table->foreign('task_ulid')->references('ulid')->on('tasks')->onDelete('set null');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_read')->default(false);
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks_chat_messages');
    }
};
