<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLatLongDataTypeWalker extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('walk_location', function(Blueprint $table)
		{
			$table->double('latitude', 15, 8);
			$table->double('longitude', 15, 8);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('walk_location', function(Blueprint $table)
		{
			$table->dropColumn('latitude');
			$table->dropColumn('longitude');
		});
	}

}
