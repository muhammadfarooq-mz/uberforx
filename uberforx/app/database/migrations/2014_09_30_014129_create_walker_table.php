<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalkerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('walker', function(Blueprint $t)
		{
			$t->increments('id');
			$t->string('first_name');
			$t->string('last_name');
			$t->string('phone');
			$t->string('email');
			$t->string('password');
			$t->string('token');
			$t->integer('token_expiry');
			$t->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('walker');
	}

}
