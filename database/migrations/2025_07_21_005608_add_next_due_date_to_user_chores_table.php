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
        Schema::table('user_chores', function (Blueprint $table) {
            $table->date('next_due_date')->nullable()->after('due_date');
            $table->boolean('is_recurring')->default(false)->after('next_due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_chores', function (Blueprint $table) {
            $table->dropColumn(['next_due_date', 'is_recurring']);
        });
    }
};