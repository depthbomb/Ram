<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('uid', 16)->unique();
			$table->string('steamid')->unique();
			$table->string('avatar');
			$table->string('username', 32);
			$table->boolean('authorized')->default(false);
			$table->boolean('admin')->default(false);
			$table->boolean('super_admin')->default(false);
			$table->integer('cooldown_modifier')->default(0);
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
		Schema::dropIfExists('users');
	}
}
