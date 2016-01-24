<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('request', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('owner_id');
			$table->integer('schedule_id');
			$table->integer('status');
			$table->integer('confirmed_walker');
			$table->integer('current_walker');
			$table->dateTime('request_start_time');
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
		Schema::drop('request');
	}

}
