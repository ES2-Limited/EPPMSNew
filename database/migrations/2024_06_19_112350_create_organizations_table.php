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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('lga_id')->nullable();
            $table->string('contact_no')->nullable();
            $table->text('purpose')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->string('motto')->nullable();
            $table->string('website')->nullable();
            $table->text('brief')->nullable();
            $table->binary('bldg')->nullable();
            $table->binary('logo')->nullable();
            $table->boolean('branch')->nullable();
            $table->smallInteger('created_by_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->smallInteger('updated_by_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
