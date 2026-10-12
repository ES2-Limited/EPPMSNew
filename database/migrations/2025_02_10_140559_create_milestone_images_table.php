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
        Schema::create('milestone_images', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->ulid('milestone_ulid')->nullable();
            $table->foreign('milestone_ulid')->references('ulid')->on('milestones')->onDelete('set null');
            $table->foreignId('uploader_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestone_images');
    }
};
