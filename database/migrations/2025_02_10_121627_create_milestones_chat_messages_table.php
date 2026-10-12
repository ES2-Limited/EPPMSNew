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
        Schema::create('milestones_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->ulid('milestone_ulid')->nullable();
            $table->foreign('milestone_ulid')->references('ulid')->on('milestones')->onDelete('set null');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_read')->default(false);
            $table->text('message');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestones_chat_messages');
    }
};
