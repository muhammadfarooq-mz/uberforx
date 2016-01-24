<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dog', function($t) {
          // auto increment id (primary key)
          $t->increments('id');
          $t->string('name');
          $t->string('age');
          $t->string('breed');
          $t->text('likes');
          $t->string('image_url');
          $t->integer('owner_id');
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
		//
	}

}
