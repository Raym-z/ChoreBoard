<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('user_chores', function (Blueprint $table) {
            $table->unsignedTinyInteger('bonus_multiplier')->default(1);
        });
    }

    public function down()
    {
        Schema::table('user_chores', function (Blueprint $table) {
            $table->dropColumn('bonus_multiplier');
        });
    }
}; 