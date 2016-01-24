<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsCancelled extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('request_meta', function(Blueprint $table)
		{
			$table->integer('is_cancelled');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('request_meta', function(Blueprint $table)
		{
			$table->dropColumn('is_cancelled');
		});
	}

}
