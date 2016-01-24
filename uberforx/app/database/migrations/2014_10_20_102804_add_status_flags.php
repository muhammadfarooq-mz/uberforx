<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusFlags extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('request', function(Blueprint $table)
		{
			$table->integer('is_walker_started');
			$table->integer('is_walker_arrived');
			$table->integer('is_started');
			$table->integer('is_completed');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('request', function(Blueprint $table)
		{
			$table->dropColumn('is_walker_arrived');
			$table->dropColumn('is_walker_started');
			$table->dropColumn('is_started');
			$table->dropColumn('is_completed');
		});
	}

}
