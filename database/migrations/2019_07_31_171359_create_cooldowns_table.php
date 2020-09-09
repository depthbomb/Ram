<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCooldownsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cooldowns', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('uid', 32)->unique();
			$table->string('user_steamid');
			$table->enum('type', ['map', 'buff', 'debuff', 'snap', 'slay', 'kick', 'ban', 'gag', 'mute', 'say', 'sound', 'other']);
			$table->unsignedInteger('expires');
			$table->boolean('expired')->default(false);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('cooldowns');
	}
}
