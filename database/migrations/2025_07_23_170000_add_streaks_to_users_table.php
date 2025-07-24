<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('daily_streak')->default(0);
            $table->unsignedInteger('weekly_streak')->default(0);
            $table->date('last_completed_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['daily_streak', 'weekly_streak', 'last_completed_at']);
        });
    }
}; 