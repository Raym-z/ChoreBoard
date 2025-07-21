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
        Schema::create('chore_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_chore_id')->constrained('user_chores')->onDelete('cascade');
            $table->string('event_type'); // e.g., completed, missed, reassigned
            $table->json('event_data')->nullable(); // Additional data for the event
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chore_log');
    }
};