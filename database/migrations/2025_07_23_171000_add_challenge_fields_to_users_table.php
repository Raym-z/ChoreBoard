<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('daily_challenge_completed')->default(false);
            $table->boolean('weekly_challenge_completed')->default(false);
            $table->unsignedInteger('challenge_xp_bonus')->default(0);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['daily_challenge_completed', 'weekly_challenge_completed', 'challenge_xp_bonus']);
        });
    }
}; 