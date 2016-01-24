<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalkTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('walk', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('schedule_id');
			$table->integer('dog_id');
			$table->integer('walker_id');
			$table->date('date');
			$table->integer('is_walker_rated');
			$table->integer('is_dog_rated');
			$table->integer('is_confirmed');
			$table->integer('is_started');
			$table->integer('is_completed');
			$table->integer('is_cancelled');
			$table->float('distance');
			$table->integer('time');
			$table->integer('is_poo');
			$table->integer('is_pee');
			$table->string('photo_url');
			$table->string('video_url');
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
		Schema::drop('walk');
	}

}
