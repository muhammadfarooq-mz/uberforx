<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOwnersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('owner', function($t) {
          // auto increment id (primary key)
          $t->increments('id');
          $t->string('first_name');
          $t->string('last_name');
          $t->string('phone');
          $t->string('email');
          $t->text('address');
          $t->string('state');
          $t->string('zipcode');
          $t->integer('dog_id');
          $t->string('password');
          $t->string('token');
          $t->integer('token_expiry');
          // created_at, updated_at DATETIME
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

		Schema::drop('owner');
	}

}
