<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLatLongToWalkerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('walker', function(Blueprint $table)
		{
			$table->float('latitude');
			$table->float('longitude');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('walker', function(Blueprint $table)
		{
			$table->dropColumn('latitude');
			$table->dropColumn('longitude');
		});
	}

}
