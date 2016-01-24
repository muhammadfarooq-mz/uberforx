<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewWalkerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('review_walker', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('walk_id');
			$table->integer('dog_id');
			$table->integer('walker_id');
			$table->integer('rating');
			$table->string('comment');
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
		Schema::drop('review_walker');
	}

}
